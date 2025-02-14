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

$sql = "SELECT * FROM logg";
$stmt = $conn->prepare($sql);
$stmt->execute();
$logger = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
        <main>
            <div class="wrapper">
                <div class="velkommen">
                    <h2>Innstillinger</h2>
                    <p>Brukerkontoer og innloggings-logg</p>
                    
                </div>

                <div class="layout">
                    <!--<div class="profil-boks oversikt">
                        <div class="layout__formHeader">
                            <h3>Brukere</h3>
                        </div>
                    </div>-->
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
</html

