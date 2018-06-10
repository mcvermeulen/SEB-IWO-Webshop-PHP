<?php

include 'includes/signin.php';

if(!isset($_SESSION['gebruiker'])){
    header('Location: index.php');
}

require_once 'includes/core.php';
$dbh = DatabaseConnect();
$huidigeGebruiker = "'" . $_SESSION['gebruiker'] . "'";


$selectQueryAccount = $dbh->prepare("SELECT * FROM GEBRUIKER WHERE GEBRUIKERSNAAM = $huidigeGebruiker");
$selectQueryAccount->execute();

$html = "<table class = 'accountinfo'>";

while ($row = $selectQueryAccount->fetch()){
    $html .= "<tr><td class = 'accountinfoKop'>Gebruikersnaam</td><td>" . $row['GEBRUIKERSNAAM'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Aanhef</td><td>" . ($row['SEXE'] == 'M' ? 'Dhr.' : 'Mvr.') . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Voornaam</td><td>" . $row['VOORNAAM'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Tussenvoegsels</td><td>" . $row['TUSSENVOEGSEL'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Achternaam</td><td>" . $row['ACHTERNAAM'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Geboortedatum</td><td>" . $row['GEBOORTEDATUM'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Telefoon</td><td>" . $row['TELEFOON'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Straatnaam</td><td>" . $row['STRAATNAAM'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Huisnummer</td><td>" . $row['HUISNUMMER'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Postcode</td><td>" . $row['POSTCODE'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Woonplaats</td><td>" . $row['WOONPLAATS'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Email</td><td>" . $row['EMAIL'] . "</td></tr>";
    $html .= "<tr><td class = 'accountinfoKop'>Nieuwsbrief</td><td>" . ($row['NIEUWSBRIEF'] < 1 ? 'Nee' : 'Ja') . "</td></tr>";
}

$html .="</table>";

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