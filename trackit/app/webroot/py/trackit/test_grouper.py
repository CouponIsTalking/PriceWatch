from grouper import *



urls = ["http://www.gap.com/browse/product.do?cid=92001&vid=1&pid=351511202", "http://www.express.com/clothing/ankle+rolled+destroyed+boyfriend+jean/pro/6995673/cat2005", "http://www.anthropologie.com/anthro/product/clothes-new/29056397.jsp?cm_sp=Grid-_-29056397-_-Regular_1", "http://www.jcrew.com/womens_feature/NewArrivals/sweaters/PRDOVR~04278/04278.jsp"]

for url in urls:
	gr = Grouper(url)
	try:
		gr.extract()
	except Exception, e:
		print e
	gr.deinit()
	print "@@@@@@@@@@@@@@@--------Done"