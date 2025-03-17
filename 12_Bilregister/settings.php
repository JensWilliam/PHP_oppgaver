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
                    <h2>Administrator side</h2>
                    <p>Eiere og hendelse-logg</p>
                    
                </div>

                <div class="layout">
                    <div class="grid-logg">
                        <div class="grid-row brukere h">                              
                            
                            <div class="grid-item-h"><p>Fullt navn</p></div>
                            <div class="grid-item-h"><p>E-post</p></div>
                            <div class="grid-item-h"><p>Status</p></div>
                            <div class="grid-item-h"><p>Brukernavn</p></div>
                            <div class="grid-item-h"><p>Handling</p></div>
                        </div>
                        <?php foreach ($eiere as $eier): ?>
                            <div class="grid-row brukere">
                                
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['navn']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['epost']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['status']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($eier['brukernavn']); ?></p></div>
                                <div class="grid-item-logg"><p><a href="confirm_update.php?type=eier&id=<?php echo urlencode($eier['id']); ?>"><svg class="svg_endre" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="25px" width="25px" fill="currentColor"> <path d="M 18 2 L 15.585938 4.4140625 L 19.585938 8.4140625 L 22 6 L 18 2 z M 14.076172 5.9238281 L 3 17 L 3 21 L 7 21 L 18.076172 9.9238281 L 14.076172 5.9238281 z"/></svg></a><a href="confirm_delete.php?type=eier&id=<?php echo urlencode($eier['id']); ?>"><svg class="svg_slett" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="25px" height="25px" fill="currentColor"><path d="M 10 2 L 9 3 L 4 3 L 4 5 L 5 5 L 5 20 C 5 20.522222 5.1913289 21.05461 5.5683594 21.431641 C 5.9453899 21.808671 6.4777778 22 7 22 L 17 22 C 17.522222 22 18.05461 21.808671 18.431641 21.431641 C 18.808671 21.05461 19 20.522222 19 20 L 19 5 L 20 5 L 20 3 L 15 3 L 14 2 L 10 2 z M 7 5 L 17 5 L 17 20 L 7 20 L 7 5 z M 9 7 L 9 18 L 11 18 L 11 7 L 9 7 z M 13 7 L 13 18 L 15 18 L 15 7 L 13 7 z"/></svg></a></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="grid-logg">
                        <!-- Overskrifter -->
                        <div class="grid-row h">
                            <div class="grid-item-h"><p>ID</p></div>                               
                            <div class="grid-item-h"><p>Brukernavn</p></div>
                            <div class="grid-item-h"><p>Handling</p></div>
                            <div class="grid-item-h"><p>Beskrivelse</p></div>
                            <div class="grid-item-h"><p>Dato</p></div>
                        </div>
                        <?php foreach ($logger as $logg):?>
                            <div class="grid-row">
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($logg['id']); ?></p></div>                               
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($logg['brukernavn']); ?></p></div>
                                <div class="grid-item-logg"><p><?php echo htmlspecialchars($logg['handling']); ?></p></div>
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