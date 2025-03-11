<?php
include 'header.php';

include 'db_connection.php';

// Sjekker om brukeren allerede er logget inn
if (isset($_SESSION['innlogget'])) {
    header("Location: index.php");
    exit();
}

$brukernavn_passord_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }

    $brukernavn = $_POST['brukernavn'];
    $passord = $_POST['passord'];
    $handling = "Innlogging";

    $sql = "SELECT * FROM brukere WHERE brukernavn = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$brukernavn]);
    $bruker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($bruker && password_verify($passord, $bruker['passord'])) {
        $_SESSION['innlogget'] = true;
        $_SESSION['brukernavn'] = htmlspecialchars($brukernavn);
        $_SESSION['status'] = $bruker['status'];
        
        // Loggfør vellykket innlogging
        $beskrivelse = 'Suksess';
        $sql_logg = "INSERT INTO logg (brukernavn, handling, beskrivelse) VALUES (?, ?, ?)";
        $stmt_logg = $conn->prepare($sql_logg);
        $stmt_logg->execute([$brukernavn, $handling, $beskrivelse]);


        // Forny CSRF-token ved vellykket innlogging
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header("Location: index.php");
        exit();
    } else {
        $brukernavn_passord_err = "Feil brukernavn eller passord.";
        if (!$bruker) {
            $beskrivelse = 'Feil brukernavn';
        } else {
            $beskrivelse = 'Feil passord';
        }
        
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
                            <h3>Logg inn med din GearHub konto</h3>
                        </div>

                        <form class="form" action="login.php" method="POST">
                            <div class="form__group">
                                <label for="brukernavn">Brukernavn:</label>
                                <input type="text" name="brukernavn" id="brukernavn" placeholder="e.g. jens_ege" required>
                            </div>
                            <div class="form__group">
                                <label for="passord">Passord:</label>
                                <input type="password" name="passord" id="passord" placeholder="&bull;&bull;&bull;&bull;&bull;" required>
                                <span><?php echo $brukernavn_passord_err; ?></span>
                            </div>

                            <!-- CSRF-token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                            <div class="knapp">
                                <button class="btn__form" type="submit">Logg inn</button>
                            </div>
                        </form>

                        <div class="layout__formFooter logreg">   
                            <p>Har du ikke en GearHub konto? <a href="registrer.php">Registrer deg her</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </body>
</html>