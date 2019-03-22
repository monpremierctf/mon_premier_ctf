#
#
# docker run -it --rm -v "$PWD":/go/src/myapp -w /go/src/myapp golang:1.12 go build -v
#
# docker build -t challenge-box-provider .
FROM golang:1.12

COPY main.go /go/src/myapp/main.go
WORKDIR /go/src/myapp
RUN go get .
RUN cp /go/bin/myapp /go/src/myapp/

# FROM docker:18.09-dind
#COPY --from=0  /go/bin/myapp /sbin/myapp 


#FROM golang:1.12
#COPY challenge-box-provider /sbin/challenge-box-provider 
