docker-compose build
docker run -it --rm --cap-add=SYS_PTRACE --security-opt seccomp=unconfined ctf-buffer:latest /bin/bash
