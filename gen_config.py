#
#
#

import ConfigParser
import urllib
import io
import json
import shutil
import os
import sys
import textwrap

challenges_dir_list = [

]

def read_challenges_dir_list():
    in_file = open("challenges_list.cfg", 'r')
    for line in in_file.readlines():
        line = textwrap.dedent(line)
        line = line.rstrip()
        if line=="":
            continue
        if line.startswith("#"):
            continue
        challenges_dir_list.append(line)
    in_file.close()

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