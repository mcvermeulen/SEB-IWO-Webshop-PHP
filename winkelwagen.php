<?php
session_start();
require_once 'includes/core.php';

$totaalbedrag = 0;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!isset($_SESSION['winkelwagen'])){
        $_SESSION['winkelwagen'] = array($_POST);
    }
    else{

        /*Controleren of dit productnummer al in de winkelwagen zit */
        $nieuwProduct = true;
        for($i = 0; $i < count($_SESSION['winkelwagen']); $i++){
            if($_POST['productnummer'] == $_SESSION['winkelwagen'][$i]['productnummer']){

                /* Zo ja, dan wordt het product niet toegevoegd als een nieuwe array, maar verhogen we het aantal in de bestaande array */
                $nieuwProduct = false;
                // TODO: na database connectie hier nog een controle van de voorraad voor hangen
                $_SESSION['winkelwagen'][$i]['aantal'] += $_POST['aantal'];
            }
        }
        if($nieuwProduct){
            /* Als we dit product nog niet in de winkelwagen hadden, dan voegen we het toe */
            $_SESSION['winkelwagen'][] = $_POST;
        }
    }
}

if(empty($_SESSION['winkelwagen'])){
    $winkelwageninhoud = "Uw winkelwagentje is leeg";
}
else {
    $winkelwageninhoud = "<section class='winkelmandoverzicht column'>
                            <div class='winkelmandItem row'>
                                <div class='winkelmandAfbeelding'></div>
                                <div class='winkelmandDetails'><h3>Productgegevens</h3></div>
                                <div class='winkelmandDetails'><h3>Aantal</h3></div>
                                <div class='winkelmandDetails'><h3>Subtotaal</h3></div>
                            </div>";
    $dbh = DatabaseConnect();


    foreach ($_SESSION['winkelwagen'] as $item) {
        $query = $dbh->prepare("SELECT * FROM PRODUCT WHERE PRODUCTNUMMER = :id");
        $query->execute(array(':id' => $item['productnummer']));
        $row = $query->fetch();
        if ($row['ACTIEPRIJS'] != null) {
            $prijs = $row['ACTIEPRIJS'];
        } else {
            $prijs = $row['PRIJS'];
        }
        $subtotaal = $item['aantal'] * $prijs;
        $totaalbedrag += $subtotaal;
        $subtotaal = number_format($subtotaal, 2);
        $winkelwageninhoud .= "<div class='winkelmandItem row'>
                                    <div class='winkelmandAfbeelding'>
                                        <a href = 'product.php?id=$item[productnummer]'><img src='$row[AFBEELDING_KLEIN]' alt='Foto van $row[PRODUCTNAAM]'/></a></div>
                                        <div class='winkelmandDetails'><p><strong>$row[PRODUCTNAAM]</strong></p><p>Prijs:  &euro; $prijs</p><p>$row[CATEGORIE]</p></div>
                                        <div class='winkelmandDetails'> <form method='post' action='winkelwagenUpdate.php'>
                                            <input type='number' id='aantal' name='aantal' value='$item[aantal]' min = '1'/>
                                            <input type='hidden' name='productnummer' value = '$row[PRODUCTNUMMER]'/>
                                            <button>Herbereken</button></form></div>
                                        <div class='winkelmandDetails'> &euro; $subtotaal</div>
                                        <div class='winkelmandDetails'> <a href='winkelwagenUpdate.php?productnummer=$item[productnummer]'>Verwijderen</a></div>
                                </div><br>";
    }

    $totaalbedrag = number_format($totaalbedrag, 2);

    $winkelwageninhoud .= "<div class='winkelmandTotaal row'>
                                <div class='winkelmandAfbeelding'></div>
                                <div class='winkelmandDetails'></div>
                                <div class='winkelmandDetails'><strong>Totaal: </strong></div>
                                <div class='winkelmandDetails'><strong>&euro; $totaalbedrag</strong></div>
                                <div class='winkelmandDetails'><a class='button' href='winkelwagenCheckout.php'>Afrekenen</a></div>
                            </div></section>";
}

  // echo "<pre>";
  //  print_r($_SESSION['winkelwagen']);
  //  echo "</pre>";
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