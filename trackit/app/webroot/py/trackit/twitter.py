import urllib2
import const
import div
from div import *
import SeleniumReader
from SeleniumReader import *

import htmlops
from htmlops import *
import misc
from misc import *

class Twitter:
	def __init__(self, pagelink):
		self.pagelink = pagelink;
		self.fullPage = ""
		
		
	def getHtmlPage(self):
		headers = {'User-Agent' : 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.7 (KHTML, like Gecko) Chrome/16.0.912.77 Safari/535.7'}
		#request = urllib2.Request(self.pagelink, headers)
		#request = urllib2.Request("http://www.google.com", headers)
		#response = urllib2.urlopen(request)
		#response = urllib2.urlopen("http://pinterest.com/")
		self.pagelink = AddHTTP(self.pagelink)
		self.reader = SeleniumReader(self.pagelink)
		#self.fullPage = reade
		
    #description = "This shape has not been described yet"
    #author = "Nobody has claimed to make this shape yet"

	def getItem(self, item_string, item_name):
		
		itemCount = 0;
		item_name_string_index = item_string.find(item_name)
		i = -1;
		
		for s in item_string:
			i = i + 1
			if i == item_name_string_index:
				break
			elif s >= '0' and s <= '9':
				itemCount = itemCount * 10 + int(s)
			elif s>= 'a' and s <= 'z':
				itemCount = 0
			else:
				continue
		#print itemCount
		return itemCount
	
	
	def getTweets(self):
		
		print "Entering getTweets"
		
		items = 0
		
		page_source = self.reader.getPageSource()
		page_source = removeCommentsAndJS(page_source)
		soup = BeautifulSoup(page_source)
		covering_ul_element = soup.find('ul', {"class" : "stats js-mini-profile-stats"})
		
		info_string = covering_ul_element.text
		print info_string
		
		if info_string:
			items = self.getItem(info_string.lower(), "tweet")
				
		#print str(items) + " boards";
		return items;
			
	def getFollowers(self):
		
		print "Entering getFollowers"
		
		items = 0
		
		page_source = self.reader.getPageSource()
		page_source = removeCommentsAndJS(page_source)
		soup = BeautifulSoup(page_source)
		covering_ul_element = soup.find('ul', {"class" : "stats js-mini-profile-stats"})
		info_string = covering_ul_element.text
		
		print info_string
		
		if info_string:
			items = self.getItem(info_string.lower(), "follower")
		
		return items;

	def getFollowing(self):
		
		print "Entering getFollowing"
		
		items = 0
		
		page_source = self.reader.getPageSource()
		page_source = removeCommentsAndJS(page_source)
		soup = BeautifulSoup(page_source)
		covering_ul_element = soup.find('ul', {"class" : "stats js-mini-profile-stats"})
		info_string = covering_ul_element.text
		
		print info_string
		
		if info_string:
			items = self.getItem(info_string.lower(), "following")
		
		return items;

		
	def getStreamPostData(self):
		
		# to hold user activity map
		activity = []
		
		# get the driver
		driver = self.reader.getDriver()
		
		# then, first of all, press the down key 1000 times to get all the 
		# tweet elements
		#ele = driver.find_elements_by_class_name("timelineLayout")
		ele = self.reader.getElementsById("page-container")
		
		#print ele
		
		if not ele:
			return activity
		
		# if we found the container element, then press the down 
		# key on the container
		i = 0;
		while i < 1000:
			ele[0].send_keys(Keys.DOWN)
			i = i+1
			# sleep for 2 secs between subsequent requests
			time.sleep(1)
		
		
		# Ok, now grab all the stream expansion clicks, such as "expand this"
		# "view summary", "view photos" and expand all the streams
		#ele = driver.find_elements_by_class_name("expand-stream-item")
		ele = self.reader.getElementsByClassName("expand-stream-item")
		
		if ele:
			for e in ele:
				e.click()
		
		# Ok, now we have pulled all elements and expanded all of those
		# now grab one tweet at a time and analyze that
		#
		#ele = driver.find_elements_by_css_selector("li.original-tweet-container")
		ele = self.reader.getElementsByCSSSelector("li.original-tweet-container")
		
		if not ele:
			return activity
		
		# for each tweet
		#
		for e in ele:
			# get tweet text and strip it apart by cutting at the newline character
			#
			s = e.text.encode("utf-8", "ignore")
			j1=0
			j2 = s.find('\n', j1)
			list = []
			while j2 != -1:
					list.append(s[j1:j2])
					j1 = j2+1
					j2 = s.find('\n', j1)
			list.append(s[j1:])
			
			for l in list:
				print l
			
			print "\n----------------------------------\n"
			# first item will be profile owner's name and link
			# skip the first item
			# second item will be date
			line = list[1]
			separator = line.find(' ')
			firstpart = line[:separator]
			second_separator = line.find(' ',separator+1)
			secondpart = ""
			if second_separator == -1:
				secondpart = line[separator+1:]
			else:
				secondpart = line[separator+1:second_separator]
			
			# build the timestamp "month date" format
			# we can change this later to suit our needs
			#print "-"+firstpart+"-"+secondpart+"-"+"\n"
			timestamp = ""
			if getDate(firstpart) >= 1 and getMonth(secondpart)>=1:
				timestamp = secondpart + " " + firstpart
			elif getDate(secondpart) >= 1 and getMonth(firstpart)>=1:
				timestamp = firstpart + " " + secondpart
			else:
				print "timestamp couldn\'t be found in "+line
			
			# now look for retweet number and favorite numbers
			retweet_count = 0;
			i = 2
			while i < len(list):
				if list[i] == "RETWEET":
					retweet_count = getDigit(list[i-1])
				i = i +1
				
			favorite_count = 0;	
			i = 2
			while i < len(list):
				if list[i] == "FAVORITE":
					favorite_count = getDigit(list[i-1])
				i = i+1
				
			# now we will have an element for the activity map
			# a date corresponds to an activity factor
			# activity factor determined by :
			# tweet done ?
			# how many retweets ?
			# how many favorite ?
			# 1 point for each tweet, 2 point for each retweet and each favorite
			
			data = timestamp, 1, retweet_count, favorite_count
			print data
			print "------------------------------\n"
			activity.append(data)
		
		# voila, we have grab a ton of activity, now return it
		
		for act in activity:
			print "T :" + str(act[0]) + " " + str(act[2]) + "RT" + " " + str(act[3]) + "FAV" + "\n"
		
		return activity
		
			
	def getMatrix(self):
		
		data = -1, -1, -1
		
		if self.reader.isValid() == 1 :
			self.total_tweets = self.getTweets()
			self.total_follower = self.getFollowers()
			self.total_following = self.getFollowing()
			data = self.total_tweets, self.total_follower, self.total_following

		return data
	
	def printMatrix(self):
		print "Twitter matrix"
		print str(self.total_tweets) + " Tweets\n"
		print str(self.total_follower) + " Followers\n"
		print str(self.total_following) + " Following\n"

	def deinit(self):
		self.reader.deinit()
		
	def test(self):
		songofstyle_pin = Twitter("https://twitter.com/aimeesong");		
		songofstyle_pin.getHtmlPage()
		#songofstyle_pin.getStreamPostData()
		songofstyle_pin.getMatrix()
		songofstyle_pin.printMatrix()
		songofstyle_pin.deinit()



#t = Twitter("")
#t.test()
