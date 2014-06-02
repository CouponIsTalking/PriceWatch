from reddit_parser import *
import sys

#rp = RedditParser("http://www.reddit.com/r/gaming/comments/1ogvj1/visiting_a_friend_gtav/")
rp = RedditParser(sys.argv[1])
try:
	rp.run()
	page_info_json = rp.get_json_page_info()
	print page_info_json
except Exception as e:
	print "Exception"
	#print e

#deinit in a separate {try, except} block, so that -
#in the event of exception occuring in getting matrixes, we still deinit stuff silently.
try:
	rp.deinit()
except Exception as e:
	pass


