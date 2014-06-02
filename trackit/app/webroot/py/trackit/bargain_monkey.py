import sys
import urllib
import urllib2
import urlparse
import const
from const import *

import BeautifulSoup
from BeautifulSoup import BeautifulSoup, Comment

import misc
from misc import *
import htmlops
from htmlops import *
from tag_classifier import *

import SeleniumReader
from SeleniumReader import *

import random

sites = "Express.com" , "J.Crew", "Banana Republic", "Anthropologie", "Ann Taylor", "Nordstrom", "Kate Spade"
sitenames = ['www.express.com', 'www.jcrew.com', 'www.bananarepublic.com', 'www.anthropologie.com', 'www.anntaylor.com', 'shop.nordstrom.com', 'www.katespade.com']
#saleurls = [] 
saleurls = ['http://www.express.com/clothing/Sale/sec/cat300003', 'http://www.jcrew.com/sale.jsp', 'http://www.bananarepublic.com/products/mens-clothing-sale.jsp', 'http://www.anthropologie.com/anthro/category/freshly+cut/shopsale-freshcuts.jsp', 'http://www.anntaylor.com/sale/cata00007', 'http://shop.nordstrom.com/c/sale', 'http://www.katespade.com/sale-designer-clothing/ks-sale-clothing,en_US,sc.html']
tag = Tags()

class MyUserAgents:

	def __init__(self):
		# get user agents
		self.useragents = []
		useragents_file = 0#open("useragents.txt", "r")

		if useragents_file:
			for line in useragents_file:
				self.useragents.append(line)
			useragents_file.close()
			
		else:
			self.useragents.append("Mozilla 5.10")
	
	def getRandomUserAgent(self):
		return self.useragents[random.randint(0, len(self.useragents)-1)]

def areListEqual (prlist, chlist):
	
	if len(prlist) != len(chlist):
		return 0
		
	i = 0
	
	while i < len(prlist):
		if prlist[i] != chlist[i]:
			return 0
		i = i+1
		
	return 1
	
	
def searchLeafElements(driver, parent_element_name_list):
	
	depth = len(parent_element_name_list)
	
	if len(parent_element_name_list) > 5:
		return 0;
	
	# we have have done more than 2 clicks and the page doesnt list products yet
	# then backtrack
	# A good shopping site should show product listing within 3 clicks (rather 2)
	if depth > 2:
		if isProductPage(driver) == 0:
			return 0;
	
	parent_name = ""
	if parent_element_name_list:
		parent_name = parent_element_name_list[len(parent_element_name_list) - 1]
		
	alist = getAList(driver, 0, 0, parent_name)
	
	prlist = []
	for a in alist:
		prlist.append(a.text.encode("utf-8", "ignore"))
	
	i = 0
	while i < len(alist):
		element_name = alist[i].text.encode("utf-8", "ignore").lower()
		print "Checking element {"+ element_name +"} "
		part_of_parent_list = 0
		print parent_element_name_list
		for s in parent_element_name_list:
			if element_name == s:
				part_of_parent_list = 1
				#print "found in parents \n"
				break
				
		if part_of_parent_list != 1:
			alist[i].click()
			if isLeafCategory(driver, element_name) == 0:
				new_parent_list  = list(parent_element_name_list)
				new_parent_list.append(element_name)
				#print new_parent_list
				searchLeafElements(driver, new_parent_list)
			else:
				print "-----------Category Leaf and Path--------------"
				print element_name
				print parent_element_name_list
				print "-----------------------------------------------"
			driver.back()
			# rebuild AList, because element locations' have changed
			alist = getAList(driver, 0, 0, parent_name)
			
		i = i+1	

def isLeafCategory(driver, element_name):

	alist = getAList(driver, 0, 0, "") # build Alist of the whole page
	i = 0
	while i < len(alist):
		if alist[i].text.encode("utf-8", "ignore").lower() == element_name.lower():
			break
		else:
			i = i+1

	if i == len(alist):
		return 0
	
	link_elements_in_its_tree = alist[i].find_elements_by_tag_name("a")	
	
	# if it is leaf element and it is a product page
	if len(link_elements_in_its_tree) < 1 and isProductPage(driver) == 1:
		
		# create parent small link list
		prlist = []
		for a in alist:
			s = a.text.encode("utf-8", "ignore").lower()
			prlist.append(s)
		
		# click the same element again
		alist[i].click()
		
		# generate another blist		
		blist = getAList(driver, 0, 0, "")		
		chlist = []
		for b in blist:
			s = b.text.encode("utf-8", "ignore").lower()
			chlist.append(s)
		
		if areListEqual(prlist, chlist) == 1 :
			print prlist[i] + " tag is category leaf "
			print "\n------------------\n"
			return 1
		else:
			return 0
	
	return 0

def findSubCategories(driver, parent_name):
	divs = driver.find_elements_by_tag_name("a")
	
	if parent_name:
		parent_name = parent_name.lower()
		for div in divs:
			try :
				s = div.text.encode("utf-8", "ignore").lower()
				if s == parent_name:
					child_divs = div.find_elements_by_tag_name("a")
					if child_divs:
						return child_divs
					else:
						continue
			except Exception, e:
				print e


def getAList(driver, maxWidth, maxHeight, parent_name):
	maxWidth = 200
	maxHeight = 30
			
	divs = driver.find_elements_by_tag_name("a")
	
	if parent_name:
		parent_name = parent_name.lower()
		for div in divs:
			try :
				s = div.text.encode("utf-8", "ignore").lower()
				if s == parent_name:
					child_divs = div.find_elements_by_tag_name("a")
					if child_divs:
						print "looking in child divs\n"
						divs = child_divs
						break
			except Exception, e:
				print e
				
	returnlist = []
	
	for div in divs:
		try:
			if div.size['width'] > maxWidth:
				continue
			if div.size['height'] > maxHeight:
				continue
		except Exception, e:  # if there is no size attribute
			continue
			
		s = div.text.encode("utf-8", "ignore")
		
		if not s:
			continue
		
		if s.count(' ') > 2:
			continue
		
		#firstword = s.lower()
		#indexof_space = firstword.find(' ')
		#if indexof_space != -1:
		#	firstword = firstword[:indexof_space]
		#exclude_dict = exclusions_list()
		
		#if firstword in exclude_dict:
		if tag.isExclusionType(s) == 1:
			#print s + " excluded\n"
			continue
		
		if tag.isApparelType(s) == 1 or tag.isPromoType(s):
			returnlist.append(div)
			#print firstword + " excluded\n"
			continue
		
		
	
	return returnlist


	

	
def isProductPage(driver):

	divs = driver.find_elements_by_tag_name("img")
	ge = {}
	
	for div in divs:
		if div.size['width'] < 90:
			continue
		if div.size['height'] < 90:
			continue
			
		s = str(div.size['width']) +"_"+ str(div.size['height'])
		if s in ge:
			ge[s] = ge[s] + 1
		else:
			ge[s] = 1
	
	max = 0
	for x in ge:
		if ge[x] > max:
			max = ge[x]
	
	if max >= 8:
		return 1
	else:
		return 0

def buildShoppingUrls():

	myua = MyUserAgents()
	useragent = myua.getRandomUserAgent()
	f = open('test_temp.html','w')
	reader = SeleniumReader("");
	
	for site in sites:
		
		query = site + " Clothing Shopping"
		query = str.replace(query, " ",  "+")
		
		url = "http://www.google.com/#q="+query
		reader.moveTo(url)
		
		results = reader.getElementsByCSSSelector("cite")
		
		for r in results:
			link = r.text.encode("utf-8", "ignore")
			indexof_slash = link.find("/")
			if indexof_slash >= 0:
				link = link[:indexof_slash]
			#linkurls = re.findall('http[s]?://(?:[a-zA-Z]|[0-9]|[$-_@.&+]|[!*\(\),]|(?:%[0-9a-fA-F][0-9a-fA-F]))+', innerhtml)
			sitenames.append(link)
			
			break
			
		time.sleep(1)
	
	print "\nlearned site names\n"
	print sitenames

	
def buildSaleUrl():
	
	reader = SeleniumReader("");
	
	for sitehome in sitenames:
		
		query = " sale+site%3A"+sitehome
		url = "http://www.google.com/#q="+query
		reader.moveTo(url)
		
		#links = reader.getElementsByCSSSelector("cite")
		results = reader.getElementsByCSSSelector("li.g")
		for r in results:
			foundlink = 0
			
			text = r.text.encode("utf-8", "ignore")
			text = text.lower()
			print "\n----------------\n"
			print text 
			print "\n----------------\n"
			if "sale" in text or "clearance" in text or "deal" in text:
				links = r.find_elements_by_tag_name("a")
				
				for linkelement in links:
					link = linkelement.get_attribute("href")
					link = link.encode("utf-8", "ignore")
				
					if not link:
						continue
					elif "google.com" in link:
						par = urlparse.parse_qs(urlparse.urlparse(link).query)
						if par['url']:
							encodedlink = par['url'][0]
							decodedlink = urllib.unquote(encodedlink).decode('utf8')
							print "\n----------------\n"
							print decodedlink
							print "\n----------------\n"
						
							salesurls.append(decodedlink)
							foundlink = 1
							break
						continue				
					else:
						print "\n----------------\n"
						print link
						print "\n----------------\n"
						
						link = link.encode("utf-8", "ignore")
						saleurls.append(link)
						foundlink = 1
						break
			
			if foundlink == 1:
				break
			
		time.sleep(1)
	
	print "\nlearned sale urls names\n"
	print saleurls
	
	
# if we dont know what sites to look for, then figure out site names	
if len(sitenames) == 0:	
	buildShoppingUrls()
	
if len(saleurls) == 0:	
	buildSaleUrl()

		
		
def test():
	reader = SeleniumReader("")
	#reader.moveTo("http://www.express.com/clothing/striped+reversible+slub+dress/pro/7862592/cat550007")
	reader.moveTo("http://www.express.com/clothing/Women/Dresses+Under+-40/cat/cat840005")
	emptylist = []
	reader.getDriver().maximize_window()
	searchLeafElements(reader.getDriver(), emptylist)
	#print isLeafCategory(reader.getDriver(), "Dresses Under $40")

#test()
