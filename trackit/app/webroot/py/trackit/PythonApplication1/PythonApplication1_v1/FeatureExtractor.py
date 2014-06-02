from SeleniumReader import *
from web_interface import *
from StringIO import StringIO
import lxml.html
from lxml import etree
from misc import *

class FeatureExtractor:
    
    def deinit(self):
        if self.reader_present:
            self.reader.deinit()
        
    def __init__(self, url):
        
        self.look_for_size_and_color = 0
        self.reader = None
        self.url = url
        self.reader_present = False;
        self.urllib2_present = False;
        self.t = None #lxml.html.fromstring(self.orig_page_source)
        
        self.title = ""
        self.price = ""
        self.product_images = []
        self.main_product_image = ""
        
        self.wi = WebInterface()
        self.tracker_info = self.wi.get_tracker_info(self.url)
        
#    def GetMetaInfoFromDB(self, website):
#        wi = WebInterface()
#        self.tracker_info = wi.get_tracker_info(website)
    
    # uses get_regex_substituted_xpath    
    def build_regex_substituted_tracker_info(self, page):
        tracker_info = self.tracker_info
        adjusted_tracker_info = tracker_info.copy()
        
        xpath_keys = ["title_path", "price_xpath", "title_and_price_xpath", "old_price_xpath", "pimg_xpath", "pimg_xpath2", "pimg_xpath3", "pimg_xpath4", "pimg_xpath5", "image_and_title_parent_xpath", "image_and_details_container_xpath"]
        for xpath_key in xpath_keys:
            xpath_regex_key = xpath_key + "_regex"
            if xpath_regex_key in tracker_info:
                adjusted_tracker_info[xpath_key] = get_regex_substituted_xpath(tracker_info[xpath_key], tracker_info[xpath_key + "_regex"], page)
        
        self.orig_tracker_info = self.tracker_info
        self.tracker_info = adjusted_tracker_info
        
        secondary_tracker_info = self.orig_tracker_info.copy()
        for xpath_key in secondary_tracker_info:
            xpath_regex_key = xpath_key + "_regex"
            if xpath_regex_key in tracker_info:
                regex = secondary_tracker_info[xpath_regex_key]
                improved_regex = re.sub(r"(\\d\\d)(\\d)+", "\d\d(\d)+", regex)
                #print improved_regex
                secondary_tracker_info[xpath_key] = get_regex_substituted_xpath(secondary_tracker_info[xpath_key], improved_regex, page)
        
        self.secondary_tracker_info = secondary_tracker_info.copy()
        #for k in self.secondary_tracker_info:
        #    print k + " : " + self.secondary_tracker_info[k]
        #print "--------"
        #for k in self.tracker_info:
        #    print k + " : " + self.tracker_info[k]
        #self.secondary_tracker_info = self.tracker_info
    
    def customize_tracker_info(self, page):
        tracker_info = self.tracker_info
        adjusted_tracker_info = tracker_info.copy()
        adjusted_tracker_info['title_xpath'] = get_id_substituted_xpath(tracker_info['title_xpath'], page)
        adjusted_tracker_info['title_and_price_xpath'] = get_id_substituted_xpath(tracker_info['title_and_price_xpath'], page)
        adjusted_tracker_info['price_xpath'] = get_id_substituted_xpath(tracker_info['price_xpath'], page)
        adjusted_tracker_info['old_price_xpath'] = get_id_substituted_xpath(tracker_info['old_price_xpath'], page)
        adjusted_tracker_info['pimg_xpath'] = get_id_substituted_xpath(tracker_info['pimg_xpath'], page)
        adjusted_tracker_info['pimg_xpath1'] = get_id_substituted_xpath(tracker_info['pimg_xpath1'], page)
        adjusted_tracker_info['pimg_xpath2'] = get_id_substituted_xpath(tracker_info['pimg_xpath2'], page)
        adjusted_tracker_info['pimg_xpath3'] = get_id_substituted_xpath(tracker_info['pimg_xpath3'], page)
        adjusted_tracker_info['pimg_xpath4'] = get_id_substituted_xpath(tracker_info['pimg_xpath4'], page)
        adjusted_tracker_info['pimg_xpath5'] = get_id_substituted_xpath(tracker_info['pimg_xpath5'], page)
        adjusted_tracker_info['image_and_title_parent_xpath'] = get_id_substituted_xpath(tracker_info['image_and_title_parent_xpath'], page)
        adjusted_tracker_info['image_and_details_container_xpath'] = get_id_substituted_xpath(tracker_info['image_and_details_container_xpath'], page)
        
        self.orig_tracker_info = self.tracker_info
        self.tracker_info = adjusted_tracker_info
        
   
    def enableUrllib2(self):
        page_content = self.wi.get_a_webpage(self.url)
        self.orig_page_source = page_content
        self.doc_etree = etree.parse(StringIO(page_content), etree.HTMLParser())
        self.urllib2_present = True;
    
    def enableReader(self):
        self.reader_present = True
        page_load_timeout = 20;
        self.reader = SeleniumReader(self.url, page_load_timeout);
        self.reader.verbose = 0
        self.reader.getDriver().implicitly_wait(3);
        self.orig_page_source = self.reader.getPageSource()

    
    def getBaseName(self):
        return get_website_name_for_tracker_update(self.url)
    
    def moveTo(self, new_page):
        cur_base_page = get_website_name_for_tracker_update(self.url)
        new_base_page = get_website_name_for_tracker_update(new_page)
        if cur_base_page != new_base_page:
            return False
        
        self.url = new_page
        if self.reader_present:
            self.reader.moveTo(new_page)
        self.title = ""
        self.price = ""
        self.product_images = []
        
        return True
    
    def GetTitle(self):
        title = self.GetTitleUsingUrllib2()
        if not title:
            title = self.GetTitleUsingSelenium()
        
        return title
    
    def GetPrice(self):
        price = self.GetPriceUsingUrllib2()
        if not price:
            price = self.GetPriceUsingSelenium()
        
        return price
 
    def GetProductImage(self):
        pimg = self.GetProductImageUsingUrllib2()
        if not pimg:
            pimg = self.GetProductImageUsingSelenium()
        
        return pimg
    
       
    def GetTextFromXPath(self, xpath_to_check):
        req_text = ""
        try:
            eles = self.doc_etree.xpath(xpath_to_check)
            if (eles is not None) and len(eles) >= 1:
                element = eles[0]
                element_html = etree.tostring(element)
                req_text_striped = etree.tostring(element, encoding="utf-8", method="text").strip()
                req_text = req_text_striped
        except Exception, e:
            raise
            pass
        
        return req_text
    
    
    def clean_product_title(self, title):
        title = re.sub('[\.|-| ]+',' ', title).strip()
        title = re.sub(' +',' ', title)
        words = title.split(' ')
        new_title = ""
        
        for w in words:
            if w == ':':
                break
            if w.lower() == 'by':
                break
            else:
                new_title = new_title + " " + w
        
        return new_title.strip()
            
    
    def GetTitleUsingUrllib2(self):
        title = ""
        if not self.urllib2_present:
            return title
        
        t = self.t;
        try:
            title_xpath = self.tracker_info['title_xpath']
            if title_xpath and title_xpath != "/":
                title_xpath = simplify_xpath_before_idtag(title_xpath)
                title = self.GetTextFromXPath(title_xpath)
            if not title:
                title = self.GetTextFromXPath("//title")
        except:
            a = 1
        
        return self.clean_product_title(title)
    
    def GetTitleUsingSelenium(self):
        title = ""
        if not self.reader_present:
            return title
        
        t = self.t;
        try:
            title_xpath = simplify_xpath_before_idtag(self.tracker_info['title_xpath'])
            if title_xpath and title_xpath != "/":
                title_element = self.reader.getElementByXPath(title_xpath)
                if title_element:
                    title = title_element.text
            if not title:
                title = self.reader.getDriver().title
        except:
            a = 1
        
        if title:
            title = title.strip()
        
        return clean_product_title(title)
    
    def RefinePrice(self, price, price_html):
        
        price = price.strip()
        if price.startswith("$"):
            price = price[1:]
        if len(price)>=3:
            possible_cents = price[-2:]
            possible_dollar = price[:-2]
            ih = price_html.replace(" ", "")
            if ((">$"+possible_dollar+"<" in ih) or (">"+possible_dollar+"<" in ih)) and (">"+possible_cents+"<" in ih):
                price = possible_dollar + "." + possible_cents
        
        return price
        
    def GetPriceFromTitleAndPriceStr(self, title_and_price_str):
        price = ""
        ss = title_and_price_str.split("\n")
        for s in ss:
            if not s:
                continue
            s = s.strip()
            if not s:
                continue
            
            new_price = textIsNewPrice(s)
            if new_price:
                price = new_price
                break
                            
            this_price = textIsPrice(s)
            if this_price and this_price > 0:
                if not price:
                    price = this_price
                elif this_price < price:
                    price = this_price
        
        return str(price)
    
    
    def GetPriceUsingUrllib2(self):
        price = ""
        if not self.urllib2_present:
            return price
        
        price_html = ""
        t = self.t;
        try:
            price_xpath = self.tracker_info['price_xpath']
            if price_xpath and price_xpath != "/":
                price_xpath = simplify_xpath_before_idtag(price_xpath)
                price = textHasPrice(self.GetTextFromXPath(price_xpath))
                if price:
                    price_html = etree.tostring(self.doc_etree.xpath(price_xpath)[0]).strip()
                    
            if not price:
                title_and_price_xpath = self.tracker_info['title_and_price_xpath']
                if title_and_price_xpath and title_and_price_xpath != "/":
                    title_and_price_xpath = simplify_xpath_before_idtag(title_and_price_xpath)
                    title_and_price = self.GetTextFromXPath(title_and_price_xpath)
                    price = self.GetPriceFromTitleAndPriceStr(title_and_price)
                    if price:
                        price_html = etree.tostring(self.doc_etree.xpath(title_and_price_xpath)[0]).strip()
        except:
            a = 1
        
        if price and price_html:
            price = self.RefinePrice(str(price), price_html)
        
        if not price: # incase price found is zero or none somewhere
            price = ""
        
        return price
    
    def GetPriceUsingSelenium(self):
        price = ""
        price_html = ""
        
        if not self.reader_present:
            return price
        
        t = self.t;
        try:
            price_xpath = simplify_xpath_before_idtag(self.tracker_info['price_xpath'])
            if price_xpath and price_xpath != "/":
                price_element = self.reader.getElementByXPath(price_xpath)
                if price_element:
                    price = textHasPrice(price_element.text.strip())
                    if price:
                        price_html = readWebElementAttr(price_element, 'outerHTML')
                        
        except:
            a = 1
        
        if not price:
            try:
                title_and_price_xpath = simplify_xpath_before_idtag(self.tracker_info['title_and_price_xpath'])
                if title_and_price_xpath and title_and_price_xpath != "/":
                    title_and_price_xpath_element = self.reader.getElementByXPath(title_and_price_xpath)
                    if title_and_price_xpath_element:
                        title_and_price = title_and_price_xpath_element.text
                        price = self.GetPriceFromTitleAndPriceStr(title_and_price)
                        if price:
                            price_html = readWebElementAttr(title_and_price_xpath_element, 'outerHTML')
                            
            except:
                a = 1
        
        if price == None:
            price = ""
        elif price and price_html:
            price = self.RefinePrice(str(price), price_html)
        else:
            price = str(price).strip()
            if price.startswith("$"):
                price = price[1:]
        
        #if price:
        #    price = price.strip()
        
        return price
    
        
    def GetProductImageUsingUrllib2(self):
        product_image = ""
        if not self.urllib2_present:
            return product_image
        
        t = self.t;
        pimg_xpaths = []
        pimg_xpaths.append(self.tracker_info['pimg_xpath'])
        pimg_xpaths.append(self.tracker_info['pimg_xpath2'])
        pimg_xpaths.append(self.tracker_info['pimg_xpath3'])
        pimg_xpaths.append(self.tracker_info['pimg_xpath4'])
        pimg_xpaths.append(self.tracker_info['pimg_xpath5'])
        real_images = []
        for xpath in pimg_xpaths:
            try:
                if xpath and xpath != "/" and xpath != 'NULL':
                    xpath = simplify_xpath_before_idtag(xpath)
                    product_images = self.doc_etree.xpath(xpath+"/@src")
                    if (product_images is not None) and (len(product_images)>=1):
                        product_image = product_images[0].strip()
                        if product_image:
                            real_images.append(AddHTTP(product_image))
                        
            except Exception, e:
                a = 1
        
        return real_images
    
    def GetProductImageUsingSelenium(self):
        
        product_image = ""
        if not self.reader_present:
            return product_image
        
        t = self.t;
        pimg_xpaths = []
        pimg_xpaths.append(self.tracker_info['pimg_xpath'])
        pimg_xpaths.append(self.tracker_info['pimg_xpath2'])
        pimg_xpaths.append(self.tracker_info['pimg_xpath3'])
        pimg_xpaths.append(self.tracker_info['pimg_xpath4'])
        pimg_xpaths.append(self.tracker_info['pimg_xpath5'])
        real_images = []
        for xpath in pimg_xpaths:
            try:
                if xpath and xpath != "/" and xpath != 'NULL':
                    xpath = simplify_xpath_before_idtag(xpath)
                    pimg_element = self.reader.getElementByXPath(xpath)#+'/@src')
                    if pimg_element:
                        img_src = readWebElementAttr(pimg_element, 'src')
                        if img_src:
                            product_image = img_src.strip()
                            real_images.append(product_image)
            except:
                a = 1
        
        return real_images
    
    def get_details(self):
        self.enableUrllib2()
        #self.customize_tracker_info(self.orig_page_source)
        self.build_regex_substituted_tracker_info(self.orig_page_source)
        
        self.title = self.GetTitleUsingUrllib2()
        #self.oldprice = self.GetOldPriceUsingUrllib2()
        self.price = self.GetPriceUsingUrllib2()
        self.product_images = self.GetProductImageUsingUrllib2()
        
        temp_tracker_info = self.tracker_info.copy()
        self.tracker_info = self.secondary_tracker_info
        if (not self.title):
            self.title = self.GetTitleUsingUrllib2()
        if (not self.price):
            self.price = self.GetPriceUsingUrllib2()
        if (not self.product_images):
            self.product_images = self.GetProductImageUsingUrllib2()
        # reset tracker info to original
        self.tracker_info = temp_tracker_info
        
        if (not self.title) or (not self.price) or (not self.product_images):
            self.enableReader()
            pass
        
        if not self.title:
            self.title = self.GetTitleUsingSelenium()
        
        if not self.price:
            self.price = self.GetPriceUsingSelenium()
        
        if not self.product_images:
            self.product_images = self.GetProductImageUsingSelenium()
        
        
        temp_tracker_info = self.tracker_info.copy()
        self.tracker_info = self.secondary_tracker_info
        if (not self.title):
            self.title = self.GetTitleUsingSelenium()
        if (not self.price):
            self.price = self.GetPriceUsingSelenium()
        if (not self.product_images):
            self.product_images = self.GetProductImageUsingSelenium()
        # reset tracker info to original
        self.tracker_info = temp_tracker_info
        
        if self.product_images:
            for img in self.product_images:
                if img:
                    self.main_product_image = img
                    break
        
        
    def run(self):
        self.get_details()
    
    
    def test(self):
        
        test_urls = []
        test_urls.append("")
        test_urls.append("")
        test_urls.append("")
        test_urls.append("")
        test_urls.append("")
        test_urls.append("")
        test_urls.append("")
        test_urls.append("")
        test_urls.append("")
        
        for url in test_urls:
            fe = FeatureExtractor(url)
            fe.run()
            fe.deinit()
    

