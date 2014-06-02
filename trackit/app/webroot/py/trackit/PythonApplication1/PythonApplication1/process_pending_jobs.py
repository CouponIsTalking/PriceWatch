from FeatureExtractor import *
from web_interface import *
from get_related_prods import *
from misc import *
import time
import signal, os

process_id = os.getpid()
with open("process_pending_jobs_pid.txt", "w") as f:
    f.write(str(process_id))


# globals
rp_urllib2 = GetRelatedProds("", "")
rp_urllib2.set_tool('urllib2')

rp_selenium = GetRelatedProds("", "")
rp_selenium.set_tool('selenium')

fe = FeatureExtractor("")

wi = WebInterface()

processing_jobs = False
    
def run():
    
    global rp_urllib2
    global rp_selenium
    global fe
    global wi
    global processing_jobs
    
    if True == processing_jobs:
        return False
    
    processing_jobs = True
    
    print "Processing jobs"
    
    while True:
        
        jobs = wi.get_pending_jobs()
        if not jobs:
            break
        
        for job in jobs:
            type = job['type']
            url = job['url']
            result = False
            
            if (type == 'get_rp'):
                rp_selenium.set_tool('selenium')
                rp_selenium.move_to_url(url)
                rp_selenium.set_tool('urllib2')
                full_links = rp_selenium.extract_related_prods();
                print full_links
                result = rp_selenium.update_related_prods_in_db() if full_links else True
            
            elif (type == 'get_prod_detail'):
                if fe.reinit(url):
                    fe.set_mode('accurate')
                    fe.run()
                    fe.print_stuff()
                    result = fe.update_prod_info_in_db()
                elif not fe.tracker_info:
                    wi.update_backend_job_status(job['id'], 101)
                # update product with title (if not already present), price, image(if not already present) data
            
            if result == True:
                wi.update_backend_job_status(job['id'], 1)
    
    processing_jobs = False
    return True
        
def new_job_handler1(signum, frame):
    print 'new_job_handler called to get prod info', signum
    new_call = run() if (True==processing_job) else False
    print new_call
    
#signal.signal(signal.SIGILL, new_job_handler1)

#while True:
#    pass

while True:
    
    new_call = False
    try:
        f = open("more_pending_jobs.txt", "r")
        val = f.readline() if not f.closed else ""
        file_close = f.close() if not f.closed else 0
        new_call = True if val and (1==int(val)) else False
    
    except IOError, e:
        print "No such file"
    except ValueError, e:
        print "value error, likely IO op on closed file"
        #time.sleep(2)
        
    if new_call:
        run()
        with open("more_pending_jobs.txt", "w") as f:
            f.write("0")
    
    time.sleep(2)

# deinit stuff
rp_selenium.deinit()    
rp_urllib2.deinit()
fe.deinit()  