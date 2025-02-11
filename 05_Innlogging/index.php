<head>
    <title>Innlogging</title>
</head>

<body>
    <h1>Innlogging</h1>
    <p>Vennligst logg inn før du tas videre</p>

    <form action="" method="POST">
        <label for="brukernavn">Brukernavn:</label>
        <input type="text" name="brukernavn" id="brukernavn" required>
        <label for="passord">Passord:</label>
        <input type="password" name="passord" id="passord" required>
        <button type="submit">Logg inn</button>
    </form> 

<?php
session_start();


$bruker_array = array(
    'admin' => 'admin123',
    'jens' => 'jens123',
    'ole' => 'ole123',
    'staale' => 'staale123'
);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brukernavn = $_POST['brukernavn'];
    $passord = $_POST['passord'];

    if (array_key_exists($brukernavn, $bruker_array) && $bruker_array[$brukernavn] === $passord) {
        $_SESSION['innlogget'] = true;
        $_SESSION['brukernavn'] = $brukernavn;
        header("Location: velkommen.php");
        exit();
    } else {
        echo "<p style='color: red;'>Feil brukernavn eller passord..</p>";
    }
}
?>
