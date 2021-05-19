<?php

    $ip = $_POST['ip'];
    $subject = $_POST['subject'];
    $semyear = $_POST['sumwinyear'];

    $semkey = "sem" . $subject . substr($semyear, 0, 3);
    $studyProSem = $_POST[$semkey];

    $con = mysqli_connect("localhost","root", "root", "workload");
    if (!$con) {
        die('Verbindung schlug fehl: ' . mysqli_error($con));
    }

    $sqlip="INSERT INTO ip (ip_address) VALUES ('$ip')";
    $result = mysqli_query($con,$sqlip);
    if (!$result) {
        echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
        exit;
    }


    $sqlNum = "SELECT MAX(number) FROM workload WHERE semYear = '$semyear' AND studyProSem = '$studyProSem'";
    if ($result=mysqli_query($con,$sqlNum)) {
        $val = mysqli_fetch_array($result)[0];
        if ($val != null) {
            $number = intval($val) + 1;
        } else {
            $number = 1;
        }
    }

    $attend = '';
    $dual = 0;
    foreach ($_POST as $key => $value) {
        //echo "Field:".$key."-is-".$value."<br>";
        if ($key != "subject" && $key != "sumwinyear" && $key != $semkey) {

            if ($key == "dual") {
                $dual = 1;
            }

           if (substr($key, -6) == "attend") {
               $attend = substr($key, 0, strlen($key)-6);
               $workload = $_POST[$attend];
               $transfer = $_POST[$attend."trans"];
               $transfer = $transfer ? "'$transfer'" : "NULL";

               $sql="INSERT INTO workload (courseID, semYear, workload, studyProSem, number, transfer, duale) VALUES ('$attend', '$semyear', '$workload', '$studyProSem', '$number', $transfer, '$dual')";
               $result = mysqli_query($con,$sql);
               if (!$result) {
                   echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
                   exit;
               }
//               else {
//                   $accesstoken = $_POST['currenttoken'];
//
//                   $checkDup = "SELECT COUNT(id) FROM workload.token WHERE id='$accesstoken'";
//                   if ($result=mysqli_query($con,$checkDup)) {
//                       $n = mysqli_fetch_array($result)[0];
//                       if ($n > 1) {
//                           $sqlDel="DELETE FROM token WHERE id='$accesstoken' ORDER BY date LIMIT 1";
//                           if (!mysqli_query($con,$sqlDel)) {
//                               echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
//                               exit;
//                           }
//                       } else {
//                           $sqlDel="DELETE FROM token where id = '$accesstoken'";
//                           if (!mysqli_query($con,$sqlDel)) {
//                               echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
//                               exit;
//                           }
//                       }
//                   }
//
//               }

           }

        }
    }

    mysqli_close($con);

?>