#!/usr/bin/python
from BaseHTTPServer import BaseHTTPRequestHandler,HTTPServer
from urlparse import parse_qs
from urlparse import urlparse
from pprint import pprint
import os
import re

PORT_NUMBER = 9911

def ufw_status_raw():
    cmd = 'sudo ufw status numbered'
    myCmd = os.popen(cmd).read()
    print(myCmd)
    return myCmd

#
# [ 1] 22                         ALLOW IN    Anywhere
# [ 4] 3767                       ALLOW IN    203.0.113.4
# [ 7] 80 (v6)                    ALLOW IN    Anywhere (v6)
def ufw_status_find_id(ip,port):
    regexp = '^\[\s*(\d+)\]\s(\d+)\s*ALLOW IN\s*(.*)\s*'
    pattern = re.compile(regexp)
    cmd = 'sudo ufw status numbered'
    with os.popen(cmd) as fp:  
        line = fp.readline()
        cnt = 1
        while line:
            if (line[0]=='['):
                if (line.find("(v6)")<0):
                    #print("{}".format(line.strip()))
                    match = pattern.match(line)
                    if match:
                        rid = match.group(1)
                        rport = match.group(2)
                        rip = match.group(3).strip()
                        if  (rport==port):
                            if  (rip==ip):
                                return match.group(1)
            line = fp.readline()
    return -1            

# sudo ufw allow from 12.0.0.10 to any port 32772
def ufw_open(ip,port):
    cmd = "sudo ufw allow from "+ip+" to any port "+port
    myCmd = os.popen(cmd).read()
    return myCmd


# sudo ufw delete allow allow from 12.0.0.10 to any port 32772
def ufw_close(ip,port):
    print "close port"
    cmd = "sudo ufw delete allow from "+ip+" to any port "+port
    myCmd = os.popen(cmd).read()
    return myCmd



#This class will handles any incoming request from
#the browser 
class myHandler(BaseHTTPRequestHandler):
    
    #Handler for the GET requests
    def do_GET(self):
        print ("== Get received : "+self.path)
        parsed = urlparse(self.path)
        params = parse_qs(parsed.query)
        if 'cmd' in params:
            cmd  = params['cmd'][0]
            print("cmd="+cmd)
            if cmd=='status':
                ret = ufw_status_raw()
                self.send_response(200)
                self.send_header('Content-type','text/html')
                self.end_headers()
                self.wfile.write(ret)
                return
            if 'ip' in params:
                ip   = params['ip'][0]
                print("ip="+ip)
                if 'port' in params:
                    port = params['port'][0]
                    print("port="+port)
                    print ("["+cmd+" "+ip+" "+port+"]")
                    # Handle request
                    ret="Bad command"
                    if (cmd=='open'):
                        ret = ufw_open(ip,port)
                    if (cmd=='close'):
                        ret = ufw_close(ip,port)
                    # Send response
                    self.send_response(200)
                    self.send_header('Content-type','text/html')
                    self.end_headers()
                    self.wfile.write(ret)
                    return
        print ("Params ko")
        self.send_response(400)
        self.send_header('Content-type','text/html')
        self.end_headers()
        self.wfile.write("Bad request")
        return

try:
    #Create a web server and define the handler to manage the
    #incoming request
    server = HTTPServer(('', PORT_NUMBER), myHandler)
    print ('Started httpserver on port ' , PORT_NUMBER)
    
    #Wait forever for incoming htto requests
    server.serve_forever()

except KeyboardInterrupt:
    print ('^C received, shutting down the web server')
    server.socket.close()
    exit