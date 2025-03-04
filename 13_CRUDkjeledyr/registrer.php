<?php
include 'header.php';

include 'db_connection.php';
?>

        <main>
            <div class="skjema">
                <div class="skjema-header">
                    <h1>Registrer nytt Kjæledyr:</h1>
                </div>
                <form action="registrer.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label for="navn">Navn:</label>
                        <input type="text" id="navn" name="navn" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dyr">Dyr:</label>
                        <input type="text" id="dyr" name="dyr" required>
                    </div>
                    

                    <div class="form-group">
                        <label for="rase">Rase:</label>
                        <input type="text" id="rase" name="rase" required>
                    </div>
                    

                    <div class="form-group">
                        <label for="fodselsdato">Fødselsdato:</label>
                        <input type="date" id="fodselsdato" name="fodselsdato" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="kjonn">Kjønn:</label>

                        <select id="kjonn" name="kjonn" required>
                            <option value="Gutt">Gutt</option>
                            <option value="Jente">Jente</option>
                        </select>
                    </div>
                    
                    <div class="knapp-parent reg">
                        <input class="knapp reg" type="submit" value="Registrer">
                    </div>
                    
                </form>
            </div>



        </main>

    </body>
</html>