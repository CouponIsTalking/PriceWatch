import SeleniumReader
from SeleniumReader import *

from shapes import *
from filter_product_page import *

import re 

from htmlops import *
from misc import *
from tag_classifier import *
from lxmlops import *

import time

#	i = 0
#	childlist = []
#	childlist.append(root)
#	while i < len(childlist):
#		children = list(childlist[i])
#		
#		for c in children:
#			href= c.get('href')
#			if href:
#				if href[0] == '#':
#					childlist.append(c)
#				elif href == curl:
#					childlist.append(c)
#				elif href.startswith(curl + '#'):
#					childlist.append(c)
#				else:
#					c.drop_tree();
#			else:
#				childlist.append(c)
#				
#		i = i +1


class Grouper:
	
	def __init__(self, url_or_reader):
		
		self.reader = None
		if isinstance(url_or_reader, str):
			self.reader = SeleniumReader(url_or_reader);
			
		elif isinstance(url_or_reader, SeleniumReader):
			self.reader = url_or_reader
		else:
			print "~~~~~~~~~ Bad param to constructor ~~~~~~~~~~~~~"
			return
		
		self.reader.getDriver().maximize_window()
		
		# generate tree from full source
		self.full_orig_page = self.reader.getPageSource()
		self.ltreeops = lxmlops(self.full_orig_page)
		
		self.PLP = ProductListingPage(self.reader)
		self.eidmap = {}
		self.xmap = {}
		self.ymap = {}
		self.wmap = {}
		self.hmap = {}
		self.wnhmap = {}
		self.classmap = {}
		self.idmap = {}
		self.tagmap = {}
		self.alle = {}
		self.y_aligned_map = {}
		self.x_aligned_map = {}
		self.tag_class_wnh_x_map = {}
		self.tag_class_wnh_y_map = {}
		self.tag_class_wnh_map = {}
		
		self.grids = []
		
		# product eids
		self.product_eids = []
		self.product_container = {}
		
		# title eids
		self.title_eid = None
		
		# desc elements
		self.desc_eids = []
		
		# detail elements
		self.details_eids = []
		
		# color eids
		# size eids
		self.color_eids = []
		self.size_eids = []
		self.color_root_eid = None
		self.size_root_eid = None
		
		# color xpaths
		# size xpaths
		self.sizesXPaths = []
		self.colorsXPaths = []
		self.size_root_xpath = None
		self.color_root_xpath = None
		
		# price eids
		self.price_eids = []
		self.oldprice_eid = None
		self.oldprice = None
		self.price_eid = None
		self.price = None
		
		# children below title
		self.children_eids_below_title = []
		
		# helper tag classifier
		self.tag_dict = Tags()
		
		self.parentmap = {}
	
	def deinit(self):
		self.reader.deinit()
	
	
	def extract(self):
		self.clean_it()
		self.group_it()
		self.same_shape_find_grid()
		self.group_main_product_images()
		#self.build_parent_map()
		print "################# insteresting stuff begin here ##########"
		self.FindTitle()
		self.FindPrice()
		print "##########################################################"
		self.GetChildrenBelowTitle()
		
		#self.AlgoLTree()
		#self.AlgoDense()
		self.AlgoYTrace()
		self.print_colors()
		self.print_sizes()
		
		wait = True
		while wait:
			time.sleep(10)

		return
		
		self.AlgoTDFT()
		self.CheckInMaps(self.tag_class_wnh_y_map)
		self.CheckInMaps(self.tag_class_wnh_x_map)
		
		print "###### Color Root ###############"
		if self.color_root_eid:
			print getAsciiText(self.alle[self.color_root_eid])
		print "###### Colors ###############"
		for eid in self.color_eids:
			color1 = self.extract_color_name(eid)
			print color1
			
			
		print "###### Size Root ###############"
		if self.size_root_eid:
			print getAsciiText(self.alle[self.size_root_eid])
		print "###### Sizes ###############"
		for eid in self.size_eids:
			text = getAsciiText(self.alle[eid])
			if text:
				print text
		
			#getColorName
	
	def print_colors(self):
		
		i = 0
		outer_htmls = []
		
		if self.color_root_xpath:
			print self.color_root_xpath
			for x in self.colorsXPaths:
				print x		
				
		elif self.color_root_eid and self.color_root_eid != True: # fix it
			
			for eid in self.color_eids:
				i = i+1
				print "------color element " + str(i) + "--------"
				text = self.getTextsFromEIDs([eid])
				outerhtml = self.readWebElementAttr(eid, 'outerHTML')
				idname = eidmap[eid]['idname']
				classname = eidmap[eid]['classname']
				tag = eidmap[eid]['tag']
				print text
				print "outerhtml:"
				print outerhtml
				print "id:"
				print idname
				print "classname:"
				print classname
				print "tag:"
				print tag
				outer_htmls.append(outerhtml)
				print "--------------------------------------------"
		
		xpaths = self.ltreeops.findXPathByOuterHtml(outer_htmls)
		for xpath in xpaths:
			print xpath
		print "---------------------------------------------"


	def print_sizes(self):
		
		eidmap = self.eidmap

		i = 0
		outer_htmls = []
		
		if self.size_root_xpath:
			print self.size_root_xpath
			for x in self.sizesXPaths:
				print x		
		
		elif self.size_root_eid and self.size_root_eid != True:
			for eid in self.size_eids:
				i = i+1
				print "------size element " + str(i) + "--------"
				text = self.getTextsFromEIDs([eid])
				outerhtml = self.readWebElementAttr(eid, 'outerHTML')
				idname = eidmap[eid]['idname']
				classname = eidmap[eid]['classname']
				tag = eidmap[eid]['tag']
				print text
				print "outerhtml:"
				print outerhtml
				print "id:"
				print idname
				print "classname:"
				print classname
				print "tag:"
				print tag
				outer_htmls.append(outerhtml)
				print "--------------------------------------------"
		
		xpaths = self.ltreeops.findXPathByOuterHtml(outer_htmls)
		for xpath in xpaths:
			print xpath
		print "---------------------------------------------"


	def generate_element_reference(self):

		eidmap = self.eidmap

		if self.size_root_eid:
			for eid in self.size_eids:
				attr = self.readWebElementAttr(eid, 'outerHTML')
				idname = eidmap[eid]['idname']
				classname = eidmap[eid]['classname']
				

	def AlgoYTrace(self):
				
		mindist = 10000000
		max_y_dist_from_title = 1000
		color_start_eid = None
		size_start_eid = None
		qty_start_eid = None
		
		eidmap = self.eidmap
		x1p = float(self.product_container['x1'])				
		x2p = float(self.product_container['x2'])				
		y1p = float(self.product_container['y1'])				
		y2p = float(self.product_container['y2'])
		
		title_eid = self.title_eid
		y1t = float(eidmap[title_eid]['y'])
		y2t = y1t + float(eidmap[title_eid]['height'])
		x1t = float(eidmap[title_eid]['x'])
		x2t = x1t + float(eidmap[title_eid]['width'])
		
		# find a price that is below the title and is closest to the image rectangle.
		# distance from product image is calculated as (x axial distance) + (y axial distance)
		for eid in eidmap:
			if eidmap[eid]['tag'] == 'img' or eidmap[eid]['tag'] == 'ul' or eidmap[eid]['tag'] == 'li' or eidmap[eid]['tag'] == 'select' or eidmap[eid]['tag'] == 'button':
				continue
			
			# further narrowing down
			x1 = float(eidmap[eid]['x'])
			y1 = float(eidmap[eid]['y'])
			x2 = x1 + float(eidmap[eid]['width'])
			y2 = y1 + float(eidmap[eid]['height'])
			
			# size,color shouldnt be above title
			if float(eidmap[eid]['y']) < y1t:
				continue
			
			# size,color shouldnt be 'max y distance' away from title
			if (float(eidmap[eid]['y']) + eidmap[eid]['width']) > (y1t + max_y_dist_from_title):
				continue
			
			# size,color shouldn't be in the left of title
			if float(eidmap[eid]['x']) < x1t:
				continue
			
			#innerhtml = self.alle[eid].get_attribute("innerHTML")
			innerhtml = self.readWebElementAttr(eid, "innerHTML")
			if innerhtml is None:
				continue
			
			texts = self.getTextsFromEIDs([eid])
			text = None
			if texts:
				text = texts[0]
			
			if not text:
				continue
						
			text = clean_text(text)
			words = [w for w in re.split('\W', text) if w]					
			
			if not words:
				continue
			
			if self.WordsMeetsColorExitCriteria([words[0]]):
				color_start_eid = eid
			
			if self.WordsMeetsSizeExitCriteria([words[0]]):
				size_start_eid = eid
			
			if self.tag_dict.isQuantityHintWord(words[0]):
				qty_start_eid = eid
			
			# sort on Y
		
		colorY = None
		sizeY = None
		qtyY = None	
		max_gap_defined = 1000000
		reasonable_gap_defined = 500
		colorGap = max_gap_defined
		sizeGap = max_gap_defined
		qtyGap = max_gap_defined	
				
		if color_start_eid:
			colorY = float(eidmap[color_start_eid]['y'])
		
		if size_start_eid:
			sizeY = float(eidmap[size_start_eid]['y'])
		
		if qty_start_eid:
			qtyY = float(eidmap[qty_start_eid]['y'])
				
		if colorY and sizeY and colorY < sizeY:
			colorGap = min(colorGap, sizeY - colorY)
			
		if colorY and qtyY and colorY < qtyY:
			colorGap = min(colorGap, qtyY - colorY)
		
		if sizeY and colorY and sizeY < colorY:
			sizeGap = min(sizeGap, colorY-sizeY)
			
		if sizeY and qtyY and sizeY < qtyY:
			sizeGap = min(sizeGap, qtyY-sizeY)
		
		if qtyY and sizeY and qtyY < sizeY:
			qtyGap = min(qtyGap, sizeY - qtyY)
			
		if qtyY and colorY and qtyY < colorY:
			qtyGap = min(qtyGap, colorY - qtyY)
		
		
		if size_start_eid and sizeY:
			if sizeGap == max_gap_defined:
				sizeGap = reasonable_gap_defined
			# find groups that are within sizeY, sizeGap+sizeY, titleX1
			# that seem like sizes
			size_eids = self.findSizeGroupInBox(x1t, None, sizeY, sizeY+sizeGap)
			if size_eids:
				self.size_root_eid = size_start_eid
				self.size_eids = size_eids	
		
		if color_start_eid and colorY:
			if colorGap == max_gap_defined:
				colorGap = reasonable_gap_defined
			# find groups that are within sizeY, sizeGap+sizeY, titleX1
			# that seem like sizes
			color_eids = self.findColorGroupInBox(x1t, None, sizeY, sizeY+sizeGap)
			if color_eids:
				self.color_root_eid = color_start_eid
				self.color_eids = color_eids	
		
		return		
		
		
		
	def findColorGroupInBox(self, bx1, bx2, by1, by2):
		
		x_aligned_map = self.tag_class_wnh_x_map
		y_aligned_map = self.tag_class_wnh_y_map
		color_eids = self.findColorEIDsInMapInBox(bx1, bx2, by1, by2, x_aligned_map)
		if color_eids:
			return color_eids
		
		color_eids = self.findColorEIDsInMapInBox(bx1, bx2, by1, by2, y_aligned_map)
		if color_eids:
			return color_eids
		
		return None
		
		
	def findSizeGroupInBox(self, bx1, bx2, by1, by2):
		
		x_aligned_map = self.tag_class_wnh_x_map
		y_aligned_map = self.tag_class_wnh_y_map
		size_eids = self.findSizeEIDsInMapInBox(bx1, bx2, by1, by2, x_aligned_map)
		if size_eids:
			return size_eids
		
		size_eids = self.findSizeEIDsInMapInBox(bx1, bx2, by1, by2, y_aligned_map)
		if size_eids:
			return size_eids
		
		return None
		
	
	def findSizeEIDsInMapInBox(self, bx1, bx2, by1, by2, group_map):
		
		result_eids = []
		
		eidmap = self.eidmap
		
		for key in group_map:
			minX=  1000000
			maxX= -1000000
			minY=  1000000
			maxY= -1000000
			eids = group_map[key]
			
			valid = True
			if len(eids) < 2:
				continue
			if eidmap[eids[0]]['leaf'] != 1:
				continue
			
			for eid in grp:
				x1 = float(eidmap[eid]['x'])
				x2 = x1 + eidmap[eid]['width']
				y1 = float(eidmap[eid]['y'])
				y2 = y1 + eidmap[eid]['height']
				minX = min(minX, x1)
				maxX = max(maxX, x2)
				minY = min(minY, y1)
				maxY = max(maxY, y2)
				if (bx1 and minX < bx1) or (bx2 and bx2 < maxX) or (by1 and minY < by1) or (by2 and by2 < maxY):
					valid = False
					break
					
			if not valid:
				continue
			
			# verify that the elements have items that seem like sizes
			texts = self.getTextsFromEIDs(eids)
			non_matching_count = 0
			for text in texts:
				sizeval = self.tag_dict.isSizeType(text)
				if not sizeval:
					non_matching_count = non_matching_count + 1
			# if half of the eids dont contain size like text, then continue
			if non_matching_count > len(eids)/2:
				continue
			# otherwise, set the result eid and break out
			result_eids = eids
			break
		
		return result_eids
		
		
	def findColorEIDsInMapInBox(self, bx1, bx2, by1, by2, group_map):
		
		result_eids = []
		
		eidmap = self.eidmap
		
		for key in group_map:
			minX=  1000000
			maxX= -1000000
			minY=  1000000
			maxY= -1000000
			eids = group_map[key]
			
			valid = True
			if eidmap[eids[0]]['tag'] != 'img':
				continue
			if eidmap[eids[0]]['leaf'] != 1:
				continue
			
			# check if image shape confirms to color images
			w = eidmap[eids[0]]['width']
			h = eidmap[eids[0]]['height']
			possible_color_image = self.tag_dict.isBigColorSquare(w, h)
			if not possible_color_image:
				continue
			
			for eid in grp:
				x1 = float(eidmap[eid]['x'])
				x2 = x1 + eidmap[eid]['width']
				y1 = float(eidmap[eid]['y'])
				y2 = y1 + eidmap[eid]['height']
				minX = min(minX, x1)
				maxX = max(maxX, x2)
				minY = min(minY, y1)
				maxY = max(maxY, y2)
				if (bx1 and minX < bx1) or (bx2 and bx2 < maxX) or (by1 and minY < by1) or (by2 and by2 < maxY):
					valid = False
					break
					
			if not valid:
				continue
			
			# verify that the elements have items that seem like colors
			non_matching_count = 0
			for eid in eids:
				color = self.extract_color_name(eid)
				if not color:
					non_matching_count = non_matching_count + 1
			# if half of the eids dont contain size like text, then continue
			if non_matching_count > len(eids)/2:
				continue
			# otherwise, set the result eid and break out
			result_eids = eids
			break
		
		return result_eids
		
	
		
	def extract_color_name(self, eid):
		
		color1 = None
		alt = readWebElementAttr(self.alle[eid], "alt")
		text = getAsciiText(self.alle[eid])
		#look in alt
		color1 = self.tag_dict.getColorName(alt)
		#look in text
		if not color1:
			color1 = self.tag_dict.getColorName(text)
		#look in outerhtml
		if not color1:
			outerhtml = readWebElementAttr(self.alle[eid], "outerHTML").encode("ascii","ignore")
			color1 = self.tag_dict.getColorFromHtml(outerhtml)
			#outerhtml = re.sub('[\.<>=\\/"|~|!|@|#|$|%|^|&|*|(|)|{|}| |1|2|3|4|5|6|7|8|9|0]+',' ', outerhtml)
			#outerhtml = outerhtml.rstrip().lstrip().lower()
			#outerhtml = re.sub(' +',' ', outerhtml)
			#words = outerhtml.split(' ')
			#print words
			#for w in words:
			#	color1 = self.tag_dict.getColorName(w)
			#	if color1:
			#		break
		return color1
	
	# not sure when this would be needed
	def setPLP(self, newPLP):
		self.PLP = newPLP
		
	def build_parent_map(self):
		print "~~~~~~~~~~~ parent map here ~~~~~~~~~~~~~~~~~~~~~~~"
		parentmap = {}
		eidmap = self.eidmap
		
		for ceid in eidmap:
			parentmap[ceid] = []
			for peid in eidmap:
				if ceid == peid:
					continue
				else:
					c_w = eidmap[ceid]['width']
					c_h = eidmap[ceid]['height']
					p_w = eidmap[peid]['width']
					p_h = eidmap[peid]['height']
					
					c_x = float(eidmap[ceid]['x'])
					c_y = float(eidmap[ceid]['y'])
					p_x = float(eidmap[peid]['x'])
					p_y = float(eidmap[peid]['y'])
					
					if p_x <= c_x and p_y <= c_y and (c_x + c_w <= p_x + p_w) and (c_y + c_h <= p_y + p_h):
						ins_in_map(ceid, parentmap, peid)
			
			i = 0
			l = len(parentmap[ceid])
			while i < l:
				j = i+1
				while j < l:
					ei = parentmap[ceid][i]
					ej = parentmap[ceid][j]
					a_i = eidmap[ei]['width'] * eidmap[ei]['height']
					a_j = eidmap[ej]['width'] * eidmap[ej]['height']
					if a_i < a_j:
						temp = parentmap[ceid][i]
						parentmap[ceid][i] = parentmap[ceid][j]
						parentmap[ceid][j] = temp
					j =j+1
				i = i+1
			
		self.parentmap = parentmap
		
		#print self.parentmap
	
	# parent map must be calculated before call to this
	# returns eids of ncs of eids list given in arguments
	def find_nca_of_children(self, eids):
		
		parentmap = self.parentmap
		
		if not parentmap:
			return None
		if len(eids) == 0:
			return None
			
		# check parentmap is built up for all elements-ids for which we need to find 'nca'
		for ceid in eids:
			if ceid not in parentmap:
				return None
			if not parentmap[ceid]:
				return None
		
		if len(eids) == 1:
			eid = eids[0]
			plen = len(parentmap[eid])
			return parentmap[eid][plen-1]
			
		pindex = {}
		for ceid in eids:
			pindex[ceid] = 0
		
		
		nca_parent_id = None
		parent_id = None
		l = len(eids)
		has_more_parents = True
		mismatch = False
		
		while has_more_parents:
			parent_id = parentmap[eids[0]][pindex[eids[0]]]
			i = 1
			while i < l:
				if parent_id != parentmap[eids[i]][pindex[eids[i]]]:
					mismatch = True
					break
				i =i+1
			
			if not mismatch:
				nca_parent_id = parent_id
				
			for ceid in eids:
				pindex[ceid] = pindex[ceid]+1
				if pindex[ceid] == len(parentmap[ceid]):
					has_more_parents = False
					break
			
		return nca_parent_id		
			
		
	
	def clean_it(self):
		self.reader.removeOutgoingElements()
		product_div = self.PLP.getProductImgDiv()
		review_divs = self.PLP.detectReviews(product_div)
		belowReviewDivs = self.PLP.thingsBelowReviewDivs(review_divs)
		shareDivs = self.PLP.getShareItems()
		
		for div in review_divs:
			self.reader.hideElement(div)
		print "~~~~~~~~~~~ Cleaned Review Div ~~~~~~~~~~~~~~~~~~~~~~~"
		for div in belowReviewDivs:
			self.reader.hideElement(div)
		print "~~~~~~~~~~~ Cleaned things below Reviews ~~~~~~~~~~~~~~"
		for div in shareDivs:
			self.reader.hideElement(div)
		print "~~~~~~~~~~~ Cleaned social share items ~~~~~~~~~~~~~~"
		
	
	
	def GetSizeAndColor(self):
		
		self.GetChildrenBelowTitle()
		self.AlgoTDFT()
		self.CheckInMaps()
		
		if self.color_root_eid:
			print "~~~~~~~~~~~~~Color root found~~~~~~~~~~~~~~~~"
			print self.color_eids
			print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
		
		if self.size_root_eid:
			print "~~~~~~~~~~~~~Size root found~~~~~~~~~~~~~~~~"
			print self.size_eids
			print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
	
	
	def GetChildrenBelowTitle(self):
		
		eidmap = self.eidmap
		parentmap = self.parentmap
		
		title_eid = self.title_eid
		x1t = float(eidmap[title_eid]['x'])
		x2t = x1t + eidmap[title_eid]['width']
		y1t = float(eidmap[title_eid]['y'])
		y2t = y1t + eidmap[title_eid]['height']
		title_dists = []
		
		
		for eid in eidmap:
			if eid == title_eid:
				continue
			
			if eidmap[eid]['leaf'] !=0:
				continue
			
			# filter things below title
			useit = False
			parents_including_child = parentmap[eid] + [eid]
			i = len(parents_including_child)-1
			while i>=0:
				peid = parents_including_child[i]
				x1p = float(eidmap[peid]['x'])
				x2p = x1p + eidmap[peid]['width']
				y1p = float(eidmap[peid]['y'])
				if ((x1t<=x1p and x1p<=x2t) or (x1t<=x2p and x2p<=x2t)) and (y1p >= y1t):
					useit = True
					break
				i = i-1
			
			if useit:
				title_dists.append( {'ydist' : float(eidmap[eid]['y'])-y1t, 'eid' : eid} )
			
		
		l = len(title_dists)
		i = 0
		while i < l:
			j =i+1
			while j <l:
				if title_dists[i]['ydist'] > title_dists[j]['ydist']:
					temp_ydist = title_dists[i]['ydist']
					temp_eid = title_dists[i]['eid']
					title_dists[i]['ydist'] = title_dists[j]['ydist']
					title_dists[i]['eid'] = title_dists[j]['eid']				
					title_dists[j]['ydist'] = temp_ydist
					title_dists[j]['eid'] = temp_eid
				j =j+1
			i = i+1
		
		eids = []
		
		for ydist_eid_pair in title_dists:
			eids.append(ydist_eid_pair['eid'])
		
		self.children_eids_below_title = eids
		
		return eids
		
	
	
	
	def getBoundingRect(self, eids):
		
		if not eids:
			return None
		
		eidmap = self.eidmap
		
		x1b = 100000000
		x2b = -100000000
		y1b = 100000000
		y2b = -100000000
		filledarea = 0
		
		for eid in eids:
			
			if eid not in eidmap:	# this should never happen
				return None
			
			x1 = float(eidmap[eid]['x'])
			y1 = float(eidmap[eid]['y'])
			x2 = x1 + eidmap[eid]['width']
			y2 = y1 + eidmap[eid]['height']
			
			x1b = min(x1b, x1)
			y1b = min(y1b, y1)
			x2b = max(x2b, x2)
			y2b = max(y2b, y2)
			filledarea = filledarea + eidmap[eid]['width'] * eidmap[eid]['height']
		
		boundingarea = (x2b-x1b)*(y2b-y1b)
		
		return {'x1':x1b, 'y1':y1b, 'x2':x2b, 'y2':y2b, 'width': x2b-x1b, 'height':y2b-y1b, 'filledarea': filledarea, 'boundingarea' : boundingarea}
	
	
	def BuildMapProps(self, group_map):
		
		eidmap = self.eidmap
		map_props = {}
		
		for key in group_map:
			eids = group_map[key]
			boundingrect = self.getBoundingRect(eids)
			if boundingrect:
				boundingrect['density'] = boundingrect['boundingarea'] / len(eids)
				boundingrect['key'] = key
			
			map_props[key] = boundingrect.copy()
		
		return map_props
		
		
	def SortMapByProp(self, group_map, prop, ascending):
		
		map_props = self.BuildMapProps(group_map)
		
		map_props_list = []
		for key in map_props:
			map_props_list.append(map_props[key])
		
		
		l = len(map_props_list)
		i=0
		while i < l:	
			j =i+1
			while j<l:
				switch = False
				if ascending:
					if map_props_list[i][prop] > map_props_list[j][prop]:
						switch = True
				else:
					if map_props_list[i][prop] < map_props_list[j][prop]:
						switch = True
				
				if switch:
					temp = map_props_list[i].copy()
					map_props_list[i] = map_props_list[j]
					map_props_list[j] = temp
					
				j =j+1
			i =i+1
		
		return map_props_list			
			
			
	def FilterPropsOnLimits(self, map_props_list, key, low, high):
		
		print "Filtering on limits"
		
		filtered_map_props_list = []
		for mp in map_props_list:
			if low <= mp[key] and mp[key] <= high:
				filtered_map_props_list.append(mp.copy())
		
		return filtered_map_props_list;
	
	
	def AlgoLTree(self):
		
		ltreeops = self.ltreeops
		
		look_for_size_root = False
		look_for_color_root = False
		if not self.size_root_eid:
			look_for_size_root = True
		else:
			print "Skipping size check"
			
		if not self.color_root_eid:
			look_for_color_root = True
		else:
			print "Skipping color check"
		
		if look_for_size_root:
			size_root_xpath, sizesXPaths = ltreeops.getSelectBox()
		if look_for_color_root:
			color_root_xpath, colorsXPaths = ltreeops.getImgTags()
		
		if size_root_xpath:
			print size_root_xpath
			for x in sizesXPaths:
				print x
			self.size_root_xpath = size_root_xpath
			self.sizesXPaths = sizesXPaths
			self.size_root_eid = True
					
		if color_root_xpath:
			print color_root_xpath
			for x in colorsXPaths:
				print x
			self.color_root_xpath = color_root_xpath
			self.colorsXPaths = imgsXPaths
			self.color_root_eid = True
		
		return
		
	def AlgoDense(self):
		
		group_map = {}
		for key in self.tag_class_wnh_x_map:
			if len(self.tag_class_wnh_x_map[key]) > 1:
				group_map[key] = self.tag_class_wnh_x_map[key][:]
		
		if group_map:
			self.Algo1(group_map)
		
		group_map = {}
		for key in self.tag_class_wnh_y_map:
			if len(self.tag_class_wnh_y_map[key]) > 1:
				group_map[key] = self.tag_class_wnh_y_map[key][:]
		
		if group_map:
			self.Algo1(group_map)
	
	
	# sort by density
	# filter on y from title
	# if elements look like size, parse elements until additional info about them
		
	def Algo1(self, group_map):
		
		eidmap = self.eidmap
		title_rect = self.getBoundingRect([self.title_eid])
		y1t = title_rect['y1']
		
		map_props_list = self.SortMapByProp(group_map, 'density', True)
		filtered_props_list = self.FilterPropsOnLimits(map_props_list, 'y1', y1t, 100000)
		
		size_root = 0
		color_root = 0
		look_for_size_root = False
		look_for_color_root = False
		
		if not self.size_root_eid:
			look_for_size_root = True
		else:
			print "Skipping size check"
			
		if not self.color_root_eid:
			look_for_color_root = True
		else:
			print "Skipping color check"
		
		for props in filtered_props_list:
			eidlist = group_map[props['key']]
			
			if len(eidlist) < 2:
				continue
			if eidmap[eidlist[0]]['leaf'] != 1:
				continue
			
			# get additional info ahead of this element list
			result = self.TraceParentUntilMoreInfoBeforeMe(eidlist)
			peid = result[0]
			if not peid:
				print "Null parent when searching more info before elements"
				continue
			
			words_before_me = result[1]
			# last word of that additional info
			last_word = words_before_me[-1]
			#print words_before_me
			
			print words_before_me
			print "----------"
			# is that a size word
			if look_for_size_root and self.WordsMeetsSizeExitCriteria(words_before_me): #(self.tag_dict.isSizeHintWord(last_word) or self.MeetsSizeExitCriteria(peid)):
				size_root = peid
				size_elements = eidlist
				look_for_size_root = False
				print "Found size"
				
			# is that a color word
			if look_for_color_root and self.WordsMeetsColorExitCriteria(words_before_me): #(self.tag_dict.isColorHintWord(last_word) or self.MeetsColorExitCriteria(peid)):
				color_elements = []
				for color_eid in eidlist:
					color = self.extract_color_name(color_eid)
					if color:
						color_element.append(color_eid)
				if color_elements:
					color_root = peid
					look_for_color_root = False
				print "Found color"
			
		# here we can add additional filtering criteria			
		if size_root:	
			self.size_root_eid = size_root
			self.size_eids = size_elements
		if color_root:
			self.color_root_eid = color_root
			self.color_eids = color_elements
		
	def WordsMeetsColorExitCriteria(self, words):
		
		if not words:
			return False
		
		l = len(words)
		lastword = words[l-1]
		
		if self.tag_dict.isColorHintWord(lastword):
			return True
		
		l = len(words)
		i =  l-2
		while i>=0:
			word = words[i]
			if self.tag_dict.isSizeHintWord(word) or self.tag_dict.isQuantityHintWord(word) :
				return False
				
			if self.tag_dict.isColorHintWord(word) or self.tag_dict.isColorWord(word):
				return True
			i = i-1
			
		return False	
	
	def WordsMeetsSizeExitCriteria(self, words):
		
		if not words:
			return False
		
		l = len(words)
		lastword = words[l-1]
		
		if self.tag_dict.isSizeHintWord(lastword):
			return True
		
		l = len(words)
		i =  l-2
		while i>=0:
			word = words[i]
			if self.tag_dict.isColorHintWord(word) or self.tag_dict.isQuantityHintWord(word) :
				return False
				
			if self.tag_dict.isSizeHintWord(word) or self.tag_dict.isSizeWord(word):
				return True
			i=i-1
			
		return False	
	
	def EIDMeetsSizeExitCriteria(self, eid):
		
		if not eid:
			return False
		
		text = getAsciiText(self.alle[eid])
		if not text:
			return False
		text = text.rstrip().lstrip().lower()
		text = re.sub(' +',' ', text)
		if text.startswith("size"):
			return True
		if text.startswith("width"):
			return True
		if text.startswith("height"):
			return True
		if text.startswith("length"):
			return True
		
		return False
	
	
	def EIDMeetsColorExitCriteria(self, eid):
		
		if not eid:
			return False
		
		text = getAsciiText(self.alle[eid])
		if not text:
			return False
		text = text.rstrip().lstrip().lower()
		text = re.sub(' +',' ', text)
		if text.startswith("color"):
			return True
		
		return False
	
	
	def getTextsFromEIDs(self, eidlist):
		
		if not eidlist:
			return None
		
		eidmap = self.eidmap
		
		texts = []
		for eid in eidlist:
			if 'text' in eidmap[eid]:
				elementtext = eidmap[eid]['text']
				texts.append(elementtext)
			else:
				elementtext = getAsciiText(self.alle[eid])  # more text may be None
				eidmap[eid]['text'] = elementtext
				texts.append(elementtext)
			
		return texts
		#text = text.rstrip().lstrip().lower()
		#text = re.sub(' +',' ', text)
		
		#return text
	
	
	# returns a pair 
	# first element - nca eid, that contains more texts
	# second element - words in additional texts
	def TraceParentUntilMoreInfoBeforeMe(self, eidlist):
		
		eidmap = self.eidmap

		if not eidlist:
			return [None, None]
		
		texts = self.getTextsFromEIDs(eidlist)
		
		# trace common parent
		trace_up = True
		childlist = eidlist
		while trace_up:
			nca_eid = self.find_nca_of_children(childlist)
			if not nca_eid:
				trace_up = False
			elif eidmap[nca_eid]['tag'] == 'select':
				childlist = [nca_eid]
				trace_up = True
			else:
				trace_up = False
		
		if not nca_eid:
			return [None, None]
			
		# find position of nca_eid in parent list of the first element
		parentmap =self.parentmap
		p0 = parentmap[eidlist[0]]
		pindex = 0
		for x in p0:
			if x == nca_eid:
				break
			pindex = pindex+1
			
		# pindex holds the position of nca eid in parent list of first element
		# from this position trace back to the top level parent
		while pindex >= 0:
			nca_eid = p0[pindex]
			nca_text = self.getTextsFromEIDs([nca_eid])
			print nca_eid
		
			# should it have check for only 2nd condition ?
			# or should it look for additional 'words' before me ?
			#print nca_text
				
			if nca_text:
				words_before_child_text = self.getWordsBeforeChildren(nca_eid, eidlist)
				#print clean_text(nca_text)
				#words_before_child_text = self.getWordsBeforeChildren(nca_eid, eidlist, False)
				#words_before_child_text = get_words_before_sub_text_list(nca_texts[0], texts, False) # False says that look for subtext from beginning
				#print words_before_child_text
				if words_before_child_text:
					return [nca_eid, words_before_child_text]
			
			pindex = pindex-1
		
		return [None, None]
		
	
	def readWebElementAttr(self, eid, attr):
		
		eidmap = self.eidmap
		if attr in eidmap[eid]:
			val = eidmap[eid][attr]
		else:
			val = readWebElementAttr(self.alle[eid], attr)
			eidmap[eid][attr] = val
		
		return val
	
		
	def getWordsBeforeChildrenHtmlWay(peid, ceid_list, look_from_start):
		
		found = False
		min_index = -1
		cOuterHTMLs = []
		pInnerHtml = self.readWebElementAttr(peid, "innerHTML")
		for ceid in ceid_list:
			cOuterHtmls.append(self.readWebElementAttr(ceid, "outerHTML"))
		
		for c_outer_html in cOuterHtmls:
			if look_from_start:
				pInnerHtml = pInnerHtml.replace(c_outer_html, " eEnNdD ", 1)
			else:
				i = pInnerHtml.rfind(c_outer_html)
				if i != -1:
					pInnerhtml = pInnerhtml[0:i] + pInnerhtml[i:].replace(c_outer_html, " eEnNdD ", 1)
			
			
		soup = BeautifulSoup(pInnerhtml)
		texts = soup.findAll(text=True)
		text_before_children = ""
		for text in texts:
			i = text.find("eEnNdD")
			if i != -1:
				text_before_children = text_before_children + text
			else:
				text_before_children = text_before_children + text[0:i]
		
		text_before_children = clean_text(text_before_children)
		words = [w for w in re.split('\W', text_before_children) if w]
		
		return words
		
	def getWordsBeforeChildren(self, peid, ceid_list):
		
		pTexts = self.getTextsFromEIDs([peid])
		cTexts = self.getTextsFromEIDs(ceid_list)
		pText = pTexts[0]
		
		for cText in cTexts:
			cText = clean_text(cText)
			if cText and cText != " ":
				i = pText.find(cText)
				if i != -1:
					pText = pText[0:i]
		
		pText = clean_text(pText)
		words = [w for w in re.split('\W', pText) if w]
		
		return words
		
		
	def TraceParentUntilMoreInfo(self, eidlist):
		
		if not eidlist:
			return None
		
		text = self.getTextsFromEIDs(eidlist)
		
		nca_eid = self.find_nca_of_children(eidlist)
		if not nca_eid:
			return None
		
		
		nca_text = self.getTextsFromEIDs([nca_eid])
		
		# should it have check for only 2nd condition ?
		# or should it look for additional 'words', instead of doing just a len check ?
		if text in nca_text and len(nca_text) > len(text): 
			return nca_eid
		
		return self.TraceParentUntilMoreInfo([nca_eid])
		
	
	
	def AlgoSimple(self):
		
		look_for_size_root = True
		
		if not look_for_size_root:
			return 
		
		eidmap = self.eidmap
		parentmap = self.parentmap
		
		size_root_eid = None
		for eid in eidmap:
			if eidmap[eid]['tag'] == 'select':
				innerhtml = readWebElementAttr(self.alle[eid], 'innerHTML')
				if innerhtml:
					soup = BeautifulSoup(removeCommentsAndJS(innerhtml))
					options = soup.findAll('option')
					notmatched = False
					for op in options:
						print op
						op_text = op.text.encode("ascii", "ignore").lower()
						if op_text.startswith("select"):
							continue
						if not self.tag_dict.isSizeType(op_text):
							notmatched = True
							break
					if not notmatched:
						meetscriteria = self.EIDMeetsSizeExitCriteria(eid)
						if not meetscriteria:
							result = self.TraceParentUntilMoreInfoBeforeMe([eid])
							peid = result[0]
							if not peid:
								continue
							
							words_before_me = result[1]
							# last word of that additional info
							last_word = words_before_me[-1]
							#print words_before_me
							# is that a size word
							for word in words_before_me:
								#if look_for_size_root and self.tag_dict.isSizeHintWord(last_word):
								if self.tag_dict.isSizeHintWord(word):
									size_root = peid
									size_elements = [eid]
									look_for_size_root = False
									break
			
			
		if size_root:
			self.size_root_eid = size_root
			self.size_eids = size_elements
						
#			if eidmap[eid]['tag'] == 'option':
#				# if the option looks like size, then move up
#				text = getAsciiText(self.alle[eid])
#				if self.tag_dict.isSizeType(text):
#					pnelist = parentmap[eid] + [eid]
#					i = len(pnelist)-1
#					while i >= 0 and i >= len(pnelist)-4:	# check only 4 levels
#						text = getAsciiText(self.alle[pnelist[i]])
#						if self.tag_dict.isSizeWord(text):
#							size_root_eid = eid
#							break
#						i = i-1
#			if size_root_eid:
#				self.size_root_eid = size_root_eid
#				break
		
		return
		
		
	# algo, trace down y column from the title
	#
	def AlgoTDFT(self):
		
		
		eids = self.children_eids_below_title
		eidmap = self.eidmap
		parentmap = self.parentmap
		x1t = float(eidmap[self.title_eid]['x'])
		x2t = x1t + eidmap[self.title_eid]['width']
		y1t = float(eidmap[self.title_eid]['y'])
		y2t = y1t + eidmap[self.title_eid]['height']
		
		color_elements = []
		color_text_eid = None
		size_elements = []
		size_text_eid = None
		
		# look for size tag
		if not self.size_root_eid:
			# for each eid
			for eid in eids:
				text = getAsciiText(self.alle[eid])
				if self.tag_dict.isSizeWord(text):
					#print text
					y1 = float(eidmap[eid]['y'])
					y2 = y1+50
					for eid2 in eidmap:
						y1e = float(eidmap[eid2]['y'])
						if y1 <= y1e and y1e <= y2:
							etext = getAsciiText(self.alle[eid2])
							print etext
							if self.tag_dict.isSizeType(etext):
								size_elements.append(eid2)
					break
					
			size_root = self.find_nca_of_children(size_elements)
			self.size_root_eid = size_root
			self.size_eids = size_elements
			
		
		# look for color element			
		if not self.color_root_eid:
			for eid in eids:
				text = getAsciiText(self.alle[eid])
				if self.tag_dict.isColorWord(text):
					print text
					y1 = float(eidmap[eid]['y'])
					y2 = y1+100
					for eid2 in eidmap:
						y1e = float(eidmap[eid2]['y'])
						if y1 <= y1e and y1e <= y2:
							we = float(eidmap[eid2]['width'])
							he = float(eidmap[eid2]['height'])
							if self.tag_dict.isColorSquare(we, he):
								color_elements.append(eid2)
								y2 = max(y2, y1e+30)
					break
					
			color_root = self.find_nca_of_children(color_elements)
		
			self.color_root_eid = color_root
			self.color_eids = color_elements
		
	
	# Algo 2, look for color and size elements in maps
	#
	def CheckInMaps(self, group_map):
		
		eidmap = self.eidmap
		parentmap = self.parentmap
		x1t = float(eidmap[self.title_eid]['x'])
		x2t = x1t + eidmap[self.title_eid]['width']
		y1t = float(eidmap[self.title_eid]['y'])
		y2t = y1t + eidmap[self.title_eid]['height']
		
		size_root = None
		size_elements = []
		color_root = None
		color_elements = []
		
		# look for color
		if not self.color_root_eid:
			for prop in group_map:
				eidlist = group_map[prop]
				if len(eidlist)>1:
					# check if the element tag is either input/button/select
					tag = eidmap[eidlist[0]]['tag']
					if tag == 'input' or tag =='option' or tag == 'button' or tag == 'img':
						# move upto parent until find nearest common ancestor of this child list, which is eidlist
						#print eidlist
						nca_eid = self.find_nca_of_children(eidlist)
						
						if not nca_eid:
							continue
						
						# move up parent to find color tag
						pnelist = parentmap[nca_eid] + [nca_eid]
						#print pnelist
						i = len(pnelist) -1
						while i >=0:
							eid = pnelist[i]
							if float(eidmap[eid]['y']) < y1t:
								break
							text = getAsciiText(self.alle[eid])
							if text:
								text = text.rstrip().lstrip().lower()
								text = re.sub(' +','', text)
								if "color :" in text or "color:" in text:
									color_root = eid
									color_elements = eidlist
									break
								elif len(text) > 20:
									break
								
							i = i-1
							
						if color_root:
							self.color_root_eid = color_root
							self.color_eids = color_elements
							break
							
		
		# look for size
		if not self.size_root_eid:
			
			# find minimum y for the groups
			group_y = []
			for prop in group_map:
				eidlist = group_map[prop]
				miny = 10000000
				for eid in eidlist:
					miny = min(float(eidmap[eid]['y']), miny)
				group_y.append({'gname': prop , 'y':miny})
			# sort the group_map on y
			i = 0
			while i < len(group_y):
				j = i+1
				while j < len(group_y):
					if group_y[i]['y'] > group_y[j]['y']:
						temp_gname = group_y[i]['gname']
						temp_y = group_y[i]['y']
						group_y[i]['y'] = group_y[j]['y']
						group_y[i]['gname'] = group_y[j]['gname']
						group_y[j]['y'] = temp_y
						group_y[j]['gname'] = temp_gname
					j =j+1
				i =i+1
			
			
			#for prop in group_map:
			yindex = -1
			while yindex < len(group_y)-1:
				
				yindex = yindex + 1
				prop = group_y[yindex]['gname']
				
				# skip it if it is above the title
				if group_y[yindex]['y'] <= y1t:
					#print "y less than"
					continue
				
				eidlist = group_map[prop]
				if len(eidlist)>1 and eidmap[eidlist[0]]['leaf'] == 1:
					# check if the element tag is either input/button/select
					#tag = eidmap[eidlist[0]]['tag']
					#print tag
					#if tag == 'input' or tag =='option' or tag == 'button' or tag == 'img' or tag == 'li' or tag == 'span':
					if 1:						
						# move upto parent to find nearest common ancestor
						nca_eid = self.find_nca_of_children(eidlist)
						
						if not nca_eid:
							continue
						
						# find the size tag 
						pnelist = parentmap[nca_eid] + [nca_eid]
						i = len(pnelist) -1
						while i >=0:
							eid = pnelist[i]
							if float(eidmap[eid]['y']) < y1t:
								break
							text = getAsciiText(self.alle[eid])
							if text:
								print "------------"
								print text
								text = text.rstrip().lstrip().lower()
								text = re.sub(' +',' ', text)
								if text.startswith("size"):	# this needs to be refined
									size_root = eid
									size_elements = eidlist
									break
								elif len(pnelist) > i+5: # look at max 4 level up
									break
							i = i-1
							
						if size_root:
							self.size_root_eid = size_root
							self.size_eids = size_elements
							break
		
		
		
	
	def parseYStackedElements(self):
		print "~~~~~~~~~~~~~~starting to make sense Y stacked elements ~~~~~~~~"
		eidlists = [] # note this is list of lists 
		for prop in gr.tag_class_wnh_x_map:
			if len(gr.tag_class_wnh_x_map[prop])>1:
					eidlists.append(gr.tag_class_wnh_x_map[prop])
					
		i = 0
		l = len(eidlists)
		while i < 0:
			j = i+1
			while j < 0:
				if len(eidlists[i]) < len(eidlists[j]):
					temp = eidlists[j]
					eidlists[j] = eidlists[i]
					eidlists[i] = temp
		
		for eids in eidlists:
			text #33#
					
	
		
	def parseXStackedElements(self):
		print "~~~~~~~~~~~~~~starting to make sense X stacked elements ~~~~~~~~"
		
	def MakeSenseOfGrids(self):
		
		print "~~~~~~~~~~~~~~starting to make sense of grid~~~~~~~~"
		
		grids = self.grids
		
		# Case 1 check the elements that are a grid of 3 elements at least
		for g in self.grids:
			eids = g.getEids()
			if len(eids) > 2:
				eids = g.getEids()
				nca_eid = self.find_nca_of_children(eids)
				if nca_eid:
					#text = getWebElementText(self.alle[nca_eid])
					text = self.alle[nca_eid].text
					if text:
						text = text.encode("ascii","ignore")
						print "---------next element------"
						print text
						print "---------------------------"
			
		print "~~~~~~~~~~~~~~done with grid~~~~~~~~~~~~~~~~~~~~~~~~"
	
	
	# should be called after find title and product images
	def FindPrice(self):
	
	
		mindist = 10000000
		price = None
		price_eid = None
		
		eidmap = self.eidmap
		x1p = float(self.product_container['x1'])				
		x2p = float(self.product_container['x2'])				
		y1p = float(self.product_container['y1'])				
		y2p = float(self.product_container['y2'])
		
		title_eid = self.title_eid
		y1t = float(eidmap[title_eid]['y'])
		y2t = y1t + float(eidmap[title_eid]['height'])
		x1t = float(eidmap[title_eid]['x'])
		x2t = x1t + float(eidmap[title_eid]['width'])
		
		# find a price that is below the title and is closest to the image rectangle.
		# distance from product image is calculated as (x axial distance) + (y axial distance)
		for eid in eidmap:
			if eidmap[eid]['tag'] == 'img' or eidmap[eid]['tag'] == 'ul' or eidmap[eid]['tag'] == 'li' or eidmap[eid]['tag'] == 'select' or eidmap[eid]['tag'] == 'button':
				continue
			
			# further narrowing down
			x1 = float(eidmap[eid]['x'])
			y1 = float(eidmap[eid]['y'])
			x2 = x1 + float(eidmap[eid]['width'])
			y2 = y1 + float(eidmap[eid]['height'])
			
			# price shouldnt be above title
			if float(eidmap[eid]['y']) < y1t:
				continue
			
			# price shouldn't be in the left of title
			if float(eidmap[eid]['x']) < x1t:
				continue
			
			#innerhtml = self.alle[eid].get_attribute("innerHTML")
			innerhtml = readWebElementAttr(self.alle[eid], "innerHTML")
			if innerhtml is None:
				continue
			
			# if not child element, then continue
			if "</" in innerhtml:
				continue
				
			text = innerhtml #getWebElementText(self.alle[eid]) # this is most likely inner html
			if not text:
				continue
			
			text_price = textHasPrice(text)
			if text_price is None:
				continue
			
			# calc distance
			xdist = 10000000
			#if title is on the right of product image
			if x2p < x1t:
				xdist = abs(x1-x2p)
			# if title is on the left of product image
			elif x2t < x1p:
				xdist = abs(x2-x1p)
			# rare case, title is on top of product image covering it
			else:
				xdist = min(abs(x1-x1p), abs(x1-x2p), abs(x2-x1p), abs(x2-x2p))
			
			# ydist is from the top of the product image
			ydist = abs(y1p-y1)
			# distance from product image = sum of xdist and ydist
			product_image_dist = xdist + ydist
			
			# find distance from title
			dist_from_title = 10000000
			# if price is tagged below title
			if y1 >= y2t and x1t <= x1 and x1 <= x2t:
				dist_from_title = abs(y2t-y1)
			# if price is tagged in parallel to the title
			elif y1 >= y1t and y1 <= y2t:
				dist_from_title = min(abs(x1-x2t), abs(x2-x1t))
			
			# overall distance, minimum of product image and title distance
			dist = min (product_image_dist, dist_from_title)
			
			if dist < mindist:
				price = text_price
				price_eid = eid
				mindist = dist
				
			
		self.price = price
		self.price_eid = eid
		print "~~~~~~~~~~~~~~~~Price~~~~~~~~~~~~~~~~~~~"
		print {'price':price, 'dist': mindist}
		print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
		#################
		### return here
		return
	
		
		
	
	# sort elements based on their font size
	# find the one that has the least Y difference from the product rect
	def FindTitle(self):
		
		# first read the title of the page
		title_elements = self.reader.getElementsByTagName('title')
		document_title = None
		full_title = ""

		for e in title_elements:
			document_title = readWebElementAttr(e, "innerHTML")
			if document_title:
				document_title = document_title.encode("ascii", "ignore")
				document_title = re.sub('[~|!|@|#|$|%|^|&|*|(|)|{|}| |1|2|3|4|5|6|7|8|9|0]+',' ', document_title)
				document_title = document_title.rstrip()
				document_title = document_title.lstrip()
				if document_title.count(' ') == 0:
					isapparel = self.tag_dict.isApparelType(document_title)
					if not isapparel:
						document_title = None			
			if document_title:
				full_title = full_title + " " + document_title;
					
				
		
		y1p = float(self.product_container['y1'])				
		y2p = float(self.product_container['y2'])				
		
		# first check the groups that are 
		eidmap = self.eidmap
		eid_list = []
		
		# discard elements that have children or that are img, li, ul, select, button
		for eid in eidmap:
			if eidmap[eid]['tag'] == 'img' or eidmap[eid]['tag'] == 'ul' or eidmap[eid]['tag'] == 'li' or eidmap[eid]['tag'] == 'select' or eidmap[eid]['tag'] == 'button':
				continue
			
			# further narrowing down
			
			# title should start before half of the main product image ends
			if float(eidmap[eid]['y']) > y1p + (y2p-y1p)/2:
				continue
			
			# title should not be above the later half of (Y of start of page, Y of start of product image)
			# or more than half of product image height above the product image
			if (float(eidmap[eid]['y']) + eidmap[eid]['height']) < y1p/2: #max(y1p - (y2p-y1p)/2, y1p/2):
				continue
				
			#innerhtml = self.alle[eid].get_attribute("innerHTML")
			innerhtml = self.readWebElementAttr(eid, "innerHTML")
			if innerhtml is None:
				continue
				
			if eidmap[eid]['leaf'] != 1:
				continue
			else:
				eid_list.append(eid)
		
		
		# sort on y distance from top of image
		i = 0
		l = len(eid_list)
		while i < l:
			j  = i +1
			while j < l:
				y1 = float(eidmap[eid_list[i]]['y'])
				y2 = float(eidmap[eid_list[i]]['y']) + float(eidmap[eid_list[i]]['height'])
				ydiff_i = min (abs(y1p-y1), abs(y1p-y2))
				
				y1 = float(eidmap[eid_list[j]]['y'])
				y2 = float(eidmap[eid_list[j]]['y']) + float(eidmap[eid_list[j]]['height'])
				ydiff_j = min (abs(y1p-y1), abs(y1p-y2))
				
				if ydiff_j < ydiff_i:
					temp = eid_list[i]
					eid_list[i] = eid_list[j]
					eid_list[j] = temp
				
				j = j+1
			
			i = i+1
		
		# now eid_list is sorted based on its Y distance from the top of the product image container
		eid_list_ydiff_sorted = eid_list[:]
		
		# sort of height of element
		i = 0
		l = len(eid_list)
		while i < l:
			j  = i +1
			while j < l:
				h_i = float(eidmap[eid_list[i]]['height'])
				h_j = float(eidmap[eid_list[j]]['height'])
				
				if h_j > h_i:
					temp = eid_list[i]
					eid_list[i] = eid_list[j]
					eid_list[j] = temp
				
				j = j+1
			
			i = i+1
		
		# now eid_list is sorted based on height of its elements
		# because we can not compare based on the font-size, because of different units of font-size, 
		# we'll compared based on the height of divs
		eid_list_h_sorted = eid_list[:]
		
		# first find the element that matches document title
		document_title = document_title.rstrip()
		
		#if document_title:
		if len(full_title) > 0:
			# find the elements that is at least 2 words
			for eid in eid_list_h_sorted:
				texts = self.getTextsFromEIDs([eid])
				text = None
				if texts:
					text = texts[0]
				# text = getAsciiText(self.alle[eid]).rstrip().lstrip().lower()
				if text is None:
					continue
					
				clean_text = re.sub(' +',' ', text)
				if clean_text.count(' ') == 0:
					continue
				
				clean_text = re.sub('[~|!|@|#|$|%|^|&|*|(|)|{|}| |1|2|3|4|5|6|7|8|9|0]+',' ', clean_text)
				clean_text = clean_text.strip()
				#print clean_text
				# if it seems like an apparel type
				if self.tag_dict.isApparelType(clean_text) == 1:
					
					#print clean_text
					allWordsInDocTitle = True
					
					words = clean_text.split()
					for w in words:
						#if w.lower() not in document_title.lower():
						if w.lower() not in full_title.lower():
							allWordsInDocTitle = False
							break
					
					if allWordsInDocTitle:
						self.title_eid = eid
						print "~~~~~~~~~~~~~~~~Title~~~~~~~~~~~~~~~~~~~"
						print clean_text
						print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
						#################
						### return here
						return
					
				
		# find the elements that is at least 2 words
		for eid in eid_list_h_sorted:
			text = getWebElementText(self.alle[eid])
			if text is None:
				continue
				
			clean_text = clean_text(text.encode("ascii", "ignore")) #re.sub(' +',' ', text.encode("ascii", "ignore").rstrip().lstrip())
			element_words = [w for w in re.split('\W', clean_text) if w]
			if clean_text.count(' ') == 0 or len(element_words) < 2:
				continue
			
			# at least 2 words (one has to be adjective, and one has to be noun, so title will most likely have at least 2 words)
			if self.tag_dict.isApparelType(clean_text) == 1:
				self.title_eid = eid
				print "~~~~~~~~~~~~~~~~Title~~~~~~~~~~~~~~~~~~~"
				print clean_text
				print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
				#################
				### return here
				return
			
	
	
	def group_main_product_images(self):
		
		product_div = self.PLP.getProductImgDiv()
		classname = product_div.get_attribute('class')
		idname = product_div.get_attribute('id')
		w = product_div.size['width']
		h = product_div.size['height']
		x = product_div.location['x']
		y = product_div.location['y']
		wnh = str(w)+"_"+str(h)
		
		product_image_list = []
		if wnh in self.wnhmap:
			product_image_list = self.wnhmap[wnh][:]
		
		product_eids = []
		
		# ensure that all the images in product_image_list share the same class, and are aligned on either x or y with product image div
		# following filtering can be refined further
		for product_eid in product_image_list:
			e = self.eidmap[product_eid]
		#	if e['classname'] == classname or e['idname'] == idname:       # can have different class for the active image
			if float(e['x']) == x or float(e['y']) == y:
				product_eids.append(product_eid)
		
		# dimensions of product div
		minx = 100000000
		maxx = -100000000
		miny = 100000000
		maxy = -100000000
		eidmap = self.eidmap
		for eid in product_eids:
			x1 = float(eidmap[eid]['x'])
			x2 = x1 + float(eidmap[eid]['width'])
			y1 = float(eidmap[eid]['y'])
			y2 = y1 + float(eidmap[eid]['height'])
			
			minx = min( min(x1,x2), minx)
			maxx = max( max(x1,x2), maxx)
			miny = min( min(y1,y2), miny)
			maxy = max( max(y1,y2), maxy)
			
		self.product_eids = product_eids
		self.product_container = {'x1' : minx, 'x2' : maxx, 'y1':miny, 'y2':maxy}
		
		print "~~~~~~~Product Eids~~~~~~~~~~~~~~~~~~~~~~~~~~"
		print self.product_eids
		print "~~~~~~~Product Image Dimensions~~~~~~~~~~~~~~"
		print self.product_container
		print "~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~"
		
		return

		
	def group_it(self):
		
		driver = self.reader.getDriver()
		#alle = driver.find_elements_by_xpath("//*")
		parentmap = self.parentmap
		roote = driver.find_element_by_tag_name("body")
		roote.seq_id = 0
		alle = [roote]
		parentmap[0] = []
		seq_id=0
		i = 0
		while i < len(alle):
			childs = self.reader.getChildren(alle[i])
			for c in childs:
				if c.is_displayed():
					# increment seq_id
					seq_id = seq_id+1
					# append seq_id
					c.seq_id = seq_id
					# append element
					alle.append(c)
					#get pareant seq id
					parent_seq_id = alle[i].seq_id
					# build parent list
					parentmap[c.seq_id] = parentmap[parent_seq_id][:] + [parent_seq_id]
			i = i+1


#		all_displayed_e = []
#		for a in alle:
#			if a.is_displayed():
#				all_displayed_e.append(a)
#		
#		alle = all_displayed_e[:]
		self.alle = alle
		
		self.eidmap = {}
		self.xmap = {}
		self.ymap = {}
		self.wmap = {}
		self.hmap = {}
		self.wnhmap = {}
		self.classmap = {}
		self.idmap = {}
		self.tagmap = {}
				
		i = 0
		for a in alle:
			#a.seq_id = i
			
			#tag, classname, idname
			tag = a.tag_name
			if tag:
				tag = tag.encode("ascii", "ignore")
				
			classname = a.get_attribute('class')
			if classname:
				classname = classname.encode("ascii", "ignore")
			
			idname = a.get_attribute('id')
			if idname:
				idname = idname.encode("ascii", "ignore")
			
			leaf = 0
			innerhtml = a.get_attribute('innerHTML')
			if innerhtml:
				if "</" not in innerhtml:
					leaf = 1
				
			self.eidmap[a.seq_id] = {'seq_id': a.seq_id, 'width' : a.size['width'], 'height' : a.size['height'], 'width_n_height': str(a.size['width'])+"_"+str(a.size['height']), 'x' : str(a.location['x']), 'y' : str(a.location['y']), 'classname' : classname, 'idname' : idname, 'tag' : tag, 'leaf':leaf}
			
#			if self.eidmap[i]['tag']:
#				self.eidmap[i]['tag'] = self.eidmap[i]['tag'].encode("ascii", "ignore")
				
#			if self.eidmap[i]['idname']:
#				self.eidmap[i]['idname'] = self.eidmap[i]['idname'].encode("ascii", "ignore")
				
#			if self.eidmap[i]['classname']:
#				self.eidmap[i]['classname'] = self.eidmap[i]['classname'].encode("ascii", "ignore")
			
			#print eidmap
			
			ins_in_map(self.eidmap[i]['x'], self.xmap, i)
			ins_in_map(self.eidmap[i]['y'], self.ymap, i)
			ins_in_map(self.eidmap[i]['width'], self.wmap, i)
			ins_in_map(self.eidmap[i]['height'], self.hmap, i)
			ins_in_map(self.eidmap[i]['width_n_height'], self.wnhmap, i)
			ins_in_map(self.eidmap[i]['classname'], self.classmap, i)
			ins_in_map(self.eidmap[i]['idname'], self.idmap, i)
			ins_in_map(self.eidmap[i]['tag'], self.tagmap, i)
			
			# insert in advanced maps
			eidmap = self.eidmap
			wnh = eidmap[i]['width_n_height']
			classname = eidmap[i]['classname']
			tag = eidmap[i]['tag']
			
			key = tag + '_'+ classname + "_" + wnh + "_x" + eidmap[i]['x'] 
			key = key.encode("ascii", "ignore")
			ins_in_map(key, self.tag_class_wnh_x_map, i)
			
			key = tag + '_'+ classname + "_" + wnh + "_y" + eidmap[i]['y'] 
			key = key.encode("ascii", "ignore")
			ins_in_map(key, self.tag_class_wnh_y_map, i)
			
			key = tag + '_'+ classname + "_" + wnh 
			key = key.encode("ascii", "ignore")
			ins_in_map(key, self.tag_class_wnh_map, i)
			
			i = i+1
		
		self.sort_map_on_key(self.tag_class_wnh_x_map, 'y')
		self.sort_map_on_key(self.tag_class_wnh_y_map, 'x')
		
	# assuming group_map is a made up of list, sorted by some keys
	# the underlying structure of this class matters a lot in this sorting
	def sort_map_on_key(self, group_map, key):
		
		eidmap = self.eidmap
		
		# simple sorting
		for g in group_map:
			l = len(group_map[g])
			i = 0
			while i < l:
				j = i +1
				while j < l:
					keyi = eidmap[group_map[g][i]][key]
					keyj = eidmap[group_map[g][j]][key]			
					# align based on increasing Y
					if keyi > keyj:
						t = group_map[g][j]
						group_map[g][j] = group_map[g][i]
						group_map[g][i] = t
					j = j+1
				i = i+1

	def same_shape_x_aligned():
		
		group_map = {}
		rss = [] 
		for wnh in wnhmap:
			if len(wnhmap[wnh]) > 2:
				group_list = wnhmap[wnh]
				for eid in group_list:
					key = eidmap[eid]['class'] + '_'+ eidmap[eid]['tag'] + "_" + wnh + "_x" + eidmap[eid]['x'] 
					ins_in_map(key, group_map, eid)
		
		# simple sorting
		for g in group_map:
			l = len(group_map[g])
			i = 0
			while i < l:
				j = i +1
				while j < l:
					yi = eidmap[group_map[g][i]]['y']
					yj = eidmap[group_map[g][j]]['y']			
					# align based on increasing Y
					if yi > yj:
						t = group_map[g][j]
						group_map[g][j] = group_map[g][i]
						group_map[g][i] = t
					j = j+1
				i = i+1
		
		self.x_aligned_map = group_map
		return group_map
		
		
	#	for tag_wnh_x in group_map:
	#		match_eid_list = group_map[tag_wnh_x]
	#	
	#		for eid in match_eid_list:
	#			x = eidmap[eid]['x']
	#			y = eidmap[eid]['y']
	#			w = eidmap[eid]['width']
	#			h = eidmap[eid]['height']
	#			tag = eidmap[eid]['tag']
	#			gotMatchingStack = False
	#			for rs in rss:
	#				if rs.isInStack(x, y, w, h, tag) == True:
	#					rs.add(x,y, eid)
	#					foundGrid = True
	#					break
	#			
	#			if not foundGrid:
	#				g = RectangleStack(x, y, w, h, tag, eid)
	#				grids.append(g)
		
		

	def same_shape_y_aligned():
		
		group_map = {}
		for wnh in wnhmap:
			if len(wnhmap[wnh]) > 2:
				group_list = wnhmap[wnh]
				for eid in group_list:
					key = eidmap[eid]['class'] + '_'+eidmap[eid]['tag'] + "_" + wnh + "_y" + eidmap[eid]['y'] 
					ins_in_map(key, group_map, eid)
		
		# simple sorting
		for g in group_map:
			l = len(group_map[g])
			i = 0
			while i < l:
				j = i +1
				while j < l:
					xi = eidmap[group_map[g][i]]['x']
					xj = eidmap[group_map[g][j]]['x']			
					# align based on increasing Y
					if xi > xj:
						t = group_map[g][j]
						group_map[g][j] = group_map[g][i]
						group_map[g][i] = t
					j = j+1
				i = i+1
			
		self.y_aligned_map = group_map
		return group_map
		
		
		
	def same_shape_find_grid(self):
		
		grids = []
		
#		for wnh in wnhmap:
#			if len(wnhmap[wnh]) > 2:
#				group_list = wnhmap[wnh]
#				for eid in group_list:
#					key = eidmap[eid]['class'] + '_'+eidmap[eid]['tag'] + "_" + wnh
#					ins_in_map(key, tag_class_wnh_map, eid)
		
		tag_class_wnh_map = self.tag_class_wnh_map
		eidmap = self.eidmap
		
		for tag_wnh in tag_class_wnh_map:
			match_eid_list = tag_class_wnh_map[tag_wnh]
			
			for eid in match_eid_list:
				x = float(eidmap[eid]['x'])
				y = float(eidmap[eid]['y'])
				w = float(eidmap[eid]['width'])
				h = float(eidmap[eid]['height'])
				tag = eidmap[eid]['tag']
					
				foundGrid = False
				for g in grids:
					if g.isInGrid(x, y, w, h, tag) == True:
						g.add(x,y, eid)
						foundGrid = True
						break
				
				if not foundGrid:
					g = Grid(x, y, w, h, tag, eid)
					grids.append(g)
		
		
		self.grids = grids
	
	
	def test():
		url = "http://www.anthropologie.com/anthro/product/clothes-new/29245875.jsp?cm_sp=Grid-_-29245875-_-Large_0"
		gr = Grouper(url)
		gr.extract()
		#gr.clean_it()
		#gr.group_it()
		#gr.same_shape_find_grid()
		#gr.group_main_product_images()
		#gr.FindTitle()

