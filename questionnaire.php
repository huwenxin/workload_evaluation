<?php
session_start();
$_SESSION["access"] = false;
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
                <div class="card-body" id="checksession">
                    <div class="login form-row">
                        <label for="accesstoken" style='margin-left: 20px;'>Access Token</label></label>
                        <input class='input--style-1' type='text' id='accesstoken' name='accesstoken'>
                        <button type="submit" id="check" class="btn--next">Check</button>
                    </div>
                </div>

                <div class="card-body" id="questionnaire" style="display: none">
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
                                            <label for="subject">Bitte wählen Sie Ihren Studiengang aus:</label>
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
                                            <label for="sumwinyear">Bitte wählen Sie das Studienjahr aus:</label>
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
                                            <label for="semwibawin">Bitte wählen Sie das entsprechende Semester aus:</label>
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
                                            <label for="semwibasum">Bitte wählen Sie das entsprechende Semester aus:</label>
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
                                            <label for="semwimawin">Bitte wählen Sie das entsprechende Semester aus:</label>
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
                                            <label for="semwimasum">Bitte wählen Sie das entsprechende Semester aus:</label>
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
                                            <label for="semsecmwin">Bitte wählen Sie das entsprechende Semester aus:</label>
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
                                            <label for="semsecmsum">Bitte wählen Sie das entsprechende Semester aus:</label>
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
                                            <p style="margin-bottom: 10px;">Für die Re-Akkreditierung der Studiengänge benötigen wir Daten über Ihren Arbeitsaufwand (Workload) im vergangenen Semester.
                                                Je nach zu vergebenen ECTS-Punkten planen wir durchschnittlich 90 bis 180 Stunden. Dabei wird ein ECTS-Punkt mit 30 Stunden Workload kalkuliert.</p>
                                            <p>Die gesamte Workload eines Moduls ergibt sich aus dem summarischen zeitlichen Aufwand für die verschiedenen Aktivitäten im Modul. Dazu zählen:</p>
                                        </span>
                                        <ul style="margin-left: 50px; margin-top: 10px; margin-bottom: 10px">
                                            <li>Die Kontaktstunden durch Vorlesungen, Seminare und Übungen,<br>z. B. 4 h x 15 Semesterwochen = 60 h bei einem Modul mit 4 SWS,
                                            </li>
                                            <li>Die Zeit für die Vor- und Nachbereitung von Vorlesungen, Seminaren und Übungen,</li>
                                            <li>Weitere Zeiten für Hausaufgaben, Projektarbeit, Teamarbeit u. ä.,</li>
                                            <li>Zeitliche Aufwände für die Prüfungsvorbereitung und die eigentliche Prüfung.</li>
                                        </ul>
                                        <p style="margin-bottom: 10px">Bitte schätzen Sie bezogen auf das vergangene Semester Ihren zeitlichen Aufwand für jedes belegte Modul.
                                            Die dual Studierenden bitten wir zusätzlich für die Transfermodule anzugeben, wie viele Stunden auf die Bearbeitung von Transferaufgaben im Betrieb angefallen sind.</p>
                                        <div id="workload">
                                        </div>
                                        <a type="submit" class="btn--next" href="#" id="finish">Confirm</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="currenttoken" id="currenttoken">
                    </form>
                </div>





            </div>
        </div>
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

        document.getElementById("check").onclick = function() {

            $.ajax({
                url:'checkAccess.php',
                type:'post',
                data:$('#accesstoken').serialize(),
                success:function(output){
                    //alert(output);
                    if (output == "invalid") {
                        alert("Falsches Token, Versuchen Sie nochmal!");
                    } else {
                        <?php $_SESSION["access"] = true; ?>
                        $("#currenttoken").val(output);
                        $("#checksession").hide();
                        $("#questionnaire").show();
                    }

                }
            });

        }

        document.getElementById("finish").onclick = function() {

            // $.ajax({
            //     url:'insert.php',
            //     type:'post',
            //     data:$('#js-wizard-form').serialize(),
            //     success:function(){
            //         $('#js-wizard-form').html("<h1>Vielen Dank für Ihre Teilnahme!</h1>");
            //     }
            // });

            document.getElementById("js-wizard-form").submit();

        }
    </script>

</body>

</html>