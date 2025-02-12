<?php
include 'header.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
}


include 'db_connection.php';

// Henter brukerens data fra databasen, og henter fullt navn
$sql = "SELECT brukere.brukernavn, brukere.eier_id, eier.navn AS bruker_navn , eier.epost AS eier_epost
        FROM brukere 
        JOIN eier ON brukere.eier_id = eier.id
        WHERE brukere.brukernavn = ?
        ";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['brukernavn']]);
$bruker = $stmt->fetch(PDO::FETCH_ASSOC);

if ($bruker) {
    foreach ($bruker as $key => $value) {
        $_SESSION[$key] = $value; // Lagrer brukerens data i session-variabler
    }
} 
?>

        <main>
            <div class="wrapper">
                <div class="velkommen">
                    <h2>Velkommen til GearHub</h2>
                    <p>Her kan du finne biler registrert hos oss</p>
                </div>

                <div class="layout">
                    <!-- Hvis brukeren er innlogget, vis brukernavnet -->
                    <?php if (isset($_SESSION['innlogget']) && $_SESSION['innlogget'] === true): ?>

                        <div class="innhold">
                            <div class="innhold__tekst">
                                <h3>Hei, <?php echo $_SESSION['bruker_navn']; ?>.</h3>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </main>
    </body>
</html>