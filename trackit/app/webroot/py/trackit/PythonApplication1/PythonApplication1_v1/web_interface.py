import sys
import urllib
import urllib2
import const
from const import *

import misc
from misc import *

import BeautifulSoup
from BeautifulSoup import BeautifulSoup, Comment
import htmlops
import json

class WebInterface:
    def test_update_tracker_info(self):
        tracker_info = {}
        website = get_website_name_for_tracker_update("http://www.express.com/clothing/ankle+rolled+destroyed+boyfriend+jean/pro/6995673/cat2005")
        
        tracker_info['website'] = website
        tracker_info['title-xpath'] =  "//div[@id='cat-pro-con-detail']/h1"
        tracker_info['price-xpath'] =  "//div[@id='cat-pro-con-detail']/div[2]/strong/span/span[2]"
        tracker_info['product-img-xpath'] =  "//div[@id='flyout']/div[1]/div[1]/div[2]/img"
        tracker_info['title_and_price-xpath'] =  "//div[@id='cat-pro-con-detail']"
        tracker_info['title_price_css'] = json.dumps({'price-css': {'color': 'rgba(255, 0, 0, 1)', 'font-size': '17px', 'font-family'	: 'Arial,sans-serif', 'font-weight': '400'}, 'title-css': {'color': 'rgba(0, 0,	0, 1)', 'font-size': '13px', 'font-family': 'Arial,sans-serif', 'font-weight': '700'}})
        tracker_info['details_xpath'] = ""
        self.update_tracker_info(tracker_info)
        
    
    ## -------------------------------
    ## Given a tracker info
    ## update the database with new 
    ## stats
    ## -------------------------------
    def update_tracker_info (self, tracker_info):
        
        data = {
                    'website' : tracker_info['website'],
                    'titlexpath' : tracker_info['title-xpath'],
                    'pricexpath' : tracker_info['price-xpath'],
                    'oldpricexpath' : tracker_info['old-price-xpath'],
                    'pimg_xpath' : tracker_info['product-img-xpath'],
                    'pimg_xpath1' : tracker_info['product-img-xpath1'],
                    'pimg_xpath2' : tracker_info['product-img-xpath2'],
                    'pimg_xpath3' : tracker_info['product-img-xpath3'],
                    'pimg_xpath4' : tracker_info['product-img-xpath4'],
                    'pimg_xpath5' : tracker_info['product-img-xpath5'],
                    'image_and_details_container_xpath' : tracker_info['image_and_details_container_xpath'],
                    'image_and_title_parent_xpath' : tracker_info['image_and_title_parent_xpath'],
                    'title_price_xpath' : tracker_info['title_and_price-xpath'],
                    'title_price_css' : tracker_info['title_price_css'],
                    'details_xpath' : tracker_info['details_xpath']
                  }
        
        update_url = SITE_NAME+'/tracker_infos/add_tracker_info_from_script/'+ PYTHON_VERIFICATION_CODE;
        user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
        headers = { 'User-Agent' : user_agent }
        data = urllib.urlencode(data)
        req = urllib2.Request(update_url, data, headers)
        try :
            print update_url
            response = urllib2.urlopen(req)
            print response
            r = response.read()
            f = open("test.html", "w")
            f.write(r)
            f.close()
            print "tracker_info_update called"
        except Exception, e:
            print "Error in updating db"
            print e
            return 0
        
        #returned_page = response.read()
        #print returned_page
        # TODO : read the page and verify that the project was added successfully.
        
        return 1
    
    ## -------------------------------
    ## Given a tracker's regex infos
    ## update the database with new 
    ## stats
    ## -------------------------------
    def update_regexes_in_tracker_info (self, trackers_regex_info, website):
        
        data = { 'website' : get_website_name_for_tracker_update(website) }
        for key in trackers_regex_info:
            data[key] = trackers_regex_info[key]
        
        
        update_url = SITE_NAME+'/tracker_infos/add_regex_info_from_script/'+ PYTHON_VERIFICATION_CODE;
        user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
        headers = { 'User-Agent' : user_agent }
        data = urllib.urlencode(data)
        req = urllib2.Request(update_url, data, headers)
        try :
            print update_url
            response = urllib2.urlopen(req)
            print response
            r = response.read()
            f = open("test_regex.html", "w")
            f.write(r)
            f.close()
            print "tracker_info_update called"
        except Exception, e:
            print "Error in updating db"
            print e
            return 0
        
        #returned_page = response.read()
        #print returned_page
        # TODO : read the page and verify that the project was added successfully.
        
        return 1
    
    
    ## -------------------------------
    ## add a news to AB
    ## -------------------------------
    def add_news (self, news):
        
        data = {
                    'company_id' : -1,
                    'product_id':0,
                    'title' : news['title'],
                    'desc' : news['desc'],
                    'link' : news['link'],
                    'topic1' : news['topic1'],
                    'state' : news['state'],
                    'type':news['type']
                  }
        
        update_url = AB_SITE_NAME+'/contents/add_from_script/'+ AB_PYTHON_VERIFICATION_CODE;
        user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
        headers = { 'User-Agent' : user_agent }
        data = urllib.urlencode(data)
        req = urllib2.Request(update_url, data, headers)
        try :
            print update_url
            response = urllib2.urlopen(req)
            print response
            r = response.read()
            f = open("test.html", "w")
            f.write(r)
            f.close()
            print "news update called"
        except Exception, e:
            print "Error in updating db"
            print e
            return 0
        
        #returned_page = response.read()
        #print returned_page
        # TODO : read the page and verify that the project was added successfully.
        
        return 1
    
    ## -------------------------------
    ## Given a stats of a blogger
    ## update the database with new 
    ## stats
    ## -------------------------------
    def update_blogger_matrix (self, bloggerid, matrixes):
        
        fbdata = matrixes[0]
        twdata = matrixes[1]
        pinterestdata = matrixes[2]
        instagramdata = matrixes[3]
        
        data = {
                    'id' : addslashes(bloggerid),
                    'fb' : {'likes' : -1, 'followers' : -1, 'active_conversations' : -1 },
                    'twitter' : {'tweets' : -1, 'followers' : -1, 'following' : -1 },
                    'instagram' : {'posts' : -1, 'followers' : -1, 'following' : -1 },
                    'pinterest' : {'likes' : -1, 'pins' : -1, 'boards' : -1, 'followers' : -1, 'following' : -1},
                  
                  }
        
        update_url = SITE_NAME+'/bloggers/update_blogger_stats/'+ PYTHON_VERIFICATION_CODE;
        user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
        headers = { 'User-Agent' : user_agent }
        data = urllib.urlencode(data)
        req = urllib2.Request(update_url, data, headers)
        try :
            print update_url
            response = urllib2.urlopen(req)
            print response
            print "db updated for blogger " + str(bloggerid)
        except Exception, e:
            print "Error in updating db"
            print e
            return 0
        
        #returned_page = response.read()
        #print returned_page
        # TODO : read the page and verify that the project was added successfully.
        
        return 1
    
    def test_get_tracker_info(self):
        return self.get_tracker_info("http://www.target.com/p/mossimo-womens-ultrasoft-cocoon-cardigan-assorted-colors/-/A-14502863?reco=Rec|pdp|14502863|NonGenreCategoryTopSellers|item_page.vertical_1&lnk=Rec|pdp|NonGenreCategoryTopSellers|item_page.vertical_1")
    
    #--------------------------------------------
    # gets tracker info
    #--------------------------------------------
    def get_tracker_info ( self, website ):
        
        
        url_to_call = SITE_NAME+'/tracker_infos/get_tracker_info_from_script/'+ PYTHON_VERIFICATION_CODE;
        data = {'website' : get_website_name_for_tracker_update(website) }
        user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
        headers = { 'User-Agent' : user_agent }
        data = urllib.urlencode(data)
        req = urllib2.Request(url_to_call, data, headers)
        resultset = None
        
        try :
            #print url_to_call
            response = urllib2.urlopen(req)
            response_page = response.read()
            #print response_page
            
            clean_response_page = htmlops.removeCommentsAndJS(response_page)
            soup = BeautifulSoup(clean_response_page)
            #print clean_response_page
            # pull the divs
            div = soup.find('div', attrs={'id' : '_tracker_info_'})
            csses_div = soup.find('div', attrs={'id' : 'csses'})
            #print main_div
            #print "print beautiful soup over"
            
            #build up result set
            resultset = { 
                        'title_xpath':div['title_xpath'].encode("ascii", "ignore"), 
                        'title_and_price_xpath':div['title_and_price_xpath'].encode("ascii", "ignore"), 
                        'price_xpath':div['price_xpath'].encode("ascii", "ignore"), 
                        'old_price_xpath':div['old_price_xpath'].encode("ascii", "ignore"), 
                        'pimg_xpath': div['pimg_xpath'].encode("ascii", "ignore"), 
                        'pimg_xpath1': div['pimg_xpath1'].encode("ascii", "ignore"), 
                        'pimg_xpath2': div['pimg_xpath2'].encode("ascii", "ignore"), 
                        'pimg_xpath3': div['pimg_xpath3'].encode("ascii", "ignore"), 
                        'pimg_xpath4': div['pimg_xpath4'].encode("ascii", "ignore"), 
                        'pimg_xpath5': div['pimg_xpath5'].encode("ascii", "ignore"), 
                        'image_and_title_parent_xpath': div['image_and_title_parent_xpath'].encode("ascii", "ignore"), 
                        'image_and_details_container_xpath': div['image_and_details_container_xpath'].encode("ascii", "ignore"), 
                        'csses': json.loads(csses_div.text.encode("ascii", "ignore")), 
                        'details_xpath':div['details_xpath'].encode("ascii", "ignore"),
                        'title_xpath_regex':div['titlexpath_regex'].encode("ascii", "ignore"), 
                        'title_and_price_xpath_regex':div['title_price_xpath_regex'].encode("ascii", "ignore"), 
                        'price_xpath_regex':div['pricexpath_regex'].encode("ascii", "ignore"), 
                        'old_price_xpath_regex':div['oldpricexpath_regex'].encode("ascii", "ignore"), 
                        'pimg_xpath_regex': div['pimg_xpath_regex'].encode("ascii", "ignore"), 
                        'pimg_xpath1_regex': div['pimg_xpath1_regex'].encode("ascii", "ignore"), 
                        'pimg_xpath2_regex': div['pimg_xpath2_regex'].encode("ascii", "ignore"), 
                        'pimg_xpath3_regex': div['pimg_xpath3_regex'].encode("ascii", "ignore"), 
                        'pimg_xpath4_regex': div['pimg_xpath4_regex'].encode("ascii", "ignore"), 
                        'pimg_xpath5_regex': div['pimg_xpath5_regex'].encode("ascii", "ignore"), 
                        'image_and_title_parent_xpath_regex': div['image_and_title_parent_xpath_regex'].encode("ascii", "ignore"), 
                        'image_and_details_container_xpath_regex': div['image_and_details_container_xpath_regex'].encode("ascii", "ignore")
                    } 
                        
            #print resultset
            # returl thus prepared url list :)
            
        except :
            print "FATAL ERROR : Couldnt get tracker info"
            print "Unexpected error:", sys.exc_info()[0]
            raise
            
        return resultset
        
    
    def get_a_webpage(self, url):
        response_page = ""
        user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
        headers = { 'User-Agent' : user_agent }
        req = urllib2.Request(url, None, headers)
        try :
            response = urllib2.urlopen(req)
            response_page = response.read()            
        except:
            pass
        
        return response_page
    
    #--------------------------------------------
    # gets urls to parse 
    # return value is html page that needs to be 
    # parsed further to extract urls 
    #--------------------------------------------
    def get_bloggers_url ( self ):
        
        url_to_call = SITE_NAME+'/bloggers/get_list_for_parsing/'+ PYTHON_VERIFICATION_CODE;
        user_agent = 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)'
        headers = { 'User-Agent' : user_agent }
        
        req = urllib2.Request(url_to_call, None, headers)
        try :
            print url_to_call
            response = urllib2.urlopen(req)
            response_page = response.read()
            
            clean_response_page = htmlops.removeCommentsAndJS(response_page)
            soup = BeautifulSoup(clean_response_page)
            #print clean_response_page
            # pull the divs
            main_div = soup.findAll('div', attrs={'id' : '_parsing_urls_'})
            #print main_div
            #print "print beautiful soup over"
            
            #build up result set
            resultset = []
            for div in main_div:
                resultset.append( 
                    { 
                        'blog':div['blog'].encode("ascii", "ignore"), 
                        'facebooklink':div['facebooklink'].encode("ascii", "ignore"), 
                        'twitterlink':div['twitterlink'].encode("ascii", "ignore"), 
                        'instagramlink':div['instagramlink'].encode("ascii", "ignore"), 
                        'pinterestlink': div['pinterestlink'].encode("ascii", "ignore"), 
                        'bloggerid':div['bloggerid'].encode("ascii", "ignore"), 
                        'ready_to_parse':div['ready_to_parse'].encode("ascii", "ignore")
                    } 
                );
            
            #print resultset
            # returl thus prepared url list :)
            return resultset
        except :
            print "FATAL ERROR : Couldnt get url list"
            print "Unexpected error:", sys.exc_info()[0]
            raise
            return None
            