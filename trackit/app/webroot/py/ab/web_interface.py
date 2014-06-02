import sys
import urllib
import urllib2
import const
from const import *

import misc
from misc import *

import BeautifulSoup
from BeautifulSoup import BeautifulSoup, Comment
import htmlops


class WebInterface:
	## -------------------------------
	## Given a stats of a blogger
	## update the database with new 
	## stats
	## -------------------------------
	def update_blogger_matrix (self, bloggerid, matrixes):
		
		fbdata = matrixes[0]
		twdata = matrixes[1]
		pinterestdata = matrixes[2]
		instagramdata = matrixes[3]
		
		data = {
					'id' : addslashes(bloggerid),
					'fb' : {'likes' : -1, 'followers' : -1, 'active_conversations' : -1 },
					'twitter' : {'tweets' : -1, 'followers' : -1, 'following' : -1 },
					'instagram' : {'posts' : -1, 'followers' : -1, 'following' : -1 },
					'pinterest' : {'likes' : -1, 'pins' : -1, 'boards' : -1, 'followers' : -1, 'following' : -1},
				  
				  }
		
		update_url = SITE_NAME+'/bloggers/update_blogger_stats/'+ PYTHON_VERIFICATION_CODE;
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		data = urllib.urlencode(data)
		req = urllib2.Request(update_url, data, headers)
		try :
			print update_url
			response = urllib2.urlopen(req)
			print response
			print "db updated for blogger " + str(bloggerid)
		except Exception, e:
			print "Error in updating db"
			print e
			return 0
		
		#returned_page = response.read()
		#print returned_page
		# TODO : read the page and verify that the project was added successfully.
		
		return 1


	#--------------------------------------------
	# gets urls to parse 
	# return value is html page that needs to be 
	# parsed further to extract urls 
	#--------------------------------------------
	def get_bloggers_url ( self ):
		
		url_to_call = SITE_NAME+'/bloggers/get_list_for_parsing/'+ PYTHON_VERIFICATION_CODE;
		user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
		headers = { 'User-Agent' : user_agent }
		
		req = urllib2.Request(url_to_call, None, headers)
		try :
			print url_to_call
			response = urllib2.urlopen(req)
			response_page = response.read()
			
			clean_response_page = htmlops.removeCommentsAndJS(response_page)
			soup = BeautifulSoup(clean_response_page)
			#print clean_response_page
			# pull the divs
			main_div = soup.findAll('div', attrs={'id' : '_parsing_urls_'})
			#print main_div
			#print "print beautiful soup over"
			
			#build up result set
			resultset = []
			for div in main_div:
				resultset.append( 
					{ 
						'blog':div['blog'].encode("ascii", "ignore"), 
						'facebooklink':div['facebooklink'].encode("ascii", "ignore"), 
						'twitterlink':div['twitterlink'].encode("ascii", "ignore"), 
						'instagramlink':div['instagramlink'].encode("ascii", "ignore"), 
						'pinterestlink': div['pinterestlink'].encode("ascii", "ignore"), 
						'bloggerid':div['bloggerid'].encode("ascii", "ignore"), 
						'ready_to_parse':div['ready_to_parse'].encode("ascii", "ignore")
					} 
				);
			
			print resultset
			# returl thus prepared url list :)
			return resultset
		except :
			print "FATAL ERROR : Couldnt get url list"
			print "Unexpected error:", sys.exc_info()[0]
			raise
			return None
			