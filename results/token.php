<?php

    $num = $_POST['num'];

    $con = mysqli_connect("localhost","root", "root", "workload");
    if (!$con) {
        die('Verbindung schlug fehl: ' . mysqli_error($con));
    }

    echo "<table class='table'>";

    for ($i = 0; $i < $num; $i++) {
        echo "<tr>";
        echo "<td>".($i+1)."</td>";
        //$str = md5(uniqid(rand(), true));

        $str = getRandomString();

        $sql = "INSERT INTO token (id) VALUES ('$str')";
        $result = mysqli_query($con,$sql);
        if (!$result) {
            echo 'Konnte Abfrage nicht ausführen: ' . mysqli_error($con);
            exit;
        }
        echo "<td>".$str."</td>";
        echo "</tr>";
    }

    echo "</table>";

    mysqli_close($con);

    function getRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';

        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $string;
    }

?>