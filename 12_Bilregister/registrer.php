<?php
include 'header.php';

include 'db_connection.php';

// Sjekk om brukeren allerede er logget inn
if (isset($_SESSION['innlogget'])) {
    header("Location: index.php");
    exit();
}

// Variabler for feilmeldinger
$brukernavn_err = $passord_err = $passord_match_err = "";
$brukernavn = $passord = ""; // Variabler for brukerinput


// Hvis skjemaet er sendt inn
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sjekk om CSRF-token er gyldig
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }

    // hente data fra skjema
    $brukernavn = trim($_POST['brukernavn']);
    $passord = $_POST['passord'];
    $passord2 = $_POST['passord2'];

    // Sjekk om brukernavnet er tomt
    if (empty($brukernavn)) {
        $brukernavn_err = "Brukernavn er påkrevd.";
    } else {
        // Sjekk om brukernavnet allerede finnes i databasen
        $sql = "SELECT * FROM brukere WHERE brukernavn = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$brukernavn]);
        if ($stmt->rowCount() > 0) {
            $brukernavn_err = "Brukernavnet er allerede tatt.";
        }
    }

    // Validering av passord
    if (empty($passord)) {
        $passord_err = "Passord er påkrevd.";
    } elseif (strlen($passord) < 6) {
        $passord_err = "Passordet må være minst 6 tegn langt.";
    }

    // sjekk om passordene matcher
    if ($passord !== $passord2) {
        $passord_match_err = "Passordene matcher ikke.";
    }

    // Hvis ingen feil, legg til bruker i databasen
    if (empty($brukernavn_err) && empty($passord_err) && empty($passord_match_err)) {
        
        $hashed_passord = password_hash($passord, PASSWORD_DEFAULT);

        $sql = "INSERT INTO brukere (brukernavn, passord) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$brukernavn, $hashed_passord])) {
            $_SESSION['innlogget'] = true;
            $_SESSION['brukernavn'] = $brukernavn;

            // Forny CSRF-token ved vellykket innlogging
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            header("Location: index.php");
            exit();
        } else {
            echo "Noe gikk galt. Prøv igjen senere.";
        }
    }
}
?>

<main>
    <div class="wrapper">
        <div class="velkommen">
            <h2></h2>
        </div>
        <div class="layout">
            <div class="layout__form">
                <div class=layout__formHeader>
                    <h3>Opprett ny GearHub konto</h3>
                </div>

                <form class="form" action="registrer.php" method="POST">
                    <div class="form__group">
                        <label for="brukernavn">Brukernavn:</label>
                        <input type="text" name="brukernavn" id="brukernavn" placeholder="e.g. jens_ege" value="<?php echo $brukernavn; ?>" required>
                        <span><?php echo $brukernavn_err; ?></span>
                    </div>
                    <div class="form__group">
                        <label for="passord">Passord:</label>
                        <input type="password" name="passord" id="passord" placeholder="&bull;&bull;&bull;&bull;&bull;" required>
                        <span><?php echo $passord_err; ?></span>
                    </div>

                    <div class="form__group">
                        <label for="passord2">Bekreft passord:</label>
                        <input type="password" name="passord2" id="passord2" required>
                        <span><?php echo $passord_match_err; ?></span>
                    </div>

                    <!-- CSRF-token -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="knapp">
                        <button class="btn__form" type="submit">Registrer</button>
                    </div>
                </form>

                <div class="layout__formFooter logreg">   
                    <p>Har du allerede en GearHub konto? <a href="login.php">Logg inn her</a></p>
                </div>
            </div>
        </div>
    </div>
</main>