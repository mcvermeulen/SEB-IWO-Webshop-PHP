<?php
session_start();
require_once 'includes/core.php';

$dbh = DatabaseConnect();
$gebruikersnamen = $dbh->query("SELECT GEBRUIKERSNAAM FROM GEBRUIKER");
$gebruikersnamen->execute();

$gebruikersnaam = $voornaam = $tussenvoegsels = $achternaam = $geboortedatum = $telefoon = '';
$woonplaats = $straat = $huisnummer = $postcode = $geslacht = $nieuwsbrief = $email = '';
$gebruikersnaamError = $wachtwoordError = $voornaamError = $tussenvoegselsError = $achternaamError = $geboortedatumError = '';
$telefoonError = $straatError = $huisnummerError = $postcodeError = $geslachtError = $nieuwsbriefError = $emailError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["gebruikersnaam"]) || strlen($_POST["gebruikersnaam"]) < 5 || strlen($_POST["gebruikersnaam"]) > 15) {
        $gebruikersnaamError = "De gebruikersnaam dient tussen de 5 en 15 karakters te zijn";
    } else {
        $gebruikersnaam = clean_input($_POST["gebruikersnaam"]);
    }
    if (empty($_POST['voornaam']) || strlen($_POST["voornaam"]) > 125) {
        $voornaamError = "Voornaam is een verplicht veld (max 125 karakters)";
    } else {
        $voornaam = clean_input($_POST['voornaam']);
    }
    if (!empty($_POST['tussenvoegsels'])) {
        $tussenvoegsels = clean_input($_POST['tussenvoegsels']);
    }
    if (empty($_POST['achternaam'])) {
        $achternaamError = "Achternaam is een verplicht veld";
    } else {
        $achternaam = clean_input($_POST['achternaam']);
    }
    if (!empty($_POST['geboortedatum'])) {
        $geboortedatum = clean_input($_POST['geboortedatum']);
    }
    if (!empty($_POST['telefoon'])) {
        $telefoon = clean_input($_POST['telefoon']);
    }
    if (empty($_POST['straat'])) {
        $straatError = "Straatnaam is een verplicht veld";
    } else {
        $straat = clean_input($_POST['straat']);
    }
    if (empty($_POST['huisnummer'])) {
        $huisnummerError = "Huisnummer is een verplicht veld";
    } else {
        $huisnummer = clean_input($_POST['huisnummer']);
    }
    if (empty($_POST['postcode'])) {
        $postcodeError = "Postcode is een verplicht veld";
    } else {
        $postcode = clean_input($_POST['postcode']);
    }
    if (empty($_POST['woonplaats'])) {
        $woonplaatsError = "Woonplaats is een verplicht veld";
    } else {
        $woonplaats = clean_input($_POST['woonplaats']);
    }
    if (empty($_POST['email'])) {
        $emailError = "Email is een verplicht veld";
    } else {
        $email = clean_input($_POST['email']);
    }
    if (empty($_POST['geslacht'])) {
        $geslachtError = "Geslacht is een verplicht veld";
    } else {
        $geslacht = clean_input($_POST['geslacht']);
    }
    if (!empty($_POST['nieuwsbrief'])) {
        $nieuwsbrief = clean_input($_POST['nieuwsbrief']);
    }
    if (empty($_POST['wachtwoord'])) {
        $wachtwoordError = "Wachtwoord is een verplicht veld";
    } else {
        if ($_POST['wachtwoord'] !== $_POST['wachtwoordCheck']) {
            $wachtwoordError = "De wachtwoorden komen niet overeen";
        } else {
            $wachtwoord = hash('sha512', ($_POST['wachtwoord'].getenv('SALT')));
        }
    }

}

/* controleren of alle verplichte velden ingevuld zijn */
if (!empty($gebruikersnaam) && !empty($voornaam) && !empty($achternaam) && !empty($straat)
    && !empty($huisnummer) && !empty($postcode) && !empty($woonplaats) && !empty($email)
    && !empty($wachtwoord)) {

    /*Controleren of de gebruikersnaam al bezet is */
    while ($row = $gebruikersnamen->fetch()) {
        if ($row['GEBRUIKERSNAAM'] == $_POST['gebruikersnaam']) {
            $naamBezet = true;
            $gebruikersnaamValue = "Kies andere naam";
            $gebruikersnaamClass = "class = 'invalidUsername'";
        }
    }

    /* na alle checks wordt de data geaccepteerd en doorgestuurd*/
    if (!$naamBezet) {
        $insertQuery = $dbh->prepare("INSERT INTO GEBRUIKER (GEBRUIKERSNAAM, VOORNAAM, TUSSENVOEGSEL, ACHTERNAAM, GEBOORTEDATUM, TELEFOON, STRAATNAAM, HUISNUMMER, POSTCODE, WOONPLAATS, EMAIL, SEXE, WACHTWOORD, NIEUWSBRIEF) VALUES (:gebruikersnaam, :voornaam, :tussenvoegsels, :achternaam, :geboortedatum, :telefoon, :straatnaam, :huisnummer, :postcode, :woonplaats, :email, :geslacht, :wachtwoord, :nieuwsbrief)");
        $insertQuery->execute([
            ':gebruikersnaam' => $gebruikersnaam,
            ':voornaam' => $voornaam,
            ':tussenvoegsel' => $tussenvoegsels,
            ':achternaam' => $achternaam,
            ':geboortedatum' => $geboortedatum,
            ':telefoon' => $telefoon,
            ':straatnaam' => $straat,
            ':huisnummer' => $huisnummer,
            ':postcode' => $postcode,
            ':woonplaats' => $woonplaats,
            ':email' => $email,
            ':geslacht' => $geslacht,
            ':wachtwoord' => $wachtwoord,
            ':nieuwsbrief' => $nieuwsbrief,
        ]);

        $_SESSION['gebruiker'] = $gebruikersnaam;
        header('Location: account.php');
    }

    $gebruikersnamen = null;
    $insertQuery = null;
    $dbh = null;
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
    <form action="registreren.php" method="post" id="registratieformulier">
        <div class="column">
            <div class="form-row">
                <label for="gebruikersnaam">* Gebruikersnaam</label>
                <input id="gebruikersnaam" name="gebruikersnaam" type="text" maxlength="15"
                       value="<?= $gebruikersnaam ?>"/>
                <span class="form-error"><?= $gebruikersnaamError ?></span>
            </div>

            <fieldset class="legacy-form-row">
                <div class="radiogroep">
                    <input id="geslacht-1" name="geslacht" type="radio" value="M" checked/>
                    <label for="geslacht-1" class="radio-label">Dhr.</label>
                </div>
                <div class="radiogroep">
                    <input id="geslacht-2" name="geslacht" type="radio" value="V"/>
                    <label for="geslacht-2" class="radio-label">Mvr.</label>
                </div>
            </fieldset>

            <div class="form-row">
                <label for="voornaam">* Voornaam</label>
                <input id="voornaam" name="voornaam" type="text" maxlength="125" value="<?= $voornaam ?>"/>
                <span class="form-error"><?= $voornaamError ?></span>
            </div>
            <div class="form-row">
                <label for="tussenvoegsels">Tussenvoegsels</label>
                <input id="tussenvoegsels" name="tussenvoegsels" type="text" maxlength="30"
                       value="<?= $tussenvoegsels ?>"/>
            </div>
            <div class="form-row">
                <label for="achternaam">* Achternaam</label>
                <input id="achternaam" name="achternaam" type="text" maxlength="125" value="<?= $achternaam ?>"/>
                <span class="form-error"><?= $achternaamError ?></span>
            </div>
            <div class="form-row">
                <label for="geboortedatum">Geboortedatum</label>
                <input id="geboortedatum" name="geboortedatum" type="date" value="<?= $geboortedatum ?>"/>
            </div>
            <div class="form-row">
                <label for="telefoon">Telefoonnummer</label>
                <input id="telefoon" name="telefoon" type="text" maxlength="10" value="<?= $telefoon ?>"/>
            </div>
            <div class="form-row">
                <label for="straat">* Straatnaam</label>
                <input id="straat" name="straat" type="text" maxlength="125" value="<?= $straat ?>"/>
                <span class="form-error"><?= $straatError ?></span>
            </div>
            <div class="form-row">
                <label for="huisnummer">* Huisnummer</label>
                <input id="huisnummer" name="huisnummer" type="text" value="<?= $huisnummer ?>"/>
                <span class="form-error"><?= $huisnummerError ?></span>
            </div>
            <div class="form-row">
                <label for="postcode">* Postcode</label>
                <input id="postcode" name="postcode" type="text" maxlength="6" value="<?= $postcode ?>"/>
                <span class="form-error"><?= $postcodeError ?></span>
            </div>
            <div class="form-row">
                <label for="woonplaats">* Woonplaats</label>
                <input id="woonplaats" name="woonplaats" type="text" maxlength="125" value="<?= $woonplaats ?>"/>
                <span class="form-error"><?= $woonplaatsError ?></span>
            </div>
            <div class="form-row">
                <label for="email">* Email</label>
                <input id="email" name="email" type="email" placeholder="jansen@voorbeeld.com"
                       maxlength="50" value="<?= $email ?>"/>
                <span class="form-error"><?= $emailError ?></span>
            </div>
            <div class="form-row">
                <label for="wachtwoord">* Wachtwoord</label>
                <input id="wachtwoord" name="wachtwoord" type="password"/>
                <span class="form-error"><?= $wachtwoordError ?></span>
            </div>
            <div class="form-row">
                <label for="wachtwoordCheck">* Herhaal wachtwoord</label>
                <input id="wachtwoordCheck" name="wachtwoordCheck" type="password"/>
            </div>

            <div class="form-row">
                <label class="checkbox-label" for="nieuwsbrief">
                    <input id="nieuwsbrief" name="nieuwsbrief" type="checkbox"
                           value="wil_nieuwsbrief_ontvangen"/>
                    <span>Stuur mij de nieuwsbrief</span>
                </label>
            </div>

            <div class="form-row">
                <button>Registreren</button>
            </div>

            <p>* = verplicht veld</p>
        </div>
        <div class="column"><img src="img/theeplukker.jpg" alt="Theeplukker"></div>
    </form>
</main>

<?php include 'includes/footer.html'; ?>

</body>
</html>