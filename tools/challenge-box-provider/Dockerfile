#
#
# docker run -it --rm -v "$PWD":/go/src/myapp -w /go/src/myapp golang:1.12 go build -v
#
# docker build -t challenge-box-provider .
FROM golang:1.12

COPY main.go /go/src/challenge-box-provider/main.go
WORKDIR /go/src/challenge-box-provider
RUN go get .
RUN mv /go/src/github.com/docker/docker/vendor/github.com/docker/go-connections/{nat,nat.old}
RUN go build -v




FROM ubuntu:latest
COPY --from=0  /go/src/challenge-box-provider/challenge-box-provider /sbin/challenge-box-provider 


CMD ["/sbin/challenge-box-provider"]
 