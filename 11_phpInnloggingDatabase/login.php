<?php
session_name("database_innlogging");
session_start();


$server = "localhost";
$database = "innlogging";
$dbUser = "root";
$dbPassword = "";

try {
    $conn = new PDO("mysql:host=$server;dbname=$database;charset=utf8", $dbUser, $dbPassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kunne ikke koble til databasen: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   // if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
   //     die("Ugyldig forespørsel (CSRF-token mismatch)");
   // }

    $brukernavn = $_POST['brukernavn'];
    $passord = $_POST['passord'];

    $sql = "SELECT * FROM brukere WHERE brukernavn = ? AND passord = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$brukernavn, $passord]);
    //$bruker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        $_SESSION['innlogget'] = true;
        $_SESSION['brukernavn'] = $brukernavn;
        // Forny CSRF-token ved vellykket innlogging
        //$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header("Location: index.php");
        exit();
    } else {
        echo "<p>Feil brukernavn eller passord!</p>";
    }
}

?>