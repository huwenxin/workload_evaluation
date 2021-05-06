$(function() {

// Chart design based on the recommendations of Stephen Few. Implementation
// based on the work of Clint Ivy, Jamie Love, and Jason Davies.
// http://projects.instantcognition.com/protovis/bulletchart/
    d3.bullet = function() {
        var orient = "left",
            reverse = false,
            vertical = false,
            ranges = bulletRanges,
            xdomain = xDomain,
            markers = bulletMarkers,
            measures = bulletMeasures,
            width = 380,
            height = 30,
            xAxis = d3.svg.axis();

        var tooltip = d3.select("#chart").append("div")
            .attr("class", "tooltip")
            .style("opacity", 0);

        var x = $("#chart").offset();

        // For each small multiple…
        function bullet(g) {

            g.each(function(d, i) {
                var rangez = ranges.call(this, d, i).slice().sort(d3.descending),
                    xdomainz = xdomain.call(this, d, i).slice().sort(d3.ascending),
                    markerz = markers.call(this, d, i).slice().sort(d3.descending),
                    measurez = measures.call(this, d, i).slice().sort(d3.descending),
                    g = d3.select(this),
                    extentX,
                    extentY;

                //var tooltip = document.getElementById("chart").getElementsByClassName("tooltip");
                //var tooltip = d3.select("tooltip");

                var wrap = g.select("g.wrap");
                if (wrap.empty()) wrap = g.append("g").attr("class", "wrap");

                if (vertical) {
                    extentX = height, extentY = width;
                    wrap.attr("transform", "rotate(90)translate(0," + -width + ")");
                } else {
                    extentX = width, extentY = height;
                    wrap.attr("transform", null);
                }

                // Compute the new x-scale.
                var x1 = d3.scale.linear()
                    .domain([xdomainz[0], xdomainz[1]])
                    .range(reverse ? [extentX, 0] : [0, extentX]);

                // Retrieve the old x-scale, if this is an update.
                var x0 = this.__chart__ || d3.scale.linear()
                    .domain([0, Infinity])
                    .range(x1.range());

                // Stash the new scale.
                this.__chart__ = x1;

                // Derive width-scales from the x-scales.
                var w0 = bulletWidth(x0),
                    w1 = bulletWidth(x1);

                // Update the range rects.
                var range = wrap.selectAll("rect.range")
                    .data(rangez);

                range.enter().append("rect")
                    .attr("class", function(d, i) { return "range s" + i; })
                    .attr("width", w0)
                    .attr("height", extentY)
                    .on("mousemove", function(d, i){
                        if(i == 1) {
                            tooltip.transition()
                                .duration(50)
                                .style("opacity", .9);
                            tooltip.html("diff:" + "<br>" + "<strong>" + (measurez[1] + xdomainz[0]) + "-" + (measurez[0] + xdomainz[0]) + "</strong>")
                                .style("left", (d3.event.pageX - Math.round(x.left)) + "px")
                                .style("top", (d3.event.pageY - Math.round(x.top) + 25) + "px");
                        }
                    })
                    .on("mouseout", function(d){ tooltip.transition()
                        .duration(100)
                        .style("opacity", 0);})
                ;

                d3.transition(range)
                    .attr("x", reverse ? x1 : 0)
                    .attr("width", w1)
                    .attr("height", extentY);

                // Update the measure rects.
                var measure = wrap.selectAll("rect.measure")
                    .data(measurez);

                measure.enter().append("rect")
                    .attr("class", function(d, i) { return "measure s" + i; })
                    .attr("width", w0)
                    .attr("height", extentY / 3)
                    .attr("x", reverse ? x0 : 0)
                    .attr("y", extentY / 3);

                d3.transition(measure)
                    .attr("width", w1)
                    .attr("height", extentY / 3)
                    .attr("x", reverse ? x1 : 0)
                    .attr("y", extentY / 3);

                // Update the marker lines.
                var marker = wrap.selectAll("line.marker")
                    .data(markerz);

                marker.enter().append("line")
                    .attr("class", "marker")
                    .attr("x1", x0)
                    .attr("x2", x0)
                    .attr("y1", extentY / 6)
                    .attr("y2", extentY * 5 / 6)
                    .on("mousemove", function(d){
                            tooltip.transition()
                                .duration(50)
                                .style("opacity", .9);
                            tooltip.html("avg:" + "<br>" + "<strong>" + markerz[0] + "</strong>")
                                .style("left", (d3.event.pageX - Math.round(x.left)) + "px")
                                .style("top", (d3.event.pageY - Math.round(x.top) + 25) + "px");
                    })
                    .on("mouseout", function(d){ tooltip.transition()
                        .duration(100)
                        .style("opacity", 0);});

                d3.transition(marker)
                    .attr("x1", x1)
                    .attr("x2", x1)
                    .attr("y1", extentY / 6)
                    .attr("y2", extentY * 5 / 6);

                var axis = g.selectAll("g.axis").data([0]);
                axis.enter().append("g").attr("class", "axis");
                axis.attr("transform", vertical ? null : "translate(0," + extentY + ")")
                    .call(xAxis.scale(x1));
            });
            d3.timer.flush();
        }

        // left, right, top, bottom
        bullet.orient = function(_) {
            if (!arguments.length) return orient;
            orient = _ + "";
            reverse = orient == "right" || orient == "bottom";
            xAxis.orient((vertical = orient == "top" || orient == "bottom") ? "left" : "bottom");
            return bullet;
        };

        // ranges (bad, satisfactory, good)
        bullet.ranges = function(_) {
            if (!arguments.length) return ranges;
            ranges = _;
            return bullet;
        };

        // markers (previous, goal)
        bullet.markers = function(_) {
            if (!arguments.length) return markers;
            markers = _;
            return bullet;
        };

        // measures (actual, forecast)
        bullet.measures = function(_) {
            if (!arguments.length) return measures;
            measures = _;
            return bullet;
        };

        bullet.width = function(_) {
            if (!arguments.length) return width;
            width = +_;
            return bullet;
        };

        bullet.height = function(_) {
            if (!arguments.length) return height;
            height = +_;
            return bullet;
        };

        return d3.rebind(bullet, xAxis, "tickFormat");
    };

    function bulletRanges(d) {
        return d.ranges;
    }

     function xDomain(d) {
         return d.xdomain;
    }

    function bulletMarkers(d) {
        return d.markers;
    }

    function bulletMeasures(d) {
        return d.measures;
    }

    function bulletWidth(x) {
        var x0 = x(0);
        return function(d) {
            return Math.abs(x(d) - x0);
        };
    }

});