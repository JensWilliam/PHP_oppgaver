<?php
session_name("database_innlogging");
session_start();

//if (!isset($_SESSION['csrf_token'])) {
//    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genererer et sikkert token
//}

if (isset($_SESSION['innlogget']) && $_SESSION['innlogget'] === true) {
    echo "<p>Du er logget inn!</p>";
} else {
    echo "<p>Du er ikke logget inn!</p>";
}
?>

<!DOCTYPE html>

<html>
    <head>
        <title>11 - Innlogging Database</title>
    </head>

    <body>
        <h1>Php Database Innlogging</h1>
        <p>Oppgave i php uke 5</p><br><br>

        <p>Vennligst logg inn:</p>
        <form action="login.php" method="POST">
            <label for="brukernavn">Brukernavn:</label>
            <input type="text" name="brukernavn" id="brukernavn" required>
            <label for="passord">Passord:</label>
            <input type="password" name="passord" id="passord" required>

            <!-- CSRF-token -->
            <!--<input type="hidden" name="csrf_token" value="<?php //echo $_SESSION['csrf_token']; ?>">-->

            <input type="submit" value="Logg inn">
        </form>

        <?php
        if (isset($_SESSION['innlogget']) && $_SESSION['innlogget'] === true) {
            echo "<p>Du er logget inn som <strong>" . htmlspecialchars($_SESSION['brukernavn']) . "</strong></p><br>";

            echo '<form method="POST" action="logout.php">
                    <input type="submit" name="loggut" value="Logg ut">
                </form>';
        }
        ?>

    </body>

</html>