<?php 
session_name("quiz");
session_start();

// Sjekk om session er startet, hvis ikke tildeles session variabler
if (!isset($_SESSION['spmNummer'])) {
    $_SESSION['spmNummer'] = 1;
    $_SESSION['poeng'] = 0;
}

$spørsmål = array(
    1=>"Hva heter Norges mest brukte kryptobørs?",
    2=>"Hva heter læreren til 2ITA i utvikling?",
    3=>"Hva er navnet på den beste klassen?",
    4=>"Hva er hovedstaden i Norge?",
    5=>"Hva er hovedstaden i Sverige?",
    6=>"Hva er dette programmet laget i?",
    7=>"Hvem er kontaktlæreren for 2ITA?",
    8=>"Hva er hovedstaden i Danmark?",
    9=>"Hva er hovedstaden i Finland?",
    10=>"Hva er hovedstaden i Island?",
);

$fasit = array(
    1=>"firi",
    2=>"staale",
    3=>"2ita",
    4=>"oslo",
    5=>"stockholm",
    6=>"php",
    7=>"per",
    8=>"københavn",
    9=>"helsinki",
    10=>"reykjavik",
);

// Sjekk om bruker har svart på spørsmålet
if (isset($_POST['submit'])) {
    $brukerSvar = strtolower(trim($_POST['svar']));
    $spmNummer = $_SESSION['spmNummer'];


    if ($brukerSvar == $fasit[$spmNummer]) {
        $_SESSION['poeng']++;
        echo "<p class='riktig'>Riktig svar!</p>";
    } else {
        echo "<p class='feil'>Feil svar!</p>";
    }

    $_SESSION['spmNummer']++;
}

// Sjekk om quizen er ferdig, vis resultat og gi mulighet til å starte på nytt

if ($_SESSION['spmNummer'] > count($spørsmål)) {
    echo "<h2>Quiz ferdig!</h2>";
    echo "<p>Du fikk {$_SESSION['poeng']} av " . count($spørsmål) . " riktige.</p>";

    echo '<form action="" method="POST">
        <input type="submit" name="restart" value="Start på nytt">
        </form>';

    
    if (isset($_POST['restart'])) {
        session_destroy();
        session_start();
        $_SESSION['spmNummer'] = 1;
        $_SESSION['poeng'] = 0;

        header("Location: index.php");
        exit();
    }
} else {
    $spmNummer = $_SESSION['spmNummer'];
    $gjeldeneSpørsmål = $spørsmål[$spmNummer];
}



if ($_SESSION['spmNummer'] <= count($spørsmål)) {
    echo "<h1>Velkommen til quiz!</h1><br>";
    echo "<h3>Spørsmål $spmNummer:</h3><br>";
    echo "<p>$gjeldeneSpørsmål</p><br>";

    echo '<form action="" method="POST">
        <label for="svar">Ditt svar:</label>
        <input type="text" name="svar" id="svar" required><br>
        <input type="submit" name="submit" value="Sjekk svar"><br>
        </form>';
}
?>


<style>
.riktig {
    color: green;
}

.feil {
    color: red;
}
</style>



