<?php
session_name("kjeledyr");
session_start();


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genererer et sikkert token
}
?>

<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kjæledyr CRUD</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <ul>
            <li class="logo">
                <img src="bilder/hund-removebg-preview.png" alt="Logo">
            </li>
            <li>Header item 2</li>
            <li>Hesder item 3</li>
        </ul>
    </nav>    
