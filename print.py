#!/usr/bin/python
# -*- coding: utf-8 -*-

import MySQLdb as mdb
import sys
try:
    con = mdb.connect('localhost', 'pi_user', 'arthas4259', 'rpi_db')
    with con:
        cur = con.cursor()
        cur.execute("SELECT * FROM measuredData")

        rows = cur.fetchall()
        for row in rows:
            print row
except _mysql.Error, e:

    print "Error %d: %s" % (e.args[0], e.args[1])
    sys.exit(1)

finally:

    if con:
        con.close()

