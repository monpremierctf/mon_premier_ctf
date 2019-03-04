#!/usr/bin/env python

import yaml

print("Loading file")
with open("flags.yml", 'r') as stream:
    try:
        flags = yaml.load(stream)
    except yaml.YAMLError as exc:
        print(exc)

print ("Ok")

for sections in flags['Sections']:
   for section_name,section_entries  in sections.items():
       print("===" + section_name + "===")
       for entry in section_entries:
           content = entry["Entry"]
           if (content is not None):
            print(content['Titre'])
            print(content['Flag'])

