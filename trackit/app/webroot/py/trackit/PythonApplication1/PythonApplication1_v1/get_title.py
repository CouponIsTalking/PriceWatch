from selenium import webdriver
import sys
import json
import urllib2
import lxml.html
			
url = sys.argv[1]
title = None

user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
headers = { 'User-Agent' : user_agent }        
try :
	req = urllib2.Request(url, None, headers)
	response = urllib2.urlopen(req)
	response_page = response.read()
	t = lxml.html.fromstring(response_page)
	title = t.find(".//title").text
except :
	a = 1
	
if not title:
	try:
		t = lxml.html.parse(url)
		title = t.find(".//title").text
	except:
		a = 1

if not title:
	driver = webdriver.Firefox()
	driver.get(url)
	title = driver.title

if title:
	print json.dumps({'title': title})


