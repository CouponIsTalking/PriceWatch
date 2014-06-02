import BeautifulSoup
from BeautifulSoup import BeautifulSoup, Comment
import htmlops

class Div:
	def __init__(self, type, name):
		self.type = type;
		self.itemid = name;
		
	def getElementsFromPage(self, fullpage):
		page_without_js_and_comments = removeCommentsAndJS(fullpage)
		if (self.type == 'id'):
			retval = soup.find("div", {self.type: self.itemid})
			
		elif self.type == 'class':
			retval = soup.find("div", {self.type: self.itemid})
			
		return retval;
			