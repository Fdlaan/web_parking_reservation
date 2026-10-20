<?php
    $servername = "localhost";
    $username = "root";
    $password = "Brian1703.";
    $database = "parking_db";

    $conn = mysqli_connect (
        $servername,
        $username,
        $password,
        $database
    );

    // tar kalau udah di test dan bener, apus aje dul
    if (!$conn) {
        error_log('Database connection failed: ' . mysqli_connect_error());

        http_response_code(500);
        exit('Database connection was fail, check your configuration database.');
    } else {
        echo 'Database connected successfully!';
    }

    mysqli_set_charset($conn, 'utf8mb4');

?>