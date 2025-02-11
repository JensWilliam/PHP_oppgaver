<?php
include 'header.php';

if (!isset($_SESSION['innlogget']) || $_SESSION['innlogget'] !== true) {
    header("Location: login.php");
    exit();
}

include 'db_connection.php';
?>
        <main>
            <div class="wrapper">
                <div class="velkommen">
                    <h2>Registrer ny bil</h2>
                </div>

                <div class="layout bilregister">
                    <div class="layout__form bilregister">
                        <div class="layout__formHeader bilregister">
                            <h3>Registrer ny bil</h3>
                        </div>

                        <form class="form bilregister" action="ny_bil.php" method="POST">
                            <div class="form__group bilregister">
                                
                            </div>

                            <div class="knapp bilregister">
                                <button class="btn__form bilregister" type="submit">Send inn</button>
                            </div>                            
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>