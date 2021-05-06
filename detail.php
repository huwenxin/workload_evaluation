<!doctype html>
<html lang="de">

<?php
    $id  = $_GET['id'];
?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="../assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/libs/css/style.css">
    <link rel="stylesheet" href="../assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="../assets/vendor/charts/morris-bundle/morris.css">
    <title>Workload-<?php echo strtoupper($id); ?></title>
</head>

<body>
<!-- ============================================================== -->
<!-- main wrapper -->
<!-- ============================================================== -->
<div class="dashboard-main-wrapper">
    <?php include 'sidebar.php' ?>
    <!-- ============================================================== -->
    <!-- wrapper  -->
    <!-- ============================================================== -->
    <div class="dashboard-wrapper">


        <div class="dashboard-ecommerce">
            <div class="container-fluid dashboard-content ">
                <!-- ============================================================== -->
                <!-- pageheader  -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                                <?php
                                    echo "<h2 class=\"pageheader-title\">";
                                    $semester = substr($id,4);
                                    $semester .= ". Semester";
                                    $study = substr($id,0, 4);
                                    if ( $study == "wiba") {
                                        $study = "WI Bachelor";
                                    } elseif ($study == "wima") {
                                        $study = "WI Master";
                                    } elseif ($study == "secm") {
                                        $study = "SecMan Master";
                                    }

                                    echo $semester." ".$study;
                                    echo "</h2><div class=\"page-breadcrumb\">
                                <nav aria-label=\"breadcrumb\">
                                    <ol class=\"breadcrumb\">
                                        <li class=\"breadcrumb-item\"><a href=\"#\" class=\"breadcrumb-link\">";
                                    echo $study;
                                    echo "</a></li><li class=\"breadcrumb-item active\" aria-current=\"page\">";
                                    echo $semester;
                                    echo "</li></ol></nav></div>";
                                ?>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader  -->
                <!-- ============================================================== -->

                <div class="row">
                    <div class="col-xl-9 col-lg-12 col-md-12 col-sm-12 col-12 mb-5">
                        <div class="pills-vertical">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <?php

                                        $con = mysqli_connect("localhost","root", "root", "workload");
                                        if (!$con) {
                                            die('Verbindung schlug fehl: ' . mysqli_error($con));
                                        }

                                        $sqlSemYear = "SELECT DISTINCT semYear, MAX(number) as number FROM workload WHERE semYear LIKE 'win%' AND studyProSem = '".$id."' GROUP BY semYear ORDER BY semYear DESC LIMIT 4";

                                        if ($result=mysqli_query($con,$sqlSemYear)) {
                                            $num = 0;
                                            $semYearArray = array();
                                            while($row = mysqli_fetch_array($result)) {
                                                $semYear = str_replace("/","",$row['semYear']);
                                                array_push($semYearArray, $row['semYear']);
                                                $number[$semYear] = $row['number'];
                                                if ($num == 0) {
                                                    echo "<a class=\"nav-link active\" id=\"".$semYear."-tab\" data-toggle=\"pill\" href=\"#".$semYear."\" role=\"tab\" aria-controls=\"".$semYear."\" aria-selected=\"true\">".$row['semYear']."</a>";
                                                    $vis[$row['semYear']] = "show active ";
                                                    $number['active'] = $semYear;
                                                } else {
                                                    echo "<a class=\"nav-link \" id=\"".$semYear."-tab\" data-toggle=\"pill\" href=\"#".$semYear."\" role=\"tab\" aria-controls=\"".$semYear."\" aria-selected=\"false\">".$row['semYear']."</a>";
                                                    $vis[$row['semYear']] = "";
                                                }

                                                $num = $num + 1;
                                            }
                                        }

                                        echo "</div></div>";

                                        ?>

                                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <?php

                                            foreach ($semYearArray as $value) {

                                                $jsonArray = array();

                                                $semYear = str_replace("/","",$value);
                                                echo "<div class=\"tab-pane fade ".$vis[$value]."\" id=\"".$semYear."\" role=\"tabpanel\" aria-labelledby=\"".$semYear."-tab\">";
                                                echo "<p class=\"lead\">Auswertung</p>";
                                                echo " <div class=\"table-responsive\">
                                                <table class=\"table\"><thead class=\"bg-light\">
                                                    <tr class=\"border-0\">
                                                        <th class=\"border-0\"></th>
                                                        <th class=\"border-0\">Veranstaltung</th>
                                                        <th class=\"border-0\">Avg</th>
                                                        <th class=\"border-0\">Min</th>
                                                        <th class=\"border-0\">Max</th>
                                                        <th class=\"border-0\">Diff</th>
                                                        <th class=\"border-0\">Anzahl</th>
                                                    </tr></thead><tbody>";

                                                $sqlData = "SELECT workload.courseID, courses.courseName, courses.ECTS, ROUND(AVG(workload), 0) AS avg, MIN(workload) as least, MAX(workload) as most,
                                                            MAX(workload) - MIN(workload) as diff, COUNT(workload) as num FROM workload INNER JOIN courses ON workload.courseID = courses.courseID WHERE studyProSem = '".$id."'
                                                            AND semYear = '$value' group by workload.courseID";

                                                if ($result=mysqli_query($con,$sqlData)) {
                                                    $num = 1;
                                                    while($row = mysqli_fetch_array($result)) {
                                                        echo "<tr><td>".$num."</td><td>".$row['courseName']."</td>";
                                                        echo "<td>".$row['avg']."</td><td>".$row['least']."</td><td>".$row['most']."</td>";
                                                        echo "<td>".$row['diff']."</td><td>".$row['num']."</td></tr>";

                                                        $num = $num + 1;

                                                        $title = $row['courseName'];
                                                        if (strlen($title) > 15) {
                                                            $title = substr($title,0,20)."...";
                                                        }
                                                        $myObj = new stdClass();
                                                        $myObj->title = $title;
                                                        $myObj->subtitle = "Stunden";
                                                        $ects = $row['ECTS'];
                                                        $rangeLow = $ects * 30 - 50;
                                                        $rangeHigh = $ects * 30 + 50;
                                                        $myObj->xdomain = array($rangeLow, $rangeHigh);
                                                        $myObj->ranges = array($row['least'] - $rangeLow, $row['most'] - $rangeLow, $rangeHigh);
                                                        $myObj->measures = array($row['least'] - $rangeLow, $row['most'] - $rangeLow);
                                                        $myObj->markers = array($row['avg']);

                                                        //$myJSON = json_encode($myObj);
                                                        array_push($jsonArray,$myObj);
                                                    }

                                                }
                                                echo "<tr><td colspan=\"9\"><br></td></tr></tbody></table>";
                                                echo "</div><a href='#' class='btn btn-secondary visual' id='visual".$semYear."'>Daten visualisieren</a></div>";

                                                $fileurl = '../data/'.$semYear.'.json';
                                                $fp = fopen($fileurl, 'w');
                                                fwrite($fp, json_encode($jsonArray));
                                                fclose($fp);
                                            }
                                            mysqli_close($con);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md- col-sm-12 col-12">

                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="card" style="width: 200px">
                                <div class="card-header bg-brand text-center p-3 ">
                                    <h4 class="mb-0 text-white"></h4>
                                </div>
                                <div class="card-body" style="margin: 0 auto; text-align: center;">
                                    <h5 class="text-muted">Teilnehmeranzahl</h5>
                                    <div class="metric-value d-inline-block">
                                        <?php
                                            foreach ($semYearArray as $value) {
                                                $semYear = str_replace("/","",$value);
                                                $active = $number['active'];
                                                if ($semYear == $active) {
                                                    echo "<h1 id=\"num".$semYear."\" class=\"display-4\">".$number[$semYear]."</h1>";
                                                } else {
                                                    echo "<h1 id=\"num".$semYear."\" class=\"display-4\" style=\"display: none\">".$number[$semYear]."</h1>";
                                                }
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div>
                    <div class="row">
                        <!-- ============================================================== -->
                        <!-- Horizontal bar chart -->
                        <!-- ============================================================== -->
                        <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Mittelwerte und Standardabweichungen</h5>
                                <div class="card-body" style="padding-left: 9px">
                                    <div class="table-responsive">

                                            <div id="chart"></div>
                                        </div>

                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- Pie graph -->
                        <!-- ============================================================== -->
                        <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Verhältnis zwischen Modulen</h5>
                                <div class="card-body">
                                    <div id="piechart"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                 </div>
        </div>


        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <div class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        Copyright ©
                        <?php
                        $year = (int)date("Y");
                        echo $year;
                        ?>
                        THB. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end wrapper  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- end main wrapper  -->
<!-- ============================================================== -->
<!-- Optional JavaScript -->
<!-- jquery 3.3.1 -->
<script src="../assets/vendor/jquery/jquery-3.3.1.min.js"></script>
<!-- bootstap bundle js -->
<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
<!-- main js -->
<script src="../assets/libs/js/main-js.js"></script>
<!-- morris js -->
<script src="../assets/vendor/charts/morris-bundle/raphael.min.js"></script>
<script src="../assets/vendor/charts/morris-bundle/morris.js"></script>

<script src="https://d3js.org/d3.v3.min.js"></script>
<script src="../assets/libs/js/bullet.js"></script>
        
<script>
    var tabs = [];
    $('.pills-vertical .nav.nav-pills .nav-link').each(function(index){
        //alert('I am index: ' + index + ' and my html value is: ' + $(this).html().replace("/",""));
        tabs.push($(this).html().replace("/",""));
    });

    $('.pills-vertical .nav.nav-pills .nav-link').click(function () {
        for (var k in tabs) {
            $('#num' + tabs[k]).hide();
        }
        $('#num'+ $(this).html().replace("/","")).show();
    });

    $('.visual').click(
        function (event) {

            $("#chart").empty();
            $("#piechart").empty();

            $('#piechart').css('height', '420px');

            var w = d3.select('#chart').node().offsetWidth;

            if (w < 350) {
                var margin = {top: 20, right: 100, bottom: 20, left: 200},
                    width = 920 - margin.left - margin.right,
                    height = 80 - margin.top - margin.bottom;
            } else {
                var margin = {top: 20, right: Math.round(w/15), bottom: 20, left: Math.round(w/15)+120},
                    width = w - margin.left - margin.right,
                    height = 80 - margin.top - margin.bottom;
            }

            var chart = d3.bullet()
                .width(width)
                .height(height);

            var fileurl = "../data/" + event.target.id.substring(6) + ".json";

            d3.json(fileurl, function(error, data) {
                if (error) throw error;

                var svg = d3.select("#chart").selectAll("svg")
                    .data(data)
                    .enter().append("svg")
                    .attr("class", "bullet")
                    .attr("width", width + margin.left + margin.right)
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g")
                    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

                var title = svg.append("g")
                    .style("text-anchor", "end")
                    .attr("transform", "translate(-6," + height / 2 + ")");

                title.append("text")
                    .attr("class", "title")
                    .text(function(d) { return d.title; });

                title.append("text")
                    .attr("class", "subtitle")
                    .attr("dy", "1em")
                    .text(function(d) { return d.subtitle; });

                svg.transition()
                    .duration(500)
                    .call(chart);

            });

            $.getJSON(fileurl, function(data) {

                var total = 0;
                data.forEach( function (x) {
                    total = total + parseInt(x.markers);
                });

                function piedata() {
                    var ret = [];

                    data.forEach(function (x) {
                        ret.push({
                            value: Math.round(parseInt(x.markers)/total * 100),
                            label: x.title
                        });
                    });
                    return ret;
                }

                Morris.Donut({
                    element: 'piechart',
                    data: piedata(),

                    labelColor: '#2e2f39',
                    gridTextSize: '14px',
                    colors: [
                        "#5969ff",
                        "#ff407b",
                        "#25d5f2",
                        "#ffc750",
                        "#a8e6cf",
                        "#7c5b42",
                        "#dcedc1",
                        "#00b159",
                        "#ffd3b6",
                        "#f37735",
                        "#ffaaa5",
                        "#ff8b94"
                    ],

                    formatter: function(x) { return x + "%" },
                    resize: true
                });


            });
        }

    );

</script>


        
</body>

</html>