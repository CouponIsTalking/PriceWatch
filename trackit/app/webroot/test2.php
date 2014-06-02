<?php


$date = date_create_from_format('Y-m-d H:i:s', "2013-11-30 23:59:59");
echo $date->format('Y-m-d H:i:s');

?>