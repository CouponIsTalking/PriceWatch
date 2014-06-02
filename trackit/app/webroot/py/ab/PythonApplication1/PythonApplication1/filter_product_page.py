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

import lxml.html
from lxml.html import etree


class ProductListingPage:
	def __init__(self, url_or_reader):
		
		if isinstance(url_or_reader, str):
			self.listing_url = url_or_reader
			self.reader = SeleniumReader(self.listing_url);
			
		elif isinstance(url_or_reader, SeleniumReader):
			self.reader = url_or_reader
			self.listing_url = self.reader.getDriver().current_url
		else:
			print "~~~~~~~~~ Bad param to constructor ~~~~~~~~~~~~~"
			return
			
		self.product_div = 0
	
	def moveTo(url):
		self.product_div = 0
		self.reader.moveTo(url)
		self.listing_url = url
		
	# Creates different bins based on (width, height) pair of images
	# Sort all images on the page in different bins
	# Pick the bin that has at least 8 images of height >=90 and width >= 90
	# returns list of those image el
	def doesElementHasProductsListed(self, ele):
		
		threshold = 8
		divs = ele.find_elements_by_tag_name("img")
		ge = {}
		
		product_divs = []
		max = 0
		for div in divs:
			if div.size['width'] < 90:
				continue
			if div.size['height'] < 90:
				continue
				
			w = div.size['width']
			h = div.size['height']
			
			if w not in ge:
				ge[w] = {}
			if h not in ge[w]:
				ge[w][h] = []
				
			ge[w][h].append(div)
			bin_size = len(ge[w][h])
			if  bin_size> max and bin_size >= threshold:
				max = bin_size
				product_divs = ge[w][h]
		
		
		return [max,product_divs]
	
	def getShareItems(self):
		
		driver = self.reader.getDriver()
		divs = driver.find_elements_by_tag_name("div")
		
		sharewords = ['fblike', 'twitter', 'social', 'share', 'email', 'pinterest', 'instagram', 'pinit', 'facebook']
		sharedivs = []
		for div in divs:
			classname = readWebElementAttr(div, 'class')
			idname = readWebElementAttr(div, 'id')
			shareitem = False
			
			if classname:
				classname = classname.encode("ascii", "ignore")
			if idname:
				idname = idname.encode("ascii", "ignore")
			
			if (not shareitem) and classname:
				classname = classname.lower()
				for w in sharewords:
					if w in classname:
						shareitem = True
						break
			
			if (not shareitem) and idname:
				idname = idname.lower()
				for w in sharewords:
					if w in idname:
						shareitem = True
						break
			
			if shareitem:
				sharedivs.append(div)
		
		return sharedivs
		
	
		
	def thingsBelowReviewDivs(self, review_divs):
		
		driver = self.reader.getDriver()
		divs = driver.find_elements_by_tag_name("div")
		
		belowReviewDiv = []
		y1Min = 10000000
		
		if not review_divs:
			return belowReviewDiv
			
		for r in review_divs:
			y1 = r.location['y'];
			if y1 < y1Min:
				y1Min = y1
		
		
		for r in divs:
			y1 = r.location['y'];
			if y1 > y1Min:
				belowReviewDiv.append(r)
				
		
		#for div in belowReviewDiv:
			#self.reader.removeElement(div)
			#self.reader.dimElement(div, 0.2)
		#	self.reader.hideElement(div)
			
		return belowReviewDiv
		

	def detectReviews(self, product_div):
		
		review_divs = self.sizeBasedReviewDetection(product_div)
		if not review_divs:
			review_divs = self.findReviewUsingLiguisticHints()

		# if couldn't find any,
		# then do a text based analysis
		# basically looking for lines (>=4 words (subject, verb, adjective, expressive emotion)) leaf elements with i, me, my, myself words in it
		if not review_divs:
			review_divs = self.findReviewUsingLiguisticHints()

		#for div in review_divs:
			#self.reader.removeElement(div)
			#self.reader.dimElement(div, 0.2)
		#	self.reader.hideElement(div)
		
		return review_divs
			
	def sizeBasedReviewDetection(self, product_div):
	
		driver = self.reader.getDriver()
		divs = driver.find_elements_by_tag_name("div")
		
		# find a div that does not contain product div 
		# and is of size 4 times greater than product div
		product_div_area = product_div.size['width'] * product_div.size['height']
		product_div_x1 = product_div.location['x']
		product_div_x2 = product_div_x1 + product_div.size['width']
		product_div_y1 = product_div.location['y']
		product_div_y2 = product_div_y1 + product_div.size['height']
		
		review_divs = []
		max = 0
		for div in divs:
			
			try:
				if False == div.is_displayed():
					continue
			except Exception, e:
				print "Element not found when checking if it is displayed, assuming element not displayed and skipping\n"
				continue
				
			x1 = div.location['x']
			y1 = div.location['y']
			x2 = x1 + div.size['width']
			y2 = y1 + div.size['height']
			
			if product_div_area * 4 > (x2-x1)*(y2-y1):
				#print "area less then threshold\n"
				continue
			
			if product_div_x1 >= x1 and product_div_x2 <= x2 and product_div_y1 >= y1 and product_div_y2 <= y2:
				#print "product div not completely outside\n"
				continue
			
			#if h > w:	# we are looking for a rectangle with height < width type of shape
			#	continue
			
			# what is this div ? of size greater than 4 times image div
			# and completely outside of it
			# lets do some more checking
			# ensure that this falls below the product image
			if y1 < product_div_y2:
				#print "product div not above\n"
				continue
			
			# now I am getting supicious, 
			# but it may contain product details :(
			# look for children, should have at least 4 children
			# that are x1 and x2 aligned
			
			# Ok, this is TODO 
			print "appending " + str(div) + "\n"
			review_divs.append(div)
		
		
		return review_divs


	def findReviewUsingLiguisticHints(self):
	
		driver = self.reader.getDriver()
		html = driver.page_source
		html = removeCommentsAndJS(html)
		tree1 = lxml.html.fromstring(driver.page_source)
		etree1 = etree.ElementTree(tree1)
		
		review_divs = []
		reviewXPaths = []
		reviewtextslen = []
		childList = []
		childList.append(tree1)
		i = 0
		first_largest_len = 0
		second_largest_len = 0
		f_xpath = ''
		s_xpath = ''
		
		while i < len(childList):
			
			nextChild = childList[i]
			nextChildsChildren = list(nextChild)
			if nextChildsChildren:
				for ncc in nextChildsChildren:
					childList.append(ncc)
			else:
				text = nextChild.text_content()
				if text:
					text = text.encode("ascii", "ignore")
					text = text.lower()
					spaceCount = text.count(' ')
					if spaceCount >= 3:
						text = text.replace('.', ' ')
						text = text.replace('$', ' ')
						text = text.replace('!', ' ')
						text = text.replace(',', ' ')
						text = text.replace('?', ' ')
						text = text.replace('\n', ' ')
						
						hasreview = False
						if not hasreview:
							hasreview = " i " in text
						if not hasreview:
							hasreview = " me " in text
						if not hasreview:
							hasreview = " my " in text
						if not hasreview:
							hasreview = " myself " in text
						if not hasreview:
							hasreview = " we " in text
						
						if hasreview:
							xpath = etree1.getpath(nextChild)
							reviewXPaths.append([xpath, len(text)])
							
							# keep track of xpaths of first 2 largest review elements
							if len(text) >= first_largest_len:
								second_largest_len = first_largest_len
								first_largest_len = len(text)
								s_xpath = f_xpath
								f_xpath = xpath
							
							elif second_largest_len < len(text) and len(text) < first_largest_len:
								second_largest_len = len(text)
								s_xpath = xpath
									
								
							
				
			i = i +1
				
		
		#print reviewXPaths
		
		maxid = 0
		maxclass = 0
		maxid_name = ''
		maxclass_name = ''
		
		idMap = {}
		for (r, l) in reviewXPaths:
			rid = r + "/@id"
			ids = etree1.xpath(rid)
			for id in ids:
				if id in idMap:
					idMap[id] = idMap[id]+1
				else:
					idMap[id] = 1
		
		
		for e in idMap:
			if idMap[e] > maxid:
				maxid = idMap[e]
				maxid_name = e
					
		
		
		classMap = {}
		for (r, l) in reviewXPaths:
			rclass = r + "/@class"
			classes = etree1.xpath(rclass)
			for aclass in classes:
				if aclass in classMap:
					classMap[aclass] = classMap[aclass] + 1
				else:
					classMap[aclass] = 1
		
		
		for e in classMap:
			if classMap[e] > maxclass:
				maxclass = classMap[e]
				maxclass_name = e
		
		#if maxid == 0 and maxclass == 0
		#	return []
			
		haha_i_found_yous = []
		if maxid > maxclass and maxid_name:
			haha_i_found_yous = driver.find_elements_by_id(maxid_name)
		elif maxid < maxclass and maxclass_name:
			haha_i_found_yous = driver.find_elements_by_class_name(maxclass_name)
		else:
			return [] # couldnt find anything
			
		# now recurse up to find the nearest encompassing rectangle
		#
		x =[]
		y =[]
		for e in haha_i_found_yous:
			x.append(e.location['x'])
			x.append(e.location['x'] + e.size['width'])
			y.append(e.location['y'])
			y.append(e.location['y'] + e.size['height'])

		x.sort()
		y.sort()
		x1 = x[0]
		x2 = x[len(x)-1] 
		y1 = y[0]
		y2 = y[len(y)-1]
		
		review_divs = []
		crappy_element = None
		pEle = haha_i_found_yous[0]
		while True:
			px1 = pEle.location['x']
			px2 = pEle.location['x'] + pEle.size['width']
			py1 = pEle.location['y']
			py2 = pEle.location['y'] + pEle.size['height']
			
			print px1, px2, py1, py2
			# does rectangle given by x1, x2 ,y1, y2 fall within px1, px2, py1, py2
			if px1 <= x1 and x2 <= px2 and py1 <= y1 and y2 <= py2:
				crappy_element = pEle
				break
			tag = pEle.get_attribute('tag')
			if tag:
				if tag.lower() == 'body':
					break
			re = self.reader.getParentOfWebElement(pEle)
			if re[0] == 1 and re[1] != None:
				pEle = re[1]
			else:
				break
		
		if crappy_element is not None:
			review_divs.append(crappy_element)
		else:
			for an_element in haha_i_found_yous:
				review_divs.append(an_element)
		
#		if f_xpath and s_xpath:
#			
#			i = 0
#			xpath1_len = len(f_xpath)
#			xpath2_len = len(s_xpath)
#			
#			while i < xpath1_len and i < xpath2_len:
#				if f_xpath[i] != s_xpath[i]:
#					xpath = f_xpath[:i]
#					break
#				i = i+1
#			
#			if xpath:
#				reviewXPaths = [xpath]
			
#		i = 0
#		review_divs = []
#		while i < len(reviewXPaths):
#			rd = reader.getElementsByXPath(reviewXPaths[i])
#			if rd:
#				review_divs = review_divs + rd
#			i = i +1
#		
		print reviewXPaths
		
		return review_divs
		
	
	def getProductImgDiv(self):
		
		# return the product div if already calculated
		if self.product_div:
			return self.product_div
			
		driver = self.reader.getDriver()
		divs = driver.find_elements_by_tag_name("img")
		ge = {}
		
		product_div = []
		max = 0
		for div in divs:
			if False == div.is_displayed():
				continue
			w = div.size['width']
			h = div.size['height']
			
			if w < 90 or h < 90:
				continue
				
			if h < w:	# we are looking for a rectangle with height > width type of shape
				continue
			
			if w not in ge:
				ge[w] = {}
			if h not in ge[w]:
				ge[w][h] = []
				
			area = w*h
			ge[w][h].append([div, area])
			bin_size = len(ge[w][h])
			
			if  area> max:
				max = area
				product_div = div

		
		self.product_div = product_div	
		return product_div
				
	
	def getProductsListed():
		
		re = self.doesElementHasProductsListed(self, self.reader.getDriver())
		
		if re[0] == 0:
			return []
		else:
			pEle = re[1][0]
			parent_re = self.reader.getParentOfWebElement(pEle)
			while parent_re[0] == 1:
				pEle = parent_re[1]
				re = self.doesElementHasProductsListed(self, pEle)
				if re[0] > 0:
					product_list = re[1]
					return product_list;
					
				parent_re = self.reader.getParentOfWebElement(self, pEle)
				
				
	
	def hideDistractingImages():
		
		driver = self.reader()
		divs = driver.find_elements_by_tag_name("img")
		for div in divs:
			div.get
		
	def removeHiddenDivs(self):
		
		driver = self.reader.getDriver()
		divs = driver.find_elements_by_tag_name('div')
		
		removeList = []
		
		for div in divs:
			if False == div.is_displayed():
				removeList.append(div)
				
		#self.removeElements(removeList)
		
		
	def removeOutgoingElements(self):
		self.reader.removeOutgoingElements()
		 
				
	def recurseUpForHoldingContainer(self, div, conditions):
	
		driver = self.reader.getDriver()
		
		text = div.text.encode("ascii", "ignore")
		wordList = re.sub("[^\w]", " ",  mystr).split()
		conditions['words'] = len(wordList)
		
		if textHasPrice(text):
			conditions['price'] = 1
		
		hrefAttr = div.get_attribute('href')
		outLink = False
		if hrefAttr:
			if isOutgoingLink(driver.current_url, hrefAttr) == True:
				conditions['outgoingLinks'] = conditions['outgoingLinks'] + 1
		
		conditions['imageCount'] = len(div.find_elements_by_tag_name('img'))
		
		# check if div can not qualify by recursing up
		qualifies = True
		if conditions['words'] > 10:
			qualifies = False
			
		if conditions['outgoingLinks'] > 1:
			qualifies = False
			
		if conditions['imageCount'] > 1:
			qualifies = False
		
		if not qualifies:
			return False
		
		# check if parent qualify
		parentDiv = getParentOfWebElement(reader, div)
		re = recurseUpForHoldingContainer(self, parentDiv, conditions)
		if re:
			return re
		
		# if you came here, means the div can not qualify by going up, so 
		# check if the div itself qualifies
		if conditions['words'] < 10 and conditions['outgoingLinks'] == 1 and conditions['imageCount'] == 1:
			return div
		
		return False
		
		
	
	def findRelatedImageContainers(self):
		
		driver = self.reader.getDriver()
		imgs = driver.find_elements_by_tag_name('img')
		
		imgsOutgoingLinkHolders = []
		outgoingImgs = []
		
		print len(imgs)
		
		i = -1
		for img in imgs:
			i = i + 1
			parentWithOutgoingLink = None
			
			pImg = img
			re = [1, pImg]
			
			while True:
				print re
				if re[0] == 0:
					parentWithOutgoingLink = None
					break
				if re[0] == 1 and re[1] is None:
					parentWithOutgoingLink = None
					break
					
				pImg = re[1]
				
				href = pImg.get_attribute('href')
				print href
				if href:
					outgoingLink = isOutgoingLink(driver.current_url, href)
					if outgoingLink:
						print "-------------------matched--------------------------------"
						parentWithOutgoingLink = pImg
					else:
						parentWithOutgoingLink = None
					break
				
				if parentWithOutgoingLink:
					imgsOutgoingLinkHolders.append(parentWithOutgoingLink)
					outgoingImgs.append(imgs[i])
					
				re = self.reader.getParentOfWebElement(pImg)
		
			
		imgs = outgoingImgs
		print imgs
		
		imgBySize = {}
		imgByX = {}
		imgByY = {}
		imgsSizeAndXAligned = {} 
		imgsSizeAndYAligned = {} 
		
		i = -1
		for img in imgs:
			i = i + 1
			size = img.size['width'] +'_'+img.size['height']
			if size in imgBySize:
				imgBySize[size].append(i)
			else:
				imgBySize[size] = []
				imgBySize[size].append(i)
			
			x = img.location['x']
			if x in imgByX:
				imgByX[x].append(i)
			else:
				imgByX[x] = []
				imgByX[x].append(i)
				
			y = img.location['y']
			if y in imgByY:
				imgByY[y].append(i)
			else:
				imgByY[y] = []
				imgByY[y].append(i)
				
			size_and_x = img.size['width'] +'_'+img.size['height'] + '_' + x
			size_and_y = img.size['width'] +'_'+img.size['height'] + '_' + y
			
			if size_and_x in imgsSizeAndXAligned:
				imgsSizeAndXAligned[size_and_x].append(i)
			
			if size_and_y in imgsSizeAndYAligned:
				imgsSizeAndYAligned[size_and_y].append(i)
			
		# for images in imgsSizeAndXAligned
		# find nearest common enclosing rectangle or nearest common ancestors
		
		NCAs = []
		
		for size_and_x in imgsSizeAndXAligned:
			imgList = imgsSizeAndXAligned[size_and_x]
			lenImgList = len(imgList)
			
			if lenImgList == 1:
				NCAs.append(imgs[imgsSizeAndXAligned[0]])
			
			if lenImgList > 1:
				p1 = imgs[imgsSizeAndXAligned[0]]
				p2 = imgs[imgsSizeAndXAligned[1]]
				p = 0	
				
				while True:
					
					innerhtml1 = p1.get_attribute('innerHTML')
					innerhtml2 = p2.get_attribute('innerHTML')
					if not innerhtml1:
						break
					if not innerhtml2:
						break
					if innerhtml1 == innerhtml2:
						imagesInP = p.find_elements_by_tag_name('img')
						if len(imagesInP) <= lenImgList:
							NCAs.append(p1)
						
						break
					
					# if not empty innerhtmls that are not equal
					
					# we can do the following because both elements must be at the same level
					# when they first start tracing to parents
					# this is an assumption which should hold true and will hold true
					re = self.reader.getParentOfWebElement(p1)
					if re[0] == 1 and re[1] is not None:
						p1 = re[1]
					else:
						break
					
					re = self.reader.getParentOfWebElement(p2)
					if re[0] == 1 and re[1] is not None:
						p2 = re[1]
					else:
						break
					
			
		for size_and_y in imgsSizeAndYAligned:
			imgList = imgsSizeAndYAligned[size_and_y]
			lenImgList = len(imgList)
			
			if lenImgList == 1:
				NCAs.append(imgs[imgsSizeAndYAligned[0]])
			
			if lenImgList > 1:
				p1 = imgs[imgsSizeAndYAligned[0]]
				p2 = imgs[imgsSizeAndYAligned[1]]
				p = 0	
				
				while True:
					
					innerhtml1 = p1.get_attribute('innerHTML')
					innerhtml2 = p2.get_attribute('innerHTML')
					if not innerhtml1:
						break
					if not innerhtml2:
						break
					if innerhtml1 == innerhtml2:
						imagesInP = p.find_elements_by_tag_name('img')
						if len(imagesInP) <= lenImgList:
							NCAs.append(p1)
						
						break
					
					# if not empty innerhtmls that are not equal
					
					# we can do the following because both elements must be at the same level
					# when they first start tracing to parents
					# this is an assumption which should hold true and will hold true
					re = self.reader.getParentOfWebElement(p1)
					if re[0] == 1 and re[1] !=0:
						p1 = re[1]
					else:
						break
					
					re = self.reader.getParentOfWebElement(p2)
					if re[0] == 1 and re[1] !=0:
						p2 = re[1]
					else:
						break
					
		
		for e in NCAs:
			driver.dimElement(e)
		
	
	def binSimilarTextCSSElements():
		
		
		driver = reader.getDriver()
		
		
			
			
	def pluckRelatedImages():
		
		re = self.doesElementHasProductsListed(self, self.reader.getDriver())
		
		if re[0] == 0:
			return []
		else:
			pEle = re[1][0]
			parent_re = self.reader.getParentOfWebElement(pEle)
			while parent_re[0] == 1:
				pEle = parent_re[1]
				re = self.doesElementHasProductsListed(self, pEle)
				if re[0] > 0:
					product_list = re[1]
					return product_list;
					
				parent_re = self.reader.getParentOfWebElement(self, pEle)
		
		
	def removeElements(self, e_list):
		
		for e in e_list:
			self.reader.removeElement(e)
	
	def hideElements(self, e_list):
		
		for e in e_list:
			self.reader.hideElement(e)
	
	def getVisibleDivs(self):

		driver = self.reader.getDriver()
		
		divs = driver.find_elements_by_tag_name("div")
		visibleDivs = []
		
		for div in divs:
			if div.is_displayed() == True:
				o = self.reader.getOpacity(div)
				if o <= 0.2:
					continue
				else:
					visibleDivs.append(div)
			
		return visibleDivs
		
	def leafsYSorted(self, divs):
		
		driver = self.reader.getDriver()
		
		#divs = driver.find_elements_by_tag_name("div")
		leafDivs = []
		yMap = {}
		ys = []
		for div in divs:
			if self.reader.isLeaf(div) == 1:
				leafDivs.append(div)
				y = div.location['y']
				if y not in yMap:
					yMap[y] = []
				yMap[y].append(div)
				ys.append(y)
				if div.text:
					print div.text.encode("ascii", "ignore")
		
		return None
				
		
		ySortedDivs = {}
		
		ys = sorted(ys)
		unique_ys = []
		
		prev_y = ys[0]
		unique_ys.append(ys[0])
		i = 1
		while i < len(ys):
			if prev_y != ys[i]:
				unique_ys.append(ys[i])
				prev_y = ys[i]
			i = i +1
		
		i = 0
		stacked_divs = []
		while i < len(unique_ys):
			for ey in yMap[unique_ys[i]]:
				if ey.text:
					s = ey.text.encode("ascii","ignore")
					stacked_divs.append([unique_ys[i], ey])
			i = i +1
		#print ys
		#print yMap
		return stacked_divs
		#return yMap
		#return leafdivs
		
	def sortByDistanceFromProduct(self, product_div):
		
		driver = self.reader.getDriver()
		
		
		# find a div that does not contain product div 
		# and is of size 4 times greater than product div
		pdiv_area = product_div.size['width'] * product_div.size['height']
		pdiv_x1 = product_div.location['x']
		pdiv_x2 = product_div_x1 + product_div.size['width']
		pdiv_y1 = product_div.location['y']
		pdiv_y2 = product_div_y1 + product_div.size['height']
		
		
		for div in divs:
			
				
			o = reader.getOpacity(div)
			if o <= 0.2:
				continue
			
				x1 = div.location['x']
				y1 = div.location['y']
				x2 = x1 + div.size['width']
				y2 = y1 + div.size['height']
				
				dist = 1000000
				
				
				if x1 >= pdiv_x2 and (not ((y2 <= pdiv_y1) or (y1 >= pdiv_y2))):
					dist = x1 - pdiv_x2
				elif x2 <= pdiv_x1 and (not ((y2 <= pdiv_y1) or (y1 >= pdiv_y2))):
					dist = pdiv_x1 - x2
				elif y2 <= pdiv_y1 and (not ((x2 <= pdiv_x1) or (x1 >= pdiv_x2))):
					dist = pdiv_y1 - y2
				elif y1 >= pdiv_y2 and (not ((x2 <= pdiv_x1) or (x1 >= pdiv_x2))):
					dist = pdiv_y2 - y1
				#else:
				#	minX = min(x1-
					
	
	def decodeDetails(self, stacked_divs, product_div):
	
		driver = self.reader.getDriver()
		
		
		# find a div that does not contain product div 
		# and is of size 4 times greater than product div
		pdiv_area = product_div.size['width'] * product_div.size['height']
		pdiv_x1 = product_div.location['x']
		pdiv_x2 = product_div_x1 + product_div.size['width']
		pdiv_y1 = product_div.location['y']
		pdiv_y2 = product_div_y1 + product_div.size['height']
	
		
		# title should come before the price
		# look for price to for title bound
		
		#for y_div in stacked_divs:
		#	if y_t
		
	def test():
		#p = ProductListingPage("http://shop.nordstrom.com/s/burberry-london-woven-silk-tie/3189709?origin=fashionresultspreview")
		p = ProductListingPage(sys.argv[1])
		review_divs = p.findReviewUsingLiguisticHints()
		exit()
		p.removeHiddenDivs()
		p.removeOutgoingElements()
		#p.findRelatedImageContainers()
		product_div = p.getProductImgDiv()
		review_divs = p.detectReviews(product_div)
		belowReviewDivs = p.thingsBelowReviewDivs(review_divs)
		p.hideElements(review_divs)
		p.hideElements(belowReviewDivs)
		divs = p.getVisibleDivs()
		stacked_divs = p.leafsYSorted(divs)
		print stacked_divs