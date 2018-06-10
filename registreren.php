<?php

require_once 'includes/core.php';
include 'includes/signin.php';
$dbh = DatabaseConnect();

$selectQueryAlles = $dbh->prepare("SELECT GEBRUIKERSNAAM FROM GEBRUIKER");
$selectQueryAlles->execute();
$gebruikersnaamValue = "";
$gebruikersnaamClass = "";
$voornaamValue = "";
$tussenvoegselsValue = "";
$achternaamValue = "";
$geboortedatumValue = "";
$telefoonValue = "";
$straatnaamValue = "";
$huisnummerValue = "";
$postcodeValue = "";
$woonplaatsValue = "";
$emailValue = "";

/* Houdt ingevoerde data vast, in het geval het formulier niet volledig / correct was ingevuld toen het werd verstuurd */
if(isset($_POST['gebruikersnaam'])){
    $gebruikersnaamValue = $_POST['gebruikersnaam'];
} if(isset($_POST['voornaam'])){
    $voornaamValue = $_POST['voornaam'];
} if(isset($_POST['tussenvoegsels'])){
    $tussenvoegselsValue = $_POST['tussenvoegsels'];
} if(isset($_POST['achternaam'])){
    $achternaamValue = $_POST['achternaam'];
} if(isset($_POST['geboortedatum'])){
    $geboortedatumValue = $_POST['geboortedatum'];
} if(isset($_POST['telefoon'])){
    $telefoonValue = $_POST['telefoon'];
} if(isset($_POST['straat'])){
    $straatnaamValue = $_POST['straat'];
} if(isset($_POST['huisnummer'])){
    $huisnummerValue = $_POST['huisnummer'];
} if(isset($_POST['postcode'])){
    $postcodeValue = $_POST['postcode'];
} if(isset($_POST['woonplaats'])){
    $woonplaatsValue = $_POST['woonplaats'];
} if(isset($_POST['emailRegistratie'])){
    $emailValue = $_POST['emailRegistratie'];
}

/* controleren of alle verplichte velden ingevuld zijn */
if (!empty($_POST['gebruikersnaam']) && !empty($_POST['voornaam']) && !empty($_POST['achternaam']) && !empty($_POST['straat'])
    && !empty($_POST['huisnummer']) && !empty($_POST['postcode']) && !empty($_POST['woonplaats']) && !empty($_POST['emailRegistratie'])
    && !empty($_POST['wachtwoordRegistratie']) && !empty($_POST['wachtwoordRegistratieCheck']) ) {

    /* Controleren of het wachtwoord klopt */
    if ($_POST['wachtwoordRegistratie'] == $_POST['wachtwoordRegistratieCheck']) {
        $naamBezet = false;

        /*Controleren of de gebruiksnaam al bezet is */
        while ($row = $selectQueryAlles->fetch()) {
            if ($row['GEBRUIKERSNAAM'] == $_POST['gebruikersnaam']) {
                $naamBezet = true;
                $gebruikersnaamValue = "Kies andere naam";
                $gebruikersnaamClass = "class = 'invalidUsername'";
            }
        }

        /* na alle checks wordt de data geaccepteerd en doorgestuurd*/
        if(!$naamBezet){
            $_SESSION['registratie'] = $_POST;
            $_SESSION['validRegistratie'] = true;
            header('Location: accountAanmaken.php');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <?php require_once 'includes/head.html'; ?>
    <title>FairFood | Registreren</title>
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Registreren</h1>
    <form action="registreren.php" method="post" class="registratieformulier">
        <img src = "img/theeplukker.jpg" alt="Theeplukker" class = "registratieplaatje">
        <div class="form-row">
            <label for="gebruikersnaam">* Gebruikersnaam</label>
            <input id="gebruikersnaam" name="gebruikersnaam" type="text" maxlength="15" value = "<?=$gebruikersnaamValue?>" <?=$gebruikersnaamClass?>/>
        </div>

        <fieldset class="legacy-form-row">
            <div class= "radiogroep">
                <input id="geslacht-1" name="geslacht" type="radio" value="M" checked />
                <label for="geslacht-1" class="radio-label">Dhr.</label>
            </div>
            <div class= "radiogroep">
                <input id="geslacht-2" name="geslacht" type="radio" value="V" />
                <label for="geslacht-2" class="radio-label">Mvr.</label>
            </div>
        </fieldset>

        <div class="form-row">
            <label for="voornaam">* Voornaam</label>
            <input id="voornaam" name="voornaam" type="text" maxlength="125" value = "<?=$voornaamValue?>"/>
        </div>
        <div class="form-row">
            <label for="tussenvoegsels">Tussenvoegsels</label>
            <input id="tussenvoegsels" name="tussenvoegsels" type="text" maxlength="30" value = "<?=$tussenvoegselsValue?>"/>
        </div>
        <div class="form-row">
            <label for="achternaam">* Achternaam</label>
            <input id="achternaam" name="achternaam" type="text" maxlength="125" value = "<?=$achternaamValue?>"/>
        </div>
        <div class="form-row">
            <label for="geboortedatum">Geboortedatum</label>
            <input id="geboortedatum" name="geboortedatum" type="date" <?php echo "value =  $geboortedatumValue  "; ?>/>
        </div>
        <div class="form-row">
            <label for="telefoon">Telefoonnummer</label>
            <input id="telefoon" name="telefoon" type="text" maxlength="10" value = "<?=$telefoonValue?>"/>
        </div>
        <div class="form-row">
            <label for="straat">* Straatnaam</label>
            <input id="straat" name="straat" type="text" maxlength="125" value = "<?=$straatnaamValue?>"/>
        </div>
        <div class="form-row">
            <label for="huisnummer">* Huisnummer</label>
            <input id="huisnummer" name="huisnummer" type="text" value = "<?=$huisnummerValue?>"/>
        </div>
        <div class="form-row">
            <label for="postcode">* Postcode</label>
            <input id="postcode" name="postcode" type="text" maxlength="6" value = "<?=$postcodeValue?>"/>
        </div>
        <div class="form-row">
            <label for="woonplaats">* Woonplaats</label>
            <input id="woonplaats" name="woonplaats" type="text" maxlength="125" value = "<?=$woonplaatsValue?>"/>
        </div>
        <div class="form-row">
            <label for="emailRegistratie">* Email</label>
            <input id="emailRegistratie" name="emailRegistratie" type="email" placeholder="jansen@voorbeeld.com" maxlength="50" value ="<?=$emailValue?>"/>
        </div>
        <div class="form-row">
            <label for="wachtwoordRegistratie">* Wachtwoord</label>
            <input id="wachtwoordRegistratie" name="wachtwoordRegistratie" type="password" maxlength="15"/>
        </div>
        <div class="form-row">
            <label for="wachtwoordRegistratieCheck">* Herhaal wachtwoord</label>
            <input id="wachtwoordRegistratieCheck" name="wachtwoordRegistratieCheck" type="password" maxlength="15"/>
        </div>

        <div class="form-row">
            <label class="checkbox-label" for="nieuwsbrief">
                <input id="nieuwsbrief" name="nieuwsbrief" type="checkbox" value="wil_nieuwsbrief_ontvangen"/>
                <span>Stuur mij de nieuwsbrief</span>
            </label>
        </div>

        <div class="form-row">
            <button>Registreren</button>
        </div>

        <p>* = verplicht veld</p>
    </form>
</main>

<?php include 'includes/footer.html'; ?>

</body>
</html>