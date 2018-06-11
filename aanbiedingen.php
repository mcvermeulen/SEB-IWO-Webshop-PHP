<?php
session_start();
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
    <?php require_once 'includes/head.html'; ?>
    <title>FairFood | Aanbiedingen</title>
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