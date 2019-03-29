#
# Generates a config file ready to be imported in CTFd
#

import ConfigParser
import urllib
import io
import json
import shutil
import os
import sys
import textwrap
from subprocess import Popen, PIPE

#
# Read challenges list from file : challenges_list.cfg
#
IPSERVER="127.0.0.1"

challenges_dir_list = [

]

def read_challenges_dir_list():
    global challenges_dir_list
    global IPSERVER
    print("Lecture de la liste de challenges : challenges_list.cfg")
    in_file = open("challenges_list.cfg", 'r')
    for line in in_file.readlines():
        line = textwrap.dedent(line)
        line = line.rstrip()
        if line=="":
            continue
        if line.startswith("#"):
            continue
        if line.startswith("["):
            end = line.find(']')
            ip = line[1:end]
            ip = textwrap.dedent(ip)
            ip = ip.rstrip()
            IPSERVER=ip
            print("Set IPSERVER="+ip)
            continue
        if (os.path.isdir(line)):
            challenges_dir_list.append(line)
        else:
            print("Erreur: Not dir ["+line+"]")
    in_file.close()
    #challenges_dir_list = challenges_dir_list[::-1]
    print(challenges_dir_list)


def dump_challenges_env():
    print "Dump challenges .env"
    read_challenges_dir_list()
    for challenge_dir in challenges_dir_list:
        print "\n["+challenge_dir+"/.env]"
        if (os.path.isfile(challenge_dir+'/.env')):
            cmd='cat '+challenge_dir+'/.env|grep "="'
            os.system(cmd)
            ##(Popen(["cat", ".env"], stdout=sys.stdout, stderr=sys.stderr, cwd=challenge_dir)).communicate()
        else:
            print "no .env file"


#
# Build, start and stop dockers challenges
# thanks to docker-compose.yml in each directory
#

def build_challenges():
    print "Build [ctf-sshd]"
    (Popen(["docker", "build", "-t","ctf-sshd","./ctf-sshd"], stdout=sys.stdout, stderr=sys.stderr)).communicate()
    print "Build [ctf-transfert]"
    (Popen(["docker", "build", "-t","ctf-transfert","./ctf-transfert"], stdout=sys.stdout, stderr=sys.stderr)).communicate()
    read_challenges_dir_list()
    for challenge_dir in challenges_dir_list:
        print "Build ["+challenge_dir+"]"
        if (os.path.isfile(challenge_dir+'/docker-compose.yml')):
            (Popen(["docker-compose", "build"], stdout=sys.stdout, stderr=sys.stderr, cwd=challenge_dir)).communicate()
        else:
            print "no docker-compose.yml file. Pass."

def start_challenges():
    read_challenges_dir_list()
    for challenge_dir in challenges_dir_list:
        print "Start ["+challenge_dir+"]"
        if (os.path.isfile(challenge_dir+'/docker-compose.yml')):
            (Popen(["docker-compose", "up", "-d"], stdout=sys.stdout, stderr=sys.stderr, cwd=challenge_dir)).communicate()
        else:
            print "no docker-compose.yml file. Pass."
        if (os.path.isfile(challenge_dir+'/challenge.sh')):
            (Popen(["./challenge.sh"], stdout=sys.stdout, stderr=sys.stderr, cwd=challenge_dir)).communicate()
        else:
            print "no challenge.sh file. Pass."


def stop_challenges():
    read_challenges_dir_list()
    for challenge_dir in challenges_dir_list:
        print "Stop ["+challenge_dir+"]"
        if (os.path.isfile(challenge_dir+'/docker-compose.yml')):
            (Popen(["docker-compose", "down"], stdout=sys.stdout, stderr=sys.stderr, cwd=challenge_dir)).communicate()
        else:
            print "no docker-compose.yml file. Pass."

#
# Populate global lists (challenges, flags, files) while reading file challenges.cfg
# in each directory

challenges=[]
challenge_id=0
flags=[]
flag_id=0
files=[]
file_id=0

def add_challenge(name, desc, value, category):
    global challenge_id
    challenge_id+=1
    challenges.append({
            "id": int(challenge_id), 
            "name": str(name), 
            "description": str(desc), 
            "max_attempts": 0, 
            "value": int (value), 
            "category": str(category), 
            "type": "standard", 
            "state": "visible", 
            "requirements": "null"
        })

def add_flag(flag):
    if flag=='':
        return
    global challenge_id
    global flag_id
    flag_id+=1
    flags.append({
        "id": int(flag_id), 
        "challenge_id": challenge_id, 
        "type": "static", 
        "content": str(flag), 
        "data": ""}
    )

def copy_file(challenge_dir, filename):
    src = challenge_dir+"/"+filename
    dst = challenge_dir+"/"+filename
    dst_tmp = "ctfd_config/tmp/uploads/"+dst
    directory = os.path.dirname(dst_tmp)
    if not os.path.exists(directory):
        os.makedirs(directory)
    print("- Copy ["+src+"]  => "+dst_tmp)
    shutil.copy2(src, dst_tmp)
    return dst

def add_file(challenge_dir, filename):
    if filename=='':
        return
    global challenge_id
    global file_id
    file_id+=1
    filename_dest = copy_file(challenge_dir, filename)
    files.append({
        "id": int(file_id), 
        "type": "challenge", 
        "location": str(filename_dest), 
        "challenge_id": int(challenge_id), 
        "page_id": None}
    )
    

def getParam(config, section, param):
    try:
        value = config.get(section, param)
    except:
        value=""
    return value



def parse_dir(challenge_dir):   
    config = ConfigParser.ConfigParser()
    config.read(challenge_dir+"/challenges.cfg")
    #print config.sections()

    for challenge in config.sections():
        print "- Processing "+challenge
        name = config.get(challenge, 'name')
        #name = name.encode('string-escape')
        desc = config.get(challenge, 'description')
        desc = desc.replace("IPSERVER", IPSERVER)
        #print(desc)
        #desc_enc = desc.encode('string-escape') #unicode-escape
        #print(desc_enc)
        value = config.get(challenge, 'value')
        category = config.get(challenge, 'category')
        #category = category.encode('string-escape')
        filename = getParam(config, challenge, 'file')
        flag = getParam(config, challenge, 'flag')
        #print(name)
        #print (description)
        add_challenge(name, desc, value, category)
        add_flag(flag)
        add_file(challenge_dir, filename) 

if __name__ == '__main__':
    read_challenges_dir_list()
    for challenge_dir in challenges_dir_list:
        print "Enter ["+challenge_dir+"]"
        parse_dir(challenge_dir)

    out_dir = "ctfd_config/tmp/db/"
    directory = os.path.dirname(out_dir)
    if not os.path.exists(directory):
        os.makedirs(directory)

    with io.open(out_dir+'challenges.json', 'w', encoding='utf8') as outfile:
        outfile.write(unicode('{"count": '+str(challenge_id)+', "results": '))
        str_ = json.dumps(challenges,
                        indent=4, sort_keys=False,
                        separators=(',', ': '), ensure_ascii=False)
        outfile.write(unicode(str_.decode('utf-8')))
        outfile.write(unicode(', "meta": {}}'))

    with io.open(out_dir+'flags.json', 'w', encoding='utf8') as outfile:
        outfile.write(unicode('{"count": '+str(flag_id)+', "results": '))
        str_ = json.dumps(flags,
                        indent=4, sort_keys=True,
                        separators=(',', ': '), ensure_ascii=False)
        outfile.write(unicode(str_))
        outfile.write(unicode(', "meta": {}}'))

    with io.open(out_dir+'files.json', 'w', encoding='utf8') as outfile:
        outfile.write(unicode('{"count": '+str(file_id)+', "results": '))
        str_ = json.dumps(files,
                        indent=4, sort_keys=True,
                        separators=(',', ': '), ensure_ascii=False)
        outfile.write(unicode(str_))
        outfile.write(unicode(', "meta": {}}'))
