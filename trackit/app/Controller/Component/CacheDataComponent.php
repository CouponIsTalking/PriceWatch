<?php

/*
Manage cached data. Mainly used to either retrieve data from
cache or repopulate the memcache using the cached_data table.
When retrieving data, the component first looksup data in the
memcache. If it doesn't find what it is looking for, it would 
look up same keys in the cached_data table.

Re-populating is straight forward. It first invalidates the 
memcached data on those keys, then repopulates them with cached_data
table.
*/

class CacheDataComponent extends Component {
	
	
	
}

?>