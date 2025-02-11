<?php
    session_name("antallBesøk");
    session_start();

    if (isset($_SESSION['antallBesøk'])) {
        $_SESSION['antallBesøk']++;
    echo "<h3>Du har besøkt siden {$_SESSION['antallBesøk']} ganger!</h3>";
    } else {
        $_SESSION['antallBesøk'] = 1;
    echo "<h3>Dette er ditt første besøk!</h3>";
    }

    if (isset($_POST['restart'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
?>


<head>
    <title>04 - antall besøk</title>
</head>

<body>
    <br>
    <br>
    <br>
    <p>Avslutt gjeldene session og start på nytt: </p>
    <form action="" method="POST">
        <input type="submit" name="restart" value="Start på nytt">
    </form>


</body>