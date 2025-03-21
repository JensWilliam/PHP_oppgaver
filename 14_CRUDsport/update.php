<?php
include 'meny.php';
include 'db_connection.php';

// Hent type og id fra URL-en
$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;

// Sjekk at ID er et tall
if (!is_numeric($id)) {
    die("Ugyldig ID.");
}

// Hent medlemmet fra databasen
$sql = "SELECT * FROM medlem WHERE m_nr = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$medlem = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medlem) {
    die("Fant ingen data for denne ID-en.");
}

$epost_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }

    $id = intval($_POST['id']);

    if ($type === 'endre') {
        $fornavn = trim($_POST['fornavn']);
        $etternavn = trim($_POST['etternavn']);
        $adresse = trim($_POST['adresse']);
        $postnr = trim($_POST['postnr']);
        $poststed = trim($_POST['poststed']);
        $fodt = trim($_POST['fodt']);
        $telefon = trim($_POST['telefon']);
        $mail = trim($_POST['mail']);
        $betalt = trim($_POST['betalt']);

        // sjekker om e-post allerede er i bruk
        $sql = "SELECT * FROM medlem WHERE mail = ? AND m_nr != ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$mail, $id]);
        if ($stmt->rowCount() > 0) {
            $epost_err = "Eposten er allerede tatt.";
        }

        if (empty($epost_err)) {
            $sql = "UPDATE medlem SET fornavn = ?, etternavn = ?, adresse = ?, postnr = ?, poststed = ?, fodt = ?, telefon = ?, mail = ?, betalt = ? WHERE m_nr = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$fornavn, $etternavn, $adresse, $postnr, $poststed, $fodt, $telefon, $mail, $betalt, $id]);

            header("Location: medlemmer.php");
            exit();
        }

    }
}
?>
        <div class="container-lg bg-primary mt-3">
            <p class="fs-1 text-center">Oppdater <?php echo $medlem['fornavn']; ?> sin info</p>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="fornavn" class="form-label">Fornavn</label>
                            <input type="text" class="form-control" id="fornavn" name="fornavn" value="<?php echo htmlspecialchars($medlem['fornavn']); ?>" required>
                        </div>
                        <div class="mb-2">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo htmlspecialchars($medlem['adresse']); ?>" required>
                        </div>
                        <div class="mb-2">
                            <label for="poststed" class="form-label">Poststed</label>
                            <input type="text" class="form-control" id="poststed" name="poststed" value="<?php echo htmlspecialchars($medlem['poststed']); ?>" required>
                        </div>
                        <div class="mb-2">
                            <label for="fodt" class="form-label">Fødselsdato</label>
                            <input type="date" class="form-control" id="fodt" name="fodt" value="<?php echo htmlspecialchars($medlem['fodt']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="etternavn" class="form-label">Etternavn</label>
                            <input type="text" class="form-control" id="etternavn" name="etternavn" value="<?php echo htmlspecialchars($medlem['etternavn']); ?>" required>
                        </div>
                        <div class="mb-2">
                            <label for="postnr" class="form-label">Postnummer</label>
                            <input type="text" pattern="\d{4}" maxlength="4" inputmode="numeric"  class="form-control" id="postnr"  name="postnr" value="<?php echo htmlspecialchars($medlem['postnr']); ?>" required>
                        </div>   
                        <div class="mb-2">
                            <label for="telefon" class="form-label">Telefonnummer</label>
                            <input type="text" class="form-control" id="telefon" name="telefon" value="<?php echo htmlspecialchars($medlem['telefon']); ?>" required>
                        </div>
                        <div class="mb-2">
                            <label for="mail" class="form-label">Epost addresse</label>
                            <input type="email" class="form-control" id="mail" name="mail" value="<?php echo htmlspecialchars($medlem['mail']); ?>" required 
                            aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text text-danger"><?php echo $epost_err; ?></div>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="betalt" class="form-label">Betalt kontigent</label>
                    <select class="form-select" aria-label="Er Kontigent betalt?"  id="betalt" name="betalt" required>
                        <option value="nei" <?php echo $medlem['betalt'] === "nei" ? 'selected' : ''; ?>>Nei</option>
                        <option value="ja" <?php echo $medlem['betalt'] === "ja" ? 'selected' : ''; ?>>Ja</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
            
        </div>
    </div>
</body>