<?php
require_once 'includes/core.php';

$dbh = DatabaseConnect();

$sth = $dbh->query("SELECT * FROM PRODUCT");
while ($row = $sth->fetchObject()) {
    $producten[] = genereerArtikel($row);
}

$sth = $dbh->query("SELECT TOP 3 * FROM PRODUCT ORDER BY NEWID()");
while ($row = $sth->fetchObject()) {
    $uitgelicht[] = genereerArtikel($row);
}

$dbh = null;
$sth = null;

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
    <section class="row uitgelicht">
        <h2>Uitgelicht</h2>
        <?php foreach ($uitgelicht as $prod) { echo $prod; } ?>
    </section>
    <aside>Wij bij FairFood staan voor kwaliteit, betrouwbaarheid en eerlijkheid.<br/>
        U bent bij ons aan het juiste adres voor FairTrade en biologische producten tegen een eerlijke prijs.
    </aside>
    <section class="row">
        <?php foreach ($producten as $prod) { echo $prod; } ?>
    </section>
</main>

<?php include 'includes/footer.html'; ?>

</body>
</html>

