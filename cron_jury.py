#!/usr/bin/env python
# -*- coding: utf-8 -*-
 
import socket, sys, re, os, errno, math, errno, random, string
import uuid
import time
import MySQLdb as mdb
from cron_jury_config import dbhost
from cron_jury_config import dbname
from cron_jury_config import dbuser
from cron_jury_config import dbpass

def randomword(length):
   return ''.join(random.choice(string.lowercase) for i in range(length))

def print_log(s):
	print(time.strftime('%X %x %Z') + " => " + s)

service_flags='/usr/share/ctfight/services/ussr-storage/service/flags/'

con = None
# http://zetcode.com/db/mysqlpython/
try:
	con = mdb.connect(dbhost, dbuser, dbpass, dbname);
	cur = con.cursor()
	cur.execute("SELECT VERSION()");
	ver = cur.fetchone()
	print_log("MySQL version: %s" % ver);
	
	# clean old flags from system
	# 

	cur.execute("SELECT flagid FROM flags WHERE dt_end < DATE_SUB(NOW(), INTERVAL 30 MINUTE)")
	rows = cur.fetchall()
	for row in rows:
		filepath = service_flags + row[0];
		if(os.path.isfile(filepath)):
			print_log("FLAG REMOVED BY ID: " + row[0])
			os.remove(filepath)
	i=0
	while(i < 20):
		i = i + 1
		flagid = randomword(10)
		flag = str(uuid.uuid4())
		res = os.popen('/usr/share/ctfight/services/ussr-storage/checker PUT localhost ' + flagid + ' ' + flag).read()
		res = res.strip();
		
		if (res == "[OK]"):
			print_log("FLAG PUTTED: " + flagid + " => " + flag)
			cur.execute("INSERT INTO flags(flagid,flag,dt_start,dt_end,userid) VALUES('" + flagid + "','" + flag + "',NOW(),DATE_ADD(NOW(), INTERVAL 15 MINUTE),0)")
			con.commit()
		else:
			print_log("Service corrupt - need restart service")

except mdb.Error, e:
	print_log("Error %d: %s" % (e.args[0], e.args[1]))
	sys.exit(1)
finally:
	if con != None:
		cur.close()
		con.close()
        
        
# todo cleanup old flags on service
