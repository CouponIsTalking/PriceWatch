<script src="<?php echo SITE_NAME; ?>js/charts/d3.v3.js"></script>
<script src="<?php echo SITE_NAME; ?>js/underscore/underscore-min.js"></script>

<style type='text/css'>

* .graph_container {
  font: 10px sans-serif;
}

path {
	fill: #CED8B6;
}

line {
	stroke: #555;
	stroke-width: 2px;
}

line#bestfit {
	stroke: #ddd;
	stroke-width: 10px;
}

#xAxis path, #yAxis path, #xAxis line, #yAxis line {
	stroke: #ccc;
	fill: none;
	shape-rendering: crispEdges;
}

text {
	font-size: 12px;
	fill: #888;
}

</style>

<script type='text/javascript'>

// HELPERS
function parseData(d) {
  var keys = _.keys(d[0]);
  return _.map(d, function(d) {
    var o = {};
    _.each(keys, function(k) {
      if( k == 'Country' )
        o[k] = d[k];
      else
        o[k] = parseFloat(d[k]);
    });
    return o;
  });
}



function RunOnLoad() {

  var margin = {top: 30, right: 40, bottom: 100, left: 50},
	width = 1000-margin.left-margin.right,
	height = 640 - margin.top-margin.bottom;
	
  var xAxis = 'Time', yAxis = 'Price';
  var description = "Change in Price";
 var data = [];//{'Time':{}, 'Price':{}};
	
	$dp_history = $('._dp_history').text();
	var $dps = $dp_history.split(',');
	var $i=0;
	var $total = ($dps.length)/2;
	while($i<$total){
		data.push({ 'Time':parseInt($dps[$i*2+1]),'Price':parseInt($dps[$i*2]) });
		//data['Time'][$i] = parseInt($dps[$i*2+1]);
		//data['Price'][$i] = parseInt($dps[$i*2]);
		$i = $i+1;
	}
	
  //var keys = _.keys(data[0]);
  //var data = parseData(data);
  var bounds = getBounds(data, 1);

  // SVG AND D3 STUFF
  var svg = d3.select("#chart").append("svg").attr("width", 1000).attr("height", 700);
  var xScale, yScale;

  svg.append('g').classed('chart', true).attr('transform', 'translate(80, -60)');

  // Country name
  d3.select('svg g.chart').append('text')
    .attr({'id': 'countryLabel', 'x': 0, 'y': 170})
    .style({'font-size': '80px', 'font-weight': 'bold', 'fill': '#ddd'});

  // Best fit line (to appear behind points)
  d3.select('svg g.chart').append('line').attr('id', 'bestfit');

  // Axis labels
  d3.select('svg g.chart').append('text')
    .attr({'id': 'xLabel', 'x': 400, 'y': 670, 'text-anchor': 'middle'})
    .text("Change in Price");

  d3.select('svg g.chart').append('text')
    .attr('transform', 'translate(-60, 330)rotate(-90)')
    .attr({'id': 'yLabel', 'text-anchor': 'middle'})
    .text('Price');

  // Render points
  updateScales();
  var pointColour = d3.scale.category20b();
  d3.select('svg g.chart')
    .selectAll('circle')
    .data(data)
    .enter()
    .append('circle')
    .attr('cx', function(d) {
      return isNaN(d[xAxis]) ? d3.select(this).attr('cx') : xScale(d[xAxis]);
    })
    .attr('cy', function(d) {
      return isNaN(d[yAxis]) ? d3.select(this).attr('cy') : yScale(d[yAxis]);
    })
    .attr('fill', function(d, i) {return pointColour(i);})
    .style('cursor', 'pointer')
    .on('mouseover', function(d) {
      d3.select('svg g.chart #countryLabel')
        .text(d.Price)
        .transition()
        .style('opacity', 1);
    })
    .on('mouseout', function(d) {
      d3.select('svg g.chart #countryLabel')
        .transition()
        .duration(1500)
        .style('opacity', 0);
    });

  updateChart(true);
  //updateMenus();

  // Render axes
  d3.select('svg g.chart')
    .append("g")
    .attr('transform', 'translate(0, 630)')
    .attr('id', 'xAxis')
    .call(makeXAxis);

  d3.select('svg g.chart')
    .append("g")
    .attr('id', 'yAxis')
    .attr('transform', 'translate(0, 0)')
    .call(makeYAxis);

	// set xaxis text 
	d3.select("#xAxis").selectAll("text")  
		.style("text-anchor", "end")
		.attr("dx", "-.8em")
		.attr("dy", ".15em")
		.attr("transform", function(d) {
			return "rotate(-65)";
			});

  //// RENDERING FUNCTIONS
  function updateChart(init) {
    updateScales();

    d3.select('svg g.chart')
      .selectAll('circle')
      .transition()
      .duration(500)
      .ease('quad-out')
      .attr('cx', function(d) {
        return isNaN(d[xAxis]) ? d3.select(this).attr('cx') : xScale(d[xAxis]);
      })
      .attr('cy', function(d) {
        return isNaN(d[yAxis]) ? d3.select(this).attr('cy') : yScale(d[yAxis]);
      })
      .attr('r', function(d) {
        return isNaN(d[xAxis]) || isNaN(d[yAxis]) ? 0 : 12;
      });

    // Also update the axes
    d3.select('#xAxis').transition().call(makeXAxis);	  
	  
	// update xaxes tick orientation
	//d3.select('#xAxis').select('.tick').attr('transform', 'rotate(-90,0)');
	
    d3.select('#yAxis').transition().call(makeYAxis);

    // Update axis labels
    d3.select('#xLabel').text("Change in prices overtime").attr('transform', 'translate(0,-50)');

    // Update correlation
    var xArray = _.map(data, function(d) {return d[xAxis];});
    var yArray = _.map(data, function(d) {return d[yAxis];});
    var c = getCorrelation(xArray, yArray);
    var x1 = xScale.domain()[0], y1 = c.m * x1 + c.b;
    var x2 = xScale.domain()[1], y2 = c.m * x2 + c.b;

    // Fade in
    d3.select('#bestfit')
      .style('opacity', 0)
      .attr({'x1': xScale(x1), 'y1': yScale(y1), 'x2': xScale(x2), 'y2': yScale(y2)})
      .transition()
      .duration(1500)
      .style('opacity', 1);
	  
  }

  function updateScales() {
    xScale = d3.scale.linear()
                    .domain([bounds[xAxis].min, bounds[xAxis].max])
                    .range([20, 780]);

    yScale = d3.scale.linear()
                    .domain([bounds[yAxis].min, bounds[yAxis].max])
                    .range([600, 100]);    
  }

  function makeXAxis(s) {
	s.call(
		d3.svg.axis().tickFormat(
		function(d){
			t = new Date(d*1000);
			return t.toString().split(' ')[0]+","+(t.getMonth()+1)+"/"+t.getDate()+"/"+(t.getYear()-100);
			//return t.toString().split(' ').slice(0,4).join(' ');
		}
	).scale(xScale).orient("bottom"));
    
  }

  function makeYAxis(s) {
    s.call(d3.svg.axis().scale(yScale).orient("left"));
  }

  function updateMenus() {
    d3.select('#x-axis-menu').selectAll('li')
      .classed('selected', function(d) {
        return d === xAxis;
      });
    d3.select('#y-axis-menu').selectAll('li')
      .classed('selected', function(d) {
        return d === yAxis;
    });
  }
  
	function getBounds(d, paddingFactor) {
	  // Find min and maxes (for the scales)
	  paddingFactor = typeof paddingFactor !== 'undefined' ? paddingFactor : 1;

	  var keys = _.keys(d[0]), b = {};
	  _.each(keys, function(k) {
		b[k] = {};
		_.each(d, function(d) {
		  if(isNaN(d[k]))
			return;
		  if(b[k].min === undefined || d[k] < b[k].min)
			b[k].min = d[k];
		  if(b[k].max === undefined || d[k] > b[k].max)
			b[k].max = d[k];
		});
		b[k].max > 0 ? b[k].max *= paddingFactor : b[k].max /= paddingFactor;
		b[k].min > 0 ? b[k].min /= paddingFactor : b[k].min *= paddingFactor;
	  });
	  return b;
	}

	function getCorrelation(xArray, yArray) {
		function sum(m, v) {return m + v;}
		function sumSquares(m, v) {return m + v * v;}
		function filterNaN(m, v, i) {isNaN(v) ? null : m.push(i); return m;}

		// clean the data (because we know that some values are missing)
		var xNaN = _.reduce(xArray, filterNaN , []);
		var yNaN = _.reduce(yArray, filterNaN , []);
		var include = _.intersection(xNaN, yNaN);
		var fX = _.map(include, function(d) {return xArray[d];});
		var fY = _.map(include, function(d) {return yArray[d];});

		var sumX = _.reduce(fX, sum, 0);
		var sumY = _.reduce(fY, sum, 0);
		var sumX2 = _.reduce(fX, sumSquares, 0);
		var sumY2 = _.reduce(fY, sumSquares, 0);
		var sumXY = _.reduce(fX, function(m, v, i) {return m + v * fY[i];}, 0);

		var n = fX.length;
		var ntor = ( ( sumXY ) - ( sumX * sumY / n) );
		var dtorX = sumX2 - ( sumX * sumX / n);
		var dtorY = sumY2 - ( sumY * sumY / n);

		var r = ntor / (Math.sqrt( dtorX * dtorY )); // Pearson ( http://www.stat.wmich.edu/s216/book/node122.html )
		var m = ntor / dtorX; // y = mx + b
		var b = ( sumY - m * sumX ) / n;

		// console.log(r, m, b);
		return {r: r, m: m, b: b};
	}

}

</script>


<?php
	echo "<div class='_dp_history' style='display:none;'>{$price_date_history}</div>";
?>
<div class='graph_container'></div>
<div id='chart'></div>