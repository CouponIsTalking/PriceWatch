<script type='text/javascript'>
function RunOnLoad(){

<?php 
if(!empty($user_id)){
echo "moveToHomePage();";
}else{
echo "show_ulf();$('#fade').hide();";
}

?>

}
</script>