import sys
import urllib
import urllib2
import const
from const import *

import BeautifulSoup
from BeautifulSoup import BeautifulSoup, Comment
import htmlops
import json

class WebInterface:
	
	
	def get_a_webpage(self, url):
		response_page = ""
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		req = urllib2.Request(url, None, headers)
		try :
			response = urllib2.urlopen(req)
			response_page = response.read()            
		except:
			pass
		
		return response_page
	
	#  Response will be a hashmap like following containing product info
	# {"pimg": "http://img0.etsystatic.com/031/0/6963384/il_570xN.595763212_9ei6.jpg", 
	#  "price": "150.0",
	#  "title": "Soviet watch Old watch Vintage Watch Russian watch Men watch Mechanical watch men's -rare clock face watch - Molnija Molnia - working"
	#  }
	# 
	def get_price_info(self, prod_url):
		
		if 'live' == RUN_WHERE:
			url_to_call = LIVE_SITE_NAME+'/products/api_get_prod_info/'+ LIVE_API_KEY;
		else:
			url_to_call = SITE_NAME+'/products/api_get_prod_info/'+ LOCAL_API_KEY;
		
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		data = {'url' : prod_url}
		data = urllib.urlencode(data)
		req = urllib2.Request(url_to_call, data, headers)
		
		try :
			#print url_to_call
			response = urllib2.urlopen(req)
			response_page = response.read()
			#print response_page;
			return response_page
		except :
			print "FATAL ERROR : error getting product info"
			print "Unexpected error:", sys.exc_info()[0]
			raise
			return None
	
	# this does not work now but will work in future
	def price_update ( self, prod_id, new_price ):
		
		if 'live' == RUN_WHERE:
			url_to_call = LIVE_SITE_NAME+'/products/update_price_from_script/' + LIVE_API_KEY;
		else:
			url_to_call = SITE_NAME+'/products/update_price_from_script/' + LOCAL_API_KEY;
		
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		data = {'prod_id' : prod_id, 'new_price' : new_price}
		data = urllib.urlencode(data)
		req = urllib2.Request(url_to_call, data, headers)
		
		try :
			#print url_to_call
			response = urllib2.urlopen(req)
			response_page = response.read()
			#print response_page;
			return response_page
		except :
			print "FATAL ERROR : web interface to update price failed"
			print "Unexpected error:", sys.exc_info()[0]
			raise
			return None
	
	
	# returns list of item, with each item as a map containing product info.
	# Product info contains id of the product and an encoded url of product page uri
	# [{"Product":{"id":"364",
	#			   "purl":"http:\/\/exclusively.in\/wedding\/sangeet-1\/suits\/grey-mukaish-kurta-withresham-cutwork-shoulder-strap-net-dupatta-and-softnetchuridar"
	#             }
	#	},
	#  {"Product":{"id":"281",
	#              "purl":"http:\/\/indiaemporium.com\/women\/sarees\/beauteous-green-blue-embroidered-designer-lehenga-style-saree.html"
	#             }
	#  }
	#  .... 
	def get_prod_list_for_price_update ( self ):
		
		if 'live' == RUN_WHERE:
			url_to_call = LIVE_SITE_NAME+'/products/plist_for_price_check/'+ LIVE_API_KEY;
		else:
			url_to_call = SITE_NAME+'/products/plist_for_price_check/'+ LOCAL_API_KEY;
		
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		
		req = urllib2.Request(url_to_call, None, headers)
		response_page = ""    
		
		try :
			print url_to_call
			response = urllib2.urlopen(req)
			response_page = response.read()
		except :
			print "FATAL ERROR : web interface to get product list failed"
			print "Unexpected error:", sys.exc_info()[0]
			raise
			return None
		
		try:
		#if 1:
			products = json.loads(response_page)
			if products and (response_page != products):
				return products
			else:
				return None
		#else:
		except:
			print "FATAL ERROR: json decoding of page failed"
		