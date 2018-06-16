<?php
session_start();
require_once 'includes/core.php';

$totaalbedrag = 0;

/* Deze eerste check is om te zorgen dat de winkelwagen bij het verversen van de winkelwagen het product in een eventuele
$_POST array opnieuw in de winkelwagen zet */
if(isset($_SESSION['newVisit'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_SESSION['winkelwagen'])) {
            $_SESSION['winkelwagen'] = array($_POST);
        } else {

            /*Controleren of dit productnummer al in de winkelwagen zit */
            $nieuwProduct = true;
            for ($i = 0; $i < count($_SESSION['winkelwagen']); $i++) {
                if ($_POST['productnummer'] == $_SESSION['winkelwagen'][$i]['productnummer']) {

                    /* Zo ja, dan wordt het product niet toegevoegd als een nieuwe array, maar verhogen we het aantal in de bestaande array */
                    $nieuwProduct = false;
                    $_SESSION['winkelwagen'][$i]['aantal'] += $_POST['aantal'];
                }
            }
            if ($nieuwProduct) {
                /* Als we dit product nog niet in de winkelwagen hadden, dan voegen we het toe */
                $_SESSION['winkelwagen'][] = $_POST;
            }
        }
    }
    unset($_SESSION['newVisit']);
}

if(empty($_SESSION['winkelwagen'])){
    $winkelwageninhoud = "Uw winkelwagen is leeg";
}
else {
    if(isset($_SESSION['gebruiker'])){
        $afrekenen = "<a class='button' href='winkelwagenCheckout.php'>Afrekenen</a>";
    }
    else {
        $afrekenen = "Log in of <a href='registreren.php'>maak een account aan</a> om af te rekenen.";
    }

    $winkelwageninhoud = "<section class='winkelmandoverzicht'>
                            <div class='winkelmandItem'>
                                <div class='winkelmandAfbeelding'></div>
                                <div class='winkelmandDetails'><h3>Product</h3></div>
                                <div class='winkelmandDetails'><h3>Aantal</h3></div>
                                <div class='winkelmandDetails'><h3>Subtotaal</h3></div>
                            </div>";
    $dbh = DatabaseConnect();

    /* Deze check is nodig om een pure array met alleen winkelwagen items te maken; in de gaten houden, het kan zijn dat
    er nog meer condities toegevoegd moeten worden aan de IF. Ik gebruik deze check ook in winkelwagenCheckout.php */
    $inhoud = array();
    foreach($_SESSION['winkelwagen'] as $array){
        if(array_key_exists('productnummer', $array)){
            $inhoud[] = $array;
        }
    }
    /* Als ik hier $_SESSION['winkelwagen'] i.p.v. $inhoud had gebruikt in de foreach, dan kan onder sommige omstandigheden
    de site proberen om bv. een gebruiker of een andere foute waarde in de winkelwagen te stoppen. Bijvoorbeeld als een
    uitgelogde gebruiker eerst items in de winkelwagen stopt, en daarna inlogt om af te rekenen. */
    foreach ($inhoud as $item) {
        $query = $dbh->prepare("SELECT * FROM PRODUCT WHERE PRODUCTNUMMER = :id");
        $query->execute(array(':id' => $item['productnummer']));
        $row = $query->fetch();
        $minValue = ($row['VOORRAAD'] >= 1 ? 1 : 0);
        $maxValue = (empty($row['VOORRAAD']) ? 0 : $row['VOORRAAD']);
        if ($row['ACTIEPRIJS'] != null) {
            $prijs = $row['ACTIEPRIJS'];
        } else {
            $prijs = $row['PRIJS'];
        }
        $subtotaal = $item['aantal'] * $prijs;
        $totaalbedrag += $subtotaal;
        $subtotaal = number_format($subtotaal, 2);
        $winkelwageninhoud .= "<div class='winkelmandItem'>
                                    <div class='winkelmandAfbeelding'>
                                        <a href = 'product.php?id=$item[productnummer]'><img src='$row[AFBEELDING_KLEIN]' alt='Foto van $row[PRODUCTNAAM]'/></a></div>
                                        <div class='winkelmandDetails'><p><strong>$row[PRODUCTNAAM]</strong></p><p>Stukprijs:  &euro; $prijs</p><p>$row[CATEGORIE]</p></div>
                                        <div class='winkelmandDetails'> <form method='post' action='winkelwagenUpdate.php'>
                                            <input type='number' id='aantal' name='aantal' value='$item[aantal]' min = '$minValue' max = '$maxValue'/>
                                            <input type='hidden' name='productnummer' value = '$row[PRODUCTNUMMER]'/>
                                            <button>Herbereken</button></form></div>
                                        <div class='winkelmandDetails subtotaal'> &euro; $subtotaal</div>
                                        <div class='winkelmandDetails'> <a href='winkelwagenUpdate.php?productnummer=$item[productnummer]'>Verwijderen</a></div>
                                </div><br>";
    }

    $totaalbedrag = number_format($totaalbedrag, 2);

    $winkelwageninhoud .= "<div class='winkelmandTotaal row'>
                                <div class='winkelmandAfbeelding'></div>
                                <div class='winkelmandDetails'></div>
                                <div class='winkelmandDetails'><strong>Totaal: </strong></div>
                                <div class='winkelmandDetails'><strong>&euro; $totaalbedrag</strong></div>
                                <div class='winkelmandDetails'>$afrekenen</div>
                            </div></section>";
}

$dbh = null;
$query = null;


   //echo "<pre>";
    //print_r($_SESSION['winkelwagen']);
    //print_r($inhoud);
    //echo "</pre>";
    //session_destroy();

?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <?php require_once 'includes/head.html'; ?>
    <title>FairFood | Uw winkelwagen</title>
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Winkelwagen</h1>

    <?=$winkelwageninhoud?>

</main>

<?php include 'includes/footer.html'; ?>

</body>
</html>