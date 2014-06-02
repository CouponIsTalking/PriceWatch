<?php
echo $this->Html->css('pchart');
echo $this->Html->script('pdisp/pchart');
echo $this->Html->script('underscore/underscore-min');
echo $this->Html->script('charts/d3.v3');
?>

<script type='text/javascript'>

function RunOnLoad(){
draw_price_chart(39);
}

</script>

<div class='graph_container' style='background-color: rgb(255,255,255);background-color:rgba(255,255,255,0.8);position:fixed;top:0px;bottom;0px;left:0px;display:block;'>
<div id='chart'></div>
<div class='price_chart_close' onclick='clear_price_chart();'>X</div>
</div>