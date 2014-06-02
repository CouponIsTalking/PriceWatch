from grouper import *
#url = "http://www.anthropologie.com/anthro/product/clothes-new/29245875.jsp?cm_sp=Grid-_-29245875-_-Large_0"
url = "http://www.gap.com/browse/product.do?cid=92001&vid=1&pid=351511202"
gr = Grouper(url)
gr.extract()

from grouper import *
url = "http://www.express.com/clothing/ankle+rolled+destroyed+boyfriend+jean/pro/6995673/cat2005"
gr = Grouper(url)
gr.extract()

from grouper import *
url = "http://www.anthropologie.com/anthro/product/clothes-new/29245875.jsp?cm_sp=Grid-_-29245875-_-Regular_0"
gr = Grouper(url)
gr.extract()
