<?php
require_once 'includes/core.php';
$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$total = 0;
$dbh = DatabaseConnect();

if (empty($_POST['zoek'])) {
    $sth = $dbh->prepare("SELECT P.* FROM (SELECT ROW_NUMBER() OVER(ORDER BY PRODUCTNUMMER) AS RowID, (SELECT COUNT(*) FROM PRODUCT) AS Total, * FROM PRODUCT) AS P WHERE RowID > :start AND RowID <= :end");
    $sth->execute(array(':start' => ($page-1) * $limit, ':end' => $page * $limit));
} else {
    $limit = 9999;
    $sth = $dbh->prepare("SELECT P.* FROM (SELECT ROW_NUMBER() OVER(ORDER BY PRODUCTNUMMER) AS RowID, (SELECT COUNT(*) FROM PRODUCT) AS Total, * FROM PRODUCT) AS P WHERE RowID > :start AND RowID <= :end AND PRODUCTNAAM like :zoek");
    $sth->execute(array(':start' => ($page-1) * $limit, ':end' => $page * $limit, ':zoek' => "%$_POST[zoek]%"));
}
while ($row = $sth->fetchObject()) {
    $total = $row->Total;
    $producten[] = genereerArtikel($row);
}
$sth = $dbh->query("SELECT TOP 3 * FROM PRODUCT ORDER BY NEWID()");
while ($row = $sth->fetchObject()) {
    $uitgelicht[] = genereerArtikel($row, false);
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
<?php echo genereerPagination($page, $limit, $total) ?>

<?php include 'includes/footer.html'; ?>

</body>
</html>

