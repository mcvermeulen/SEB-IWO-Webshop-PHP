<?php
session_start();

require_once 'includes/core.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$zoek = $_REQUEST['zoek'] ?? null;
$total = 0;
$dbh = DatabaseConnect();

if (empty($zoek)) {
    $sth = $dbh->prepare("SELECT P.* FROM (SELECT ROW_NUMBER() OVER(ORDER BY PRODUCTNUMMER) AS RowID, (SELECT COUNT(*) FROM PRODUCT) AS Total, * FROM PRODUCT) AS P WHERE RowID > :start AND RowID <= :end");
    $sth->execute(array(':start' => ($page-1) * $limit, ':end' => $page * $limit));
} else {
    $sth = $dbh->prepare("SELECT P.* FROM (SELECT ROW_NUMBER() OVER(ORDER BY PRODUCTNUMMER) AS RowID, (SELECT COUNT(*) FROM PRODUCT WHERE PRODUCTNAAM like :zoek1) AS Total, * FROM PRODUCT WHERE PRODUCTNAAM like :zoek2) AS P WHERE RowID > :start AND RowID <= :end");
    $sth->execute(array(':start' => ($page-1) * $limit, ':end' => $page * $limit, ':zoek1' => "%$zoek%", ':zoek2' => "%$zoek%"));
}
while ($row = $sth->fetchObject()) {
    $total = $row->Total;
    $producten[] = genereerArtikel($row);
}
if (empty($producten)) {
    $producten[] = "Er zijn geen producten gevonden";
}
$sth = $dbh->query("SELECT TOP 3 * FROM PRODUCT ORDER BY NEWID()");
while ($row = $sth->fetchObject()) {
    $uitgelicht[] = genereerArtikel($row, false);
}
if (empty($uitgelicht)) {
    $uitgelicht[] = "Er zijn geen producten gevonden";
}
$dbh = null;
$sth = null;

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <title>FairFood</title>
    <?php require_once 'includes/head.html'; ?>
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
<?php echo genereerPagination($page, $limit, $total, $zoek) ?>

<?php include 'includes/footer.html'; ?>

</body>
</html>

