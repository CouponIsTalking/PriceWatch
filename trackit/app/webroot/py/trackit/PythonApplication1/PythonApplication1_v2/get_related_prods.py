from url_groups import *
from misc import *
from web_interface import *
import lxml.html
from lxml import etree
from StringIO import StringIO

class GetRelatedProds:
    
    def __init__(self, input, method):
        self.wi = WebInterface()
        if method == 'page_url':
            self.move_to_url(input)
            
    def move_to_url (self, url):
        self.initvars()
        self.page_url = url
        self.page = self.wi.get_a_webpage(self.page_url)
        self.et = etree.parse(StringIO(self.page), etree.HTMLParser())   
    
    def initvars(self):    
        self.page = self.page_url = ""
        self.et = None
        self.related_links = self.full_links = []
    
    def deinit(self):
        self.page = self.page_url = ""
        self.et = None
        self.related_links = self.full_links = []
                         
        
    def extract_related_prods(self, page):
            
        et = self.et
        related_links = []
        
        images = et.xpath("//img")
        for image in images:
            ele = image
            link = self.get_prod_info_by_lxml_element(ele)
            if link: 
                related_links.append(link)
        
        l = len(related_links)
        full_links = [""] * l
        i = 0
        while i<l:
            if "javascript" in related_links[i]:
                full_links[i] = ""
            else:
                full_links[i] = get_full_path(related_links[i], self.page_url)
            i+=1
        
        self.related_links = related_links
        self.full_links = full_links
        print full_links
        return full_links
    
    def get_prod_info_by_lxml_element(self, ele):
        
        et = self.et
        price_found = False
        info_found = False
        link = ""
        
        while True:
            #descendant-or-self
            xpath = et.getpath(ele)
            all_images = ele.xpath(xpath+"/descendant-or-self::img")
            #print all_images
            if len(all_images) != 1:
                break
            
            if not link:
                if ele.attrib.get("href"):
                    link = ele.attrib.get("href").strip()
            
            text = etree.tostring(ele, encoding="utf-8", method="text")
            text = nicely_clean_text(text)
            words = nicely_clean_word_list(text)
            if len(words) > 15:
                break
            
            #print text
            #print "--------"
            if text and textHasDollarPrice(text):
                #print text
                price_found = True
            
            
            if link and price_found:
                info_found = True
                break
            
            ele = ele.getparent()
            if ele is None:
                break
            
            #print ele.findall("img")
            #total_images_in_this_div = len(ele.findall("img"))
            #print str(total_images_in_this_div)
            #if total_images_in_this_div != 1:
            #    break
        
        if info_found:
            return link
        else:
            return ""

def test_get_related_prods():
    
    rp = GetRelatedProds("", "")
    #related_prods_by_page_url("http://www.latindancefashions.com/shop/index.php?main_page=product_info&cPath=159_162&products_id=5259")
    
    for urls in url_groups:
        for url in urls:
            rp.move_to_url(url)
            rp.extract_related_prods(url)
    
    #related_prods_by_page_url("http://athleta.gap.com/browse/product.do?cid=1000054&vid=1&pid=918993&mlink=46750,7184035,TSAlacrity9_24&clink=7184035")

#test_get_related_prods()