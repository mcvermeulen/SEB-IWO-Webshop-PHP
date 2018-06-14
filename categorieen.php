<?php
session_start();
require_once 'includes/core.php';

//change values if requested
if ($_SERVER['REQUEST_METHOD'] === "GET" || $_SERVER['REQUEST_METHOD'] === "POST"){
    if (!empty($_GET['limit'])){
        $_SESSION['limit'] = $_GET['limit'];
    }
    if (!empty($_GET['categorie'])) {
        if ($_GET['categorie'] === 'alles') {
            unset($_SESSION['categorie']);
            $categorie = null;
        } else {
            $_SESSION['categorie'] = $_GET['categorie'];
        }
    }
    if (!empty($_POST['zoek'])){
        $_SESSION['zoek'] = $_POST['zoek'];
    }
}

// set default values
$page = $_GET['page'] ?? 1;
$limit = 10;
$categorie = null;
$total = 0;

// override defaults if set by user
if (isset($_SESSION['limit'])) {
    $limit = $_SESSION['limit'];
}
if (isset($_SESSION['zoek'])) {
    $zoek = $_SESSION['zoek'];
}
if (isset($_SESSION['categorie'])) {
    $categorie = $_SESSION['categorie'];
}
$dbh = DatabaseConnect();

// Haal alle categorieen op
$sth = $dbh->query('SELECT CATEGORIENAAM FROM CATEGORIE');
$categorieen = "<nav class='categorieen open'>
                   <ul>
                       <li><a href='?categorie=alles'>Alles</a></li>";
while ($row = $sth->fetchObject()) {
    $categorieen .= "<li><a href='?categorie=$row->CATEGORIENAAM'>$row->CATEGORIENAAM</a></li>";
}
$categorieen .= "</ul></nav>";

// Haal alle producten op, binnen een categorie als dat is aangegeven
if (empty($categorie)) {
    $sth = $dbh->prepare("SELECT P.* FROM (SELECT ROW_NUMBER() OVER(ORDER BY PRODUCTNUMMER) AS RowID, (SELECT COUNT(*) FROM PRODUCT) AS Total, * FROM PRODUCT) AS P WHERE RowID > :start AND RowID <= :end");
    $sth->execute(array(':start' => ($page - 1) * $limit, ':end' => $page * $limit));
} else {
    $sth = $dbh->prepare("SELECT P.* FROM (SELECT ROW_NUMBER() OVER(ORDER BY PRODUCTNUMMER) AS RowID, (SELECT COUNT(*) FROM PRODUCT WHERE CATEGORIE = :categorie1) AS Total, * FROM PRODUCT WHERE CATEGORIE = :categorie2) AS P WHERE RowID > :start AND RowID <= :end");
    $sth->execute(array(':start' => ($page - 1) * $limit, ':end' => $page * $limit, ":categorie1" => $categorie, ":categorie2" => $categorie));
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