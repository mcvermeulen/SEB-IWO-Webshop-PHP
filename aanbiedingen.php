<?php
require_once 'includes/core.php';

$dbh = DatabaseConnect();
$sth = $dbh->query("SELECT * FROM PRODUCT WHERE ACTIEPRIJS IS NOT NULL ");

$producten = array();
while ($row = $sth->fetchObject()) {
    $producten[] = genereerArtikel($row);
}

$dbh = null;
$sth = null;

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FairFood | Aanbiedingen</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
          integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
    <h2>Nu in de aanbieding:</h2>
    <section class="row">
        <?php foreach ($producten as $prod) { echo $prod; } ?>
    </section>
</main>

<?php include 'includes/footer.html'; ?>

</body>
</html>