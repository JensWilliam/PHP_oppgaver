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
    $handling = "Registrering"; // Til logging

    // NYE FELT FOR EIER
    $eier_navn = trim($_POST['eier_navn']);
    $eier_epost = trim($_POST['eier_epost']);

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


        try {
            // Start transaksjon
            $conn->beginTransaction();

            // 1. Legg til eier
            $sql = "INSERT INTO eier (navn, epost) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$eier_navn, $eier_epost]);

            // 2. Hent eier_id
            $eier_id = $conn->lastInsertId();

            // 3. Legg til bruker med eier_id
            $sql = "INSERT INTO brukere (brukernavn, passord, eier_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$brukernavn, $hashed_passord, $eier_id]);

            // Fullfør transaksjonen
            $conn->commit();

            // hente bruker fra databasen
            $sql = "SELECT * FROM brukere WHERE brukernavn = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$brukernavn]);
            $bruker = $stmt->fetch(PDO::FETCH_ASSOC);
            

            $_SESSION['innlogget'] = true;
            $_SESSION['brukernavn'] = $bruker['brukernavn'];
            $_SESSION['status'] = $bruker['status'];
            $_SESSION['eier_id'] = $bruker['eier_id'];

            // Loggfør vellykket registrering
            $beskrivelse = 'Suksess';
            $sql_logg = "INSERT INTO logg (brukernavn, handling, beskrivelse) VALUES (?, ?, ?)";
            $stmt_logg = $conn->prepare($sql_logg);
            $stmt_logg->execute([$brukernavn, $handling, $beskrivelse]);

            // Forny CSRF-token ved vellykket innlogging
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            echo "Noe gikk galt: " . $e->getMessage();
        }
    } else {
        // Loggfør feilregistrering
        $beskrivelse = 'Feilregistrering';
        $sql_logg = "INSERT INTO logg (brukernavn, handling, beskrivelse) VALUES (?, ?, ?)";
        $stmt_logg = $conn->prepare($sql_logg);
        $stmt_logg->execute([$brukernavn, $handling, $beskrivelse]);
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
                        <label for="eier_navn">Fullt navn:</label>
                        <input type="text" name="eier_navn" id="eier_navn" placeholder="e.g. Ola Normann" required>
                    </div>
                    <div class="form__group">
                        <label for="eier_epost">E-post:</label>
                        <input type="email" name="eier_epost" id="eier_epost" placeholder="e.g. olanormann@mail.no" required>
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