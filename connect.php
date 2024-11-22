<?php

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "contactdb";

// Create database connection
mysqli_connect($servername, $username, $password, $dbname);

$conn = new mysqli($servername, $username, $password, $dbname);
