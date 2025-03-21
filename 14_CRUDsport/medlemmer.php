<?php
include 'meny.php';
include 'db_connection.php';








// Standard SQL for å hente alle medlemmer
if (empty($sok)) {
    $sql = "SELECT * FROM medlem";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
} else {
    // SQL for søk – treff kun i starten av et ord
    $sql = "SELECT * FROM medlem 
            WHERE LOWER(fornavn) LIKE :sok_start 
            OR LOWER(etternavn) LIKE :sok_start";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        'sok_start' => strtolower($sok) . '%', // Treffer kun starten av ord
    ]);
}


$medlemmer = $stmt->fetchAll(PDO::FETCH_ASSOC);

$antall_treff = count($medlemmer);

function highlightMatch($text, $search) {
    if (!$search) return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
    $escapedSearch = preg_quote($search, '/'); // Escape spesielle tegn
    return preg_replace("/($escapedSearch)/i", "<span class='highlight'>$1</span>", htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
}


?>
        <div class="container-lg bg-primary mt-3">
            <p class="fs-1 text-center">SPORT Idrettsforening</p>
            <?php if (empty($sok)): ?>
                <p class="fs-5 text-center"><?php echo $antall_treff; ?> medlemmer</p>
            <?php else: ?>
                <p class="fs-5 text-center"><?php echo "'$sok' ga $antall_treff treff"; ?></p>
            <?php endif; ?>
            <div class="container-lg text-center">
                <div class="table-responsive-lg">
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Fornavn</th>
                                <th>Etternavn</th>
                                <th>Adresse</th>
                                <th>Postnr</th>
                                <th>Poststed</th>
                                <th>Født</th>
                                <th>Telefon</th>
                                <th>Mail</th>
                                <th>Betalt</th>
                                <th>Endre / Slett</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($medlemmer as $medlem): ?>
                                <tr>
                                    <td><?php echo highlightMatch($medlem['fornavn'] ?? '', $sok); ?></td>
                                    <td><?php echo highlightMatch($medlem['etternavn'] ?? '', $sok); ?></td>
                                    <td><?php echo highlightMatch($medlem['adresse'] ?? '', $sok); ?></td>
                                    <td><?php echo highlightMatch($medlem['postnr'] ?? '', $sok); ?></td>
                                    <td><?php echo highlightMatch($medlem['poststed'] ?? '', $sok); ?></td>
                                    <td><?php echo highlightMatch($medlem['fodt'] ?? '', $sok); ?></td>
                                    <td><?php echo highlightMatch($medlem['telefon'] ?? '', $sok); ?></td>
                                    <td><?php echo highlightMatch($medlem['mail'] ?? '', $sok); ?></td>
                                    <td><?php echo highlightMatch($medlem['betalt'] ?? '', $sok); ?></td>
                                    <td><a href="update.php?type=endre&id=<?php echo urlencode($medlem['m_nr']); ?>"><svg class="svg_endre profil" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="25px" width="25px" fill="currentColor"> <path d="M 18 2 L 15.585938 4.4140625 L 19.585938 8.4140625 L 22 6 L 18 2 z M 14.076172 5.9238281 L 3 17 L 3 21 L 7 21 L 18.076172 9.9238281 L 14.076172 5.9238281 z"/></svg></a><a href="delete.php?type=slett&id=<?php echo urlencode($medlem['m_nr']); ?>"><svg class="svg_slett profil" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 24 24" width="25px" height="25px" fill="currentColor"><path d="M 10 2 L 9 3 L 4 3 L 4 5 L 5 5 L 5 20 C 5 20.522222 5.1913289 21.05461 5.5683594 21.431641 C 5.9453899 21.808671 6.4777778 22 7 22 L 17 22 C 17.522222 22 18.05461 21.808671 18.431641 21.431641 C 18.808671 21.05461 19 20.522222 19 20 L 19 5 L 20 5 L 20 3 L 15 3 L 14 2 L 10 2 z M 7 5 L 17 5 L 17 20 L 7 20 L 7 5 z M 9 7 L 9 18 L 11 18 L 11 7 L 9 7 z M 13 7 L 13 18 L 15 18 L 15 7 L 13 7 z"/></svg></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search");

            searchInput.addEventListener("search", function () {
                if (!this.value) { // Hvis inputen er tom etter X trykkes
                    const url = new URL(window.location);
                    url.searchParams.delete("sok"); // Fjern søkeparameteren fra URL
                    window.location.href = url; // Last siden på nytt
                }
            });
        });
    </script>
</body>