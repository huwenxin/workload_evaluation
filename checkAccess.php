<?php

    $accesstoken = $_POST['accesstoken'];

    $con = mysqli_connect("localhost","root", "root", "workload");
    if (!$con) {
        die('Verbindung schlug fehl: ' . mysqli_error($con));
    }

    $sql = "SELECT COUNT(id) FROM token WHERE id = '$accesstoken'";
    $result = mysqli_query($con,$sql);

    if ($result) {
        $val = mysqli_fetch_array($result);

        if ($val[0] != 0) {
            echo $accesstoken;
        } else {
            echo "invalid";
        }
    } else {
        echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
        exit;
    }


    mysqli_close($con);

?>
