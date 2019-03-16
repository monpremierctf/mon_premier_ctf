import socket
import os
import time

UDP_IP=os.environ.get('CTFNET_IP_ALICE', "12.0.0.220")
UDP_PORT=8082
MESSAGE="flag_tu_3st_vr41m3nt_c3rt41n_qu3_c_3st_s3cur3_?"

#print("UDP target IP:", UDP_IP)
#print("UDP target port:", UDP_PORT)
#print("message:", MESSAGE)

while True:
    sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM) # UDP
    sock.sendto(bytes(MESSAGE, "utf-8"), (UDP_IP, UDP_PORT))
    time.sleep(1)
