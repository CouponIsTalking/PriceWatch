function get_select_option_by_value($parent, $value){
if (is_ie()){$op_ele = $parent.find("option[value='" + $value + "']");}else{$op_ele = $parent.find("option[value=" + $value + "]");}
return $op_ele;
}

function set_select_options_display($options, $opr){
$options.each(function(){if ('show' == $opr){show_select_option($(this));}else if ('hide' == $opr){hide_select_option($(this));}});
}

function show_select_option($op_ele){
if(is_ie() || is_netscape()){if ($op_ele.get(0).nodeName.toUpperCase()==='OPTION'){
 var span = $op_ele.parent();if($op_ele.parent().is('span')){$op_ele.show();$(span).replaceWith($op_ele.get(0));}
}}else{$op_ele.show();$op_ele.css('display', 'block');}
}

function hide_select_option($op_ele){if (is_netscape()){$op_ele.remove();return;}
if (!is_ie()){$op_ele.css('display', 'none');return;}
if ($op_ele.is('option') && (!$op_ele.parent().is('span'))){$op_ele.wrap((is_ie()) ? '<span>' : null).hide();}
}
