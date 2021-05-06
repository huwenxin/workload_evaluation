
<?php

    header("Content-Type: application/json; charset=UTF-8");

    $sem = $_GET['id'];
    $studyProgram = substr($sem, 0, 4);
    $semester = substr($sem, 4, 1);


    $con = mysqli_connect("localhost","root", "root", "workload");
    if (!$con) {
        die('Verbindung schlug fehl: ' . mysqli_error($con));
    }

    $sql="SELECT courseID, courseName, compulsory, ECTS FROM courses WHERE studyProgram = \"".$studyProgram."\" AND semester = \"".$semester."\" ORDER BY compulsory DESC, ECTS DESC";
    $result = mysqli_query($con,$sql);

    if (!$result) {
        echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
        exit;
    }

    if ($studyProgram == "wiba") {

        echo "<div class=\"container\" id='course'><ul class=\"responsive-table\"><li class=\"table-header\">
            <div class=\"col col-1\"></div><div class=\"col col-2\">Stunden über das gesamte Semester</div></li>
            <li class='table-header'><div class='col col-1'>Lehrveranstaltung (ECTS 5)</div><div class='col-3'>100</div>
            <div class='col-3'>110</div><div class='col-3'>120</div><div class='col-3'>130</div><div class='col-3'>140</div>
            <div class='col-3'>150</div><div class='col-3'>160</div><div class='col-3'>170</div><div class='col-3'>180</div>
            <div class='col-3'>190</div><div class='col-3'>200</div></li>";

        $array = array(100, 110, 120, 130, 140, 150, 160, 170, 180, 190, 200);

            while($row = mysqli_fetch_array($result)) {
                echo "<li class=\"table-row\">";
                echo "<div class=\"col col-1\">" . $row['courseName'] . "</div>";

                foreach ($array as $key=>$value) {
                    echo "<div class=\"col col-3\">";
                    if ($key == 5 && $row['compulsory'] == '1') {
                        echo "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".$value."\" checked>";
                    } else {
                        echo "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".$value."\">";
                    }
                    echo "</div>";
                }

                echo "</li>";
            }
        echo "</ul></div>";

    } elseif ($studyProgram == "secm" && $semester == "3") {

            echo "<div class=\"container\" id='course'><ul class=\"responsive-table\"><li class=\"table-header\">
            <div class=\"col col-1\"></div><div class=\"col col-2\">Stunden über das gesamte Semester</div></li>
            <li class='table-header'><div class='col col-1'>Lehrveranstaltung (ECTS 3)</div><div class='col-3'>40</div>
            <div class='col-3'>50</div><div class='col-3'>60</div><div class='col-3'>70</div><div class='col-3'>80</div>
            <div class='col-3'>90</div><div class='col-3'>100</div><div class='col-3'>110</div><div class='col-3'>120</div>
            <div class='col-3'>130</div><div class='col-3'>140</div></li>";

            $array = array(40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140);

            while($row = mysqli_fetch_array($result)) {
                echo "<li class=\"table-row\">";
                echo "<div class=\"col col-1\">" . $row['courseName'] . "</div>";

                foreach ($array as $key=>$value) {
                    echo "<div class=\"col col-3\">";
                    echo "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".$value."\">";
                    echo "</div>";
                }
                echo "</li>";
            }
            echo "</ul></div>";

    } else {

        $str6 = "<div class=\"container\" id='course'><ul class=\"responsive-table\"><li class=\"table-header\">
            <div class=\"col col-1\"></div><div class=\"col col-2\">Stunden über das gesamte Semester</div></li>
            <li class='table-header'><div class='col col-1'>Lehrveranstaltung (ECTS 6)</div><div class='col-3'>130</div>
            <div class='col-3'>140</div><div class='col-3'>150</div><div class='col-3'>160</div><div class='col-3'>170</div>
            <div class='col-3'>180</div><div class='col-3'>190</div><div class='col-3'>200</div><div class='col-3'>210</div>
            <div class='col-3'>220</div><div class='col-3'>230</div></li>";

        $str3 = "<div class=\"container\" id='course'><ul class=\"responsive-table\">
            <li class='table-header'><div class='col col-1'>Lehrveranstaltung (ECTS 3)</div><div class='col-3'>40</div>
            <div class='col-3'>50</div><div class='col-3'>60</div><div class='col-3'>70</div><div class='col-3'>80</div>
            <div class='col-3'>90</div><div class='col-3'>100</div><div class='col-3'>110</div><div class='col-3'>120</div>
            <div class='col-3'>130</div><div class='col-3'>140</div></li>";

        $num3 = 0;

        $array3 = array(40, 50, 60, 70, 80, 90, 100, 110, 120, 130, 140);
        $array6 = array(130, 140, 150, 160, 170, 180, 190, 200, 210, 220, 230);

        while($row = mysqli_fetch_array($result)) {

            if ($row['ECTS'] == '3') {

                $num3 = $num3 + 1;

                $str3 = $str3 . "<li class=\"table-row\"><div class=\"col col-1\">" . $row['courseName'] . "</div>";
                foreach ($array3 as $key=>$value) {
                    $str3 = $str3 . "<div class=\"col col-3\">";

                    if ($key == 5 && $row['compulsory'] == '1') {
                        $str3 = $str3 . "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".$value."\" checked>";
                    } else {
                        $str3 = $str3 . "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".$value."\">";
                    }
                    $str3 = $str3 . "</div>";
                }
                $str3 = $str3 . "</li>";

            } else {

                $str6 = $str6 . "<li class=\"table-row\"><div class=\"col col-1\">" . $row['courseName'] . "</div>";
                foreach ($array6 as $key=>$value) {
                    $str6 = $str6 . "<div class=\"col col-3\">";
                    if ($key == 5 && $row['compulsory'] == '1') {
                            $str6 = $str6 . "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".$value."\" checked>";
                    } else {
                            $str6 = $str6 . "<input type=\"radio\" name=\"". $row['courseID'] ."\" value=\"".$value."\">";
                    }
                    $str6 = $str6 . "</div>";
                }
                $str6 = $str6 . "</li>";
            }

        }
        $str3 = $str3 . "</ul></div>";
        $str6 = $str6 . "</ul></div>";

        echo $str6;
        if ($num3 > 0) {
            echo $str3;
        }
    }


    mysqli_close($con);

?>