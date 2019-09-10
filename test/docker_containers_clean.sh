docker kill $(docker ps -a | grep ctf- | awk ' { print $1 } ')
docker rm   $(docker ps -a | grep ctf- | awk ' { print $1 } ')