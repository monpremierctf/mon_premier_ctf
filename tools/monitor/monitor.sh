#!/bin/bash
#
# Use CAdvisor to monitor the server & docker containers
#
# http://localhost:9999

docker run --volume=/:/rootfs:ro --volume=/var/run:/var/run:rw --volume=/sys:/sys:ro --volume=/var/lib/docker/:/var/lib/docker:ro  --publish=9999:8080  --detach=true --name=cadvisor  google/cadvisor:latest
