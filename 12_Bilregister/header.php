<?php
session_name('Bilregister');
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genererer et sikkert token
}


$currentPage = basename($_SERVER['PHP_SELF']);
?>


<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GearHub - ditt bilregister</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="script.js" defer></script> <!-- kobler til javaScript -->
</head>
<body>
    <header>
        <a href="index.php" class="minlogo">
            <img src="bilder/NORGES STØRTE BILREGISTER.png" alt="Bilregister logo">
        </a>
        <nav>
            <ul>
                <li class="<?php echo ($currentPage == 'index.php') ? 'aktiv' : ''; ?>"><a href="index.php">Hjem</a></li>
                <li class="<?php echo ($currentPage == 'bilregister.php') ? 'aktiv' : ''; ?>"><a href="bilregister.php">Bilregister</a></li>
                <li class="<?php echo ($currentPage == 'liste.php') ? 'aktiv' : ''; ?>"><a href="liste.php">Liste</a></li>
                <li class="<?php echo ($currentPage == 'sok.php') ? 'aktiv' : ''; ?>"><a href="sok.php">Søk</a></li>

                <!-- Vis logg ut-knapp hvis brukeren er logget inn -->
                <?php if (isset($_SESSION['innlogget']) && $_SESSION['innlogget'] === true): ?>
                    <li class="<?php echo ($currentPage == 'profil.php') ? 'aktiv' : ''; ?>">
                        <a href="profil.php">
                            <svg fill="currentColor" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 45.532 45.532" xml:space="preserve">
                                <g>
                                    <path d="M22.766,0.001C10.194,0.001,0,10.193,0,22.766s10.193,22.765,22.766,22.765c12.574,0,22.766-10.192,22.766-22.765
                                        S35.34,0.001,22.766,0.001z M22.766,6.808c4.16,0,7.531,3.372,7.531,7.53c0,4.159-3.371,7.53-7.531,7.53
                                        c-4.158,0-7.529-3.371-7.529-7.53C15.237,10.18,18.608,6.808,22.766,6.808z M22.761,39.579c-4.149,0-7.949-1.511-10.88-4.012
                                        c-0.714-0.609-1.126-1.502-1.126-2.439c0-4.217,3.413-7.592,7.631-7.592h8.762c4.219,0,7.619,3.375,7.619,7.592
                                        c0,0.938-0.41,1.829-1.125,2.438C30.712,38.068,26.911,39.579,22.761,39.579z"/>
                                </g>
                            </svg>
                        </a>    
                    </li>
                    
                    
                
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    
                <!-- her ifra ekspanderes det fra de andre sidene -->