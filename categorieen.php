<?php
session_start();
require_once 'includes/core.php';

$page = $_GET['page'] ?? 1;
$limit = $_GET['limit'] ?? 10;
$total = 0;
$dbh = DatabaseConnect();
$sth = $dbh->query('SELECT CATEGORIENAAM FROM CATEGORIE');

$categorieen = "<nav class='categorieen open'>
                   <ul>";
while ($row = $sth->fetchObject()) {
    $categorieen .= "<li><a href='?categorie=$row->CATEGORIENAAM'>$row->CATEGORIENAAM</a></li>";
}
$categorieen .= "</ul></nav>";

if (empty($_GET['categorie'])) {
    $sth = $dbh->prepare("SELECT P.* FROM (SELECT ROW_NUMBER() OVER(ORDER BY PRODUCTNUMMER) AS RowID, (SELECT COUNT(*) FROM PRODUCT) AS Total, * FROM PRODUCT) AS P WHERE RowID > :start AND RowID <= :end");
    $sth->execute(array(':start' => ($page - 1) * $limit, ':end' => $page * $limit));
} else {
    $sth = $dbh->prepare("SELECT P.* FROM (SELECT ROW_NUMBER() OVER(ORDER BY PRODUCTNUMMER) AS RowID, (SELECT COUNT(*) FROM PRODUCT WHERE CATEGORIE = :categorie1) AS Total, * FROM PRODUCT WHERE CATEGORIE = :categorie2) AS P WHERE RowID > :start AND RowID <= :end");
    $sth->execute(array(':start' => ($page - 1) * $limit, ':end' => $page * $limit, ":categorie1" => $_GET['categorie'], ":categorie2" => $_GET['categorie']));
}
while ($row = $sth->fetchObject()) {
    $total = $row->Total;
    $producten[] = genereerArtikel($row);
}
if (empty($producten)) {
    $producten[] = "Er zijn geen producten gevonden in deze categorie";
}

$sth = null;
$dbh = null;
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php require_once 'includes/head.html'; ?>
    <title>FairFood</title>
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
    <?= $categorieen ?>
    <section class="row">
        <?php foreach ($producten as $prod) {
            echo $prod;
        } ?>
    </section>
</main>
<?php
    echo genereerPagination($page, $limit, $total);
    include 'includes/footer.html';
?>

</body>
</html>