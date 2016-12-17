#!c:/python/python.exe
# -*- coding: UTF-8 -*-
##
##  printenv -- demo CGI program which just prints its environment
##

import os
print "Content-Type: text/plain\n"
for key in os.environ.keys():
    print "%s=%s" % (key,os.environ[key])
