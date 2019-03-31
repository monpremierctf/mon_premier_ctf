#!/bin/bash


CMD=/usr/sbin/sshd
IMAGE=ctf-transfert
LIFE=600
LISTEN=0.0.0.0:9090
EXPOSEDPORT=22

echo Starting challenge_box_provider
echo Logs in /tmp/challenge_box_provider.log
#./challenge-box-provider -cmd $CMD -image $IMAGE -life $LIFE -listen $LISTEN -port $EXPOSEDPORT  &> /tmp/challenge_box_provider.log &
./challenge-box-provider -cmd $CMD -image $IMAGE -life $LIFE -listen $LISTEN -port $EXPOSEDPORT  &> /dev/null &
