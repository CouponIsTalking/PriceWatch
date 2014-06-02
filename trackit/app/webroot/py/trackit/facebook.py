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

class Facebook:
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

	
	# expects an all lower case string		
	def isFbTimeAndDate(self, s):
	
		orig_str = s;
		
		i1 = s.find("hours ago")
		i2 = s.find("hour ago")
		i3 = s.find("minutes ago")
		i4 = s.find("minute ago")
		
		if i1 != -1 or i2 != -1 or i3 != -1 or i4 != -1:
			return getTodayMonthDateString("%b %d")
		
		i1 = s.find("yesterday")
		
		if i1 != -1:
			return getYesterdayMonthDateString("%b %d")
		
		firstspace = orig_str.find(' ')
		
		if firstspace == -1:
			return ""
		
		firstpart = orig_str[:firstspace]
		
		nextspace = orig_str.find(' ', firstspace + 1)
		
		secondpart = ""
		if nextspace == -1:
			secondpart = orig_str[firstspace+1:len(orig_str)]
		else:
			secondpart = orig_str[firstspace+1:nextspace]
		print firstpart
		print secondpart
		date = 0
		if getDate(firstpart) >= 1 and getMonth(secondpart) >= 1:
			return secondpart + " " + firstpart
		elif getDate(secondpart) >= 1 and getMonth(firstpart) >= 1:
			return firstpart + " " + secondpart
		else:
			return ""

	# returns number of likes if this is a "x, y and z like this"
	# else returns -1
	def isLikeThisLine(self, s):
	
		orig_str = s
		#print s
		
		if s.rfind("like this") != -1:
			if len(s)-1 - s.rfind("like this") == 9:
				if len(s) <= 11:
					return 0
					
				a = s.count(',') + 1
				like_count = 0
				for c in s:
					if c>='0' and c<='9':
						like_count = like_count * 10 + int(c)
				
				return like_count + a;
			else:
				return -1
		else:
			return -1

		
	def getItem(self, item_string, item_name):
		
		itemCount = 0;
		item_name_string_index = item_string.find(item_name)
		if item_name_string_index == 0:
			item_name_string_index = item_string.find(item_name, item_name_string_index+1)
			
		i = -1;
		#print item_name_string_index
		#print item_string
		
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
	
	
	def getLikes(self):
		
		items = 0
		if self.reader.isValid() == 0 :
			return -1	# cant calculate
			
		page_source = self.reader.getPageSource()
		page_source = removeCommentsAndJS(page_source)
		soup = BeautifulSoup(page_source)
		timeline_header_element = soup.find("div", {"id":"fbTimelineHeadline"})		
		
		info_string = timeline_header_element.text
		
		if info_string:
			items = self.getItem(info_string.lower(), "like")
				
		#print str(items) + " boards";
		return items;
			
	def getTalkingAboutThis(self):
		
		items = 0

		if self.reader.isValid() == 0 :
			return -1	# cant calculate
			
		page_source = self.reader.getPageSource()
		page_source = removeCommentsAndJS(page_source)
		soup = BeautifulSoup(page_source)
		timeline_header_element = soup.find("div", {"id":"fbTimelineHeadline"})		
		
		info_string = timeline_header_element.text
		
		if info_string:
			items = self.getItem(info_string.lower(), "talking")
		
		return items;


	def getTimelinePostsData(self):
		
		activity = []
		
		if self.reader.isValid() == 0 :
			return activity	# cant calculate
			
		# first of all, press the down key 1000 times to get all the 
		# timeline elements
		ele = self.reader.getElementsByClassName("timelineLayout")
		
		if not ele:
			return activity
		
#		i = 0;
#		while i < 1000:
#			ele[0].send_keys(Keys.DOWN)
#			i = i+1
#			# sleep for 2 secs between subsequent requests
#			time.sleep(2)
		
		# read all timeline elements 
		timeline_elements = self.reader.getElementsByCSSSelector("li.fbTimelineUnit")
		
		# parse each timeline element now
		for e in timeline_elements:
			
			# get the element data string
			s = ""
			if e.text.encode("utf-8", "ignore"):
				s = e.text.encode("utf-8", "ignore").lower()
			
			# separate element string based on newline characters
			# and create a list of string for each timeline element
			j1 = 0
			j2 = s.find('\n', j1)
			list = []
			while j2 != -1:
				list.append(s[j1:j2])
				j1 = j2 +1
				j2 = s.find('\n', j1)
			
			list.append(s[j1:])
			
			# first (offset 0) element will be the name of poster
			# skip the name of the poster
			# second element will be the date and time of post
			# get this data
			timestamp = self.isFbTimeAndDate(list[1])
			#print timestamp
			#print list[1]
			# 3rd (offset 2) will be status (sometimes status might not be there)
			# 4th (offset 3) will be like, share comment line
			# we dont care about the actual status right now
			
			numberoflikes = self.isLikeThisLine(list[3])
			next = 4;
			if numberoflikes == -1:
				numberoflikes = self.isLikeThisLine(list[4])
				next = 5
			
			if numberoflikes == -1:
				numberoflikes = 0;
			
			comments = 0
			if next < len(list):
				
				# find total comments now	
				morecomments = 0
				s = list[next]
				
				if s.find("more comment") != -1:
					for c in s:
						if c>=0 and c<=9:
							morecomments = morecomments * 10 + int(c)
					
					next = next + 1
					# count the number of dates, because each comment
					# will have a date associated with it
				
				# count viewable comments now
				viewablecomments = 0;
				i = next;
				while i < len(list):
					line = list[i]
					if getMonth(line) >= 0 and getDate(line) >=0:
						viewablecomments = viewablecomments + 1
					i = i +1
				
				# total comment = viewable comments + hidden comments
				comments = viewablecomments + morecomments
			
			data = timestamp, 1, numberoflikes, comments
			print data
			print "------------------------------\n"
			activity.append(data)
		
		# voila, we have grab a ton of activity, now return it
		
		for act in activity:
			print "FB Post :" + str(act[0]) + " " + str(act[2]) + " likes" + " " + str(act[3]) + " comments" + "\n"
		
		return activity
		
		
	def getMatrix(self):
	
		data = -1, -1
		
		if self.reader.isValid() == 1 :
			self.total_likes = self.getLikes()
			self.total_ppl_talking_about = self.getTalkingAboutThis()
			#self.total_follower = self.getFollowers()
			#self.total_following = self.getFollowing()
			data = self.total_likes, self.total_ppl_talking_about, self.total_ppl_talking_about
			
		return data
	
	def printMatrix(self):
		print "Facebook matrix"
		print str(self.total_likes) + " Likes\n"
		print str(self.total_ppl_talking_about) + " People Talking About\n"
		#print str(self.total_following) + " Following\n"

	def deinit(self):
		self.reader.deinit()
		
	def test(self):
		songofstyle_pin = Facebook("https://www.facebook.com/SongOfStyle");		
		songofstyle_pin.getHtmlPage()
		songofstyle_pin.getTimelinePostsData()
		#songofstyle_pin.getMatrix()
		#songofstyle_pin.printMatrix()
		#songofstyle_pin.deinit()


#f = Facebook("")
#f.test()

