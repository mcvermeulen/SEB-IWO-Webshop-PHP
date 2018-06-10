<?php
session_start();

if(!isset($_SESSION['gebruiker'])){
    header('Location: index.php');
}

$_SESSION['gebruiker'] = [];
session_destroy();
header('Location: index.php');

?>