<?php

foreach($menus as $cat => $menu){
	echo "<div style='padding:15px;margin:5px;'>";
	echo "<div style='font-size:22px;'>{$cat}</div>";
	foreach($menu as $action => $link){
		echo "<div><a target='_blank' href=\"{$link}\">{$action}</a><br/></div>";
	}
	echo "</div>";
}
?>