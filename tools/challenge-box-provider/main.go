package main

import (
	"bytes"
	"context"
	"flag"
	"fmt"
	"log"
	"net/http"
	"os/exec"
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
	id    string
	image string
	port  int
}

var (
	challengeBoxDockerImage    string
	challengeBoxDockerPort     int
	challengeBoxDockerLifespan int
	httpServerListener         string

	db           *bolt.DB
	dockerClient *client.Client
	ctx          = context.Background()
	challenges   [2]challenge
)

func init() {
	// Set program flags
	flag.StringVar(&challengeBoxDockerImage, "image", "ubuntu", "Docker image")
	flag.IntVar(&challengeBoxDockerPort, "port", 22, "Container exposed port to dynamically map on the host")
	flag.IntVar(&challengeBoxDockerLifespan, "life", 60, "Challenge lifetime before the box closes")
	flag.StringVar(&httpServerListener, "listen", "0.0.0.0:8080", "Address:Port the http server will bind to")

	// check docker requirement
	_, err := exec.LookPath("docker")
	if err != nil {
		log.Fatalf("Error Docker not found : %s", err)
	}

	// Instantiate a BBolt Database with a bucket dedicated for the configuration
	db, err = bolt.Open("./state.db", 0600, nil)
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

	dockerClient, err = client.NewClientWithOpts(client.WithVersion("1.38"))
	if err != nil {
		panic(err)
	}

}

//
func createNewChallengeBox(box string, duration, port int, uid string) (containerID string, err error) {
	ctx := context.Background()

	// Labels
	labels := map[string]string{
		"ctf-uid":        fmt.Sprintf("CTF_UID_%s", uid),
		"ctf-start-time": time.Now().String(),
		"ctf-duration":   string(strconv.Itoa(duration))}

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

	if err := dockerClient.NetworkConnect(ctx, "a986c74b90dc", resp.ID, nil); err != nil {
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

func createNewUserNet(uid string) (containerID []byte, err error) {
	containerIDDirty, err := exec.Command(
		"docker", "network", "create",
		"--label", fmt.Sprintf("ctf-uid=CTF_UID_%s", uid),
		fmt.Sprintf("Net_%s", uid),
	).Output()
	containerID = bytes.TrimSpace(containerIDDirty)
	return
}

func oldcreateNewChallengeBox(box string, duration, port int) (containerID []byte, err error) {
	containerIDDirty, err := exec.Command(
		"docker", "container", "run",
		"--detach", "--rm",
		"--publish", fmt.Sprintf("%d", port),
		fmt.Sprintf("%s", box),
		"sleep", fmt.Sprintf("%d", duration)).Output()
	containerID = bytes.TrimSpace(containerIDDirty)
	return
}

func getHostSSHPort(containerID string) (port []byte, err error) {
	port, err = exec.Command(
		"docker", "inspect",
		"-f", "{{range $p, $conf := .NetworkSettings.Ports}} {{(index $conf 0).HostPort}} {{end}}",
		fmt.Sprintf("%s", containerID)).Output()
	if err != nil {
		port = bytes.TrimSpace(port)
	}
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

func listChallengeBox(w http.ResponseWriter, r *http.Request) {
	json := "["
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
			json += "{"
			json += "'ID'='" + cont.ID + "'"
			fmt.Println(cont.Names)
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
			json += "},\n"
		}
	}
	json += "]"
	fmt.Fprintf(w, json)
	fmt.Println(json)
}

func createChallengeBox(w http.ResponseWriter, r *http.Request) {
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
		log.Println("port : " + string(strconv.Itoa(challenges[cindex].port)))
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

		sshPort, err := getHostSSHPort(string(boxID))
		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
		} else {
			fmt.Fprintf(w, "A new challenge box has been created : available via SSH for %d seconds on port %s", challengeBoxDockerLifespan, sshPort)
		}

	} else {
		log.Println("cid not found : " + string(cid))
	}
}

func createUserNet(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintf(w, "Create UserNet")

	// Get uid
	uids, ok := r.URL.Query()["uid"]
	if !ok || len(uids[0]) < 1 {
		log.Println("Url Param 'uid' is missing")
		return
	}
	uid := uids[0]
	log.Println("Url Param 'uid' is: " + string(uid))

	// create docker
	boxID, err := createNewUserNet(uid)

	if err != nil {
		log.Println("error: " + err.Error())
		http.Error(w, err.Error(), http.StatusInternalServerError)
	} else {
		log.Println("boxID is: " + string(boxID))
	}

}

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

func cleanDB() {

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

func main() {
	chall := [2]challenge{
		// xterm
		challenge{"1", "xtermjs3130_xtermjs", 3000},
		// challenges
		challenge{"2", "ctf-transfert", 22},
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

	//http.Handle("/", http.FileServer(http.Dir("./src")))
	http.HandleFunc("/provide/", provideChallengeBox)
	http.HandleFunc("/listChallengeBox/", listChallengeBox)

	http.HandleFunc("/createUserNet/", createUserNet)
	http.HandleFunc("/createUserTerm/", createUserTerm)
	http.HandleFunc("/createChallengeBox/", createChallengeBox)

	log.Fatal(http.ListenAndServe(httpServerListener, nil))

}
