<?php 
include 'header.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
} 

if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'administrator') {
    header("Location: index.php");
    exit();
}

include 'db_connection.php';

$sql = "SELECT * FROM logg ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$logger = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql2 = "SELECT eier.*, brukere.brukernavn, brukere.status FROM eier LEFT JOIN brukere ON eier.id = brukere.eier_id;";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$eiere = $stmt2->fetchAll(PDO::FETCH_ASSOC);



?>
        <main>
            <div class="wrapper">
                <div class="velkommen">
                    <h2>Innstillinger</h2>
                    <p>Eiere og innloggings-logg</p>
                    
                </div>

                <div class="layout">
                    <div class="grid-logg">
                        <div class="grid-row brukere h">                              
                            <div class="grid-item-h"><p>ID</p></div>
                            <div class="grid-item-h"><p>Fullt navn</p></div>
                            <div class="grid-item-h"><p>E-post</p></div>
                            <div class="grid-item-h"><p>Brukernavn</p></div>
                            <div class="grid-item-h"><p>Status</p></div>
                        </div>
                        <?php foreach ($eiere as $eier): ?>
                            <div class="grid-row brukere">
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['id']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['navn']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['epost']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['brukernavn']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['status']); ?></p></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="grid-logg">
                        <!-- Overskrifter -->
                        <div class="grid-row h">
                            <div class="grid-item-h"><p>ID</p></div>                               
                            <div class="grid-item-h"><p>Brukernavn</p></div>
                            <div class="grid-item-h"><p>Passord</p></div>
                            <div class="grid-item-h"><p>Beskrivelse</p></div>
                            <div class="grid-item-h"><p>Dato</p></div>
                        </div>
                        <?php foreach ($logger as $logg):?>
                            <div class="grid-row">
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($logg['id']); ?></p></div>                               
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($logg['brukernavn']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($logg['passord']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($logg['beskrivelse']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($logg['tid']); ?></p></div>
                            </div>
                        <?php endforeach; ?>
        
                    </div>
                    
                    
                    
                

                </div>

            </div>
        </main>
    </body>
</html>