<?php
session_start();

if(!isset($_SESSION['validRegistratie'])){
    header('Location: registreren.php');
}

require_once 'includes/core.php';
$dbh = DatabaseConnect();

if(isset($_SESSION['registratie'])){
    $gebruikersnaam = "'" . $_SESSION['registratie']['gebruikersnaam'] . "'";
    $geslacht = "'" . $_SESSION['registratie']['geslacht'] . "'";
    $voornaam = "'" . $_SESSION['registratie']['voornaam'] . "'";
    $tussenvoegsels = "null";
    $achternaam = "'" . $_SESSION['registratie']['achternaam'] . "'";
    $geboortedatum = "null";
    $telefoon = "null";
    $straatnaam = "'" . $_SESSION['registratie']['straat'] . "'";
    $huisnummer = "'" . $_SESSION['registratie']['huisnummer'] . "'";
    $postcode = "'" . $_SESSION['registratie']['postcode'] . "'";
    $woonplaats = "'" . $_SESSION['registratie']['woonplaats'] . "'";
    $email = "'" . $_SESSION['registratie']['emailRegistratie'] . "'";
    $wachtwoord = "'" . $_SESSION['registratie']['wachtwoordRegistratie'] . "'";
    $nieuwsbrief = 0;

    if(!empty($_SESSION['registratie']['tussenvoegsels'])){
        $tussenvoegsels = "'" . $_SESSION['registratie']['tussenvoegsels'] . "'";
    } if(!empty($_SESSION['registratie']['geboortedatum'])){
        $geboortedatum = "'" . $_SESSION['registratie']['geboortedatum'] . "'";
    } if(!empty($_SESSION['registratie']['telefoon'])){
        $telefoon =  "'" . $_SESSION['registratie']['telefoon'] . "'";
    } if(!empty($_SESSION['registratie']['nieuwsbrief']) && $_SESSION['registratie']['nieuwsbrief'] == "wil_nieuwsbrief_ontvangen"){
        $nieuwsbrief = 1;
    }

    $insertQuery = $dbh->prepare ("INSERT INTO GEBRUIKER ([GEBRUIKERSNAAM], [VOORNAAM], [TUSSENVOEGSEL], [ACHTERNAAM], [GEBOORTEDATUM], [TELEFOON], [STRAATNAAM], [HUISNUMMER], [POSTCODE], [WOONPLAATS], [EMAIL], [SEXE], [WACHTWOORD], [NIEUWSBRIEF]) VALUES ($gebruikersnaam, $voornaam, $tussenvoegsels, $achternaam, $geboortedatum, $telefoon, $straatnaam, $huisnummer, $postcode, $woonplaats, $email, $geslacht, $wachtwoord, $nieuwsbrief)");
    $insertQuery ->execute();

    unset($_SESSION['registratie']);
    if(isset($_SESSION['validRegistratie'])){
        unset($_SESSION['validRegistratie']);
    }
    $_SESSION['gebruiker'] = $gebruikersnaam;
    header('Location: account.php');
}

$dbh  = null;

?>