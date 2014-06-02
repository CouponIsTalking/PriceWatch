from imgur_parser import *
import sys

#rp = ImgurParser("http://imgur.com/gallery/iSAGiob")
rp = ImgurParser(sys.argv[1])
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

