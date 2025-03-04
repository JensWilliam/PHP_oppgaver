<?php
include 'db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $registreringsnummer = $_POST['registreringsnummer'];
    $merke = $_POST['merke'];
    $modell = $_POST['modell'];
    $arsmodell = $_POST['arsmodell'];
    $farge = $_POST['farge'];

    

    $sql = "UPDATE bil SET registreringsnummer = ?, merke = ?, modell = ?, arsmodell = ?, farge = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$registreringsnummer, $merke, $modell, $arsmodell, $farge, $id]);

    header("Location: profil.php");
    exit();
}

?>