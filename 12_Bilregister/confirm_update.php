<?php
include 'header.php';
include 'db_connection.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Ugyldig ID.");
}

$eier_id = $_SESSION['eier_id'];
$id = intval($_GET['id']);

// Hent bilinfo fra databasen
$sql = "SELECT * FROM bil WHERE id = ? AND eier_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id, $eier_id]);
$bil = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bil) {
    die("Bilen finnes ikke eller er ikke din.");
}

$registreringsnummer_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }

    $id = intval($_POST['id']);
    $registreringsnummer = trim($_POST['registreringsnummer']);
    $merke = trim($_POST['merke']);
    $modell = trim($_POST['modell']);
    $arsmodell = intval($_POST['arsmodell']);
    $farge = trim($_POST['farge']);;

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
                                <div class="form__group update">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($bil['id']); ?>">

                                    <label for="registreringsnummer">Reg-nr:</label>
                                    <input type="text" name="registreringsnummer" id="registreringsnummer" value="<?php echo htmlspecialchars($bil['registreringsnummer']); ?>" REQUIRED>
                                    <span><?php echo $registreringsnummer_err; ?></span>

                                    <label for="merke">Merke:</label>
                                    <input type="text" name="merke" id="merke" value="<?php echo htmlspecialchars($bil['merke']); ?>" REQUIRED>

                                    <!-- CSRF-token -->
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                    <label for="modell">Modell:</label>
                                    <input type="text" name="modell" id="modell" value="<?php echo htmlspecialchars($bil['modell']); ?>" REQUIRED>

                                    <label for="arsmodell">Årsmodell:</label>
                                    <input type="text" name="arsmodell" id="arsmodell" value="<?php echo htmlspecialchars($bil['arsmodell']); ?>" REQUIRED>

                                    <label for="farge">Farge:</label>
                                    <input type="text" name="farge" id="farge" value="<?php echo htmlspecialchars($bil['farge']); ?>" REQUIRED>
                                </div>
                                
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