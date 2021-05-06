
<?php

header("Content-Type: application/json; charset=UTF-8");

$sem = $_GET['id'];
$studyProgram = substr($sem, 0, 4);
$semester = substr($sem, 4, 1);

$con = mysqli_connect("localhost","root", "root", "workload");
if (!$con) {
    die('Verbindung schlug fehl: ' . mysqli_error($con));
}

$getECTS = "SELECT DISTINCT ECTS FROM courses WHERE studyProgram = \"".$studyProgram."\" AND semester = \"".$semester."\" ORDER BY ECTS DESC";
$resultECTS = mysqli_query($con,$getECTS);
$arrECTS = array();

while($row = mysqli_fetch_array($resultECTS)) {
    array_push($arrECTS, $row['ECTS']);
}

$sql="SELECT courseID, courseName, compulsory, ECTS FROM courses WHERE studyProgram = \"".$studyProgram."\" AND semester = \"".$semester."\" ORDER BY compulsory DESC, ECTS DESC";
$result = mysqli_query($con,$sql);

if (!$result) {
    echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
    exit;
}

echo "<div style='overflow: auto'><div class=\"form-group row\" style='float: right;'><label style='padding-right: 10px; padding-top: 2px;'>Dual?</label><div class=\"switch-button switch-button-yesno\">
          <input type=\"checkbox\" name=\"dual\" id=\"dual\"><span><label for=\"dual\"></label></span></div></div></div>";

$tableArray = array();
for ($i = 0; $i < count($arrECTS); $i++) {

    $tableHeader = "";

    if ($i == 0) {
        $tableHeader .= "<div class=\"container\" id='course'><ul class=\"responsive-table\"><li class=\"table-header\">
            <div class=\"col col-1\"></div><div class=\"col col-2\" style='text-align: center'>Stunden über das Semester</div></li>";
    }

//        $tableHeader .= "<li class='table-header'><div class='col col-1'>Lehrveranstaltung (ECTS ".$arrECTS[$i].")</div><div class='col-3'>"
//            .($arrECTS[$i]*30-50)."</div><div class='col-3'>".($arrECTS[$i]*30-40)."</div><div class='col-3'>".($arrECTS[$i]*30-30).
//            "</div><div class='col-3'>".($arrECTS[$i]*30-20)."</div><div class='col-3'>".($arrECTS[$i]*30-10)."</div><div class='col-3'>"
//            .($arrECTS[$i]*30)."</div><div class='col-3'>".($arrECTS[$i]*30+10)."</div><div class='col-3'>".($arrECTS[$i]*30+20).
//            "</div><div class='col-3'>".($arrECTS[$i]*30+30)."</div><div class='col-3'>".($arrECTS[$i]*30+40)."</div><div class='col-3'>"
//            .($arrECTS[$i]*30+50)."</div></li>";
    $diff = $arrECTS[$i] * 5;

    $tableHeader .= "<li class='table-header'><div class='col col-4'>Belegt?</div><div class='col col-1'>Lehrveranstaltung (ECTS ".$arrECTS[$i].")</div>
        <div class='col-3'>".($arrECTS[$i]*30-($diff*2))."</div><div class='col-3'>".($arrECTS[$i]*30-$diff)."</div><div class='col-3'>"
        .($arrECTS[$i]*30)."</div><div class='col-3'>".($arrECTS[$i]*30+$diff)."</div><div class='col-3'>".($arrECTS[$i]*30+($diff*2)).
        "</div><div class='col col-5'>davon Transfer</div></li>";

//        $tableHeader .= "<li class='table-header'><div class='col col-1'>Lehrveranstaltung (ECTS ".$arrECTS[$i].")</div><div class='col-3'>"
//            .($arrECTS[$i]*2-3)."</div><div class='col-3'>".($arrECTS[$i]*2-2)."</div><div class='col-3'>".($arrECTS[$i]*2-1)."</div><div class='col-3'>"
//            .($arrECTS[$i]*2)."</div><div class='col-3'>".($arrECTS[$i]*2+1)."</div><div class='col-3'>".($arrECTS[$i]*2+2).
//            "</div><div class='col-3'>".($arrECTS[$i]*2+3)."</div></li>";

    array_push($tableArray, $tableHeader);
}

while($row = mysqli_fetch_array($result)) {

    $key = array_search($row['ECTS'], $arrECTS);

    $tableArray[$key] .= "<li class=\"table-row\"><div class='col col-4'><input type='checkbox' name='".$row['courseID']."attend' id='".$row['courseID']."attend'></div><div class=\"col col-1\">" . $row['courseName'] . "</div>";
    $x = 0;
    $diff = $arrECTS[$key] * 5;
    $base = $arrECTS[$key]*30-($diff*2);
    //$base = $arrECTS[$key]*2-3;

    do {
        $tableArray[$key] .= "<div class=\"col col-3 ". $row['courseID'] ."\" style='padding-left: 2px'>";
        if ($x == 2) {
            $tableArray[$key] .= "<input type=\"radio\" name=\"". $row['courseID'] ."\" id=\"". $row['courseID'] ."\" value=\"".($x * $diff + $base)."\" checked disabled>";
            //$tableArray[$key] .= "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".(($x + $base) * 15)."\" checked>";
        } else {
            $tableArray[$key] .= "<input type=\"radio\" name=\"". $row['courseID'] ."\" id=\"". $row['courseID'] ."\" value=\"".($x * $diff + $base)."\"  disabled>";
            //$tableArray[$key] .= "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".(($x + $base) * 15)."\">";
        }
        $tableArray[$key] .= "</div>";
        $x++;
    } while ($x < 5);

    $tableArray[$key] .= "<div class=\"col col-5\"><input type='number' name='".$row['courseID']."trans' id='".$row['courseID']."trans' min='0' max='".($diff * 2 + $base)."' disabled></div>";
    $tableArray[$key] .= "</li>";
}
$tableArray[$key] .= "</ul></div>";

foreach ($tableArray as $value) {
    echo $value;
    echo "<br>";
}

mysqli_close($con);

?>