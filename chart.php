<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>D3.js Bar Chart Example</title>
  <script src="https://d3js.org/d3.v7.min.js"></script>
  <style>
    /* Add some basic styles to the chart */
    .bar {
      fill: steelblue;
    }
  </style>
</head>
<body>
  <svg width="500" height="300"></svg>


<script>
  // Define the chart dimensions and margins
  var margin = {top: 20, right: 30, bottom: 30, left: 40},
      width = 500 - margin.left - margin.right,
      height = 300 - margin.top - margin.bottom;

  // Define the initial x-scale and axis
  var x = d3.scaleTime()
      .domain([new Date(), new Date(Date.now() + 3600 * 1000)])
      .range([0, width]);

  var xAxis = d3.axisBottom(x)
      .tickFormat(d3.timeFormat("%H:%M"));

  // Define the chart element
  var svg = d3.select("#chart")
      .append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .append("g")
      .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  // Add the x-axis to the chart
  var gX = svg.append("g")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  // Update the x-axis every second
  d3.interval(function() {
    var now = new Date();
    x.domain([now, new Date(now.getTime() + 3600 * 1000)]);
    gX.call(xAxis);
  }, 1000);
</script>



</body>
</html>
