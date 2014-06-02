# Configure RUN_WHERE in const.py
# Uses purl_list_example.py
#
import sys
import urllib
import urllib2
import const
import json
from const import *
from web_interface import *

class PList:
	
	def __init__(self):
		self.wi = WebInterface();
	def test(self):
		plist_page = self.wi.get_prod_list_for_price_update()
		return plist_page
		

pl=PList()
plist_page = pl.test()
for p in plist_page:
	print "-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#"
	print "Prod ID  :" + p['Product']['id']
	print "Prod URL :" + p['Product']['purl']

print "-#-#-#-#-#-#-#-#-#-#-#-#-#-#-#"

        