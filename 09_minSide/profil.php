<?php
session_start();
?>

<body>

    <h1>Din Profil</h1>
    <p>Her kan du se på profilen din, og endre infoen om du ønsker.</p><br><br>

</body>


<?php


// viser brukerens informasjon hvis den er satt, ellers vises standardverdier

if (!isset($_SESSION['name'])) {
    echo "<p>Brukernavn: ola_normann!</p>";
    echo "<p>Alder: 21</p>";
    echo "<p>E-post: ola@normann.no</p><br>";
} else {
    echo "<p>Brukernavn: " . htmlspecialchars($_SESSION['name']) . "</p>";
    echo "<p>Alder: " . $_SESSION['alder'] . "</p>";
    echo "<p>E-post: " . htmlspecialchars($_SESSION['mail']) . "</p> <br><br>";
    echo "<p>Antall oppdateringer: " . $_SESSION['oppdatert'] . "</p>";
}





// knapp for å redigere informasjonen i profilen
echo '<form action="profil.php" method="POST">
        <input type="submit" name="rediger" value="Oppdater informasjon">
    </form> <br><br>';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rediger'])) {
    header("Location: rediger.php");
    exit();
}

// resetter session variabler og sender brukeren tilbake til index.php

echo '<form action="profil.php" method="POST">
            <label for="reset">Vil du nullstille og slette endrede info?</label>
            <input type="submit" name="reset" value="JA">
        </form>';


if (isset($_POST['reset'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
