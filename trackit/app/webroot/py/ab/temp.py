from selenium import webdriver
from selenium.common.exceptions import TimeoutException
from selenium.common.exceptions import *   
from selenium.webdriver.support.ui import WebDriverWait # available since 2.4.0
from selenium.webdriver.common.keys import Keys
import math
import datetime
import time
from datetime import date
from datetime import datetime
import re

import BeautifulSoup
from BeautifulSoup import BeautifulSoup, Comment
import htmlops

import hashlib

from const import *

import web_interface
#import db_connection
import selenium_extra_ops

import misc
from misc import *

# assumes day followed by month, followed by year
def decodeInstagramDate(s):

	s = s.lower()
	
	month = lineHasMonthName(s)
	if month == -1:
		return ""
		
	day = 0
	month = getMonth(month)
	year = 0
	
	monthfound = 0
	for c in s:
		
		if monthfound == 0 and c >= '0' and c <= '9':
			day = day *10 + int(c)
		elif c >= 'a' and c <= 'z': # if letter comes then ,for simplicity, assume that it is a month
			monthfound = 1
		elif monthfound == 1 and c >= '0' and c <= '9':
			year = year *10 + int(c)
		else:
			continue;	# there is a char other than a letter or a number 
			
	
	if year < 20:
		year = year + 2000
	
	date =  day, month, year
	
	return date

def decodeInstagramLikesCount(s):
	
	point_index = -1
	letter_index = -1
	likes = 0
	divisor = 1
	multiplier = 1
	index = -1
	
	for c in s:
		index = index + 1
		if c >='0' and c<='9':
			if point_index == -1:
				likes = likes * 10 + float(c)
			else:
				likes = likes + float(c)/divisor
				divisor = divisor*10
			
		elif c == '.':
			point_index = index
			divisor = 10
			
		elif c == 'k':
			if index == len(s) -1:
				multiplier = 1000
			else:
				return -1
				
		elif c == 'm':
			if index == len(s) -1:
				multiplier = 1000000
			else:
				return -1
			
		else:
			return -1
		
	
	return int(likes * multiplier)
	
	
def test(driver):

	activity = []
	
	main_container = driver.find_elements_by_class_name("main")
	
	if len(main_container) == 0:
		return
	
	#print len(main_container[0])
	
	photo_number = 0
	while 1:
		main_container[0].send_keys(Keys.DOWN)
		time.sleep(2)
		more_photos_elements = driver.find_elements_by_class_name("more-photos-enabled")
		if len(more_photos_elements) > 0:
			more_photos_elements[0].click()
		
		photos = driver.find_elements_by_css_selector("li.photo")
		
		# if no photos found then break
		if len(photos) <= 0:
			break
			
		if photo_number == len (photos):
			break;
		
		while photo_number < len(photos):
		
			html = photos[photo_number].get_attribute("innerHTML")
			html = removeCommentsAndJS(html)		
			soup = BeautifulSoup(html)
			date = soup.find('time', {"class": "photo-date"})
			date = date.text.encode("utf-8", "ignore")
			#'25August2013'
			#decode date
			date = decodeInstagramDate(date)
			likes = soup.find('li', {"class": "stat-likes"})
			likes.text.encode("utf-8", "ignore")
			likes = decodeInstagramLikesCount(likes)
			#'7.17k'
			comments = soup.find('li', {"class":"stat-comments"})
			comments.text.encode("utf-8", "ignore")
			# comments are encoded the same way as likes
			comments = decodeInstagramLikesCount(comments) 
			#'62'
			
			data = date, likes, comments
			activity.append(data)
			
			photo_number = photo_number + 1
			
				
			
	for act in activity:
		# if date available
				
		print "Date " + act[0] +", " +act[1] +" likes, " + act[2] + "comments\n"
		
		
	return activity
	