function show_coupon($p){var $t=$p["obj"][0]; var $ti=$t.closest(".h_100_tile");var $cd=$ti.find(".coupon_code");$cd.css('opacity','1');$cd.css('display','block');$cd.fadeIn('fast'); var $cnt_id=$ti.find("content_id").text(); var $cpn=$cd.text();
cpn_msg($cpn);cpn_add_call({content_id:$cnt_id,coupon:$cpn});
}
function show_dynamic_coupon($p){var $t=$p["obj"][0]; var $bp=$t.closest('.brand_promote');var $cd=$bp.find(".coupon_code_tag");$cd.css('opacity','1');$cd.css('display','block');$cd.fadeIn('fast');var $cid=$bp.find(".hidden_comp_id").text(); var $cpn=$cd.find('.coupon_code').text().trim(); 
cpn_msg($cpn);$('._after_open_fadeout').fadeOut('fast');cpn_add_call({company_id:$cid,coupon:$cpn});
}
function cpn_msg($c){ s_s_m("Congratulations! :) Your coupon code is <b>"+$c+"</b>. We will email you this coupon code as well.");r_i_c('div.success_msg');} 
function cpn_add_call($d){ $.ajax({type:"POST",data:$d,url: $S_N+"user_coupons/add_ajax/",success:function(data){},error:function(data){}}); } 