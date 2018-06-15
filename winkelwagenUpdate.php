<?php
session_start();

if(!isset($_SESSION['winkelwagen'])){
    header ('Location: index.php');
}

/* Verwijderen van een item uit de winkelwagen */
if ($_SERVER["REQUEST_METHOD"] == "GET"){
    for ($i = 0; $i < count($_SESSION['winkelwagen']); $i++) {
        if (array_search($_GET['productnummer'], $_SESSION['winkelwagen'][$i]) != null) {
            $index = $i;
        }
    }
    unset($_SESSION['winkelwagen'][$index]);
    $_SESSION['winkelwagen'] = array_values($_SESSION['winkelwagen']);
}

/* Aantallen aanpassen in de winkelwagen */
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    for ($i = 0; $i < count($_SESSION['winkelwagen']); $i++) {
        if (array_search($_POST['productnummer'], $_SESSION['winkelwagen'][$i]) != null) {
            $index = $i;
        }
    }
    $_SESSION['winkelwagen'][$index]['aantal'] = $_POST['aantal'];
}

header ('Location: winkelwagen.php');

?>