<?php
session_start();
require_once 'includes/core.php';

/* Dit is t.b.v. een check in winkelwagen.php, zie de opmerking daar */
if(isset($_SESSION['newVisit'])){
    unset($_SESSION['newVisit']);
    $_SESSION['newVisit'] = true;
} else {
    $_SESSION['newVisit'] = true;
}

$dbh = DatabaseConnect();
$sth = $dbh->prepare("SELECT * FROM PRODUCT WHERE PRODUCTNUMMER = :id");
$sth->execute(array(':id' => $_GET['id']));

$row = $sth->fetchObject();
$title = $row->PRODUCTNAAM;
$status = "";
$minValue = $row->VOORRAAD >= 1 ? 1 : 0;
$maxValue = (empty($row->VOORRAAD) ? 0 : $row->VOORRAAD);
$product = "<section class='row product'>
                <div class='column'>
                    <img src='$row->AFBEELDING_GROOT' alt='Foto van $row->PRODUCTNAAM'/>
                    <p>$row->OMSCHRIJVING</p>
                </div>
                <div class='column'>
                    <h2>$row->PRODUCTNAAM</h2>";
if($row->VOORRAAD > 0) {
    if (!empty($row->ACTIEPRIJS)) {
        $product .= "<span class='prijs actie'>&euro; $row->ACTIEPRIJS</span>
                     <span class='prijs niet-actie'>&euro; $row->PRIJS</span>";
    } else {
        $product .= "<span class='prijs'>&euro; $row->PRIJS</span>";
    }
} else{
    $status = "disabled";
    if (!empty($row->ACTIEPRIJS)) {
        $product .= "<span class='prijs actie uitverkocht'>&euro; $row->ACTIEPRIJS</span>
                     <span class='prijs niet-actie uitverkocht'>&euro; $row->PRIJS</span><span class='actie'>(Tijdelijk uitverkocht)</span>";
    } else {
        $product .= "<span class='prijs uitverkocht'>&euro; $row->PRIJS</span><span class='actie'>(Tijdelijk uitverkocht)</span>";
    }
}
$product .=         "<form method='post' action='winkelwagen.php' class='winkelmandje'>
                        <div class='form-group'>
                            <input type='hidden' name='productnummer' value = '$row->PRODUCTNUMMER'/>
                            <label for='aantal'>Aantal: </label>
                            <input type='number' id='aantal' name='aantal' value='$minValue' min = '$minValue' max = '$maxValue'/>
                        </div>
                        <button type='submit' $status>In mijn winkelmandje <i class='fas fa-shopping-basket'></i></button>
                    </form>
                </div>
            </section>";

$sth = $dbh->prepare("SELECT TOP 3 * FROM PRODUCT WHERE PRODUCTNUMMER IN (SELECT PRODUCTNUMMER_GERELATEERD_PRODUCT FROM PRODUCT_GERELATEERD_PRODUCT WHERE PRODUCTNUMMER = :id)");
$sth->execute(array(':id' => $_GET['id']));

$related = array();
while ($row = $sth->fetchObject()) {
    $related[] = genereerArtikel($row);
}

$dbh = null;
$sth = null;
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php require_once 'includes/head.html'; ?>
    <title>FairFood | <?=$title?></title>
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
    <?=$product?>

    <section class="row uitgelicht">
        <h2>Misschien vind je dit ook leuk</h2>
        <?php foreach ($related as $prod) { echo $prod; } ?>
    </section>
</main>

<?php include 'includes/footer.html'; ?>

</body>
</html>

