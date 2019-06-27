docker network rm $(docker network ls | awk '{print $(2)}' | grep Net_)
