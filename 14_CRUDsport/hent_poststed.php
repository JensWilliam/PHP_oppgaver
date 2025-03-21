<?php
// hent_poststed.php

// Sjekk om postnummeret er sendt via GET
if (isset($_GET['postal_code'])) {
    $postal_code = $_GET['postal_code'];

    require 'db_connection.php';

    try {
        // Forbered SQL-spørringen for å hente poststed basert på postnummer
        $stmt = $conn->prepare('SELECT kommunenavn FROM norske_postnummer WHERE postnummer = :postal_code');
        $stmt->bindParam(':postal_code', $postal_code, PDO::PARAM_STR);
        $stmt->execute();

        // Sjekk om vi fant et poststed for dette postnummeret
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Returner poststedet
            echo $row['kommunenavn'];
        } else {
            echo ''; // Ingen treff for postnummeret
        }
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>