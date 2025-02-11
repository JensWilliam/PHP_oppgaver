<?php
session_start();
?>

<body>

    <h1>Min Side</h1>
    <p>Velkommen til denne lille webapplikasjonen.<br>
    Her kan du se på profilen din, og endre infoen om du ønsker.</p>

    <form action="index.php" method="POST">
        <input type="submit" name="tilprofil" value="Se profilen din">
    </form>

</body>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tilprofil'])) {
        header("Location: profil.php");
        exit();
    }
?>