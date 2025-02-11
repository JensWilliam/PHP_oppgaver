<?php
session_start();
?>


<body>

    <form action="rediger.php" method="POST">
        <label for="name">Brukernavn: </form><br>
        <input type="text" name="name" id="name"  required><br>

        <label for="alder">Alder: </form><br>
        <input type="number" name="alder" id="alder" min="1" max="120" required><br>

        <label for="mail">E-post adresse: </form><br>
        <input type="text" name="mail" id="mail" required><br><br>

        <input type="submit" name="lagre" value="Lagre endringer">
    </form> 

</body>

<?php

// validerer input og lagrer i session variabler, gir feilmelding hvis input er ugyldig

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["lagre"])) {
    $name = trim($_POST["name"]);
    $alder = trim($_POST["alder"]);
    $mail = trim($_POST["mail"]);

    if (preg_match_all('/[\W_]/', $name) < 2) {
        echo "Brukernavn må inneholde minst to forskjellige spesialtegn.";
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        echo "Ugyldig e-postadresse.";
    } elseif ($alder < 1 || $alder > 120) {
        echo "Alder må være mellom 1 og 120.";
    } else {
        $_SESSION["name"] = $name;
        $_SESSION["alder"] = $alder;
        $_SESSION["mail"] = $mail;
        $_SESSION["oppdatert"] +=1;

        header("Location: profil.php");
        exit();
    }
}

?>