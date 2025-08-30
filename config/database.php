<?php

$host = "localhost";
$username = "root";
$password = "";
$db_name = "library_system";

$connect = new mysqli($host, $username, $password, $db_name);

if ($connect->connect_error) {
    die("Connectin Failed: ". $connect->connect_error);
}

// echo "DATABASE CONNECTION SUCCEEDED. (LIBRARY).";