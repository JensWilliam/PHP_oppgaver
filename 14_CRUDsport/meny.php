<?php
session_name('sport');
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generer et nytt token
}

$current_page = basename($_SERVER['PHP_SELF']);

if ($current_page === 'medlemmer.php') {
    $sok = isset($_GET['sok']) ? trim($_GET['sok']) : '';
    $sok = strtolower($sok);
}


?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</head>


<body>
    <div class="container-lg">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">SPORT IL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <?php if ($current_page === 'medlemmer.php'): ?>
                    <form class="d-flex" role="search" action="medlemmer.php" method="GET">
                        <input id="search" class="form-control me-2" type="search" placeholder="Søk for- eller etternavn" aria-label="Search" name="sok" value="<?php echo htmlspecialchars($sok) ?>">
                        <button class="btn btn-outline-success" type="submit">Søk</button>
                    </form>
                <?php endif; ?>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php">Hjem</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="medlemmer.php">Se alle Medlemmer</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Medlemmer
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Betalt</a></li>
                            <li><a class="dropdown-item" href="#">Ikke betalt</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="ny_medlem.php">Legg til nytt medlem</a></li>
                        </ul>
                    </li>
                    
                </ul>
                
            </div>
        </div>
    </nav>
    