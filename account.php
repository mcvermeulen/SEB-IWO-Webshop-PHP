<?php

include 'includes/signin.php';

if(!isset($_SESSION['gebruiker'])){
    header('Location: index.php');
}

require_once 'includes/core.php';
$dbh = DatabaseConnect();
$huidigeGebruiker = $_SESSION['gebruiker'];


$selectQueryAccount = $dbh->prepare("SELECT * FROM GEBRUIKER WHERE GEBRUIKERSNAAM = :gebruiker");
$selectQueryAccount->execute([':gebruiker' => $huidigeGebruiker]);

$html = "<div>";

while ($row = $selectQueryAccount->fetch()){
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Gebruikersnaam</li><li class = 'accountinfoData'>" . $row['GEBRUIKERSNAAM'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Aanhef</li><li class = 'accountinfoData'>" . ($row['SEXE'] == 'M' ? 'Dhr.' : 'Mvr.') . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Voornaam</li><li class = 'accountinfoData'>" . $row['VOORNAAM'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Tussenvoegsels</li><li class = 'accountinfoData'>" . $row['TUSSENVOEGSEL'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Achternaam</li><li class = 'accountinfoData'>" . $row['ACHTERNAAM'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Geboortedatum</li><li class = 'accountinfoData'>" . $row['GEBOORTEDATUM'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Telefoon</li><li class = 'accountinfoData'>" . $row['TELEFOON'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Straatnaam</li><li class = 'accountinfoData'>" . $row['STRAATNAAM'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Huisnummer</li><li class = 'accountinfoData'>" . $row['HUISNUMMER'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Postcode</li><li class = 'accountinfoData'>" . $row['POSTCODE'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Woonplaats</li><li class = 'accountinfoData'>" . $row['WOONPLAATS'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Email</li><li class = 'accountinfoData'>" . $row['EMAIL'] . "</li></ul>";
    $html .= "<ul class = 'accountinfo'><li class = 'accountinfoKop'>Nieuwsbrief</li><li class = 'accountinfoData'>" . ($row['NIEUWSBRIEF'] < 1 ? 'Nee' : 'Ja') . "</li></ul>";
}

$html .= "</div>";

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php require_once 'includes/head.html'; ?>
    <title>FairFood | Uw account</title>
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Account</h1>

    <?=$html?>

    <p><a href="signout.php">Uitloggen</a></p>
</main>

<?php include 'includes/footer.html'; ?>

</body>
</html>