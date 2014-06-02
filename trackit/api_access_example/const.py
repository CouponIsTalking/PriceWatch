
	

# Here we set the browser agent string that we're going to send to Google.
# We can't use Python's default since Google doesn't allow that.
UserAgentString = 'Mozilla/5.0 '
UserAgentString += "(Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.5)"
UserAgentString += "Gecko/2008120121 Firefox/3.0.5"
#UserAgentString = "Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.63 Safari/534.3"
#UserAgentString = "Mozilla/5.0 (Windows NT 6.0; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1"

#------------------------
# ---- site addr config consts ----

LOCAL_SITE_NAME = 'http://localhost/trackit'
SITE_NAME = LOCAL_SITE_NAME # SITE_NAME is an alias for local site
LIVE_SITE_NAME = "http://alpha.couponistalking.com"

#---------------------------------------------
#------important secret config consts --------
LIVE_API_KEY = "adadad348jqdj2~!@!!@"
LOCAL_API_KEY = "adadad348jqdj2~!@!!@"

#where to run
RUN_WHERE = 'local' #