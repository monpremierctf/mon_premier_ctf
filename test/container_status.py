#
# pip install docker
#


import docker
from pprint import pprint


ctfInfraNames = [
    "traefik",

    "webserver_nginx",
    "webserver_php",
    "webserver_mysql",

    "challenge-box-provider",

]

ctfSharedChallengesNames = [

    "ctf-sqli_nginx",
    "ctf-sqli_php",
    "ctf-sqli_mysql",

    "ctf-passwd-web",
    "ctf-passwd-php"
]

def listContainers(client):
    print "==== Containers ===="
    for container in client.containers.list():
        print container.id, container.name, container.status
        if container.name in ctfInfraNames:
            print "ctfInfraNames"
        if container.name in ctfSharedChallengesNames:
            print "ctfSharedChallengesNames"   
        if 'ctf-uid' in container.labels:
            print "ctfContainer"
        #pprint (container.labels)


#
# Main
#
if __name__ == '__main__':
    client = docker.from_env()


    listContainers(client)

    print ""
    print "==== Networks ===="
    for network in client.networks.list():
        network.reload()
        print "["+network.name+"]"
        #pprint (network.containers)
        for c in network.containers:
            print " - "+c.name
        print("")

    print ("")
    #c_traefik = client.containers.get("traefik")
    #print c_traefik.id, c_traefik.name, c_traefik.status
    #print ("")