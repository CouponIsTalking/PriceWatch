import sys
import re
import time

s = "";
s = s+ "{ ";
for i in range (0, 10001):
	s = s+  "\"";
	s = s+  str(i);
	s = s+  "\"";
	s = s+  ",";
	s = s+  " ";
	
s = s+  " }";

print s;

s = "";
s = s+ "{ ";
for i in range (0, 10001):
	j = 1;
	while 1:
		i = i/10;
		if i == 0:
			break;
		j = j + 1;
	
	s = s+  str(j);
	s = s+  ",";
	s = s+  " ";
	
s = s+  " }";

print s;



	
	