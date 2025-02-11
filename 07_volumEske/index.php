<?php
session_name("volumEske");
session_start();

$ark_typer = array( 
    "A2" => array(42.0, 59.4),
    "A3" => array(29.7, 42.0),
    "A4" => array(21.0, 29.7),
    "A5" => array(14.8, 21.0),
    "A6" => array(10.5, 14.8),
);


function volumA4($hoyde, $ark_bredde, $ark_lengde) {
    $bredde = $ark_bredde - $hoyde * 2;
    $lengde = $ark_lengde - $hoyde * 2;
    $volum = $bredde * $lengde * $hoyde;
    return $volum;
}
?>

<head>
    <title>Volum Regneren</title>

    <script>
        // Funksjon for å oppdatere maks-høyde basert på valgt papirstørrelse
        function oppdaterMaxHoyde() {
            var papirstorrelse = document.getElementById("papirstorrelse").value;
            var hoydeInput = document.getElementById("hoyde");
            
            // Sett maksimal høyde basert på valgt papirstørrelse
            if (papirstorrelse == "A2") {
                hoydeInput.max = 21;                    // Maks høyde for A2 (42 cm / 2)
            } else if (papirstorrelse == "A3") {
                hoydeInput.max = 14.85;                 // Maks høyde for A3 (29.7 cm / 2)
            } else if (papirstorrelse == "A4") {
                hoydeInput.max = 10.5;                  // Maks høyde for A4 (21 cm / 2)
            } else if (papirstorrelse == "A5") {
                hoydeInput.max = 7.4;                   // Maks høyde for A5 (14.8 cm / 2)
            } else if (papirstorrelse == "A6") {
                hoydeInput.max = 5.25;                  // Maks høyde for A6 (10.5 cm / 2)
            } else {
                hoydeInput.max = "";                    // Ingen begrensning hvis ingen papirstørrelse er valgt
            }
        }
        
        window.onload = function() {
            oppdaterMaxHoyde();
        };
    </script>
</head>

<body>
    <h1>Volum Regneren</h1>
    <h3>Regn ut volumet av en eske</h3>

    <form action="" method="POST">
        <label for="papirstorrelse">Velg papirstørrelse:</label>
            <select name="papirstorrelse" id="papirstorrelse" required onchange="oppdaterMaxHoyde()">
                <option value="" disabled selected>Velg en størrelse</option> <!-- Denne er som en placeholder disabled selected gjør at det ikke er ett gyldig valg for å¨sende skjeamet -->
                <option value="A2">A2</option>
                <option value="A3">A3</option>
                <option value="A4">A4</option>
                <option value="A5">A5</option>
                <option value="A6">A6</option>
            </select>
        <label for="hoyde">Høyde i cm:</label>
        <input type="number" name="hoyde" id="hoyde" min="0"  step="0.01" required>
        <input type="submit" value="Regn ut">
    </form>
</body>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $ark_storrelse = $_POST["papirstorrelse"];
        $hoyde = $_POST["hoyde"];

        // Valider at høyden er et gyldig tall
        if (is_numeric($hoyde) && $hoyde > 0) {
            // Gyldig høyde, fortsett med beregning

            // Henter ut bredden og lengden til valgt papirstørrelse
            $ark_bredde = $ark_typer[$ark_storrelse][0];
            $ark_lengde = $ark_typer[$ark_storrelse][1];

            // Regner ut volumet av esken
            $volum = volumA4($hoyde, $ark_bredde, $ark_lengde);
            echo "<p style='color: green;'>Volumet av esken er: $volum cm^3</p>";
        } else {
            // Ugyldig høyde
            echo "<p style='color: red;'>Ugyldig høyde. Vennligst skriv inn et gyldig tall.</p>";
        }

        // Form for å starte på nytt
        echo "<form action='' method='POST'>
                <label for='Restart'>Vil du prøve på nytt?</label>
                <input type='submit' name='restart' value='Ja! Start på nytt'>
            </form>";
        
        // Håndter "restart"-knappen
        if (isset($_POST["restart"])) {
            session_destroy();
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    }
?>
