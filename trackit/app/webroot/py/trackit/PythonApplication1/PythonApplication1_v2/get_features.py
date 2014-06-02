from FeatureExtractor import *
from misc import *
import sys
import json

product_url = sys.argv[1]
fe = FeatureExtractor(product_url)
title = ""
price = ""
pimg = ""
#try:
if 1:
    fe.run()
    title = fe.title
    price = fe.price
    pimgs = fe.product_images
    main_product_image = fe.main_product_image
try:
    a=1
except:
    a = 1

fe.deinit()

to_dump = {'title': title, 'price':price, 'pimg':main_product_image}
i = 0
for pimg in pimgs:
    i+=1
    key = 'pimg'+str(i)
    to_dump[key] = pimg

print json.dumps(to_dump)