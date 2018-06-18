<?php
require_once 'core.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['gebruiker']) && !empty($_POST['wachtwoord'])) {
    $dbh = DatabaseConnect();

    $selectQueryGebruiker = $dbh->prepare("SELECT GEBRUIKERSNAAM FROM GEBRUIKER WHERE GEBRUIKERSNAAM = :naam AND WACHTWOORD = :wachtwoord");
    $selectQueryGebruiker->execute([
        ':naam' => $_POST['gebruiker'],
        ':wachtwoord' => hash('sha512', ($_POST['wachtwoord'].getenv('SALT')))
    ]);
    $row = $selectQueryGebruiker->fetch();
    if (isset($row['GEBRUIKERSNAAM'])) {
        $_SESSION['gebruiker'] = $row['GEBRUIKERSNAAM'];
    }

    $selectQueryGebruiker = null;
    $dbh = null;

    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

?>