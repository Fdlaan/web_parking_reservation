<?php
    $servername = "localhost";
    $database = "parking_db";
    $username = "root";
    $password = "";
    $conn = mysqli_connect($servername, $password, $database);

    if (!$conn) {
        die("Fail Connection: " . mysqli_connect_error()); // ini nanti di hapus
    } else {
        echo ("Successfully Connection");
    }
?>