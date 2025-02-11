<?php
session_name('Bilregister');
session_start();


// Sjekk CSRF-token for ekstra sikkerhet
    if (!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }

    // Fjern alle sesjonsvariabler
    $_SESSION = [];

    // Ødelegg selve sesjonen
    session_destroy();

    // Forny CSRF-token i tilfelle brukeren logger inn igjen
    session_start();
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    // Omdiriger brukeren tilbake til forsiden
    header("Location: index.php");
    exit();

?>