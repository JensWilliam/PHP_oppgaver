<?php
include 'header.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
}


include 'db_connection.php';

// Henter brukernavn eier_id, navn og epost fra databasen
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

// Sjekk om det er et søk
$sok = isset($_GET['sok']) ? trim($_GET['sok']) : '';

// Gjør søkestrengen små bokstaver
$sok = strtolower($sok);

// Standard SQL for å hente alle biler
if (empty($sok)) {
    $sql = "SELECT bil.*, eier.navn AS eier FROM bil JOIN eier ON bil.eier_id = eier.id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
} else {
    // SQL for søk – treff kun i starten av et ord
    $sql = "SELECT bil.*, eier.navn AS eier 
            FROM bil 
            JOIN eier ON bil.eier_id = eier.id 
            WHERE LOWER(bil.registreringsnummer) LIKE :sok_start 
            OR LOWER(bil.merke) LIKE :sok_start 
            OR LOWER(eier.navn) LIKE :sok_start";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'sok_start' => strtolower($sok) . '%', // Treffer kun starten av ord
    ]);
}


$biler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// antall treff / biler
$antall_biler = count($biler);

function highlightMatch($text, $search) {
    if (!$search) return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
    $escapedSearch = preg_quote($search, '/'); // Escape spesielle tegn
    return preg_replace("/($escapedSearch)/i", "<span class='highlight'>$1</span>", htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}
?>

        <main>
            <div class="wrapper">
                
                <div class="velkommen">
                    <h2>Velkommen til GearHub</h2>
                    <p>Her kan du finne alle biler registrert hos oss</p>
                </div>
                <div class="layout__form bilregister">
                        

                        <form class="form bilregister" action="index.php" method="GET">
                            <div class="form__group bilregister">
                                <label for="sok">Søk etter RegNr, Merke eller Navn </label>
                                <input type="text" name="sok" id="sok" value="<?php echo htmlspecialchars($sok) ?>" placeholder="e.g. AB12345 | Ford | Ola Normann" required>
                                
                            </div>

                            <div class="knapp">
                                <button class="btn__form bilregister" type="submit">Søk</button>
                            </div>                            
                        </form>
                        <div class="layout__formFooter hjem">
                            
                            
                            <?php if (!empty($sok)): ?>
                                <p><?php echo $antall_biler; ?> treff</p>
                                                             
                                <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>">Fjern søk</a>                               
                            <?php endif; ?>
                        </div>
                    </div>

                <div class="layout">
                    
                    <div class="grid-logg biler">
                        <div class="grid-row biler h">
                            
                            <div class="grid-item-h"><p>Regnr</p></div>
                            <div class="grid-item-h"><p>Merke</p></div>
                            <div class="grid-item-h"><p>Modell</p></div>
                            <div class="grid-item-h"><p>Årsmodell</p></div>
                            <div class="grid-item-h"><p>Farge</p></div>
                            <div class="grid-item-h"><p>Eier</p></div>
                        
                        </div>
                        <?php foreach ($biler as $bil): ?>
                            <div class="grid-row biler">
                                <div class="grid-item-logg"><p><?php echo highlightMatch($bil['registreringsnummer'] ?? '', $sok); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo highlightMatch($bil['merke'] ?? '', $sok); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo highlightMatch($bil['modell'] ?? '', $sok); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo highlightMatch($bil['arsmodell'] ?? '', $sok); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo highlightMatch($bil['farge'] ?? '', $sok); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo highlightMatch($bil['eier'] ?? '', $sok); ?></p></div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

            </div>
        </main>
    </body>
</html>