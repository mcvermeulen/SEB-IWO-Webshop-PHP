<?php
require_once 'includes/core.php';

$dbh = DatabaseConnect();
$sth = $dbh->prepare("SELECT * FROM PRODUCT WHERE PRODUCTNUMMER = :id");
$sth->execute(array(':id' => $_GET['id']));

$row = $sth->fetchObject();
$title = $row->PRODUCTNAAM;
$product = "<section class='row product'>
                <div class='column' style='flex-basis: 45%'>
                    <img src='$row->AFBEELDING_GROOT' alt='Foto van $row->PRODUCTNAAM'/>
                    <p>$row->OMSCHRIJVING</p>
                </div>
                <div class='column' style='flex-basis: 35%'>
                    <h2>$row->PRODUCTNAAM</h2>";
if (!empty($row->ACTIEPRIJS)) {
    $product .= "<span class='prijs actie'>&euro; $row->ACTIEPRIJS</span>
                 <span class='prijs niet-actie'>&euro; $row->PRIJS</span>";
} else {
    $product .= "<span class='prijs'>&euro; $row->PRIJS</span>";
}
$product .=         "<form class='winkelmandje'>
                        <div class='form-group'>
                            <label for='aantal'>Aantal: </label>
                            <input type='number' id='aantal' name='aantal' value='1'/>
                        </div>
                        <button type='submit'>In mijn winkelmandje <i class='fas fa-shopping-basket'></i></button>
                    </form>
                </div>
            </section>";

$sth = $dbh->prepare("SELECT TOP 3 * FROM PRODUCT WHERE PRODUCTNUMMER IN (SELECT PRODUCTNUMMER_GERELATEERD_PRODUCT FROM PRODUCT_GERELATEERD_PRODUCT WHERE PRODUCTNUMMER = :id);
");
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

