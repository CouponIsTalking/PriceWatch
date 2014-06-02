function fontfamilypicker($this, $p){

$onchange_call = $p['onchange'];

$html = ""
+"<select class=''>"
+"<option value=''>Arial</option>"
+"<option value=''>Arial Black</option>"
+"<option value=''>Arial Narrow</option>"
+"<option value=''>Arial Rounded MT Bold</option>"
+"<option value=''>Avant Garde</option>"
+"<option value=''>Calibri</option>"
+"<option value=''>Candara</option>"
+"<option value=''>Century Gothic</option>"
+"<option value=''>Franklin Gothic Medium</option>"
+"<option value=''>Futura</option>"
+"<option value=''>Geneva</option>"
+"<option value=''>Gill Sans</option>"
+"<option value=''>Helvetica</option>"
+"<option value=''>Impact</option>"
+"<option value=''>Lucida Grande</option>"
+"<option value=''>Optima</option>"
+"<option value=''>Segoe UI</option>"
+"<option value=''>Tahoma</option>"
+"<option value=''>Trebuchet MS</option>"
+"<option value=''>Verdana</option>"
+"<option value=''>Baskerville</option>"
+"<option value=''>Big Caslon</option>"
+"<option value=''>Bodoni MT</option>"
+"<option value=''>Book Antiqua</option>"
+"<option value=''>Calisto MT</option>"
+"<option value=''>Cambria</option>"
+"<option value=''>Didot</option>"
+"<option value=''>Garamond</option>"
+"<option value=''>Georgia</option>"
+"<option value=''>Goudy Old Style</option>"
+"<option value=''>Hoefler Text</option>"
+"<option value=''>Lucida Bright</option>"
+"<option value=''>Palatino</option>"
+"<option value=''>Perpetua</option>"
+"<option value=''>Rockwell</option>"
+"<option value=''>Rockwell Extra Bold</option>"
+"<option value=''>Times New Roman</option>"
+"<option value=''>Andale Mono</option>"
+"<option value=''>Consolas</option>"
+"<option value=''>Courier New</option>"
+"<option value=''>Lucida Console</option>"
+"<option value=''>Lucida Sans Typewriter</option>"
+"<option value=''>Monaco</option>"
+"<option value=''>Copperplate</option>"
+"<option value=''>Papyrus</option>"
+"<option value=''>Brush Script MT</option>"
+"</select>";
	
	$eh = $this.closest('._font_family_picker').find('._show_sel').html().trim();
	if (undefined == $eh || $eh == ""){
		$this.closest('._font_family_picker').find('._show_sel').html($html);
		$this.closest('._font_family_picker').find('._show_sel').hide();
	}
	
	$this.closest('._font_family_picker').find('._show_sel').slideToggle('slow');
	
	if ($onchange_call){
		$this.closest('._font_family_picker').find('._show_sel').bind('onchange', $onchange_call);
	}
}

function sizepick($this, $p){

$onchange_call = $p['onchange'];

$html = ""
+"<select class=''>"
+"<option value='10px'>10px</option>"
+"<option value='11px'>11px</option>"
+"<option value='12px'>12px</option>"
+"<option value='13px'>13px</option>"
+"<option value='14px'>14px</option>"
+"<option value='15px'>15px</option>"
+"<option value='16px'>16px</option>"
+"<option value='17px'>17px</option>"
+"<option value='18px'>18px</option>"
+"<option value='19px'>19px</option>"
+"<option value='20px'>20px</option>"
+"<option value='21px'>21px</option>"
+"<option value='22px'>22px</option>"
+"<option value='23px'>23px</option>"
+"<option value='24px'>24px</option>"
+"<option value='25px'>25px</option>"
+"<option value='26px'>26px</option>"
+"<option value='27px'>27px</option>"
+"<option value='28px'>28px</option>"
+"<option value='29px'>29px</option>"
+"<option value='30px'>30px</option>"
+"<option value='31px'>31px</option>"
+"<option value='32px'>32px</option>"
+"<option value='33px'>33px</option>"
+"<option value='34px'>34px</option>"
+"<option value='35px'>35px</option>"
+"<option value='36px'>36px</option>"
+"<option value='37px'>37px</option>"
+"<option value='38px'>38px</option>"
+"<option value='39px'>39px</option>"
+"<option value='40px'>40px</option>"
+"<option value='41px'>41px</option>"
+"<option value='42px'>42px</option>"
+"<option value='43px'>43px</option>"
+"<option value='44px'>44px</option>"
+"<option value='45px'>45px</option>"
+"<option value='46px'>46px</option>"
+"<option value='47px'>47px</option>"
+"<option value='48px'>48px</option>"
+"<option value='49px'>49px</option>"
+"</select>";
	
	$eh = $this.closest('._size_picker').find('._show_sel').html().trim();
	if (undefined == $eh || $eh == ""){
		$this.closest('._size_picker').find('._show_sel').html($html);
		$this.closest('._size_picker').find('._show_sel').hide();
	}
	
	$this.closest('._size_picker').find('._show_sel').slideToggle('slow');
		
	if ($onchange_call){
		$this.closest('._size_picker').find('._show_sel').bind('onchange', $onchange_call);
	}
}