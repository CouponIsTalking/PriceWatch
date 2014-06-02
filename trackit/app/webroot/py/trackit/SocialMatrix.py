import facebook
from facebook import *
import twitter
from twitter import *
import instagram
from instagram import *
import pinterest
from pinterest import *


class SocialMatrixInf:

	def __init__(self):
		self.twitterlink = ""
		self.facebooklink = ""
		self.instagramlink = ""
		self.pinterestlink = ""

	def setTwitterUrl(self, url):
		self.twitterlink = url
		
	def setFacebookUrl(self, url):
		self.facebooklink = url
		
	def setPinterestUrl(self, url):
		self.pinterestlink = url
	
	def setInstagramUrl(self, url):
		self.instagramlink = url
		
	def getMatrix(self):
		
		fb_matrix = []
		tw_matrix = []
		instagram_matrix = []
		pinterest_matrix = []
		
		# facebook page
		if self.facebooklink:
			songofstyle_pin = Facebook(self.facebooklink);		
			songofstyle_pin.getHtmlPage()
			time.sleep(10)
			fb_matrix = songofstyle_pin.getMatrix()
			songofstyle_pin.printMatrix()
			songofstyle_pin.deinit()
				
		# twitter page
		if self.twitterlink:
			songofstyle_pin = Twitter(self.twitterlink);		
			songofstyle_pin.getHtmlPage()
			time.sleep(10)
			tw_matrix = songofstyle_pin.getMatrix()
			songofstyle_pin.printMatrix()
			songofstyle_pin.deinit()

		# pinterest page
		if self.pinterestlink:
			songofstyle_pin = Pinterest(self.pinterestlink);		
			songofstyle_pin.getHtmlPage()
			time.sleep(10)
			pinterest_matrix = songofstyle_pin.getMatrix()
			songofstyle_pin.printMatrix()
			songofstyle_pin.deinit()
		
		# instagram page
		if self.instagramlink:
			songofstyle_pin = Instagram(self.instagramlink);		
			songofstyle_pin.getHtmlPage()
			time.sleep(10)
			instagram_matrix = songofstyle_pin.getMatrix()
			songofstyle_pin.printMatrix()
			songofstyle_pin.deinit()

		matrixes = fb_matrix, tw_matrix, pinterest_matrix, instagram_matrix
		
		return matrixes
		
	def deinit(self):
		print "------"
	
	def test(self):
		sm = SocialMatrixInf()
		sm.setFacebookUrl("https://www.facebook.com/SongOfStyle")
		sm.setTwitterUrl("https://twitter.com/aimeesong")
		sm.setInstagramUrl("http://instagram.com/songofstyle")
		sm.setPinterestUrl("http://pinterest.com/songofstyle/")
		matrixes = sm.getMatrix()
		#print matrixes
		sm.deinit()
		

#sm_test = SocialMatrixInf()
#sm_test.test()