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
                <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'administrator'): ?>
                    <li class="<?php echo ($currentPage == 'settings.php') ? 'aktiv' : ''; ?>">
                        <a href="settings.php" class="settings_ikon">
                            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 45.532 45.532">
                                <path d="M39.139,26.282C38.426,25.759,38,24.919,38,24.034s0.426-1.725,1.138-2.247l3.294-2.415	
                                c0.525-0.386,0.742-1.065,0.537-1.684c-0.848-2.548-2.189-4.872-3.987-6.909c-0.433-0.488-1.131-0.642-1.728-0.38l-3.709,1.631	
                                c-0.808,0.356-1.749,0.305-2.516-0.138c-0.766-0.442-1.28-1.23-1.377-2.109l-0.446-4.072c-0.071-0.648-0.553-1.176-1.191-1.307	
                                c-2.597-0.531-5.326-0.54-7.969-0.01c-0.642,0.129-1.125,0.657-1.196,1.308l-0.442,4.046c-0.097,0.88-0.611,1.668-1.379,2.11	
                                c-0.766,0.442-1.704,0.495-2.515,0.138l-3.729-1.64c-0.592-0.262-1.292-0.11-1.725,0.377c-1.804,2.029-3.151,4.35-4.008,6.896	
                                c-0.208,0.618,0.008,1.301,0.535,1.688l3.273,2.4C9.574,22.241,10,23.081,10,23.966s-0.426,1.725-1.138,2.247l-3.294,2.415	
                                c-0.525,0.386-0.742,1.065-0.537,1.684c0.848,2.548,2.189,4.872,3.987,6.909c0.433,0.489,1.133,0.644,1.728,0.38l3.709-1.631	
                                c0.808-0.356,1.748-0.305,2.516,0.138c0.766,0.442,1.28,1.23,1.377,2.109l0.446,4.072c0.071,0.648,0.553,1.176,1.191,1.307	
                                C21.299,43.864,22.649,44,24,44c1.318,0,2.648-0.133,3.953-0.395c0.642-0.129,1.125-0.657,1.196-1.308l0.443-4.046	
                                c0.097-0.88,0.611-1.668,1.379-2.11c0.766-0.441,1.705-0.493,2.515-0.138l3.729,1.64c0.594,0.263,1.292,0.111,1.725-0.377	
                                c1.804-2.029,3.151-4.35,4.008-6.896c0.208-0.618-0.008-1.301-0.535-1.688L39.139,26.282z M24,31c-3.866,0-7-3.134-7-7s3.134-7,7-7	
                                s7,3.134,7,7S27.866,31,24,31z"/>
                            </svg>

                        </a>
                    </li>
                
                <?php endif; ?>
                
            </ul>
        </nav>
    </header>
    
                <!-- her ifra ekspanderes det fra de andre sidene -->