import requests
import random
import string
import sys
import time
import json
from pprint import pprint
from random import randint

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


class UserSession():
    def __init__(self):
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
    if (resp.text.find(login)):
        print("OK")
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
    print(resp)
    #print(resp.text)
    id = get_terminal_id(resp.text)
    print (id)
    time.sleep(5)  # 5 seconds
    resp = user.session.get('https://localhost/'+id)
    print(resp)
    

def create_container(user, cont_id):
    resp = user.session.get('https://localhost/yoloctf/containers_cmd.php?create='+cont_id)
    print(resp)

def terminate_container(user, cont_id):
    resp = user.session.get('https://localhost/yoloctf/containers_cmd.php?terminate='+cont_id)
    print(resp)


def load_flags():
    # ../web_server/www_site/yoloctf/db/flags.json 
    with open('../web_server/www_site/yoloctf/db/flags.json') as f:
        flags = json.load(f)
    #pprint(flags)
    return flags


def validate_flag(user, chal_id, flag):
    # https://localhost/yoloctf/is_flag_valid.php?id=1&flag=373c51258167377b8a81168f11aea626
    print (chal_id, flag)
    resp = user.session.get('https://localhost/yoloctf/is_flag_valid.php?id='+str(chal_id)+'&flag='+flag)
    print(resp)
    return


users=[]

containers = ["ctf-shell",  
    "ctf-escalation",  
    "ctf-buffer",  
    "ctf-transfert", 
    "ctf-exploit", 
    "ctf-tcpserver", 
]

if __name__ == '__main__':
    print ("= Init")
    init()
    flags = load_flags()

    print ("Register users")
    for x in range(50):
        user1  = UserSession()
        if (not register_user(user1, user1.login, user1.password, user1.mail, 'yolo')):
            print ("=> KO")
        else:
            users.append(user1)

    nb_xterm=0
    nb_containers=0
    print ("Users validate Flags")
    totalflag= len(flags['results']) * len(users)
    while (totalflag>0):
        for u in users:
            # Flags
            chal_id = flags['results'][u.flag_count]['challenge_id']
            if (randint(0, 9)<u.skill):
                if (u.flag_count<len(flags)):
                    flag = flags['results'][u.flag_count]['content']
                    u.flag_count = u.flag_count+1
                    totalflag = totalflag-1

            else:
                flag = generate_name(12)
            validate_flag(u, chal_id, flag)
            # xterm
            if (randint(0, 9)>=8):
                if (not u.xterm):
                    open_terminal(u)
                    print("open_terminal ")
                    u.xterm=True
                    nb_xterm=nb_xterm+1
                    print "nb_xterm => "+str(nb_xterm) 

            # Create container
            if (randint(0, 9)>=80):
                if (u.container_count<len(containers)):
                    cont_id = containers[u.container_count]
                    u.container_count= u.container_count+1
                    create_container(u, cont_id)
                    print("create "+cont_id)
                    nb_containers=nb_containers+1
                    print "nb_containers => "+str(nb_containers)
        time.sleep(randint(3, 8))
    exit()

    for f in flags['results']:
        validate_flag(user1, f['challenge_id'], f['content'])
        time.sleep(randint(3, 18))

    exit()

    print ("Open terminals")
    for u in users:
        open_terminal(u)

    print ("Create containers")
    for u in users:
        create_container(u, 'ctf-shell')
    
    time.sleep(5)  # 5 seconds
    print ("Terminate containers")
    for u in users:
        terminate_container(u, 'ctf-shell')

    
    
    
    

