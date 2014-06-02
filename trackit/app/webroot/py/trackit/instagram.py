import urllib2
import const
import div
from div import *
import SeleniumReader
from SeleniumReader import *

from misc import *
from htmlops import *

class Instagram:
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
		
		if not s:
			return -1;
			
		s = s.lower()
		
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
		
		
	def getInstagramActivity(self, driver):

		activity = []
		
		main_container = self.reader.getElementsByClassName("main")
		
		if len(main_container) == 0:
			return
		
		#print len(main_container[0])
		
		photo_number = 0
		while 1:
			main_container[0].send_keys(Keys.PAGE_DOWN)
			# get load more element
			more_photos_elements = self.reader.getElementsByClassName("more-photos-enabled")
			if len(more_photos_elements) > 0:
				more_photos_elements[0].click()
			
			photos = self.reader.getElementsByCSSSelector("li.photo")
			
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
				likes = likes.text.encode("utf-8", "ignore")
				likes = decodeInstagramLikesCount(likes)
				#'7.17k'
				comments = soup.find('li', {"class":"stat-comments"})
				comments = comments.text.encode("utf-8", "ignore")
				# comments are encoded the same way as likes
				comments = decodeInstagramLikesCount(comments) 
				#'62'
				
				data = date, likes, comments
				activity.append(data)
				
				photo_number = photo_number + 1
				
				
			# sleep for 2 sec in subsequent load more calls
			time.sleep(2)
			
				
		for act in activity:
			# if date available
					
			print "Date " + act[0] +", " +act[1] +" likes, " + act[2] + "comments"
			print "---------------------------------------------------------------"
			
		return activity
			
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
	
	
	def getPosts(self):
		
		items = 0
		userstats = self.reader.getElementsByClassName("user-stats");
		
		for elements in userstats:
			line = elements.text.lower()
			items = self.getItem(line, "post")
				
		#print str(items) + " posts";
		return items;
	
	def getFollowers(self):
		
		items = 0
		followersFollowingStates = self.reader.getElementsByClassName("user-stats");
		
		for elements in followersFollowingStates:
			line = elements.text.lower()
			items = self.getItem(line, "follower");
			
		#print str(items) + " follower";
		return items;

	def getFollowing(self):
		
		items = 0
		
		followersFollowingStates = self.reader.getElementsByClassName("user-stats");
		for elements in followersFollowingStates:
			line = elements.text.lower()
			items = self.getItem(line, "following")
			
		#print str(items) + " following";
		return items;
		
	
	def getMatrix(self):
		
		data = -1, -1, -1
		
		if self.reader.isValid() == 1 :
			self.total_posts = self.getPosts()
			self.total_follower = self.getFollowers()
			self.total_following = self.getFollowing()
			data = self.total_posts, self.total_follower, self.total_following
		
		return data
	
	def printMatrix(self):
		print "Instagram matrix"
		print str(self.total_posts) + " Posts\n"
		print str(self.total_follower) + " Followers\n"
		print str(self.total_following) + " Following\n"

	def deinit(self):
		self.reader.deinit()
		
	
	def test(self):
		songofstyle_pin = Instagram("http://instagram.com/songofstyle");		
		songofstyle_pin.getHtmlPage()
		#songofstyle_pin.getInstagramActivity()
		songofstyle_pin.getMatrix()
		songofstyle_pin.printMatrix()
		songofstyle_pin.deinit()


		
#inst = Instagram("")
#inst.test()
