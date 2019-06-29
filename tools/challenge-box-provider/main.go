package main

import (
	"context"
	"flag"
	"fmt"
	"io/ioutil"
	"log"
	"net/http"
	"strconv"
	"strings"
	"time"

	//	"os"
	"encoding/json"
	//"io/ioutil"

	"github.com/docker/docker/api/types"
	"github.com/docker/docker/api/types/container"
	"github.com/docker/docker/api/types/network"
	"github.com/docker/docker/client"

	// Fix: mv ~/go_workspace//src/github.com/docker/docker/vendor/github.com/docker/go-connections/{nat,nat.old}

	bolt "go.etcd.io/bbolt"
)

type Challenge struct {
	Id       string
	Image    string
	Port     string
	Duration string
}

/*
type Challenges struct {
    challenges []Challenge `json:"challenges"`
}
*/
var (
	ufwProxyPort               int
	challengeBoxDockerImage    string
	challengeBoxDockerPort     int
	challengeBoxDockerLifespan int
	httpServerListener         string

	db           *bolt.DB
	dockerClient *client.Client
	ctx          = context.Background()
	challenges   []Challenge

	net_1 int
	net_2 int
)

const CONST_USER_NET_DURATION = 3600

func init() {
	// Set program flags
	flag.IntVar(&ufwProxyPort, "ufwport", 0, "Activate firewall thanks ufw proxy")
	flag.StringVar(&challengeBoxDockerImage, "image", "ubuntu", "Docker image")
	flag.IntVar(&challengeBoxDockerPort, "port", 22, "Container exposed port to dynamically map on the host")
	flag.IntVar(&challengeBoxDockerLifespan, "life", 60, "Challenge lifetime before the box closes")
	flag.StringVar(&httpServerListener, "listen", "0.0.0.0:8080", "Address:Port the http server will bind to")

	// check docker requirement
	/*
		_, err := exec.LookPath("docker")
		if err != nil {
			log.Fatalf("Error Docker not found : %s", err)
		}
	*/

	// Instantiate a BBolt Database with a bucket dedicated for the configuration

	db, err := bolt.Open("./state.db", 0600, nil)
	if err != nil {
		log.Fatalf("Error creating Bbolt DB : %s", err)
	}
	db.Update(func(tx *bolt.Tx) error {
		_, err := tx.CreateBucketIfNotExists([]byte("State"))
		if err != nil {
			return fmt.Errorf("create bucket: %s", err)
		}
		return nil
	})
	db.Close()

	// Docker client
	dockerClient, err = client.NewClientWithOpts(client.WithVersion("1.38"))
	if err != nil {
		panic(err)
	}

}

//
// Ufw proxy
//

func ufw_status(proxyport int) {
	if proxyport == 0 {
		return
	}
	req := fmt.Sprintf("http://localhost:%d/?cmd=status", proxyport)
	response, err := http.Get(req)
	if err != nil {
		fmt.Printf("%s", err)
	} else {
		defer response.Body.Close()
		contents, err := ioutil.ReadAll(response.Body)
		if err != nil {
			fmt.Printf("%s", err)
		}
		fmt.Printf("%s\n", string(contents))
	}
}

func ufw_open_port(proxyport int, port string, ip string) {
	if proxyport == 0 {
		return
	}
	req := fmt.Sprintf("http://localhost:%d/?cmd=open&ip=%s&port=%s", proxyport, ip, port)
	response, err := http.Get(req)
	if err != nil {
		fmt.Printf("%s", err)
	} else {
		defer response.Body.Close()
		contents, err := ioutil.ReadAll(response.Body)
		if err != nil {
			fmt.Printf("%s", err)
		}
		fmt.Printf("%s\n", string(contents))
	}
}

func ufw_close_port(proxyport int, port string, ip string) {
	if proxyport == 0 {
		return
	}
	req := fmt.Sprintf("http://localhost:%d/?cmd=close&ip=%s&port=%s", proxyport, ip, port)
	response, err := http.Get(req)
	if err != nil {
		fmt.Printf("%s", err)
	} else {
		defer response.Body.Close()
		contents, err := ioutil.ReadAll(response.Body)
		if err != nil {
			fmt.Printf("%s", err)
		}
		fmt.Printf("%s\n", string(contents))
	}
}

//
//
// Docker API utils
//

func listImages(cli *client.Client) {

	//List all images available locally
	images, err := cli.ImageList(context.Background(), types.ImageListOptions{})
	if err != nil {
		panic(err)
	}

	fmt.Println("LIST IMAGES\n-----------------------")
	fmt.Println("Image ID | Repo Tags | Size")
	for _, image := range images {
		fmt.Printf("%s | %s | %d\n", image.ID, image.RepoTags, image.Size)
	}

}

func listSwarmNodes(cli *client.Client) {
	swarmNodes, err := cli.NodeList(context.Background(), types.NodeListOptions{})
	if err != nil {
		panic(err)
	}

	//List all nodes - works only in Swarm Mode
	fmt.Print("\n\n\n")
	fmt.Println("LIST SWARM NODES\n-----------------------")
	fmt.Println("Name | Role | Leader | Status")
	for _, swarmNode := range swarmNodes {
		fmt.Printf("%s | %s | isLeader = %t | %s\n", swarmNode.Description.Hostname, swarmNode.Spec.Role, swarmNode.ManagerStatus.Leader, swarmNode.Status.State)
	}

}

func listContainers(cli *client.Client) {
	//Retrieve a list of containers
	containers, err := cli.ContainerList(context.Background(), types.ContainerListOptions{})
	if err != nil {
		panic(err)
	}

	fmt.Print("\n\n\n")
	fmt.Println("LIST CONTAINERS\n-----------------------")
	fmt.Println("Container Names | Image | Mounts")
	//Iterate through all containers and display each container's properties
	for _, container := range containers {
		fmt.Printf("%s | %s | %s\n", container.Names, container.Image, container.Mounts)
	}

}

//
func getNetworkId(netId string) (networkID string) {

	networkID = ""
	networks, err := dockerClient.NetworkList(ctx, types.NetworkListOptions{})
	if err != nil {
		log.Printf("ERROR: getNetworkId() %s", err.Error())
	}
	for _, network := range networks {
		//fmt.Printf("[%s]-[%s]\n", network.Name, network.ID)
		if network.Name == netId {
			networkID = network.ID
		}
	}
	return
}

func getNetworkIdFromUID(uid string) (networkID string) {
	netName := "Net_" + uid
	networkID = getNetworkId(netName)
	return
}

//
// Create New Challenge Container
//
func createNewChallengeBox(box string, duration string, port string, uid string) (containerID string, err error) {
	ctx := context.Background()

	// Labels
	labels := map[string]string{
		"ctf-uid":               fmt.Sprintf("CTF_UID_%s", uid),
		"ctf-start-time":        time.Now().Format("2006-01-02 15:04:05"),
		"ctf-duration":          duration,
		"traefik.enable":        "true",                                      //traefik.enable=true
		"traefik.frontend.rule": fmt.Sprintf("PathPrefix:/%s_%s/", box, uid), //traefik.frontend.rule=Path:/yoloboard
		"traefik.port":          fmt.Sprintf("%s", port),
	}
	env := []string{}

	if box == "ctf-tool-xterm" {
		env = append(env, fmt.Sprintf("URLPREFIX=/%s_%s", box, uid))
		labels["traefik.docker.network"] = "webserver_webLAN"
	}
	// Port binding
	/*
		hostBinding := nat.PortBinding{
			HostIP:   "0.0.0.0",
			HostPort: "8999",
		}
		containerPort, err := nat.NewPort("tcp", string(port))
		if err != nil {
			panic("Unable to get the port")
		}
		portBinding := nat.PortMap{containerPort: []nat.PortBinding{hostBinding}}
	*/
	// You can define memory limit using Resources field of HostConfig struct.
	// Resources: container.Resources{ Memory:3e+7 }
	// https://godoc.org/github.com/docker/docker/api/types/container#Resources

	// Create
	resp, err := dockerClient.ContainerCreate(ctx,
		&container.Config{
			Image:    box,
			Labels:   labels,
			Hostname: box,
			Env:      env,
		},
		&container.HostConfig{
			AutoRemove:      true,
			PublishAllPorts: false,
			Resources: container.Resources{
				Memory:   3e+7, // in bytes, 30 000 000, 30Mb
				NanoCPUs: 1e+8, // 0.1 CPU max per container
			},
			//PortBindings: portBinding,
		},
		nil,
		/*
			&container.NetworkingConfig{
				EndpointsConfig ep,
			},*/
		fmt.Sprintf("%s_%s", box, uid))
	if err != nil {
		panic(err)
	}

	// If xterm, add webLAN
	if (box=="1") {
		nid := getNetworkId("webserver_webLAN")
		if err := dockerClient.NetworkConnect(ctx, nid, resp.ID, nil); err != nil {
			panic(err)
		}
	}

	// Add user network
	nid = getNetworkIdFromUID(uid)
	if nid == "" {
		nid, _ = createNewUserNet(uid, 3600)
	}
	if err := dockerClient.NetworkConnect(ctx, nid, resp.ID, nil); err != nil {
		panic(err)
	}

	// Remove default network : bridge
	nid = getNetworkId("bridge")
	if err := dockerClient.NetworkDisconnect(ctx, nid, resp.ID, true); err != nil {
		panic(err)
	}

	// Start container
	if err := dockerClient.ContainerStart(ctx, resp.ID, types.ContainerStartOptions{}); err != nil {
		panic(err)
	}

	fmt.Println(resp.ID)
	containerID = resp.ID

	// Open firewall
	if ufwProxyPort > 0 {

		sshPort, err2 := getHostSSHPort(string(containerID))
		if err2 != nil {
			log.Printf(err2.Error())
			return
		}
		log.Printf("Open ufw port %s", sshPort)
		ufw_open_port(ufwProxyPort, sshPort, "12.0.0.10")
	}
	return
}

func createNewUserNet(uid string, duration int) (containerID string, err error) {
	labels := map[string]string{
		"ctf-uid":        fmt.Sprintf("CTF_UID_%s", uid),
		"ctf-start-time": time.Now().String(),
		"ctf-duration":   string(strconv.Itoa(duration))}

	net_2++
	if net_2 > 250 {
		net_1++
		net_2 = 10
	}
	ipamConfig := network.IPAMConfig{
		Subnet:  fmt.Sprintf("%d.%d.0.0/16", net_1, net_2),
		Gateway: fmt.Sprintf("%d.%d.0.1", net_1, net_2),
	}
	log.Printf("Create subnet : %s", ipamConfig.Subnet)
	ipam := network.IPAM{
		//Driver: "Default",
		Config: []network.IPAMConfig{ipamConfig},
	}
	resp, err := dockerClient.NetworkCreate(
		ctx, fmt.Sprintf("Net_%s", uid),
		types.NetworkCreate{
			Labels: labels,
			IPAM:   &ipam},
			Internal: True) // No external access
	if err != nil {
		panic(err)
	}
	containerID = resp.ID
	fmt.Println(containerID)
	/*
		containerIDDirty, err := exec.Command(
			"docker", "network", "create",
			"--label", fmt.Sprintf("ctf-uid=CTF_UID_%s", uid),
			fmt.Sprintf("Net_%s", uid),
		).Output()
		containerID = bytes.TrimSpace(containerIDDirty)
	*/
	return
}

func oldcreateNewChallengeBox(box string, duration, port string) (containerID []byte, err error) {
	/*
		containerIDDirty, err := exec.Command(
			"docker", "container", "run",
			"--detach", "--rm",
			"--publish", fmt.Sprintf("%d", port),
			fmt.Sprintf("%s", box),
			"sleep", fmt.Sprintf("%d", duration)).Output()
		containerID = bytes.TrimSpace(containerIDDirty)
	*/
	return
}

func getHostSSHPort(containerID string) (port string, err error) {

	port = "0"
	containers, err := dockerClient.ContainerList(context.Background(), types.ContainerListOptions{})
	if err != nil {
		panic(err)
	}

	//fmt.Println("===")
	for _, container := range containers {
		if container.ID == containerID {
			if len(container.Ports) >= 1 {
				//fmt.Println(container.Ports[0].PublicPort)
				port = fmt.Sprintf("%d", container.Ports[0].PublicPort)
			}
			/*
				if container.NetworkSettings != nil {
					fmt.Println(container.NetworkSettings.Networks)
				}*/
		}

	}
	/*
		fmt.Println("===")
		port, err = exec.Command(
			"docker", "inspect",
			"-f", "{{range $p, $conf := .NetworkSettings.Ports}} {{(index $conf 0).HostPort}} {{end}}",
			fmt.Sprintf("%s", containerID)).Output()
		if err != nil {
			port = bytes.TrimSpace(port)
		}*/
	return
}

func terminateContainer(containerID string) error {
	fmt.Printf("Terminate [%s]\n", containerID)

	// Open firewall
	if ufwProxyPort > 0 {

		sshPort, err2 := getHostSSHPort(string(containerID))
		if err2 != nil {
			log.Printf(err2.Error())
			return (err2)
		}
		log.Printf("Close ufw rule for associated port %s", sshPort)
		ufw_close_port(ufwProxyPort, sshPort, "12.0.0.10")
	}

	err := dockerClient.ContainerStop(ctx, containerID, nil)
	if err != nil {
		panic(err)
	}
	return err
}

func getChallengeBox(box string, uid string) (containerID string) {
	containerID = ""
	containers, err := dockerClient.ContainerList(ctx, types.ContainerListOptions{})
	if err != nil {
		return
	}

	findName := fmt.Sprintf("/%s_%s", box, uid)
	for _, cont := range containers {
		_, prs := cont.Labels["ctf-uid"]
		if prs {
			//fmt.Printf("[%s][%s]", cont.Names[0], findName)
			//fmt.Println()
			if cont.Names[0] == findName {
				containerID = cont.ID
				return
			}
		}
	}
	return
}

func listChallengeBox(w http.ResponseWriter, r *http.Request) {
	json := "[\n"
	containers, err := dockerClient.ContainerList(ctx, types.ContainerListOptions{})
	if err != nil {
		json += "]"
		fmt.Fprintf(w, json)
		fmt.Println(json)
		return
	}

	count := len(containers)
	log.Printf("-- listChallengeBox : %d containers", count)
	for _, cont := range containers {
		uid, prs := cont.Labels["ctf-uid"]
		if prs {
			if len(json) > 3 {
				json += ",\n"
			}
			json += "{"
			json += "\"Name\":\"" + cont.Names[0] + "\","
			json += "\"Id\":\"" + cont.ID + "\","
			json += "\"Uid\":\"" + uid + "\","
			fmt.Println(cont.Names)
			fmt.Printf("Is from %s\n", uid)

			//fmt.Println(cont.Labels)
			sshPort, err := getHostSSHPort(cont.ID)
			if err != nil {
				fmt.Printf("no port\n")
				json += "\"port\":\"0\""
			} else {
				port := strings.TrimSpace(string(sshPort))
				p, _ := strconv.Atoi(port)
				fmt.Printf("port (%d)\n", p)
				json += "\"port\":\"" + sshPort + "\""
			}
			json += "}"
		}
	}
	json += "]"
	fmt.Fprintf(w, json)
	fmt.Println(json)
}

func createChallengeBox(w http.ResponseWriter, r *http.Request) {

	// Get uid
	uids, ok := r.URL.Query()["uid"]
	if !ok || len(uids[0]) < 1 {
		log.Println("createChallengeBox 'uid' is missing")
		fmt.Fprintf(w, "ko")
		return
	}
	uid := uids[0]
	log.Println("createChallengeBox 'uid' is: " + string(uid))

	// Is uid allowed ?

	// get Cid
	cids, ok := r.URL.Query()["cid"]
	if !ok || len(cids[0]) < 1 {
		log.Println("createChallengeBox'cid' is missing")
		fmt.Fprintf(w, "ko")
		return
	}
	cid := cids[0]
	log.Println("createChallengeBox 'cid' is: " + string(cid))

	// find entry
	var cindex int = -1
	for index, chall := range challenges {
		//log.Println("Search: " + string(chall.id))
		if chall.Id == cid {
			cindex = index
		}
	}
	if cindex == -1 {

		log.Println("cid not found : " + string(cid))
		fmt.Fprintf(w, "ko")
		return
	}

	// Existe ?
	boxID := getChallengeBox(
		challenges[cindex].Image,
		uid,
	)

	if boxID != "" {
		sshPort, err := getHostSSHPort(string(boxID))
		if err != nil {
			fmt.Fprintf(w, "Found box")
		} else {
			fmt.Fprintf(w, "{\"Name\":\"%s\", \"Port\":\"%s\"}", challenges[cindex].Image+"_"+uid, sshPort)
		}
		return
	}
	// create docker
	log.Println("Starting new docker box")
	log.Println("id   : " + string(challenges[cindex].Id))
	log.Println("image: " + string(challenges[cindex].Image))
	log.Println("port : " + string(challenges[cindex].Port))
	boxID, err := createNewChallengeBox(
		challenges[cindex].Image,
		challenges[cindex].Duration,
		challenges[cindex].Port,
		uid,
	)

	if err != nil {
		log.Println("error: " + err.Error())
		fmt.Fprintf(w, "ko")
	} else {
		log.Println("boxID is: " + string(boxID))
	}

	sshPort, err := getHostSSHPort(string(boxID))
	if err != nil {
		fmt.Fprintf(w, "Create box")
	} else {
		fmt.Fprintf(w, "{\"Name\":\"%s\", \"Port\":\"%s\"}", challenges[cindex].Image+"_"+uid, sshPort)
	}

}

func createUserNet(w http.ResponseWriter, r *http.Request) {

	// Get uid
	uids, ok := r.URL.Query()["uid"]
	if !ok || len(uids[0]) < 1 {
		log.Println("Url Param 'uid' is missing")
		return
	}
	uid := uids[0]
	log.Println("createUserNet: " + string(uid))

	// create docker
	boxID, err := createNewUserNet(uid, CONST_USER_NET_DURATION)

	if err != nil {
		log.Println("error: " + err.Error())
		fmt.Fprintf(w, "ko")
	} else {
		log.Println("boxID is: " + string(boxID))
		fmt.Fprintf(w, "ok")
	}

}

/*
func createUserTerm(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintf(w, "Create box")

	// Get uid
	uids, ok := r.URL.Query()["uid"]
	if !ok || len(uids[0]) < 1 {
		log.Println("Url Param 'uid' is missing")
		return
	}
	uid := uids[0]
	log.Println("Url Param 'uid' is: " + string(uid))

	// Is uid allowed ?

	// get Cid
	cids, ok := r.URL.Query()["cid"]
	if !ok || len(cids[0]) < 1 {
		log.Println("Url Param 'cid' is missing")
		return
	}
	cid := cids[0]
	log.Println("Url Param 'cid' is: " + string(cid))

	// find entry
	var cindex int = -1
	for index, chall := range challenges {
		log.Println("Search: " + string(chall.id))
		if chall.id == cid {
			cindex = index
		}
	}

	// create docker
	if cindex > -1 {
		log.Println("Starting new docker box")
		log.Println("id   : " + string(challenges[cindex].id))
		log.Println("image: " + string(challenges[cindex].image))
		log.Println("port : " + string(challenges[cindex].port))
		boxID, err := createNewChallengeBox(
			challenges[cindex].image,
			challengeBoxDockerLifespan,
			challenges[cindex].port,
			uid,
		)

		if err != nil {
			log.Println("error: " + err.Error())
			http.Error(w, err.Error(), http.StatusInternalServerError)
		} else {
			log.Println("boxID is: " + string(boxID))
		}

	} else {
		log.Println("cid not found : " + string(cid))
	}
}
*/
func cleanDBSav() {

	containers, err := dockerClient.ContainerList(ctx, types.ContainerListOptions{})
	//containerSet := set.NewSetFromSlice(containers)
	if err != nil {
		panic(err)
	}
	count := len(containers)
	log.Printf("-- Watch : %d containers", count)
	for _, cont := range containers {
		fmt.Printf("ID [%s]\n", cont.ID)
		fmt.Println(cont.Names)
		uid, prs := cont.Labels["ctf-uid"]
		if prs {
			fmt.Printf("Is from %s\n", uid)

			//fmt.Println(cont.Labels)
			sshPort, err := getHostSSHPort(cont.ID)
			if err != nil {
				fmt.Printf("no port\n")
			} else {
				port := strings.TrimSpace(string(sshPort))
				p, _ := strconv.Atoi(port)
				fmt.Printf("port (%d)\n", p)
			}
		}
	}

}

func oldcleanDB() {

	containers, err := dockerClient.ContainerList(ctx, types.ContainerListOptions{})
	//containerSet := set.NewSetFromSlice(containers)
	if err != nil {
		panic(err)
	}
	db.View(func(tx *bolt.Tx) error {
		// Assume bucket exists and has keys
		//b := tx.Bucket([]byte("State"))
		// TODO check whether DB contains a containerid in the slice containers
		return nil
	})

	fmt.Printf("%v\n", containers)

}

func stopChallengeBox(w http.ResponseWriter, r *http.Request) {

	// Get uid
	uids, ok := r.URL.Query()["uid"]
	if !ok || len(uids[0]) < 1 {
		log.Println("stopChallengeBox 'uid' is missing")
		fmt.Fprintf(w, "ko")
		return
	}
	uid := uids[0]
	log.Println("stopChallengeBox 'uid' is: " + string(uid))

	// Is uid allowed ?

	// get Cid
	cids, ok := r.URL.Query()["cid"]
	if !ok || len(cids[0]) < 1 {
		log.Println("stopChallengeBox'cid' is missing")
		fmt.Fprintf(w, "ko")
		return
	}
	cid := cids[0]
	log.Println("stopChallengeBox 'cid' is: " + string(cid))

	// find entry
	var cindex int = -1
	for index, chall := range challenges {
		//log.Println("Search: " + string(chall.Id))
		if chall.Id == cid {
			cindex = index
		}
	}
	if cindex == -1 {

		log.Println("stopChallengeBox cid not found : " + string(cid))
		fmt.Fprintf(w, "ko")
		return
	}

	// Existe ?
	boxID := getChallengeBox(
		challenges[cindex].Image,
		uid,
	)

	// Stop
	if boxID != "" {
		log.Println("stopChallengeBox stopping : " + boxID)
		err := dockerClient.ContainerStop(ctx, boxID, nil)
		if err != nil {
			fmt.Fprintf(w, "Problem stopping")

		} else {
			fmt.Fprintf(w, "Stopped")
		}
		return
	}
	fmt.Fprintf(w, "Cant find box")
}

func statusChallengeBox(w http.ResponseWriter, r *http.Request) {

	// Get uid
	uids, ok := r.URL.Query()["uid"]
	if !ok || len(uids[0]) < 1 {
		log.Println("statusChallengeBox 'uid' is missing")
		fmt.Fprintf(w, "ko")
		return
	}
	uid := uids[0]
	log.Println("statusChallengeBox 'uid' is: " + string(uid))

	// Is uid allowed ?

	// get Cid
	cids, ok := r.URL.Query()["cid"]
	if !ok || len(cids[0]) < 1 {
		log.Println("statusChallengeBox'cid' is missing")
		fmt.Fprintf(w, "ko")
		return
	}
	cid := cids[0]
	log.Println("statusChallengeBox 'cid' is: " + string(cid))

	// find entry
	var cindex int = -1
	for index, chall := range challenges {
		log.Println("Search: " + string(chall.Id))
		if chall.Id == cid {
			cindex = index
		}
	}
	if cindex == -1 {

		log.Println("cid not found : " + string(cid))
		fmt.Fprintf(w, "ko")
		return
	}

	// Existe ?
	boxID := getChallengeBox(
		challenges[cindex].Image,
		uid,
	)

	if boxID != "" {
		sshPort, err := getHostSSHPort(string(boxID))
		if err != nil {
			fmt.Fprintf(w, "Ko")

		} else {
			fmt.Fprintf(w, "{\"Name\":\"%s\", \"Port\":\"%s\"}", challenges[cindex].Image+"_"+uid, sshPort)
		}
		return
	}
	fmt.Fprintf(w, "Ko")
}

func cleanDB() {

	containers, err := dockerClient.ContainerList(ctx, types.ContainerListOptions{})
	//containerSet := set.NewSetFromSlice(containers)
	if err != nil {
		panic(err)
	}
	//count := len(containers)
	//log.Printf("-- Watch : %d containers", count)
	for _, cont := range containers {
		//fmt.Printf("ID [%s] [%s]\n", cont.ID, cont.Names[0])
		format := "2006-01-02 15:04:05"
		ctf_start_time, prs := cont.Labels["ctf-start-time"]
		ctf_duration, prs := cont.Labels["ctf-duration"]
		sec, _ := strconv.Atoi(ctf_duration)
		if prs {
			start_time, _ := time.Parse(format, ctf_start_time)
			//2019-05-17 12:10:58.312295116 +0000 UTC m=+60.300201465]
			end_time := start_time.Add(time.Second * time.Duration(sec))
			//fmt.Printf("Start [%s][%s] Duration [%s] End[%s] Now[%s]\n",
			//	ctf_start_time, start_time.Format(format),
			//	ctf_duration, end_time, time.Now().String())
			if end_time.Before(time.Now()) {
				//fmt.Println("Terminate")
				terminateContainer(cont.ID)
			}
			//fmt.Println(cont.Labels)

		}
	}
	//2019-05-18 09:23:21

}

func Keys(m map[string]interface{}) (keys []string) {
	for k := range m {
		keys = append(keys, k)
	}
	return keys
}

type Yolo struct {
	Id       string
	Image    string
	Port     string
	Duration string
}

func readConfigFile(filename string) {
	var chall Challenge
	/*
		var yolo Yolo
		bb := []byte(`{ "Id":"1", "Image":"ctf", "Port": "22"}`)
		err := json.Unmarshal(bb, &yolo)
		if err != nil {
			log.Printf("Error parsing %s", err.Error)
			return
		}
		fmt.Println(yolo)
	*/
	//b := []byte(`{ "Name":"Bob","Food":"Pickle"}`)
	//b := []byte(`{ "id":"1","image":"ctf-tool-xterm"}`)

	fmt.Printf("readConfigFile(%s)\n", filename)
	content, err := ioutil.ReadFile(filename)
	if err != nil {
		log.Printf("Error opening file %s", err.Error())
		return
	}
	//fmt.Println(content)
	lines := strings.Split(string(content), "\n")
	for _, ch := range lines {
		fmt.Println("Parsing [" + ch + "]")
		s := strings.Split(ch, "#")
		ch1 := s[0]
		if strings.Contains(ch1, "id") {
			err = json.Unmarshal([]byte(ch1), &chall)
			if err != nil {
				log.Printf("Error parsing %s", err.Error())
				return
			}
			fmt.Println(chall)
			challenges = append(challenges, chall)
		}
	}

	/*
		jsonFile, err := os.Open(fn)
		if err != nil {
			log.Printf("Can t read config file %s", err.Error)
			return;
		}
		defer jsonFile.Close()
		byteValue, err1 := ioutil.ReadAll(jsonFile)
		if err1 != nil {
			log.Printf("Error read all",  err1.Error)
			return;
		}


		var challs Challenges
		err = json.Unmarshal(byteValue, &challs)
		if err != nil {
			log.Printf("Error parsing %s", err.Error)
			return;
		}
		fmt.Println(challs)
	*/
	/*
		var result map[string]interface{}
		json.Unmarshal([]byte(byteValue), &result)
		fmt.Println(result)
		fmt.Println(result["challenges"])
		for _, ch := range Keys(result) {
			fmt.Println(ch)
			//challenges = append(challenges, ch)
		}

	*/
	fmt.Printf("readConfigFile(): ok")
}

func main() {
	/*
		chall := [11]challenge{
			// xterm
			challenge{"1", "ctf-tool-xterm", 3000, 30 * 3600}, // 3h
			// challenges
			challenge{"2", "ctf-shell", 22, 10 * 60 * 60}, // 10 min
			challenge{"3", "ctf-sqli", 22, 10 * 60 * 60},
			challenge{"4", "ctf-escalation", 80, 10 * 60 * 60},
			challenge{"5", "ctf-buffer", 22, 10 * 60 * 60},
			challenge{"6", "ctf-transfert", 22, 10 * 60 * 60},
			challenge{"7", "ctf-exploit", 22, 10 * 60 * 60},

			challenge{"ctf-tcpserver", "ctf-tcpserver", 22, 10 * 60 * 60},
			challenge{"ctf-telnet", "ctf-telnet", 22, 10 * 60 * 60},
			challenge{"ctf-ftp", "ctf-ftp", 22, 10 * 60 * 60},
			challenge{"ctf-smtp", "ctf-smtp", 22, 10 * 60 * 60},
		}


		for _, ch := range chall {
			challenges = append(challenges, ch)
		}
			//challenges = chall
	*/
	net_1 = 16
	net_2 = 1
	flag.Parse()

	readConfigFile("/var/challenge-box-provider/challenge-box-provider.cfg")
	fmt.Println(challenges)

	go func() {
		for {
			// Wait for 10s.
			cleanDB()
			time.Sleep(10 * time.Second)
		}
	}()

	//fmt.Println("==\n")
	//fmt.Println(getChallengeBox("ctf-transfert", "23"))
	//fmt.Println("==\n")

	//http.Handle("/", http.FileServer(http.Dir("./src")))

	http.HandleFunc("/listChallengeBox/", listChallengeBox)

	http.HandleFunc("/createUserNet/", createUserNet)
	//http.HandleFunc("/createUserTerm/", createUserTerm)
	http.HandleFunc("/createChallengeBox/", createChallengeBox)
	http.HandleFunc("/statusChallengeBox/", statusChallengeBox)
	http.HandleFunc("/stopChallengeBox/", stopChallengeBox)
	//fmt.Printf("Net id =%s ==", getNetworkId("22"))
	log.Fatal(http.ListenAndServe(httpServerListener, nil))

}
