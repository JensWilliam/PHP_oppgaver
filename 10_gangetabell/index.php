<?php
session_name('gangetabell');
session_start();
?>

<body>

    <h1>Gangetabell</h1>
    <h2>Her kan du vise gangetabellen for et tall du velger selv</h2>


    <form action="" method="POST">
        <label for="tall">Skriv et tall mellom 1 og 10</label>
        <input type="number" name="tall" id="tall" min="1" max="10" required>
        <input type="submit" name="regnut" value="Vis gangetabell">
    </form>

</body>

<?php

if (isset($_POST['regnut'])) {
    $tall = $_POST['tall'];
    echo "<h3>Gangetabell for $tall</h3>";

    for ($i = 1; $i <= 10; $i++) {
        echo "<p>" . $tall * $i . "</p>";
    }
}

?>