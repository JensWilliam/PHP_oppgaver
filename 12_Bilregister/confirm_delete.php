<?php
include 'header.php';
include 'db_connection.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
}


$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;

// Sjekk at ID er et tall
if (!is_numeric($id)) {
    die("Ugyldig ID.");
}

// Hent data fra databasen basert på type
if ($type === 'eier') {
    $sql = "SELECT * FROM eier WHERE id = ?";
    if ($_SESSION['status'] !== 'administrator') {
        die("Du har ikke tilgang til denne siden.");
    }
} elseif ($type === 'bil') {
    $sql = "SELECT * FROM bil WHERE id = ?";
} elseif ($type === 'profil') {
    $sql = "SELECT * FROM eier WHERE id = ?";
    if ($_SESSION['eier_id'] != $id) {
        die("Du har ikke tilgang til denne siden.");
    }
} else {
    die("Ugyldig type.");
}


$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Bilen finnes ikke.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Ugyldig forespørsel (CSRF-token mismatch)");
    }

    // Felles for alle typer
    $id = intval($_POST['id']);

    if ($type === 'bil') {
        $sql = "DELETE FROM bil WHERE id = ?";
    } elseif ($type === 'eier') {
        $sql = "DELETE FROM eier WHERE id = ?";
    } elseif ($type === 'profil') {
        $sql = "DELETE FROM eier WHERE id = ?";
        
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    
    if ($type === 'bil') {
        header("Location: profil.php");
        exit();
    } elseif ($type === 'eier') {
        header("Location: settings.php");
        exit();
    } elseif ($type === 'profil') {
        header("Location: logout.php?csrf_token=" . $_SESSION['csrf_token']);
        exit(); 
    }
}

?>

<main>
            <div class="wrapper">
                <div class="velkommen">
                    <h2>Din GearHub konto</h2>
                </div>

                <div class="layout">
                    <div class="layout__form bilregister">
                        <?php if ($type === 'bil') : ?>
                            <div class="layout__formHeader">
                                <h3>Er du sikker på at du vil fjerne</h3>
                                <p>(<?php echo htmlspecialchars($data['registreringsnummer']); ?>) <?php echo htmlspecialchars($bil['merke'] . ' ' . $bil['modell'] . ' ' . $bil['arsmodell']); ?>?</p>
                            </div>
                        <?php elseif ($type === 'eier') : ?>
                            <div class="layout__formHeader">
                                <h3>Er du sikker på at du vil fjerne brukeren: </h3>
                                <p><?php echo htmlspecialchars($data['epost']); ?><br>NB! Dette vil fjerne brukeren og bilene fra bilregisteret.</p>
                            </div>
                        <?php elseif ($type === 'profil') : ?>
                            <div class="layout__formHeader">
                                <h3>Er du sikker på at du vil fjerne brukeren din?</h3>
                                <p><?php echo htmlspecialchars($data['epost']); ?><br>NB! Dette vil fjerne deg og bilene dine fra bilregisteret.</p>
                            </div>
                        <?php endif; ?>

                        <form class="form bilregister" method="POST">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">

                            <!-- CSRF-token -->
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

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