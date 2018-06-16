<?php
session_start();
require_once 'includes/core.php';

if(!isset($_SESSION['winkelwagen']) || empty($_SESSION['winkelwagen'])){
    header('Location: index.php');
}


/* Zie voor deze check mijn opmerking in winkelwagen.php */
$inhoud = array();
foreach($_SESSION['winkelwagen'] as $array){
    if(array_key_exists('productnummer', $array)){
        $inhoud[] = $array;
    }
}
$dbh = DatabaseConnect();
foreach ($inhoud as $item) {
    $sth = $dbh->prepare("SELECT * FROM PRODUCT WHERE PRODUCTNUMMER = :id");
    $sth->execute(array(':id' => $item['productnummer']));
    $row = $sth->fetchObject();
    $nieuweVoorraad = ($row->VOORRAAD - $item['aantal'] > 0) ? $row->VOORRAAD - $item['aantal'] : null;

    $updateVoorraad = $dbh->prepare("UPDATE PRODUCT SET VOORRAAD = :aantal WHERE PRODUCTNUMMER = :id");
    $updateVoorraad->execute(array(':aantal' => $nieuweVoorraad, ':id' => $item['productnummer']));
}

$dbh = null;
$sth = null;
$updateVoorraad = null;

$_SESSION['winkelwagen'] = [];
unset($_SESSION['winkelwagen']);
header('Location: winkelwagen.php');

?>