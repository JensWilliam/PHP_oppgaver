<head>
    <title>Velkommen</title>
</head>

<body>
    <h1>Velkommen</h1>
    <p>Velkommen til siden!</p>
    <form action="" method="POST">
        <button type="submit">Logg ut</button>
    </form>
</body>



<?php
session_start();

if (isset($_SESSION['brukernavn'])) {
    echo "<p>Du er logget inn som: " . $_SESSION['brukernavn'] . "</p>";
}

if (!isset($_SESSION['innlogget'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>



