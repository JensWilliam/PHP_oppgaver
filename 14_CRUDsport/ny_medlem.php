<?php
include 'meny.php';
include 'db_connection.php';


$epost_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }

    



    $fornavn = trim($_POST['fornavn']);
    $etternavn = trim($_POST['etternavn']);
    $adresse = trim($_POST['adresse']);
    $postnr = trim($_POST['postnr']);
    $poststed = trim($_POST['poststed']);
    $fodt = trim($_POST['fodt']);
    $telefon = trim($_POST['telefon']);
    $mail = trim($_POST['mail']);
    $betalt = trim($_POST['betalt']);

    // E-post validering
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        die("Ugyldig e-postadresse");
    } else {
        $sql = "SELECT * FROM medlem WHERE mail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$mail]);
        if ($stmt->rowCount() > 0) {
            $epost_err = "Eposten er allerede tatt.";
        }
    }


    if (empty($epost_err)) {
        $sql = "INSERT INTO medlem (fornavn, etternavn, adresse, postnr, poststed, fodt, telefon, mail, betalt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$fornavn, $etternavn, $adresse, $postnr, $poststed, $fodt, $telefon, $mail, $betalt]);

        header("Location: medlemmer.php");
        exit();
    }
}

?>
        <div class="container-lg bg-primary mt-3">
            <p class="fs-1 text-center">Registrer nytt medlem</p>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="fornavn" class="form-label">Fornavn</label>
                            <input type="text" class="form-control" id="fornavn" name="fornavn" required>
                        </div>
                        <div class="mb-2">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" required>
                        </div>
                        <div class="mb-2">
                            <label for="poststed" class="form-label">Poststed</label>
                            <input type="text" class="form-control" id="poststed" name="poststed" required>
                        </div>
                        <div class="mb-2">
                            <label for="fodt" class="form-label">Fødselsdato</label>
                            <input type="date" class="form-control" id="fodt" name="fodt" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="etternavn" class="form-label">Etternavn</label>
                            <input type="text" class="form-control" id="etternavn" name="etternavn" required>
                        </div>
                        <div class="mb-2">
                            <label for="postnr" class="form-label">Postnummer</label>
                            <input type="text" pattern="\d{4}" maxlength="4" inputmode="numeric"  class="form-control" id="postnr"  name="postnr" required onkeyup="fetchPoststed()">
                        </div>   
                        <div class="mb-2">
                            <label for="telefon" class="form-label">Telefonnummer</label>
                            <input type="text" class="form-control" id="telefon" name="telefon" required>
                        </div>
                        <div class="mb-2">
                            <label for="mail" class="form-label">Epost addresse</label>
                            <input type="email" class="form-control" id="mail" name="mail" required 
                            aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text text-danger"><?php echo $epost_err; ?></div>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="betalt" class="form-label">Betalt kontigent</label>
                    <select class="form-select" aria-label="Er Kontigent betalt?"  id="betalt" name="betalt" required>
                        <option value="nei">Nei</option>
                        <option value="ja">Ja</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Submit</button>
            </form>
            
        </div>
    </div>
    <script>
        function fetchPoststed() {
            var postalCode = document.getElementById('postnr').value;

            // Sørg for at postnummeret har riktig lengde
            if (postalCode.length === 4) {
                // Send en AJAX-forespørsel til serveren for å hente poststedet
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'hent_poststed.php?postal_code=' + postalCode, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var poststed = xhr.responseText;
                        document.getElementById('poststed').value = poststed;
                    }
                };
                xhr.send();
            }
        }
    </script>
</body>