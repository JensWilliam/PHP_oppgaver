<?php
session_name("database_innlogging");
session_start();

if (isset($_SESSION['innlogget']) && $_SESSION['innlogget'] === true) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
} else {
    echo "<p>Du er ikke logget inn!</p>";
}
?>