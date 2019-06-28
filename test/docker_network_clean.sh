docker network rm $(docker network ls | awk '{print $(2)}' | grep Net_)
docker network prune -f
