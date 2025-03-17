<?php
include 'header.php';
include 'db_connection.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
}

// Hent type og id fra URL-en
$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;

// Sjekk at ID er et tall
if (!is_numeric($id)) {
    die("Ugyldig ID.");
}


// Hent data fra databasen basert på type

if ($type === 'eier') {
    $sql = "SELECT eier.*, brukere.brukernavn, brukere.status FROM eier LEFT JOIN brukere ON eier.id = brukere.eier_id WHERE eier.id = ?";
    if ($_SESSION['status'] != 'administrator') {
        die("Du har ikke tilgang til denne siden.");
    }
} elseif ($type === 'bil') {
    $sql = "SELECT * FROM bil WHERE id = ?";
} elseif ($type === 'profil') {
    $sql = "SELECT eier.*, brukere.brukernavn, brukere.status FROM eier LEFT JOIN brukere ON eier.id = brukere.eier_id WHERE eier.id = ?";
    if ($id != $_SESSION['eier_id']) {
        die("Du har ikke tilgang til denne siden.");
    }
} else {
    die("Ukjent type.");
}



// Kjør SQL-spørringen på en sikker måte
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Fant ingen data for denne ID-en.");
}

// feilmelding for bil og eier skjema
$registreringsnummer_err = "";
$eier_navn_err = ""; 
$brukernavn_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }



    // Felles for alle typer
    $id = intval($_POST['id']);


    if ($type === 'bil') {
        $registreringsnummer = trim($_POST['registreringsnummer']);
        $merke = trim($_POST['merke']);
        $modell = trim($_POST['modell']);
        $arsmodell = intval($_POST['arsmodell']);
        $farge = trim($_POST['farge']);

        // Sjekk om bilen allerede finnes
        $sql = "SELECT * FROM bil WHERE registreringsnummer = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$registreringsnummer, $id]);
        $eksisterende_bil = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($eksisterende_bil) {
            $registreringsnummer_err = "Bilen er allerede registrert.";
        } else {
            try {
                // Oppdater bilinfo
                $sql = "UPDATE bil SET registreringsnummer = ?, merke = ?, modell = ?, arsmodell = ?, farge = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$registreringsnummer, $merke, $modell, $arsmodell, $farge, $id]);

                // Forny CSRF-token ved vellykket innlogging
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header("Location: profil.php");
                exit();
            } catch (PDOException $e) {
                $registreringsnummer_err = "Kunne ikke oppdatere bilen. Prøv igjen.";
            }
        }
    } elseif ($type === 'eier') {
        // Håndter oppdatering av eier
        $navn = trim($_POST['navn']);
        $epost = trim($_POST['epost']);
        $status = trim($_POST['status']);
        $brukernavn = trim($_POST['brukernavn']);

        // Sjekk om eierens e-post er i bruk av en annen bruker
        $sql = "SELECT * FROM eier WHERE epost = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$epost, $id]);
        $eksisterende_eier = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql2 = "SELECT * FROM brukere WHERE brukernavn = ? AND eier_id != ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$brukernavn, $id]);
        $eksisterende_brukernavn = $stmt2->fetch(PDO::FETCH_ASSOC);


        if ($eksisterende_eier || $eksisterende_brukernavn) {
            $eier_navn_err = "E-posten er allerede i bruk.";
            $brukernavn_err = "Brukernavnet er allerede i bruk.";
        } else {
            try {
                // Oppdater eierinfo
                $sql = "UPDATE eier JOIN brukere ON eier.id = brukere.eier_id SET eier.navn = ?, eier.epost = ?, brukere.status = ?, brukere.brukernavn = ? WHERE eier.id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$navn, $epost, $status, $brukernavn, $id]);

                // Forny Session-variabel for brukernavn tilfelle det endres.
                $_SESSION['brukernavn'] = htmlspecialchars($brukernavn);

                // Forny CSRF-token ved vellykket innlogging
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header("Location: settings.php");
                exit();
            } catch (PDOException $e) {
                $eier_navn_err = "Kunne ikke oppdatere eieren. Prøv igjen.";
            }
        }
    } elseif ($type === 'profil') {
        // Håndter oppdatering av eier
        $navn = trim($_POST['navn']);
        $epost = trim($_POST['epost']);
        $brukernavn = trim($_POST['brukernavn']);

        // Sjekk om eierens e-post er i bruk av en annen bruker
        $sql = "SELECT * FROM eier WHERE epost = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$epost, $id]);
        $eksisterende_eier = $stmt->fetch(PDO::FETCH_ASSOC);

        // Sjekk om brukernavnet er i bruk av en annen bruker
        $sql2 = "SELECT * FROM brukere WHERE brukernavn = ? AND eier_id != ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute([$brukernavn, $id]);
        $eksisterende_brukernavn = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($eksisterende_eier || $eksisterende_brukernavn) {
            $eier_navn_err = "Allerede i bruk.";
            $brukernavn_err = "Brukernavnet er allerede i bruk.";
        } else {
            try {
                // Oppdater eierinfo
                $sql = "UPDATE eier JOIN brukere ON eier.id = brukere.eier_id SET eier.navn = ?, eier.epost = ?, brukere.brukernavn = ? WHERE eier.id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$navn, $epost, $brukernavn, $id]);

                // Forny Session-variabel for brukernavn tilfelle det endres.
                $_SESSION['brukernavn'] = htmlspecialchars($brukernavn);

                // Forny CSRF-token ved vellykket innlogging
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                header("Location: profil.php");
                exit();
            } catch (PDOException $e) {
                $eier_navn_err = "Kunne ikke oppdatere. Prøv igjen.";
            }
        }
    } else {
        die("Ukjent type.");
    }

    

    
}

?>
        <main>
            <div class="wrapper">
                <div class="velkommen">
                    
                </div>

                <div class="layout">
                        <div class="layout__form">
                            <div class="layout__formHeader">
                                <h3>Oppdater Bil-informasjon</h3>
                                
                            </div>
                            <form class="form" method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                                <?php if ($type === 'bil'): ?>
                                    <div class="form__group update">

                                        <label for="registreringsnummer">Reg-nr:</label>
                                        <input type="text" name="registreringsnummer" id="registreringsnummer" value="<?php echo htmlspecialchars($data['registreringsnummer']); ?>" REQUIRED>
                                        <span><?php echo $registreringsnummer_err; ?></span>

                                        <label for="merke">Merke:</label>
                                        <input type="text" name="merke" id="merke" value="<?php echo htmlspecialchars($data['merke']); ?>" REQUIRED>

                                        <!-- CSRF-token -->
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <label for="modell">Modell:</label>
                                        <input type="text" name="modell" id="modell" value="<?php echo htmlspecialchars($data['modell']); ?>" REQUIRED>

                                        <label for="arsmodell">Årsmodell:</label>
                                        <input type="text" name="arsmodell" id="arsmodell" value="<?php echo htmlspecialchars($data['arsmodell']); ?>" REQUIRED>

                                        <label for="farge">Farge:</label>
                                        <input type="text" name="farge" id="farge" value="<?php echo htmlspecialchars($data['farge']); ?>" REQUIRED>
                                    </div>
                                <?php elseif ($type === 'eier'): ?>
                                    <div class="form__group update">
                                        <label for="navn">Navn:</label>
                                        <input type="text" name="navn" id="navn" value="<?php echo htmlspecialchars($data['navn']); ?>" REQUIRED>
                                        <span><?php echo $eier_navn_err; ?></span>

                                        <label for="epost">E-post:</label>
                                        <input type="text" name="epost" id="epost" value="<?php echo htmlspecialchars($data['epost']); ?>" REQUIRED>

                                        <label for="status">Status:</label>
                                        <input type="text" name="status" id="status" value="<?php echo htmlspecialchars($data['status']); ?>" REQUIRED>
                                        

                                        <!-- CSRF-token -->
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <label for="brukernavn">Brukernavn:</label>
                                        <input type="text" name="brukernavn" id="brukernavn" value="<?php echo htmlspecialchars($data['brukernavn']); ?>" REQUIRED>
                                    </div>
                                <?php elseif ($type === 'profil'): ?>
                                    <div class="form__group update">
                                        <label for="navn">Navn:</label>
                                        <input type="text" name="navn" id="navn" value="<?php echo htmlspecialchars($data['navn']); ?>" REQUIRED>
                                        <span><?php echo $eier_navn_err; ?></span>

                                        <label for="brukernavn">Brukernavn:</label>
                                        <input type="text" name="brukernavn" id="brukernavn" value="<?php echo htmlspecialchars($data['brukernavn']); ?>" REQUIRED>
                                        
                                        <label for="epost">E-post:</label>
                                        <input type="text" name="epost" id="epost" value="<?php echo htmlspecialchars($data['epost']); ?>" REQUIRED>

                                        <!-- CSRF-token -->
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        
                                    </div>
                                <?php endif; ?>
                                
                                <div class="form bilregister">
                                    <div class="knapp">
                                        <button class="btn__form" type="submit">Oppdater</button>
                                    </div>

                                    <div class="knapp">
                                        <a class="btn__form avbryt" href="profil.php">Avbryt</a>
                                    </div>
                                </div>      
                            </form>
                        </div>

                </div>

            </div>
        </main>
    </body>
</html>