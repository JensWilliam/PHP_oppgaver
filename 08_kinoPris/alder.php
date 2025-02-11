<?php
session_name("kinoPris");
session_start();
?>

<head>
    <title>Kino pris</title>
</head>
<body>
    <h1>Velg alder for billettene</h1>


    <?php
    if (isset($_SESSION['antall_billetter'])) {
        $antall = $_SESSION['antall_billetter'];

        // skjema for alder til billetter
        echo "<form action='alder.php' method='POST'>";
        for ($i = 1; $i <= $antall; $i++) {

            echo '<label for="alder_' . $i . '">Alder for billett ' . $i . ':</label><br>';
            echo '<input type="number" name="alder[]" id="alder_' . $i . '" min="0" required><br><br>';
        }
        echo '<input type="submit" name="beregn_pris" value="Kjøp billetter">';
        echo '</form>';
    } else {
        echo 'Vennligst gå tilbake og legg inn antall billetter';
    }
    ?>
</body>

<?php
// Håndterer POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["beregn_pris"])) {
    $aldre = $_POST["alder"];

    $billettPris = 150; //Standard pris
    $barnrabatt = 50; //50% rabbatt for barn
    $ungdomrabatt = 25; //25% rabatt for ungdom
    $totalPris = 0; //Total pris

    foreach ($aldre as $alder) {
        if ($alder >= 2 && $alder <= 12) {
            // regner ut rabattbeløp 
            $rabbatBelop = $billettPris * ($barnrabatt / 100);
            $totalPris += $billettPris - $rabbatBelop;
            echo 'Billett for barn (Alder: ' . $alder . '): 50% rabbatt = ' . $billettPris - $rabbatBelop . ' kr<br>';
        } elseif ($alder > 12 && $alder <= 17) {
            $rabbatBelop = $billettPris * ($ungdomrabatt / 100);
            $totalPris += $billettPris - $rabbatBelop;
            echo 'Billett for ungdom (Alder: ' . $alder . '): 25% rabbatt = ' . $billettPris - $rabbatBelop . ' kr<br>';
        } elseif ($alder <= 1) {
            $totalPris += 0;
            echo 'Billett for spedbarn: 0kr<br>';
        } else {
            $totalPris += $billettPris;
            echo 'Billett for voksen: ' . $billettPris . ' kr<br>';
        }
    }
    echo '<br>Total pris: ' . $totalPris . ' kr<br>';
}

echo "<form action='alder.php' method='POST'>
            <label for='Restart'>Vil du kjøpe flere billetter?</label>
            <input type='submit' name='restart' value='Ja'>
        </form>";
    

    if (!isset($_SESSION['antall_billetter'])) {
        header("Location: index.php");
        exit();
    }

    
    // Håndter "restart"-knappen
    if (isset($_POST["restart"])) {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }
?>