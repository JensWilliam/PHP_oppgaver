<?php
include 'header.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
} 

include 'db_connection.php';

// Henter bilene til den innloggede brukeren
$sql = "SELECT * FROM bil WHERE eier_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION['eier_id']]);
$biler = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

        <main>
            <div class="wrapper">
                <div class="velkommen">
                    <h2>Din GearHub konto</h2>
                </div>

                <div class="layout single">           
                    <div class="profil-boks">
                        <div class="layout__formHeader">
                            <h3><?php echo $_SESSION['bruker_navn']; ?></h3>
                        </div>

                        <div class="grid-container profil">
                                <!-- Partall er resultat av søk -->
                            <div class="grid-item profil"><p>Brukernavn:</p></div>
                            <div class="grid-item profil"><p><?php echo $_SESSION['brukernavn'] ?? ''; ?></p></div>

                            <div class="grid-item profil"><p>Fullt navn:</p></div>
                            <div class="grid-item profil"><p><?php echo $_SESSION['bruker_navn'] ?? ''; ?></p></div>

                            <div class="grid-item profil"><p>E-post:</p></div>
                            <div class="grid-item profil"><p><?php echo $_SESSION['eier_epost'] ?? ''; ?></p></div>
                
                        </div>

                        <div class="layout__formFooter">
                            <!--<div class="knapp bilregister">
                                <button class="btn__form" id=toggleCars>Se dine biler</button>
                            </div>-->

                            <div class="knapp">    
                                <a class="btn__form" href="logout.php?csrf_token=<?php echo $_SESSION['csrf_token']; ?>">Logg ut</a>
                            </div>
                            
                            <button class="togglebiler" id=toggleCars>
                                Dine biler
                                <svg class="rotate" fill="currentColor" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                    viewBox="0 0 330 330" xml:space="preserve">
                                    <path id="XMLID_222_" d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001
                                    c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213
                                    C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606
                                    C255,161.018,253.42,157.202,250.606,154.389z"/>
                                </svg>
                            </button>
                            
                        </div>
                    </div>

                    
                        

                    <div class="grid-logg profil">
                        <!-- Overskrifter -->
                        <div class="grid-row profil h">
                            <div class="grid-item-h"><p>Reg-nr:</p></div>                               
                            <div class="grid-item-h"><p>Merke:</p></div>
                            <div class="grid-item-h"><p>Modell:</p></div>
                            <div class="grid-item-h"><p>Årsmodell:</p></div>
                            <div class="grid-item-h"><p>Farge:</p></div>
                            <div class="grid-item-h"><p>Endre / Slett:</p><!--<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="25px" width="25px" fill="currentColor"> <path d="M 18 2 L 15.585938 4.4140625 L 19.585938 8.4140625 L 22 6 L 18 2 z M 14.076172 5.9238281 L 3 17 L 3 21 L 7 21 L 18.076172 9.9238281 L 14.076172 5.9238281 z"/></svg>--></div>
                        </div>

                        <?php if ($biler): ?>
                            <?php $ingenbil_err = ""; ?> <!-- Hvis bruker har biler settes error melding til tom -->

                            <?php foreach ($biler as $bil): ?>
                                <div class="grid-row profil">
                                    <div class="grid-item-logg"><p><?php echo htmlspecialchars($bil['registreringsnummer']); ?></p></div>
                                    <div class="grid-item-logg"><p><?php echo htmlspecialchars($bil['merke']); ?></p></div>
                                    <div class="grid-item-logg"><p><?php echo htmlspecialchars($bil['modell']); ?></p></div>
                                    <div class="grid-item-logg"><p><?php echo htmlspecialchars($bil['arsmodell']); ?></p></div>
                                    <div class="grid-item-logg"><p><?php echo htmlspecialchars($bil['farge']); ?></p></div>
                                    <div class="grid-item-logg"><p><a href="confirm_update.php?id=<?php echo urlencode($bil['id']); ?>"><svg class="svg_endre" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="25px" width="25px" fill="currentColor"> <path d="M 18 2 L 15.585938 4.4140625 L 19.585938 8.4140625 L 22 6 L 18 2 z M 14.076172 5.9238281 L 3 17 L 3 21 L 7 21 L 18.076172 9.9238281 L 14.076172 5.9238281 z"/></svg></a><a href="confirm_delete.php?id=<?php echo urlencode($bil['id']); ?>"><svg class="svg_slett" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="25px" height="25px" fill="currentColor"><path d="M 10 2 L 9 3 L 4 3 L 4 5 L 5 5 L 5 20 C 5 20.522222 5.1913289 21.05461 5.5683594 21.431641 C 5.9453899 21.808671 6.4777778 22 7 22 L 17 22 C 17.522222 22 18.05461 21.808671 18.431641 21.431641 C 18.808671 21.05461 19 20.522222 19 20 L 19 5 L 20 5 L 20 3 L 15 3 L 14 2 L 10 2 z M 7 5 L 17 5 L 17 20 L 7 20 L 7 5 z M 9 7 L 9 18 L 11 18 L 11 7 L 9 7 z M 13 7 L 13 18 L 15 18 L 15 7 L 13 7 z"/></svg></a></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <?php $ingenbil_err = "Du har ingen biler registrert"; ?> <!-- Hvis bruker ikke har biler settes error melding -->
                        <?php endif; ?>

                    </div>

                    <!--<div class="layout__formFooter">
                        <span><?php #echo $ingenbil_err; ?></span>
                        <p>Legg til en bil <a href="">her</a></p>
                    </div>-->
                    
                </div>

            </div>
        </main>

         <!-- Script for for vis-biler knapp -->
        <script>
            const button = document.getElementById('toggleCars');
            const arrow = document.getElementById('Layer_1');

            button.addEventListener('click', () => {
                // Toggle the 'rotated' class on the arrow (SVG)
                arrow.classList.toggle('rotated');
            });
        </script>
    </body>
</html>