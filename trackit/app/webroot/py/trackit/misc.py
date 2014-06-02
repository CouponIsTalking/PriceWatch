import datetime
import re

# incomplete, define rectangle distance
def rectangle_dist(ax1, ax2, ay1, ay2, bx1, bx2, by1, by2):
	
	x_dist = min(abs(ax1-bx1), abs(ax1-bx2), abs(ax2-bx1), abs(ax2-bx2))
	y_dist = min(abs(ay1-by1), abs(ay1-by2), abs(ay2-by1), abs(ay2-by2))
	
def clean_text(text):
	text = text.replace("\n", " ")
	text = re.sub("[' ']+",' ', text)
	text = text.lstrip().rstrip()
	return text
	
def get_words_before_sub_text(text, sub_text, look_from_end):
	
	if look_from_end:
		sub_text_index = text.rfind(sub_text)
	else:
		sub_text_index = text.find(sub_text)
		
	if sub_text_index > 0:
		text_before_sub_text = text[:sub_text_index]
		text_before_sub_text = text_before_sub_text.replace("\n", " ")
		#text_before_sub_text = re.sub('[\.<>=\\/"|~|!|@|#|$|%|^|&|*|(|)|{|}| |1|2|3|4|5|6|7|8|9|0]+',' ', text_before_sub_text)
		words_before_sub_text = [w for w in re.split('\W', text_before_sub_text) if w]
		return words_before_sub_text
	
	return []
	
def ins_in_map (k, map, v):
	if k not in map:
		map[k] = []
	map[k].append(v)

def textHasPrice(s):
	
	dollarIndex = 0
	s = s.rstrip().lstrip()
	s = re.sub(' +','', s)
	lastIndex = len(s) - 1
	
	while dollarIndex <= lastIndex:
	
		while dollarIndex <= lastIndex:
			if s[dollarIndex] == '$':
				break
			else:
				dollarIndex = dollarIndex + 1
		
		i = dollarIndex +1
		beforeDec = 0
		afterDec = 0
		
#		while i <= lastIndex and s[i] == ' ':
#			i = i + 1
			
		while i <= lastIndex and s[i] <= '9' and s[i] >= '0':
			beforeDec = beforeDec * 10 + (int)(s[i]) 
			i = i+1
		
#		while i <= lastIndex and s[i] == ' ':
#			i = i + 1
			
		if i <= lastIndex and s[i] == '.':
			i = i+1
		
#		while i <= lastIndex and s[i] == ' ':
#			i = i + 1
			
		while i <= lastIndex and s[i] <= '9' and s[i] >= '0':
			afterDec = afterDec * 10 + (int)(s[i]) 
			i = i+1
		
		# assumes that price has to be greater than 0.0
		if beforeDec > 0 or afterDec > 0:
			return (float)(str(beforeDec)+'.'+str(afterDec))
		
		dollarIndex = i
		
	
	return None
	
	

# assumes that both urls passed in are valid
# , checks if clicking div_url will take outside of the current page given by curl
def isOutgoingLink(curl, div_url):
	
	if not div_url:
		return False
		
	if curl == div_url:
		return False
	
	if True == div_url.startswith(curl+"#"):
		return False
	
	if "javascript" in div_url:
		return False
		
	return True
	
def AddHTTP(s):
	
	# check here if s is a valid url
	# right now we only check for emptiness
	if not s:
		return s
		
	orig_s = s
	s=s.lower()
	http_index = s.find("http://")
	https_index = s.find("https://")
	
	if http_index == -1 and https_index == -1:
		return "http://" + orig_s
		
	return s

def addslashes(s):
    d = {'"':'\\"', "'":"\\'", "\0":"\\\0", "\\":"\\\\"}
    return ''.join(d.get(c, c) for c in s)

def getTodayMonthDateString(format):
	
	today = datetime.date.today()
	return today.strftime(format)

def getYesterdayMonthDateString(format):

	yesterday = datetime.date.today() - datetime.timedelta(1)
	return yesterday.strftime(format)

def getDigit(s):
	digit = 0
	for c in s:
		if c >= '0' and c <= '9':
			digit = digit*10 + int(c)
		else:
			return 0
			
	return digit
	
def getDate(s):
	date = 0
	for c in s:
		if c>='0' and c<='9':
			date = date*10 + int(c)
		else:
			return 0;
	
	if date >= 1 and date <= 31:
		return date;
	else:
		return 0
		
def getMonth(s):
	
	s = s.lower()
	
	if s == "jan" or s == "january":
			return 1
	if s == "feb" or s == "february":
			return 2
	if s == "mar" or s == "march":
			return 3
	if s == "apr" or s == "april":
			return 4
	if s == "may" or s == "may":
			return 5
	if s == "jun" or s == "jun":
			return 6
	if s == "july" or s == "july":
			return 7
	if s == "aug" or s == "august":
			return 8
	if s == "sep" or s == "september":
			return 9
	if s == "oct" or s == "october":
			return 10
	if s == "nov" or s == "november":
			return 11
	if s == "dec" or s == "december":
			return 12

	return 0;

def lineHasMonthName(s):
	
	s = s.lower()
	
	shortname = []
	shortname.append("jan")
	shortname.append("feb")
	shortname.append("mar")
	shortname.append("apr")
	shortname.append("may")
	shortname.append("jun")
	shortname.append("jul")
	shortname.append("aug")
	shortname.append("sep")
	shortname.append("oct")
	shortname.append("nov")
	shortname.append("dec")
	
	longname = []
	longname.append("january")
	longname.append("february")
	longname.append("march")
	longname.append("april")
	longname.append("may")
	longname.append("jun")
	longname.append("july")
	longname.append("august")
	longname.append("september")
	longname.append("october")
	longname.append("november")
	longname.append("december")
	
	i = 0
	while i < len(shortname):
		
		# if line contains full long name of the month, then return index at that point
		if s.find(longname[i]) >= 0:
			return longname[i]
			
		# else if short month name exists then ensure that it has terminating characters before and after it
		elif s.find(shortname[i]) >= 0:
			
			p = s.find(shortname[i])
			q = p + len(shortname[i])
			
			terminating_char_before_p = -1
			terminating_char_at_q = -1
			
			# is there terminating char before index p or at index p-1 ?
			if p == 0:
				terminating_char_before_p = 1
			else:
				if s[p-1] < 'a' or s[p-1] > 'z': # note that the string is all lower case
					terminating_char_before_p = 1
			
			
			# is there a terminating char at index q ?
			if q == len(s):
				terminating_char_at_q = 1
			else:
				if s[q] < 'a' or s[q] > 'z':  # note that the string is all lower case
					terminating_char_at_q = 1
			
			if terminating_char_before_p == 1 and terminating_char_at_q == 1:
				return longname[i]
		
		
		# increment 'i'
		i = i+1
		
		

		
	return -1
	
