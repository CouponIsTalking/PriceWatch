from grouper import *
from misc import *

class XPathPatrnMaker(object):
 
    def __init__(self, urls):
        
        """description of class"""
        self.urls = urls
        self.grs = []
        for url in urls:
            self.grs.append(Grouper(url))
        
    def runGroupers(self):
        for gr in self.grs:
            gr.extract()
    
    def generate_patrn_from_xpaths(self):
        
        regexes = {}
        
        xpaths = {}
        xpaths['titlexpaths'] = []
        xpaths['pricexpaths'] = []
        xpaths['title_price_xpaths'] = []
        xpaths['oldpricexpaths'] = []
        xpaths['pimg_xpaths'] = []
        xpaths['pimg_xpaths1'] = []
        xpaths['pimg_xpaths2'] = []
        xpaths['pimg_xpaths3'] = []
        xpaths['pimg_xpaths4'] = []
        xpaths['pimg_xpaths5'] = []
        xpaths['details_xpaths'] = []
        xpaths['image_and_title_parent_xpaths'] = []
        xpaths['image_and_details_container_xpaths'] = []

        for gr in self.grs:
            xpaths['titlexpaths'].append(gr.tracker_info['title-xpath'])
            xpaths['pricexpaths'].append(gr.tracker_info['price-xpath'])
            xpaths['title_price_xpaths'].append(gr.tracker_info['title_and_price-xpath'])
            xpaths['oldpricexpaths'].append(gr.tracker_info['old-price-xpath'])
            xpaths['pimg_xpaths'].append(gr.tracker_info['product-img-xpath'])
            xpaths['pimg_xpaths1'].append(gr.tracker_info['product-img-xpath1'])
            xpaths['pimg_xpaths2'].append(gr.tracker_info['product-img-xpath2'])
            xpaths['pimg_xpaths3'].append(gr.tracker_info['product-img-xpath3'])
            xpaths['pimg_xpaths4'].append(gr.tracker_info['product-img-xpath4'])
            xpaths['pimg_xpaths5'].append(gr.tracker_info['product-img-xpath5'])
            xpaths['details_xpaths'].append(gr.tracker_info['details_xpath'])
            xpaths['image_and_title_parent_xpaths'].append(gr.tracker_info['image_and_title_parent_xpath'])
            xpaths['image_and_details_container_xpaths'].append(gr.tracker_info['image_and_details_container_xpath'])
            pass
            
        for k in xpaths:
            regexes[k.replace("xpaths", "xpath") + "_regex"] = get_id_regex_from_xpaths(xpaths[k])
        
        self.regexes = regexes
        print "########################################################"
        print "####### Generated Patterns #####################"
        for k in self.regexes:
            print [k, self.regexes[k]]
        print "####### Generated Patterns End #####################"
        print "########################################################"
    
    def update_regex_in_db(self):
        wi = WebInterface()
        wi.update_regexes_in_tracker_info(self.regexes, self.urls[0])

    
    def doTheDew(self):
        self.runGroupers()
        self.generate_patrn_from_xpaths()
        self.update_regex_in_db()
    
    