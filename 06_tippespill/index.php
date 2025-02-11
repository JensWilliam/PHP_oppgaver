<head>
    <title>TippeSpill</title>
</head>

<body>
    <h1>TippeSpill</h1>
    <p>Velkommen til TippeSpill!</p>

    <form action="" method="GET">
        <label for="Tall">Gjett et tall mellom 1 og 10:</label>
        <input type="number" name="tall" id="tall" min="1" max="10" required>
        <button type="submit">Gjett</button>
    </form> 


    <form action="" method="POST">
        <button type="submit">Ny session</button>
    </form>
</body>

<?php
session_name("tippespill");
session_start();

if(!isset($_SESSION['antall_gjett'])) {
    $_SESSION['antall_gjett'] = 0;
    $_SESSION['antall_gjett'] ++;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

if (isset($_GET['tall'])) {
    $tall = $_GET['tall'];
    $riktigTall = rand(1, 10);
    if ($tall == $riktigTall) {
        echo "<p style='color: green;'>Gratulerer, du gjettet riktig tall!</p>";
        echo "<p>Du brukte {$_SESSION['antall_gjett']} gjett</p>";
    } else {
        echo "<p style='color: red;'>Du gjettet feil tall. Riktig tall var $riktigTall.</p>";
        $_SESSION['antall_gjett']++;
    }
}
?>

