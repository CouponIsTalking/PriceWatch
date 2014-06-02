import SocialMatrix
from SocialMatrix import *
import web_interface
from web_interface import *

from misc import *

web = WebInterface()

while 1:
	
	bloggers = web.get_bloggers_url()
	
	for blogger in bloggers :
		print "Getting social clout of BloggerID " + blogger['bloggerid']
		print "Blog " + blogger['blog']
		
		# open the interface 
		sm = SocialMatrixInf()
		print blogger['facebooklink']
		print blogger['twitterlink']
		print blogger['instagramlink']
		print blogger['pinterestlink']
		
		sm.setFacebookUrl(blogger['facebooklink'])
		sm.setTwitterUrl(blogger['twitterlink'])
		sm.setPinterestUrl(blogger['pinterestlink'])
		sm.setInstagramUrl(blogger['instagramlink'])
						
		matrixes = sm.getMatrix()
		
		#matrixes = dummy_matrixes
		#matrixes =((53217, 6732, 6732), (0, 338, 0), (2603, 988117, 538), (26, 1454, 27, 36541, 8)) 
		print matrixes
		
		sm.deinit()
		
		web.update_blogger_matrix(blogger['bloggerid'], matrixes)
		
		time.sleep(2)