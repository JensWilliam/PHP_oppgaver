<?php
$server = "localhost";
$database = "kjeledyrcrud";
$dbUser = "root";
$dbPassword = "";

// Sett opp tilkoblingen
try {
    $conn = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Klarte ikke å koble til databasen: " . $e->getMessage());
}
?>