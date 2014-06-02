import urllib2
import const
import div
from div import *
import SeleniumReader
from SeleniumReader import *
from misc import *

class Pinterest:
	def __init__(self, pagelink):
		self.pagelink = pagelink;
		self.repinDiv = Div("class", "socialItem")
		self.likeDiv = Div("class", "socialItem likes")
		self.totalPinsDiv = Div("class", "counts")
		self.totalFollowersDiv = Div("class", "counts")
		self.fullPage = ""
		self.sampleXpath = "/html/body/div[2]/div[3]/div[1]/div[2]/div[3]/div/div/div/div[1]/div/a/div/span[2]";
		
		
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
	
	
	def getBoards(self):
		
		userstats = self.reader.getElementsByCSSSelector("ul.userStats");
		#print userstats
		items = 0
		for elements in userstats:
			line = elements.text.lower()
			items = self.getItem(line, "board")
				
		#print str(items) + " boards";
		return items;
	
	def getPins(self):
		
		userstats = self.reader.getElementsByCSSSelector("ul.userStats");
		items = 0
		#print userstats
		for elements in userstats:
			line = elements.text.lower()
			items = self.getItem(line, "pin")
				
		#print str(items) + " pins";
		return items;
	
	def getLikes(self):
		
		items = 0
		userstats = self.reader.getElementsByCSSSelector("ul.userStats");
		
		for elements in userstats:
			line = elements.text.lower()
			items = self.getItem(line, "like")
				
		#print str(items) + " Likes";
		return items;
		
	def getFollowers(self):
		
		items = 0
		followersFollowingStates = self.reader.getElementsByCSSSelector("ul.followersFollowingLinks");
		
		for elements in followersFollowingStates:
			#print elements.text
			line = elements.text.lower()
			items = self.getItem(line, "follower")
				
		#print str(items) + " Followers";
		return items;

	def getFollowing(self):
		
		items = 0
		followersFollowingStates = self.reader.getElementsByCSSSelector("ul.followersFollowingLinks");
		
		for elements in followersFollowingStates:
			line = elements.text.lower()
			items = self.getItem(line, "following")
				
		#print str(items) + " Following";
		return items;

		
	def getTotalRepins(self):
		repins_div = self.repinDiv.getElementsFromPage(self.fullPage)
		# process and extract repins
		print repins_div
		
		
	def getMatrix(self):
		
		data = -1, -1, -1, -1, -1
		if self.reader.isValid() == 1 :
			self.total_boards = self.getBoards()
			self.total_pins = self.getPins()
			self.total_likes = self.getLikes()
			self.total_follower = self.getFollowers()
			self.total_following = self.getFollowing()
			data = self.total_boards, self.total_pins, self.total_likes, self.total_follower, self.total_following

			return data
	
	def printMatrix(self):
		print "Pinterest matrix"
		print str(self.total_boards) + " Boards\n"
		print str(self.total_pins) + " Pins\n"
		print str(self.total_likes) + " Likes\n"
		print str(self.total_follower) + " Followers\n"
		print str(self.total_following) + " Following\n"

	def deinit(self):
		self.reader.deinit()
		
	def test(self):
		songofstyle_pin = Pinterest("http://pinterest.com/songofstyle/");		
		songofstyle_pin.getHtmlPage()
		songofstyle_pin.getMatrix()
		songofstyle_pin.printMatrix()
		songofstyle_pin.deinit()


#p = Pinterest("")
#p.test()
