<?php
include 'header.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
} 

include 'db_connection.php';


$registreringsnummer_err = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registreringsnummer = $_POST['registreringsnummer'];
    $merke = $_POST['merke'];
    $modell = $_POST['modell'];
    $arsmodell = $_POST['arsmodell'];
    $farge = $_POST['farge'];

    $sql = "SELECT * FROM bil WHERE registreringsnummer = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$registreringsnummer]);
    $bil = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($bil) {
        $registreringsnummer_err = "Bilen er allerede registrert.";
    } else {
        $sql = "INSERT INTO bil (registreringsnummer, merke, modell, arsmodell, farge, eier_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$registreringsnummer, $merke, $modell, $arsmodell, $farge, $_SESSION['eier_id']]);

        header("Location: profil.php");
        exit();
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
                        <div class="layout__formHeader">
                            <h3>Registrer ny bil</h3>
                        </div>

                        <form class="form" action="ny_bil.php" method="POST">
                            <div class="form__group">
                                <label for="registreringsnummer">Registreringsnummer:</label>
                                <input type="text" name="registreringsnummer" id="registreringsnummer" placeholder="e.g. AB12345" required>
                                <span><?php echo $registreringsnummer_err; ?></span>
                            </div>
                            <div class="form__group">
                                <label for="merke">Bil-merke:</label>
                                <input type="text" name="merke" id="merke" placeholder="e.g. Honda" required>
                            </div>
                            <div class="form__group">
                                <label for="modell">Bil-modell:</label>
                                <input type="text" name="modell" id="modell" placeholder="e.g. Civic" required>
                            </div>
                            <div class="form__group">
                                <label for="arsmodell">Årsmodell:</label>
                                <input type="text" name="arsmodell" id="arsmodell" placeholder="e.g. 2010" required>
                            </div>
                            <div class="form__group">
                                <label for="farge">Farge:</label>
                                <input type="text" name="farge" id="farge" placeholder="e.g. Rød" required>
                            </div>

                            

                            <div class="knapp">
                                <button class="btn__form" type="submit">Send inn</button>
                            </div>                            
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>