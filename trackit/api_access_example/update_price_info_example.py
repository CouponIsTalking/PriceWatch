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

class UpdatePriceInfo:
	
	def __init__(self):
		self.wi = WebInterface();
	def test(self, prod_id, new_price):
		update_response = self.wi.price_update(prod_id, new_price)
		return update_response
		

upi=UpdatePriceInfo()
#update_response = upi.test(39, 10) #Integer type works for product-id and new-price, as well as string type
update_response = upi.test('39', '11')
print update_response
print "######################"
        