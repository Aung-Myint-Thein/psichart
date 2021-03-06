

var margin = {top: 40, right: 20, bottom: 30, left: 40},
    width = 1000 - margin.left - margin.right,
    height = 420 - margin.top - margin.bottom ;

var tformat = d3.format(".0");

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .range([height, 0]);
    
    
    var svg = d3.select("#chart_div_d3").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
    
    


var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(tformat);



d3.csv("d3/data2.csv", function(error, data) {

  data.forEach(function(d) {
    d.PSI = +d.PSI;
  });

  x.domain(data.map(function(d) { return d.time; }));
  y.domain([0, d3.max(data, function(d) { return d.PSI; })]);
  
  // Draw Y-axis grid lines
  svg.selectAll("line.y")
    .data(y.ticks(4))
    .enter().append("line")
    .attr("class", "y")
    .attr("x1", 0)
    .attr("x2", width - 8)
    .attr("y1", y)
    .attr("y2", y)
    .style("stroke", "#e6e6e6");

  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis);
      
  svg.append("text")
        .attr("x", 0)             
        .attr("y", 0 - (margin.top/2))
        .style("font-size", "14px") 
        .style("font-weight","bold")  
        .text("3 hr average PSI Reading by NEA 23-24 June 2013");
      
  var sel = svg.selectAll(".bar")
    .data(data).enter();

sel.append("rect")
   .attr("class", "bar")
   .attr("x", function(d) { return x(d.time)+4; })
   .attr("width", x.rangeBand()-8)
   .attr("y", function(d) { return y(d.PSI); })
   .attr("height", function(d) { return height - y(d.PSI); })
   .style("fill", function(d) {
      if(d.PSI<50){
        return "#19D1FF";
      }else if(d.PSI<100){
        return "#FFDF4D";
      }else if(d.PSI<200){
        return "#4DB84D";
      }else if(d.PSI<300){
        return "#E69400";
      }else if(d.PSI<400){
        return "#B80000";
      }else{
        return "black";
      }});

sel.append("text")
   .attr("x", function(d) { return x(d.time); })
   .attr("y", function(d) { return y(d.PSI)- 5; })
   .attr("font-size", "11px")
   .text(function(d) { return d.PSI});
});