
	

# Here we set the browser agent string that we're going to send to Google.
# We can't use Python's default since Google doesn't allow that.
UserAgentString = 'Mozilla/5.0 '
UserAgentString += "(Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.0.5)"
UserAgentString += "Gecko/2008120121 Firefox/3.0.5"
#UserAgentString = "Mozilla/5.0 (X11; U; Linux i686; en-US) AppleWebKit/534.3 (KHTML, like Gecko) Chrome/6.0.472.63 Safari/534.3"
#UserAgentString = "Mozilla/5.0 (Windows NT 6.0; WOW64; rv:7.0.1) Gecko/20100101 Firefox/7.0.1"

#------------------------
# ---- config consts ----

SITE_NAME = 'http://localhost/trackit'
TRACKIT_SITE_NAME = 'http://localhost/trackit'
AB_SITE_NAME = 'http://localhost/AB'

# define config constants here

#---------------------------------------------
#------important secret config consts --------
PYTHON_VERIFICATION_CODE = 'fksdfosdofolekrw7etgf474r234'
AB_PYTHON_VERIFICATION_CODE = "fksdfosdofolekrw7etgf474r234"