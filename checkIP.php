<?php

    $ip = $_POST['ip'];

    $con = mysqli_connect("localhost","root", "root", "workload");
    if (!$con) {
        die('Verbindung schlug fehl: ' . mysqli_error($con));
    }

    $sql = "SELECT COUNT(ip_address) FROM ip WHERE ip_address = '$ip'";
    $result = mysqli_query($con,$sql);

    if ($result) {
        $val = mysqli_fetch_array($result);

        if ($val[0] != 0) {
            echo "true";
        } else {
            echo "false";
        }
    } else {
        echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
        exit;
    }


    mysqli_close($con);

?>
