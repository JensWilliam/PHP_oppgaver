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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }

    $id = intval($_POST['id']);

    $sql = "DELETE FROM medlem WHERE m_nr = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    header("Location: medlemmer.php");
    exit();
}
?>
        <div class="container-lg bg-primary mt-3">
            <p class="fs-1 text-center mb-5">Bekreft slett</p>
            <p class="fs-5 text-center">Er du sikker på at du vil slette medlemmet: <?php echo htmlspecialchars($medlem['fornavn']); ?>?</p>
            <div class="container-lg d-flex justify-content-center">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <button type="submit" class="btn btn-danger">Slett</button>
                </form>
                <a class="btn btn-success" href="medlemmer.php" role="button">Avbryt</a>
            </div>
            
            
        </div>

    </div>
</body>