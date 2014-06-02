from misc import *
import sys
import json

url = get_website_name_for_tracker_update(sys.argv[1])
print json.dumps({'url': url})