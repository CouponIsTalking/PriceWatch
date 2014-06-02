from lxmlops import *
from htmlops import *
from misc import *
from SeleniumReader import *
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


class ImgurParser:
	def __init__(self, url):
		self.url = AddHTTP(url)
		self.reader = None
		#print self.url
		self.views = 0
		self.points = 0
		self.comments = 0
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
		self.views = self.get_views()
		self.points = self.get_points()
		self.comments = self.get_comments()
		#self.submitter = self.get_submitter()
		#print "points " + str(self.points)
		#print "submitter " + str(self.submitter)
	
	def get_json_page_info(self):
		page_info = {}
		page_info['views'] = self.views;
		page_info['points'] = self.points;
		page_info['comments'] = self.comments;
		#page_info['submitter'] = self.submitter;
		return json.dumps(page_info);
		
	def get_number(self, word):
		
		if not word:
			return None
		
		num = 0
		for c in word:
			if c>='0' and c<='9':
				num = num * 10 + int(c)
		
		return num
	
	
	def get_views(self):
		
		page = self.page
		soup = BeautifulSoup(page)
		
		views_div = soup.find('span', attrs={'id': 'stats-views'})
		views_div_text = ""
		if views_div:
			views_div_text = views_div.text.encode("ascii", "ignore");
		
		views = self.get_number(views_div_text);
		return views
			
	
	def get_points(self):
		
		page = self.page
		soup = BeautifulSoup(page)
		
		info_div = soup.find('div', attrs={'class': 'info textbox'})
				
		text = info_div.text.encode("ascii", "ignore")
		#text = ''.join(div.findAll(text=True))
		text = clean_text(text).lower()
		text = text.replace("&#32;", " ")
		index_of_points = text.find("point")
		
		text = text[:index_of_points]
		points = self.get_number(text)
		
		return points;
	
	def get_comments(self):
		
		page = self.page
		soup = BeautifulSoup(page)
		
		comment_count_div = soup.find('span', attrs={'id': 'comment-count'})
		#print comment_count_div		
		text = comment_count_div.text.encode("ascii", "ignore")
		#print text
		#text = ''.join(div.findAll(text=True))
		text = clean_text(text).lower()
		text = text.replace("&#32;", " ")
		index_of_points = text.find("comment")
		
		text = text[:index_of_points]
		comments = self.get_number(text)
		
		return comments;
	

	#def get_submitter(self):
	#	page = self.page
	#	soup = BeautifulSoup(page)
		
	#	siteTable_div = soup.find('div', attrs={"id": "siteTable"})
	#	tagline_divs = siteTable_div.findAll('p', attrs={'class': 'tagline'})
		
	#	for div in tagline_divs:
	#		text = div.text.encode("ascii", "ignore")
	#		#text = ''.join(div.findAll(text=True))
	#		text = clean_text(text).lower()
	#		text = text.replace("&#32;", " ")
	#		
	#		#print text
	#		if 'submitted' in text:
	#			index_of_by = text.find("by")
	#			if index_of_by >= 0:
	#				words = text[index_of_by:].split(' ')
	#				if len(words) >1:
	#					user = words[1]
	#					return user
		
	#	return "";
		
	
		
