import lxml.html
from lxml.html import etree
from htmlops import *
from selenium import webdriver
from selenium.common.exceptions import TimeoutException
from selenium.common.exceptions import *   
from selenium.webdriver.support.ui import WebDriverWait # available since 2.4.0
from selenium.webdriver.common.keys import Keys

#div_compare_mem = {}

def removeSpaceAndNewlineChar(s):
	s = s.replace(" ", "")
	s = s.replace("\n", "")
	s = s.replace("\r", "")
	
	return s

def getAllSubElementList(root):
	q = []
	ri = 0
	q.append(root)
	while ri < len(q):
		children = list(q[ri])
		ri = ri + 1
		for c in children:
			q.append(c)
	
	return q

def getImmediateSubElementList(root):
	q = []
	q.append(root)
	children = list(q[ri])
	for c in children:
		q.append(c)
	
	return q


def compareTextContentIgnoreEmpty(c1, c2):
	str1 = c1.text_content()
	str2 = c2.text_content()
#	str1 = removeSpaceAndNewlineChar(str1)
#	str2 = removeSpaceAndNewlineChar(str2)
	
	l1 = len(str1)
	l2 = len(str2)
	if l1 == 0 and l2 != 0:
		return 0
	if l1 != 0 and l2 == 0:
		return 0
	if l1 == 0 and l2 == 0:
		return 1
	# non zero length for both strings	
	if str1 == str2:
		return 1
	
	return 0
	

def compareTextContent(c1, c2):
	str1 = c1.text_content().encode("ascii","ignore")
	str2 = c2.text_content().encode("ascii","ignore")
#	str1 = removeSpaceAndNewlineChar(str1)
#	str2 = removeSpaceAndNewlineChar(str2)
	
	if str1 and str2 and str1 == str2:
		return 1
	
	return 0
	
def compareXPath(c1, etree1, c2, etree2):
	xpath1 = etree1.getpath(c1)
	xpath2 = etree2.getpath(c2)
	if xpath1 and xpath2 and xpath1 == xpath2:
		return 1
	else:
		return 0

def compareATag(c1, etree1, c2, etree2):
	if c1.tag == 'a' and c2.tag == 'a':
		if compareTextContent(c1, c2) == 1:
			return 1
	
	return 0
	
def compareImgTag(c1, etree1, c2, etree2):
	
	if c1.tag != 'img' or c2.tag != 'img':
		return 0
		
	xpath1 = etree1.getpath(c1)
	xpath2 = etree2.getpath(c2)
	imgsrc1 = etree1.xpath(xpath1+'/@src')
	imgsrc2 = etree1.xpath(xpath2+'/@src')
	
	#if xpath1 and xpath2 and xpath1 == xpath2 and imgsrc1 == imgsrc2:
	if xpath1 and xpath2 and imgsrc1 == imgsrc2:
		return 1
	else:
		return 0



def getMem(div_compare_mem, div1, etree1, div2, etree2):
	#global div_compare_mem;
	
	xpath1 = etree1.getpath(div1)
	xpath2 = etree2.getpath(div2)
	
	if (xpath1, xpath2) in div_compare_mem:
		return div_compare_mem[(xpath1, xpath2)]
	else:
		div_compare_mem[(xpath1, xpath2)] = -1
		return -1

def setMem(div_compare_mem, div1, etree1, div2, etree2, result):
	#global div_compare_mem;
	
	xpath1 = etree1.getpath(div1)
	xpath2 = etree2.getpath(div2)	
	div_compare_mem[(xpath1, xpath2)] = result
	return div_compare_mem[(xpath1, xpath2)]

# is div1 a subtree of div2 ?
def containsSubTree(div_compare_mem, div1, etree1, div2, etree2):
	#global div_compare_mem;
	
	relationship = getMem(div_compare_mem, div1, etree1, div2, etree2)
	
	if relationship == 0 or relationship == 1 or relationship == 2:
		return relationship
	
	# by default, no relationship
	relationship = 0
	
	# if elements are equal, then relationship is 1
	if compareElements(div_compare_mem, div1, etree1, div2, etree2) == 1:
		relationship = 1
		
	# if a copy of div1 is contained within div2 as ones of its child, then relationship is 2
	else:
		childs2 = list(div2)
		for c2 in childs2:
			if containsSubTree(div_compare_mem, div1, etree1, c2, etree2) != 0:
				relationship = 2
				break
	
	setMem(div_compare_mem, div1, etree1, div2, etree2, relationship) 
	
	return 0
	
def compareElements(div_compare_mem, div1, etree1, div2, etree2):
	#global div_compare_mem;
	
	# if we have already compared these 2 divs, then dont compare again
	# simply return stored comparison result
	#
	pre_calc_result = getMem(div_compare_mem, div1, etree1, div2, etree2) 
	if pre_calc_result == 0 or pre_calc_result == 2:
		return 0
	
	if pre_calc_result == 1:
		return pre_calc_result
	
	# set the comparison result as true
	result = 1
	
	#few base conditions
	
	# if div tags dont match, compare will fail
	if div1.tag != div2.tag:
		result = 0
	
	# if number of childs dont match, compare will fail
	childs1 = list(div1)
	childs2 = list(div2)
	if len(childs1) != len(childs2):
		result = 0
	
	# if comparision failed, so far, then store the comparison's result
	# and return
	if result == 0:
		setMem(div_compare_mem, div1, etree1, div2, etree2, result)
		return 0
	
	if len(childs1) == 0:
		if div1.tag == 'a' and compareATag(div1, etree1, div2, etree2) == 1:
			result = 1
		elif div1.tag == 'img' and compareImgTag(div1, etree1, div2, etree2) == 1:
			result = 1
		elif compareTextContent(div1, div2) == 1:
			result = 1
		else:
			result = 0
	
	else:
		# except img div, we should compare the text content of divs
		compareText = 1
		if div1.tag == 'img':
			compareText = 0
		
		if compareText == 1 and compareTextContent(div1, div2) == 0:
			result = 0
		else:
			result = 1
			for c1 in childs1:
				#xpath1 = etree1.getpath(c1)
				#children2 = etree2.xpath(xpath1)
				match_found = 0
				for c2 in childs2:
					if compareElements(div_compare_mem, c1, etree1, c2, etree2) == 1:
						match_found = 1
						break
				if match_found == 0:
					result = 0
					break
					
	setMem(div_compare_mem, div1, etree1, div2, etree2, result)
	
	return result
	
def compareLITag(div_compare_mem, l1, etree1, l2, etree2):
	
	return compareElements(div_compare_mem, l1, etree1, l2, etree2);
	
#	children1 = list(l1)
#	for c
#	xpath1 = etree1.getpath(c1)
#	xpath2 = etree2.getpath(c2)
#	if xpath1 and xpath2 and xpath1 == xpath2:
#		return 1
#	else:
#		return 0
		
def compareULTag(div_compare_mem, ul1, etree1, ul2, etree2):
	
	return compareElements(div_compare_mem, ul1, etree1, ul2, etree2);

#	xpath1 = etree1.getpath(ul1)
#	xpath2 = etree2.getpath(ul2)
#	if xpath1 and xpath2 and xpath1 == xpath2:
#		return 1
#	else:
#		return 0

def print_element_at_xpath(etree, xpath):
	eles = etree.xpath(xpath)
	for e in eles:
		print e.text_content().encode("ascii", "ignore")


def calcRelationships(div_compare_mem, root1, etree1, root2, etree2):
	
	levelSortedChild1List = []
	nextChildId = 0
	levelSortedChild1List.append(root1)
	
	while nextChildId < len(levelSortedChild1List):
		nextChild = levelSortedChild1List[nextChildId]
		
		# if subtree is not present under root2, then add its childrens to
		# level sorted child list
		if containsSubTree(div_compare_mem, nextChild, etree1, root2, etree2) == 0:
		
			itsChildren = list(nextChild)
			for c in itsChildren:
				levelSortedChild1List.append(c)
	
		nextChildId = nextChildId + 1


def compareDocs(driver1, driver2):
	
	#global div_compare_mem;
	
	src1 = removeCommentsAndJS(driver1.page_source)
	tree1 = lxml.html.fromstring(src1)
	etree1 = etree.ElementTree(tree1)

	src2 = removeCommentsAndJS(driver2.page_source)
	tree2 = lxml.html.fromstring(src2)
	etree2 = etree.ElementTree(tree2)

	print "Built both trees\n"

	# drop all memorized results
	div_compare_mem = {}
	print "Looking for subtrees of first doc in another\n"
	calcRelationships(div_compare_mem, tree1, etree1, tree2, etree2)
	print "Done.\n"
	xpaths_to_hide1 = []
	for (x,y) in div_compare_mem:
		if div_compare_mem[(x,y)] > 0:
			xpaths_to_hide1.append(x)

	dimOpacityFromXpath(driver1, xpaths_to_hide1, 0.2)
			
	div_compare_mem_rev = {}
	for (x,y) in div_compare_mem:
		if div_compare_mem[(x,y)] == 2:
			div_compare_mem_rev[(y,x)] = 0
		
		elif div_compare_mem[(x,y)] == 1:
			div_compare_mem_rev[(y,x)] = 1
			
		#elif div_compare_mem[(x,y)] == 0:
		#	div_compare_mem_rev[(y,x)] = 0

	#div_compare_mem = div_compare_mem_rev
	print "Looking for subtrees of second doc in first\n"
	calcRelationships(div_compare_mem_rev, tree2, etree2, tree1, etree1)
	print "Done.\n"
	xpaths_to_hide2 = []
	for (x,y) in div_compare_mem_rev:
		if div_compare_mem_rev[(x,y)] > 0:
			xpaths_to_hide2.append(x)

	print "Setting Opacities\n"
	dimOpacityFromXpath(driver2, xpaths_to_hide2, 0.2)
	
	return (xpaths_to_hide1, xpaths_to_hide2)

def isempty(i_str):
	empty = 1
	for c in i_str:
		if c >= 'a' and c <= 'z':
			empty = 0
			break
		if c >= 'A' and c <= 'Z':
			empty = 0
			break
		if c >= '1' and c <= '9':
			empty = 0
			break
	return empty
	

def dimOpacityFromXpath(driver, xpaths, opacity_value):
	for xpath in xpaths:
		try:
			eles = driver.find_elements_by_xpath(xpath)
		except Exception, e:
			print "exception while looking for " + xpath
			print e
		try:
			for e in eles:
				if opacity_value < 1:
					driver.execute_script("arguments[0].style.opacity='0.2'", e)
				else:
					driver.execute_script("arguments[0].style.opacity='1'", e)
		except Exception, e:
			print "exception while dimming for " + xpath
			print e

def hideElementsFromXpath(driver, xpaths):
	for xpath in xpaths:
		try:
			eles = driver.find_elements_by_xpath(xpath)
		except Exception, e:
			print "exception while looking for " + xpath
		try:
			for e in eles:
				driver.execute_script("arguments[0].style.display='none';", e)
		except Exception, e:
			print "exception while hiding for " + xpath

def showElementsFromXpath(driver, xpaths):
	for xpath in xpaths:
		try:
			eles = driver.find_elements_by_xpath(xpath)
		except Exception, e:
			print "exception while looking for " + xpath
		try:
			for e in eles:
				driver.execute_script("arguments[0].style.display='';", e)
		except Exception, e:
			print "exception while hiding for " + xpath

			
def test():
	driver1 = webdriver.Firefox()
	driver2 = webdriver.Firefox()
	driver3 = webdriver.Firefox()
	driver1.get("http://www.express.com")
	driver2.get("http://www.express.com/clothing/Women/sec/womenCategory")
	driver3.get("http://www.express.com/clothing/Men/sec/menCategory")
	driver1.find_element_by_link_text("Close").click()
	#driver2.find_element_by_link_text("Close").click()
	
	#driver2.find_element_by_link_text("WOMEN").click()
	#driver2.find_element_by_link_text("WOMEN").click()
	re12 = compareDocs(driver1, driver2)
	re23 = compareDocs(driver2, driver3)
	re13 = compareDocs(driver1, driver3)
 
#test()