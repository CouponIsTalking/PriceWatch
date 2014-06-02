from lxmlops import *
from htmlops import *
from misc import *
from BeautifulSoup import *
import urllib2
import json
from HTMLParser import HTMLParser

class MLStripper(HTMLParser):
    def __init__(self):
        self.reset()
        self.fed = []
    def handle_data(self, d):
        self.fed.append(d)
    def get_data(self):
        return ''.join(self.fed)


class RedditParser:
	def __init__(self, url):
		self.url = AddHTTP(url)
		self.reader = None
		self.points = 0
		self.submitter = "";
	
	def deinit(self):
		if self.reader:
			self.reader.deinit()
	
	def get_page_from_selenium(self):
		self.reader = SeleniumReader(self.url)
		page = self.reader.getPageSource()
		page = removeCommentsAndJS(page)
		self.page = page
		
	
	def get_page_from_urllib2(self):
		
		opener = urllib2.build_opener()
		opener.addheaders = [('User-agent', 'Mozilla/5.0')]
		response = opener.open(self.url)
		page = response.read()
		##print page
		page = removeCommentsAndJS(page)
		self.page = page		
	
	def run(self):
		#self.get_page_from_urllib2()
		self.get_page_from_selenium()
		self.points = self.get_points()
		self.submitter = self.get_submitter()
		#print "points " + str(self.points)
		#print "submitter " + str(self.submitter)
	
	def get_json_page_info(self):
		page_info = {}
		page_info['points'] = self.points;
		page_info['submitter'] = self.submitter;
		return json.dumps(page_info);
		
	def get_number(self, word):
		
		if not word:
			return None
		
		num = 0
		for c in word:
			if c>='0' and c<='9':
				num = num * 10 + int(c)
		
		return num
	
	
	def get_points(self):
		page = self.page
		soup = BeautifulSoup(page)
		
		side_div = soup.find('div', attrs={'class': 'side'})
		score_div = side_div.find('div', attrs={'class' : 'score'})
		number_div = score_div.find('span', attrs={'class':'number'})
				
		word = number_div.text.encode("ascii", "ignore")
		points = self.get_number(word)
		
		return points;
	
	def get_submitter(self):
		page = self.page
		soup = BeautifulSoup(page)
		
		siteTable_div = soup.find('div', attrs={"id": "siteTable"})
		tagline_divs = siteTable_div.findAll('p', attrs={'class': 'tagline'})
		
		for div in tagline_divs:
			text = div.text.encode("ascii", "ignore")
			#text = ''.join(div.findAll(text=True))
			text = clean_text(text).lower()
			text = text.replace("&#32;", " ")

			#print text
			if 'submitted' in text:
				index_of_by = text.find("by")
				if index_of_by >= 0:
					words = text[index_of_by:].split(' ')
					if len(words) >1:
						user = words[1]
						return user
		
		return "";
		
	
		
