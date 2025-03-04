<?php
include 'header.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
}


if (!isset($_GET['id'])) {
    die("Mangler id");
}


include 'db_connection.php';

$id = $_GET['id'];

// Hent bilinfo fra databasen
$sql = "SELECT * FROM bil WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$bil = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bil) {
    die("Bilen finnes ikke.");
}
?>

<main>
            <div class="wrapper">
                <div class="velkommen">
                    <h2>Din GearHub konto</h2>
                </div>

                <div class="layout">
                    <div class="layout__form bilregister">
                        <div class="layout__formHeader">
                            <h3>Er du sikker på at du vil fjerne</h3>
                            <p>(<?php echo htmlspecialchars($bil['registreringsnummer']); ?>) <?php echo htmlspecialchars($bil['merke'] . ' ' . $bil['modell'] . ' ' . $bil['arsmodell']); ?>?</p>
                        </div>

                        <form class="form bilregister" action="delete.php" method="POST">
                            
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($bil['id']); ?>">

                            <div class="knapp">
                                <button class="btn__form" type="submit">FJERN</button>
                            </div>

                            <div class="knapp">
                                <a class="btn__form avbryt" href="profil.php">Avbryt</a>
                            </div>
                        </form>
                        
                    </div>
                </div>
    </body>
</html>