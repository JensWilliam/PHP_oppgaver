<?php
include 'header.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';

$registreringsnummer = $merke = $modell = $arsmodell = $farge = $eier_id = "";

$regnr_err = ""; 
$regnr = "";

// Sjekker at skjemaet er sendt via GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['regnr'])) {
    $regnr = trim($_GET['regnr']);

    $sql = "SELECT bil.*, eier.navn AS eier_navn 
            FROM bil 
            JOIN eier ON bil.eier_id = eier.id 
            WHERE bil.registreringsnummer = ?
            ";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$regnr]);
    $bil = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($bil) {
        $registreringsnummer = $bil['registreringsnummer'];
        $merke = $bil['merke'];
        $modell = $bil['modell'];
        $arsmodell = $bil['arsmodell'];
        $farge = $bil['farge'];
        $eier_navn = $bil['eier_navn']; // Fra JOIN med eier-tabellen
        $regnr_err = "";
    } else {
        $regnr_err = "Fant ingen biler med reg-nummer: " . htmlspecialchars($regnr, ENT_QUOTES, 'UTF-8');
    }

}
?>
        <main>
            <div class="wrapper">
                <div class="velkommen">
                    <h2>GearHub's bilregister</h2>
                    <p>Her kan du se informasjon om biler og eiere registrert hos oss, eller registrere en ny bil.</p>
                </div>

                <div class="layout">
                    <div class="layout__form bilregister">
                        <div class="layout__formHeader">
                            <h3>Søk i bilregisteret</h3>
                        </div>

                        <form class="form bilregister" action="bilregister.php" method="GET">
                            <div class="form__group bilregister">
                                <label for="regnr">Søk etter registreringsnummer:</label>
                                <input type="text" name="regnr" id="regnr" placeholder="e.g. AB12345" required>
                                <span><?php echo $regnr_err; ?></span>
                            </div>

                            <div class="knapp">
                                <button class="btn__form bilregister" type="submit">Søk</button>
                            </div>                            
                        </form>
                        <?php if (!empty($registreringsnummer)): ?>
                            <div class="layout__formFooter">
                                <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>">Fjern søk</a>
                            </div>
                        <?php else: ?>
                            <div class="layout__formFooter">
                                <p>Eller <a href="ny_bil.php">registrer ny bil</a></p>
                            </div>
                            
                        <?php endif; ?>
                    </div>
                     
                    <?php if (!empty($registreringsnummer)): ?>
                        <div class="layout__form bilregister">

                            <div class="layout__formHeader">
                                <h3>Søk-resultat: <?php echo htmlspecialchars($regnr); ?></h3>
                            </div>
                            

                            <div class="grid-container">
                                <!-- Partall er resultat av søk -->
                                <div class="grid-item"><p>Registreringsnummer:</p></div>
                                <div class="grid-item"><p><?php echo htmlspecialchars($registreringsnummer ?? '', ENT_QUOTES, 'UTF-8'); ?></p></div>

                                <div class="grid-item"><p>Merke:</p></div>
                                <div class="grid-item"><p><?php echo htmlspecialchars($merke ?? '', ENT_QUOTES, 'UTF-8'); ?></p></div>

                                <div class="grid-item"><p>Modell:</p></div>
                                <div class="grid-item"><p><?php echo htmlspecialchars($modell ?? '', ENT_QUOTES, 'UTF-8'); ?></p></div>

                                <div class="grid-item"><p>Årsmodell:</p></div>
                                <div class="grid-item"><p><?php echo htmlspecialchars($arsmodell ?? '', ENT_QUOTES, 'UTF-8'); ?></p></div>

                                <div class="grid-item"><p>Farge:</p></div>
                                <div class="grid-item"><p><?php echo htmlspecialchars($farge ?? '', ENT_QUOTES, 'UTF-8'); ?></p></div>

                                <div class="grid-item"><p>Eier:</p></div>
                                <div class="grid-item"><p><?php echo htmlspecialchars($eier_navn ?? '', ENT_QUOTES, 'UTF-8'); ?></p></div>
                            </div>                  
                        </div>   
                    <?php endif; ?>
                        

                </div>

                


            </div>
        </main>
    </body>
</html>