# Configure RUN_WHERE in const.py
# Uses get_price_info_example "<http://product-url>"
#
import sys
import urllib
import urllib2
import const
import json
from const import *
from web_interface import *

class GetPriceInfo:
	
	def __init__(self):
		self.wi = WebInterface();
	def test(self, purl):
		price_info = self.wi.get_price_info(purl)
		return price_info
		

gpi = GetPriceInfo()
price_info = gpi.test(sys.argv[1])

if type(price_info) is str:
	price_info = json.loads(price_info)
	price_info = eval(price_info[0]);
	print "Title :"+price_info['title']
	print "Price :"+price_info['price']
	print "Image :"+price_info['pimg']

        