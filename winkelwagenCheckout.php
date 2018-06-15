<?php
session_start();
if(!isset($_SESSION['winkelwagen']) || empty($_SESSION['winkelwagen'])){
    header('Location: index.php');
}

$_SESSION['winkelwagen'] = [];
unset($_SESSION['winkelwagen']);
header('Location: winkelwagen.php');

?>