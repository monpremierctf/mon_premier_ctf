import requests
import random
import string
import sys
import time
import json
from pprint import pprint
from random import randint
import os
import threading
import signal

# Generate random name
VOWELS = "aeiou"
CONSONANTS = "".join(set(string.ascii_lowercase) - set(VOWELS))
def generate_name(length):
    word = ""
    for i in range(length):
        if i % 2 == 0:
            word += random.choice(CONSONANTS)
        else:
            word += random.choice(VOWELS)
    return word


def init():
    requests.packages.urllib3.disable_warnings()
    return


userCount=0

class UserSession():
    def __init__(self):
        global userCount
        userCount = userCount+1
        self.id = userCount
        self.uid=""
        self.session = requests.Session()
        self.session.verify = False
        self.login = generate_name(8)
        self.password = generate_name(8)
        self.mail = generate_name(8)
        self.code = 'yolo'
        self.skill = randint(2, 8)
        self.flag_count=0
        self.container_count=0
        self.xterm = False


def test():
    user  = UserSession()
    resp = user.session.get('http://google.com')
    print(resp)
    print(user.session.cookies.get_dict())
    return



def register_user(user, login, password, mail, code):
    resp = user.session.get('https://localhost/yoloctf/index.php')
    #print(resp)
    resp = user.session.get('https://localhost/yoloctf/register.php')
    #print(resp)
    payload = {'login':login, 'password':password, 'mail':mail, 'code':code}
    resp = user.session.post(url='https://localhost/yoloctf/register.php', data=payload)
    #print(resp)
    #print(user.session.cookies.get_dict())
    #print(resp.text + '...')
    # On est d accord, pas de test avec une balise html comme nom.. hein ?
    if (resp.text.find(login)>0):
        return True
    return False



def login_user(login,password):
    resp = user1.session.get('https://localhost/yoloctf/login.php', verify=False)    
    return


def get_terminal_id(txt):
    # https://localhost/ctf-tool-xterm_5d148a3a08019/"
    start = txt.find("ctf-tool-xterm_")
    end   = txt.find("\"", start)
    xterm_name = txt[start:end]
    #print (xterm_name)
    return xterm_name


def open_terminal(user):
    resp = user.session.get('https://localhost/yoloctf/my_term.php')
    #print(resp)
    #print(resp.text)
    id = get_terminal_id(resp.text)
    user.uid = id[15:-1]
    #print("====> uid="+user.uid)
    #print (id)
    time.sleep(5)  # 5 seconds
    resp = user.session.get('https://localhost/'+id)
    #print(resp)
    

def create_container(user, cont_id):
    resp = user.session.get('https://localhost/yoloctf/containers_cmd.php?create='+cont_id)
    #print(resp.text)
    if (resp.text.find("Name")>0):
        return True
    return False

def terminate_container(user, cont_id):
    resp = user.session.get('https://localhost/yoloctf/containers_cmd.php?terminate='+cont_id)
    print(resp.text)


def load_flags():
    # ../web_server/www_site/yoloctf/db/flags.json 
    with open('../web_server/www_site/yoloctf/db/flags.json') as f:
        flags = json.load(f)
    #pprint(flags)
    return flags


def validate_flag(user, chal_id, flag):
    # https://localhost/yoloctf/is_flag_valid.php?id=1&flag=373c51258167377b8a81168f11aea626
    #print (chal_id, flag)
    resp = user.session.get('https://localhost/yoloctf/is_flag_valid.php?id='+str(chal_id)+'&flag='+flag)
    #print(resp.text)
    if (resp.text.find("ok")>0):
        return True
    return False


def cmd_sqlmap(user):
    # docker exec  ctf-tool-xterm_5d161ec2da045 /opt/sqlmap/sqlmap.py -u http://ctf-sqli/getmsg.php?idmsg=673489 --tables
    return

def cmd_cpu_load(user):
    # 100% load during 10s
    # docker exec  ctf-tool-xterm_5d161ec2da045 timeout 10  dd if=/dev/zero of=/dev/null
    os.system("docker exec  ctf-tool-xterm_"+user.uid+" timeout 10  dd if=/dev/zero of=/dev/null")
    return


def run_rand_cmd(u):
    if (u.uid == ""):
        print "["+str(u.id)+"] uid not set "
        return
    
    target = randint(0, u.container_count)
    if (target<1): 
        print "["+str(u.id)+"] no container "
        return
    
    if (target==1):
        return

    if (target==2):
        return
    #cmd_cpu_load(user)
    
    return



users=[]

containers = [
    "ctf-shell",  
    "ctf-escalation",  
    "ctf-buffer",  
    "ctf-transfert", 
    "ctf-exploit", 
    "ctf-tcpserver", 
]


def scenario_serial(nbUserMax, noxterm, nocontainer, maxsleep):
    init()
    flags = load_flags()
    print ("Registering "+str(nbUserMax)+" users : ")
    for x in range(nbUserMax):
        user1  = UserSession()
        if (register_user(user1, user1.login, user1.password, user1.mail, 'yolo')):
            users.append(user1)
            print "."+str(user1.id)
    print ("Registered "+str(len(users))+" users.")

    # CTF ongoing
    nb_xterm=0
    nb_containers=0
    totalflag= len(flags['results']) * len(users)
    while (totalflag>0):
        print("")
        print ("=======================")
        print ("| Nb User       : "+str(len(users)))
        print ("| Nb Flags left : "+str(totalflag))
        print ("| Nb xterm      : "+str(nb_xterm))
        print ("| Nb containers : "+str(nb_containers))
        for u in users:
            #
            # Flags
            if (u.flag_count<len(flags['results'])):
                chal_id = flags['results'][u.flag_count]['challenge_id']
                # Try the right flag or a false one ?
                if (randint(0, 9)<u.skill):
                    flag = flags['results'][u.flag_count]['content']
                    u.flag_count = u.flag_count+1
                    totalflag = totalflag-1
                else:
                    flag = generate_name(12)
                print "["+str(u.id)+"] Send Flag "+str(chal_id)+" : ["+flag+"]"
                validate_flag(u, chal_id, flag)
                
            #
            # xterm
            if (not noxterm):
                if (not u.xterm):
                    if (randint(0, 9)>=8):    
                        starttime = time.time()
                        print "["+str(u.id)+"] Open Terminal"
                        open_terminal(u)
                        duration = time.time() - starttime
                        u.xterm=True
                        nb_xterm=nb_xterm+1
                        print "["+str(u.id)+"] Opened Terminal in "+str(round(duration))
                        #print "nb_xterm => "+str(nb_xterm) 

            #
            # Create container
            if (not nocontainer):
                if (u.container_count<len(containers)):
                    if (randint(0, 9)>=8):
                        cont_id = containers[u.container_count]
                        u.container_count= u.container_count+1
                        print "["+str(u.id)+"] Create container " +cont_id  
                        starttime = time.time()    
                        create_container(u, cont_id)
                        duration = time.time() - starttime
                        print "["+str(u.id)+"] Created container in "+str(round(duration))
                        nb_containers=nb_containers+1
                        #print "nb_containers => "+str(nb_containers)

                #
                # Run cmd in container
                if (randint(0, 9)>=5):
                    print "["+str(u.id)+"] start cmd in container "
                    run_rand_cmd(u)
                    print "["+str(u.id)+"] stop cmd in container "

        time.sleep(randint(2, maxsleep))


    ## Destroy all containers
    time.sleep(5)  # 5 seconds
    print ("Terminate containers")
    for u in users:
        for c in containers:
            terminate_container(u, c)
    return




SHOULD_TERMINATE = False

def run_user_journey(u, flags):
    time.sleep(randint(2, 4))
    print ("["+str(u.id)+"] Register ")
    register_user(u, u.login, u.password, u.mail, 'yolo')


    totalflag= len(flags['results']) 
    while (totalflag>0):
        #
        # Flags
        if (u.flag_count<len(flags['results'])):
            chal_id = flags['results'][u.flag_count]['challenge_id']
            # Try the right flag or a false one ?
            if (randint(0, 9)<u.skill):
                flag = flags['results'][u.flag_count]['content']
                u.flag_count = u.flag_count+1
                totalflag = totalflag-1
            else:
                flag = generate_name(12)
            print "["+str(u.id)+"] Send Flag "+str(chal_id)+" : ["+flag+"]"
            validate_flag(u, chal_id, flag)
            
        #
        # xterm
        if (not u.xterm):
            if (randint(0, 9)>=8):    
                starttime = time.time()
                print "["+str(u.id)+"] Open Terminal"
                open_terminal(u)
                duration = time.time() - starttime
                u.xterm=True
                print "["+str(u.id)+"] Opened Terminal in "+str(round(duration))
                #print "nb_xterm => "+str(nb_xterm) 

        #
        # Create container
        if (u.container_count<len(containers)):
            if (randint(0, 9)>=8):
                cont_id = containers[u.container_count]
                u.container_count= u.container_count+1
                print "["+str(u.id)+"] Create container " +cont_id  
                starttime = time.time()    
                create_container(u, cont_id)
                duration = time.time() - starttime
                print "["+str(u.id)+"] Created container in "+str(round(duration))
                #print "nb_containers => "+str(nb_containers)

        #
        # Run cmd in container
        if (randint(0, 9)>=5):
            print "["+str(u.id)+"] start cmd in container "
            run_rand_cmd(u)
            print "["+str(u.id)+"] stop cmd in container "


        if SHOULD_TERMINATE:
            return

    time.sleep(randint(2, 4))


    
    return

thread_list = []

def scenario_parallel(nbUserMax):
    init()
    flags = load_flags()
    for x in range(nbUserMax):
        user  = UserSession()
        users.append(user)
        t = threading.Thread(target=run_user_journey, args = (user,flags, ))
        thread_list.append(t)
        #t.daemon = True
    for thread in thread_list:
        thread.start()



class ServiceExit(Exception):
    """
    Custom exception which is used to trigger the clean exit
    of all running threads and the main program.
    """
    pass
 
 
def service_shutdown(signum, frame):
    print('Caught signal %d' % signum)
    raise ServiceExit


#
# Main
#
if __name__ == '__main__':
    signal.signal(signal.SIGTERM, service_shutdown)
    signal.signal(signal.SIGINT, service_shutdown)

    # Init
    print ("= Init")
    #scenario_serial(150, True, True, 2)
    #exit()

    # Register users
    nbUserMax = 10
    #scenario_serial(nbUserMax)
    try:
        scenario_parallel(nbUserMax)
        while True:
            time.sleep(0.5)
    except ServiceExit:
        # Terminate the running threads.
        # Set the shutdown flag on each thread to trigger a clean shutdown of each thread.
        SHOULD_TERMINATE = True
        for thread in thread_list:
            thread.join()
        exit()


    

    
    
    
    

