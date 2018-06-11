<?php
require_once 'includes/core.php';

$dbh = DatabaseConnect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['gebruiker']) && !empty($_POST['wachtwoord'])) {
    $selectQueryGebruiker = $dbh->prepare("SELECT GEBRUIKERSNAAM FROM GEBRUIKER WHERE GEBRUIKERSNAAM = :naam AND WACHTWOORD = :wachtwoord");
    $selectQueryGebruiker->execute([':naam' => $_POST['gebruiker'], ':wachtwoord' => $_POST['wachtwoord']]);
    $row = $selectQueryGebruiker->fetch();

    if (isset($row['GEBRUIKERSNAAM'])) {
        $_SESSION['gebruiker'] = $row['GEBRUIKERSNAAM'];
    }
    header('Location: index.php');
}

?>