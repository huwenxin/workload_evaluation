<?php
//session_start();
//$_SESSION["access"] = false;
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_address = $_SERVER['REMOTE_ADDR'];
}
?>

<html lang="de">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title>Workload Evaluation</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">

</head>

<body>
    <div class="page-wrapper bg-img-1 p-t-275 p-b-100">
        <div class="wrapper wrapper--w900">
            <div class="card card-1">
                <div class="card-heading">
                    <h2 class="title">Workload-Erfassung</h2>
                </div>
<!--                <div class="card-body" id="checksession">-->
<!--                    <div class="login form-row">-->
<!--                        <label for="accesstoken" style='margin-left: 20px;'>Access Token</label></label>-->
<!--                        <input class='input--style-1' type='text' id='accesstoken' name='accesstoken'>-->
<!--                        <button type="submit" id="check" class="btn--next">Check</button>-->
<!--                    </div>-->
<!--                </div>-->

                <div class="card-body" id="questionnaire" style="display: block">
                    <form class="wizard-container" method="POST" action="insert.php" id="js-wizard-form">
                        <ul class="tab-list">
                            <li class="tab-list__item active">
                                <a class="tab-list__link" href="#tab1" data-toggle="tab">
                                    <span class="step">1</span>
                                    <span class="desc">Studiengang</span>
                                </a>
                            </li>
                            <li class="tab-list__item">
                                <a class="tab-list__link" href="#tab2" data-toggle="tab">
                                    <span class="step">2</span>
                                    <span class="desc">Semester</span>
                                </a>
                            </li>
                            <li class="tab-list__item">
                                <a class="tab-list__link" href="#tab3" data-toggle="tab">
                                    <span class="step">3</span>
                                    <span class="desc">Workload</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">

                                <div class="form">
                                    <div class="input-group">
                                        <div class="form-row">
                                            <label for="subject">Bitte w??hlen Sie Ihren Studiengang aus:</label>
                                            <div class="form-holder form-holder-2">
                                                <select name="subject" id="subject" class="form-control">
                                                    <option value="" disabled selected>Studiengang</option>
                                                    <option value="wiba">Bachelor WI</option>
                                                    <option value="wima">Master WI</option>
                                                    <option value="secm">Master SecMan</option>
                                                </select>
                                                <span class="select-btn">
														<i class="zmdi zmdi-chevron-down"></i>
												</span>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <label for="sumwinyear">Bitte w??hlen Sie das Studienjahr aus:</label>
                                            <div class="form-holder form-holder-2">
                                                <select name="sumwinyear" id="sumwinyear" class="form-control">
                                                    <option value="" disabled selected>Jahrgang</option>
                                                    <?php
                                                    $month = (int)date("m");
                                                    $year = (int)date("Y");
                                                    if ($month < 4) {
                                                        $valwin = ($year-1)."/".$year;
                                                        print "<option value=\"win".$valwin."\">Wintersemester ".$valwin."</option>";
                                                    }
                                                    elseif($month > 10){
                                                        $valwin = $year."/".($year+1);
                                                        print "<option value=\"win".$valwin."\">Wintersemester ".$valwin."</option>";
                                                    }
                                                    else {
                                                        $valwin = ($year-1)."/".$year;
                                                        print "<option value=\"sum".$year."\">Sommersemester ".$year."</option>";
                                                    }
                                                    ?>

                                                </select>
                                                <span class="select-btn">
                                                            <i class="zmdi zmdi-chevron-down"></i>
                                                   </span>
                                            </div>
                                        </div>
                                        <a class="btn--next" href="#">next step</a>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="form">
                                    <div class="input-group">

                                        <div class="form-row" id="wibawin" style="display:none">
                                            <label for="semwibawin">Bitte w??hlen Sie das entsprechende Semester aus:</label>
                                            <div class="form-holder form-holder-2">
                                                <select name="semwibawin" id="semwibawin" class="form-control" required="required">
                                                    <option value="" disabled selected>Wintersemester</option>
                                                    <option value="wiba1">1.Semester WI Bachelor</option>
                                                    <option value="wiba3">3.Semester WI Bachelor</option>
                                                    <option value="wiba5">5.Semester WI Bachelor</option>
                                                </select>
                                                <span class="select-btn">
													<i class="zmdi zmdi-chevron-down"></i>
												</span>

                                            </div>
                                        </div>

                                        <div class="form-row" id="wibasum" style="display:none">
                                            <label for="semwibasum">Bitte w??hlen Sie das entsprechende Semester aus:</label>
                                            <div class="form-holder form-holder-2">
                                                <select name="semwibasum" id="semwibasum" class="form-control" required="required">
                                                    <option value="" disabled selected>Sommersemester</option>
                                                    <option value="wiba2">2.Semester WI Bachelor</option>
                                                    <option value="wiba4">4.Semester WI Bachelor</option>
                                                </select>
                                                <span class="select-btn">
													<i class="zmdi zmdi-chevron-down"></i>
												</span>
                                            </div>
                                        </div>

                                        <div class="form-row" id="wimawin" style="display:none">
                                            <label for="semwimawin">Bitte w??hlen Sie das entsprechende Semester aus:</label>
                                            <div class="form-holder form-holder-2">
                                                <select name="semwimawin" id="semwimawin" class="form-control" required="required">
                                                    <option value="" disabled selected>Wintersemester</option>
                                                    <option value="wima1">1.Semester WI Master</option>
                                                    <option value="wima3">3.Semester WI Master</option>
                                                </select>
                                                <span class="select-btn">
													<i class="zmdi zmdi-chevron-down"></i>
												</span>
                                            </div>
                                        </div>

                                        <div class="form-row" id="wimasum" style="display:none">
                                            <label for="semwimasum">Bitte w??hlen Sie das entsprechende Semester aus:</label>
                                            <div class="form-holder form-holder-2">
                                                <select name="semwimasum" id="semwimasum" class="form-control" required="required">
                                                    <option value="" disabled selected>Sommersemester</option>
                                                    <option value="wima2">2.Semester WI Master</option>
                                                </select>
                                                <span class="select-btn">
													<i class="zmdi zmdi-chevron-down"></i>
												</span>
                                            </div>
                                        </div>

                                        <div class="form-row" id="secmwin" style="display:none">
                                            <label for="semsecmwin">Bitte w??hlen Sie das entsprechende Semester aus:</label>
                                            <div class="form-holder form-holder-2">
                                                <select name="semsecmwin" id="semsecmwin" class="form-control" required="required">
                                                    <option value="" disabled selected>Wintersemester</option>
                                                    <option value="secm1">1.Semester SecMan Master</option>
                                                    <option value="secm3">3.Semester SecMan Master</option>
                                                </select>
                                                <span class="select-btn">
													<i class="zmdi zmdi-chevron-down"></i>
												</span>
                                            </div>
                                        </div>

                                        <div class="form-row" id="secmsum" style="display:none">
                                            <label for="semsecmsum">Bitte w??hlen Sie das entsprechende Semester aus:</label>
                                            <div class="form-holder form-holder-2">
                                                <select name="semsecmsum" id="semsecmsum" class="form-control" required="required">
                                                    <option value="" disabled selected>Sommersemester</option>
                                                    <option value="secm2">2.Semester SecMan Master</option>
                                                </select>
                                                <span class="select-btn">
													<i class="zmdi zmdi-chevron-down"></i>
												</span>
                                            </div>
                                        </div>

                                        <a class="btn--next" href="#">next step</a>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab3">
                                <div class="form" id="databaseResult">
                                    <div class="input-group">
                                        <label for="workload">Workload-Erfassung</label>
                                        <span>
                                            <p style="margin-bottom: 10px;">F??r die Re-Akkreditierung der Studieng??nge ben??tigen wir Daten ??ber Ihren Arbeitsaufwand (Workload) im vergangenen Semester.
                                                Je nach zu vergebenen ECTS-Punkten planen wir durchschnittlich 90 bis 180 Stunden. Dabei wird ein ECTS-Punkt mit 30 Stunden Workload kalkuliert.</p>
                                            <p>Die gesamte Workload eines Moduls ergibt sich aus dem summarischen zeitlichen Aufwand f??r die verschiedenen Aktivit??ten im Modul. Dazu z??hlen:</p>
                                        </span>
                                        <ul style="margin-left: 50px; margin-top: 10px; margin-bottom: 10px">
                                            <li>Die Kontaktstunden durch Vorlesungen, Seminare und ??bungen,<br>z. B. 4 h x 15 Semesterwochen = 60 h bei einem Modul mit 4 SWS,
                                            </li>
                                            <li>Die Zeit f??r die Vor- und Nachbereitung von Vorlesungen, Seminaren und ??bungen,</li>
                                            <li>Weitere Zeiten f??r Hausaufgaben, Projektarbeit, Teamarbeit u. ??.,</li>
                                            <li>Zeitliche Aufw??nde f??r die Pr??fungsvorbereitung und die eigentliche Pr??fung.</li>
                                        </ul>
                                        <p style="margin-bottom: 10px">Bitte sch??tzen Sie bezogen auf das vergangene Semester Ihren zeitlichen Aufwand f??r jedes belegte Modul.
                                            Die dual Studierenden bitten wir zus??tzlich f??r die Transfermodule anzugeben, wie viele Stunden auf die Bearbeitung von Transferaufgaben im Betrieb angefallen sind.</p>
                                        <div id="workload">
                                        </div>
                                        <a type="submit" class="btn--next" href="#" id="finish">Confirm</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="currenttoken" id="currenttoken">
                        <input type="hidden" name="ip" id="ip" value="<?php echo $ip_address ?>">
                    </form>
                </div>

            </div>
        </div>
        <div id="cookie">
            Diese Website verwendet Cookies. Durch die Nutzung dieser Website akzeptieren Sie die Benutzung von Cookies.
            <a id="acceptCookies">OK</a>
        <div>
    </div>


    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/jquery-validate/jquery.validate.min.js"></script>
    <script src="vendor/bootstrap-wizard/bootstrap.min.js"></script>
    <script src="vendor/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>

    <script>
        document.getElementById('acceptCookies').onclick = function() {
            document.getElementById('cookie').remove();
        };

        //document.getElementById("check").onclick = function() {
        //
        //    $.ajax({
        //        url:'checkAccess.php',
        //        type:'post',
        //        data:$('#accesstoken').serialize(),
        //        success:function(output){
        //            //alert(output);
        //            if (output == "invalid") {
        //                alert("Falsches Token, Versuchen Sie nochmal!");
        //            } else {
        //                <?php //$_SESSION["access"] = true; ?>
        //                $("#currenttoken").val(output);
        //                $("#checksession").hide();
        //                $("#questionnaire").show();
        //            }
        //
        //        }
        //    });
        //
        //}

        document.getElementById("finish").onclick = function() {

            checkCookie();
            //document.getElementById("js-wizard-form").submit();
        }

        function setCookie(cname,cvalue,exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            var expires = "expires=" + d.toGMTString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkCookie() {
            var value=getCookie("value");
            $.ajax({
                url:'checkIP.php',
                type:'post',
                data: { ip:'<?php echo $ip_address ?>' },
                success:function(output){
                    if (output == "true" || value != "") {
                        alert("Sie haben den Fragebogen schon abgegeben!");
                    } else {
                        setCookie("value", true, 30);
                        $.ajax({
                            url:'insert.php',
                            type:'post',
                            data:$('#js-wizard-form').serialize(),
                            success:function(){
                                $('#js-wizard-form').html("<h1>Vielen Dank f??r Ihre Teilnahme!</h1>");
                            }
                        });
                    }
                }
            });
        }
    </script>

</body>
<style>
    #cookie {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 0.5em 1em;
        text-align: center;
        background: black;
        color: white;
    }

    #cookie #acceptCookies {
        margin-left: 20px;
        display: inline-block;
        padding: 0.25em 1em;
        border-radius: 5px;
        border: 1px solid white;
    }

    #cookie #acceptCookies:hover {
        color: black;
        background: white;
    }
</style>

</html>