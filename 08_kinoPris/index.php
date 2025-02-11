<?php
session_name("kinoPris");
session_start();
?>

<head>
    <title>Kino pris</title>
</head>
<body>
    <h1>Kjøp kino-billetter!</h1>
    <p>Velkommen til kinoen! Legg inn antall billetter du vil kjøpe:</p>

    <form action="" method="POST">
        <label for="antall">Antall billetter:</label>
        <input type="number" name="antall" id="antall" min="1" required>
        <input type="submit" value="Fortsett">
    </form>
</body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["antall"])) {
    $_SESSION['antall_billetter'] = $_POST["antall"];
    header("Location: alder.php");
    exit();
}

var_dump($_SESSION);
?>