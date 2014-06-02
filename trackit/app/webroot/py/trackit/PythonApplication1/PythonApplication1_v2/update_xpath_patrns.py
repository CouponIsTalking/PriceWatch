from XPathPatrnMaker import *
from url_groups import *

total_exceptions = 0
error_url_file = open("xpath_pattern_builder_errors1.txt", "w")

for urls in url_groups:
    if "zappos.com" not in urls[0]:
        continue
    print "Testing urls -\n"
    print urls
    #try:
    if 1:
        xppm = XPathPatrnMaker(urls)
        xppm.doTheDew()
        xppm.deinit()
    try:
        a = 1
    except MemoryError, e:
        print "Memory Error"
        error_url_file.write(str(urls))
        error_url_file.write("\n")
        print e
        total_exceptions = total_exceptions + 1
        
    except Exception, e:
        print "Exception"
        print e
        error_url_file.write(str(urls))
        error_url_file.write("\n")
        total_exceptions = total_exceptions + 1

error_url_file.close()
print "\n\nTotal Exceptions : " + str(total_exceptions) + "\n"
