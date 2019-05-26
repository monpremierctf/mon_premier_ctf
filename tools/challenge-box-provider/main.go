package main

import (
	"context"
	"flag"
	"fmt"
	"log"
	"net/http"
	"strconv"
	"strings"
	"time"

	//"io/ioutil"

	"github.com/docker/docker/api/types"
	"github.com/docker/docker/api/types/container"
	"github.com/docker/docker/client"

	// Fix: mv ~/go_workspace//src/github.com/docker/docker/vendor/github.com/docker/go-connections/{nat,nat.old}

	bolt "go.etcd.io/bbolt"
)

type challenge struct {
	id       string
	image    string
	port     int
	duration int
}

var (
	challengeBoxDockerImage    string
	challengeBoxDockerPort     int
	challengeBoxDockerLifespan int
	httpServerListener         string

	db           *bolt.DB
	dockerClient *client.Client
	ctx          = context.Background()
	challenges   [11]challenge
)

const CONST_USER_NET_DURATION = 3600

func init() {
	// Set program flags
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
func getNetworkId(uid string) (networkID string) {

	networkID = ""
	networks, err := dockerClient.NetworkList(ctx, types.NetworkListOptions{})
	if err != nil {
		log.Printf("ERROR: getNetworkId() %s", err.Error())
	}
	netId := "Net_" + uid
	for _, network := range networks {
		//fmt.Printf("[%s]-[%s]\n", network.Name, network.ID)
		if network.Name == netId {
			networkID = network.ID
		}
	}
	return
}

//
// Create New Challenge Container
//
func createNewChallengeBox(box string, duration, port int, uid string) (containerID string, err error) {
	ctx := context.Background()

	// Labels
	labels := map[string]string{
		"ctf-uid": fmt.Sprintf("CTF_UID_%s", uid),
		"ctf-start-time": time.Now().Format("2006-01-02 15:04:05"),
		"ctf-duration":   string(strconv.Itoa(duration))}
		"traefik.enable":        "true",                               //traefik.enable=true
		"traefik.frontend.rule": fmt.Sprintf("PathPrefix:/%s_%s/", box, uid), //traefik.frontend.rule=Path:/yoloboard

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
	// Create
	resp, err := dockerClient.ContainerCreate(ctx,
		&container.Config{
			Image:    box,
			Labels:   labels,
			Hostname: box,
		},
		&container.HostConfig{
			AutoRemove:      true,
			PublishAllPorts: true,
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

	nid := getNetworkId(uid)
	if nid == "" {
		nid, _ = createNewUserNet(uid, 3600)
	}
	if err := dockerClient.NetworkConnect(ctx, nid, resp.ID, nil); err != nil {
		panic(err)
	}
	if err := dockerClient.ContainerStart(ctx, resp.ID, types.ContainerStartOptions{}); err != nil {
		panic(err)
	}

	fmt.Println(resp.ID)
	containerID = resp.ID
	/*
		containerIDDirty, err := exec.Command(
			"docker", "container", "run",
			"--detach", "--rm",
			"--label", fmt.Sprintf("ctf-uid=CTF_UID_%s", uid),
			"--label", fmt.Sprintf("ctf-start-time=%s", time.Now().String()),
			"--label", fmt.Sprintf("ctf-duration=%s", string(strconv.Itoa(duration))),
			"--network", fmt.Sprintf("Net_%s", uid),
			"--hostname", fmt.Sprintf("%s", box),
			"--name", fmt.Sprintf("%s_%s", box, uid),
			"--publish", fmt.Sprintf("%d", port),
			fmt.Sprintf("%s", box),
		).Output()
		//containerID = bytes.TrimSpace(containerIDDirty)
	*/
	return
}

func createNewUserNet(uid string, duration int) (containerID string, err error) {
	labels := map[string]string{
		"ctf-uid":        fmt.Sprintf("CTF_UID_%s", uid),
		"ctf-start-time": time.Now().String(),
		"ctf-duration":   string(strconv.Itoa(duration))}

	resp, err := dockerClient.NetworkCreate(ctx, fmt.Sprintf("Net_%s", uid), types.NetworkCreate{Labels: labels})
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

func oldcreateNewChallengeBox(box string, duration, port int) (containerID []byte, err error) {
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

func provideChallengeBox(w http.ResponseWriter, r *http.Request) {
	db, err := bolt.Open("./state.db", 0600, nil)
	if err != nil {
		log.Fatalf("Error creating Bbolt DB : %s", err)
	}
	defer db.Close()

	var srcIP string
	srcIP = strings.Split(r.RemoteAddr, ":")[0]

	db.View(func(tx *bolt.Tx) error {
		b := tx.Bucket([]byte("State"))
		containerID := b.Get([]byte(srcIP))

		if containerID == nil {
			log.Printf("Source IP %s is not known: creating a new challenge box.", srcIP)
			boxID, err := oldcreateNewChallengeBox(challengeBoxDockerImage, challengeBoxDockerLifespan, challengeBoxDockerPort)
			if err != nil {
				http.Error(w, err.Error(), http.StatusInternalServerError)
			}
			db.Update(func(tx *bolt.Tx) error {
				b := tx.Bucket([]byte("State"))
				err := b.Put([]byte(srcIP), boxID)
				return err
			})

			sshPort, err := getHostSSHPort(string(boxID))
			if err != nil {
				http.Error(w, err.Error(), http.StatusInternalServerError)
			}

			fmt.Fprintf(w, "A new challenge box has been created : available via SSH for %d seconds on port %s", challengeBoxDockerLifespan, sshPort)

		} else {
			log.Printf("Source IP %s has already a challenge box : %s", srcIP, containerID)
			sshPort, err := getHostSSHPort(string(containerID))
			if err != nil {
				http.Error(w, err.Error(), http.StatusInternalServerError)
			}
			log.Printf("The port associated with SSH in the box is %s", sshPort)

			fmt.Fprintf(w, "Picking an existing Challenge box on port %s", sshPort)

		}
		return nil
	})

}

func terminateContainer(containerID string) error {
	fmt.Printf("Terminate [%s]\n", containerID)
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
		log.Println("Search: " + string(chall.id))
		if chall.id == cid {
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
		challenges[cindex].image,
		uid,
	)

	if boxID != "" {
		sshPort, err := getHostSSHPort(string(boxID))
		if err != nil {
			fmt.Fprintf(w, "Found box")
		} else {
			fmt.Fprintf(w, "{\"Name\":\"%s\", \"Port\":\"%s\"}", challenges[cindex].image+"_"+uid, sshPort)
		}
		return
	}
	// create docker
	log.Println("Starting new docker box")
	log.Println("id   : " + string(challenges[cindex].id))
	log.Println("image: " + string(challenges[cindex].image))
	log.Println("port : " + string(strconv.Itoa(challenges[cindex].port)))
	boxID, err := createNewChallengeBox(
		challenges[cindex].image,
		challenges[cindex].duration,
		challenges[cindex].port,
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
		fmt.Fprintf(w, "{\"Name\":\"%s\", \"Port\":\"%s\"}", challenges[cindex].image+"_"+uid, sshPort)
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
		log.Println("Search: " + string(chall.id))
		if chall.id == cid {
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
		challenges[cindex].image,
		uid,
	)

	if boxID != "" {
		sshPort, err := getHostSSHPort(string(boxID))
		if err != nil {
			fmt.Fprintf(w, "Ko")

		} else {
			fmt.Fprintf(w, "{\"Name\":\"%s\", \"Port\":\"%s\"}", challenges[cindex].image+"_"+uid, sshPort)
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
	count := len(containers)
	log.Printf("-- Watch : %d containers", count)
	for _, cont := range containers {
		fmt.Printf("ID [%s] [%s]\n", cont.ID, cont.Names[0])
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

func main() {
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
	challenges = chall
	fmt.Println(challenges)
	flag.Parse()

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
	//http.HandleFunc("/provide/", provideChallengeBox)
	http.HandleFunc("/listChallengeBox/", listChallengeBox)

	http.HandleFunc("/createUserNet/", createUserNet)
	//http.HandleFunc("/createUserTerm/", createUserTerm)
	http.HandleFunc("/createChallengeBox/", createChallengeBox)
	http.HandleFunc("/statusChallengeBox/", statusChallengeBox)
	//fmt.Printf("Net id =%s ==", getNetworkId("22"))
	log.Fatal(http.ListenAndServe(httpServerListener, nil))

}
