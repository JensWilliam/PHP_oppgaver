<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UV-02 - hvem er eldst?</title>
</head>
<body>



<form action="" method="GET">
    <input type="text" name="name1" placeholder="Navn 1"><br>
    <input type="text" name="alder1" placeholder="Alder 1"><br>
    <input type="text" name="name2" placeholder="Navn 2"><br>
    <input type="text" name="alder2" placeholder="Alder 2"><br>

    <input type="submit" name="submit" value="Submit"><br>



<?php

    if (isset($_GET['submit'])) {

        echo $_GET['name1'] . " er " . $_GET['alder1'] . " år gammel.<br>";
        echo $_GET['name2'] . " er " . $_GET['alder2'] . " år gammel.<br>";

        if ($_GET['alder1'] > $_GET['alder2']) {
            echo $_GET['name1'] . " er eldre enn " . $_GET['name2'];
        } elseif ($_GET['alder1'] < $_GET['alder2']) {
            echo $_GET['name2'] . " er eldre enn " . $_GET['name1'];
        } else {
            echo $_GET['name1'] . " og " . $_GET['name2'] . " er like gamle";
        }
        
    } else {
        echo "Fyll inn navn og alder";
    }
        
?>
    
</body>
</html>