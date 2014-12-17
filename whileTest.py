#!/usr/bin/python

import time
import sys
import MySQLdb as mdb
import os

try:
    con = mdb.connect('localhost', 'pi_user', 'arthas4259', 'rpi_db')
    while True:
        with con:
            cur = con.cursor()
            cur.execute("INSERT INTO measuredData(temperature) VALUES(80)")
        if (os.path.exists('/home/hizi/Desktop/BakoskaZaloha-git/whileTestOff.py')):
            break
    os.remove('/home/hizi/Desktop/BakoskaZaloha-git/whileTestOff.py')
except _mysql.Error, e:
    print "Error %d: %s" % (e.args[0], e.args[1])
    sys.exit(1)

finally:
    if con:
        con.close()
