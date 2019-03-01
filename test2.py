#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Fri Mar  1 13:49:45 2019

@author: xinruishao
"""

import urllib.request
import json
#myurl = "https://tfl-action-item-service.azurewebsites.net"
input = {'uids':['051bf0487a30b02f931824ef01be46dc9c8b3e7f0a76c4ee192d6269913416ae','06629768faee495605066a3f5b4bbb5ecce0e1d0c82288d33c410fdf5610d675']}
myurl = "http://localhost:5000"
req = urllib.request.Request(myurl)
req.add_header('Content-Type', 'application/json; charset=utf-8')
jsondata = json.dumps(input)
jsondataasbytes = jsondata.encode('utf-8')   # needs to be bytes
req.add_header('Content-Length', len(jsondataasbytes))
print (jsondataasbytes)
response = urllib.request.urlopen(req, jsondataasbytes)
data = response.read()
gg=json.loads(data)
print(gg)