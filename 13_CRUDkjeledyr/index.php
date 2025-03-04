<?php
include 'header.php';


include 'db_connection.php';

// Henter data fra databasen
$sql = "SELECT * FROM kjeledyr ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$kjeledyr = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

        <main>
            <div class="kol1">
                <div class="grid-row header">
                    <div class="gridchild h">nummer</div>
                    <div class="gridchild h">Navn</div>
                    <div class="gridchild h">Dyr</div>
                    <div class="gridchild h">Rase</div>
                    <div class="gridchild h">Født:</div>
                    <div class="gridchild h">Kjønn</div>
                </div>
                
                <?php foreach ($kjeledyr as $dyr) : ?>
                    <div class="grid-row">
                        <div class="gridchild"><p><?php echo htmlspecialchars($dyr['id']); ?></p></div>
                        <div class="gridchild"><p><?php echo htmlspecialchars($dyr['navn']); ?></p></div>
                        <div class="gridchild"><p><?php echo htmlspecialchars($dyr['dyr']); ?></p></div>
                        <div class="gridchild"><p><?php echo htmlspecialchars($dyr['rase']); ?></p></div>
                        <div class="gridchild"><p><?php echo htmlspecialchars($dyr['fodselsdato']); ?></p></div>
                        <div class="gridchild"><p><?php echo htmlspecialchars($dyr['kjonn']); ?></p></div>
                    </div>
                <?php endforeach; ?> 
            </div>

            <div class="kol2">
                <h1>Registrer</h1>

                <p>Registrer ett nytt kjeledyr:</p>

                <div class="knapp-parent">
                    <a class="knapp" href="registrer.php">Registrer</a>
                </div>
                
                
            </div>
        </main>
    </body>
</html>