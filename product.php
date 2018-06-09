<?php
require_once 'includes/core.php';

$dbh = DatabaseConnect();
$stmt = $dbh->prepare("SELECT * FROM PRODUCT WHERE PRODUCTNUMMER = :id");
$stmt->execute(array(':id' => $_GET['id']));

$row = $stmt->fetchObject();

$product = "<section class='row product'>
                <div class='column' style='flex-basis: 45%'>
                    <img src='$row->AFBEELDING_GROOT'/>
                    <p>$row->OMSCHRIJVING</p>
                </div>
                <div class='column' style='flex-basis: 35%'>
                    <h2>$row->PRODUCTNAAM</h2>
                    <span class='prijs'>&euro; $row->PRIJS</span>
                    <form class='winkelmandje'>
                        <div class='form-group'>
                            <label for='aantal'>Aantal: </label>
                            <input type='number' id='aantal' name='aantal' value='1'/>
                        </div>
                        <button type='submit'>In mijn winkelmandje <i class='fas fa-shopping-basket'></i></button>
                    </form>
                </div>
            </section>";

$stmt = $dbh->prepare("SELECT TOP 3 * FROM PRODUCT WHERE PRODUCTNUMMER IN (SELECT PRODUCTNUMMER_GERELATEERD_PRODUCT FROM PRODUCT_GERELATEERD_PRODUCT WHERE PRODUCTNUMMER = :id);
");
$stmt->execute(array(':id' => $_GET['id']));

$related = array();
while ($row = $stmt->fetchObject()) {
    $related[] = genereerArtikel($row);
}

$dbh = null;
$stmt = null;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FairFood</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
          integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
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

