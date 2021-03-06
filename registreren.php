<?php
session_start();
require_once 'includes/core.php';

if (isset($_SESSION['gebruiker'])) {
    header('Location: account.php');
}

$dbh = DatabaseConnect();
$gebruikersnamen = $dbh->query("SELECT GEBRUIKERSNAAM, EMAIL FROM GEBRUIKER");
$gebruikersnamen->execute();

$gebruikersnaam = $voornaam = $tussenvoegsels = $achternaam = $geboortedatum = $telefoon = null;
$woonplaats = $straat = $huisnummer = $postcode = $geslacht = $nieuwsbrief = $email = null;
$gebruikersnaamError = $wachtwoordError = $voornaamError = $tussenvoegselsError = $achternaamError = $geboortedatumError = '';
$telefoonError = $straatError = $huisnummerError = $postcodeError = $geslachtError = $nieuwsbriefError = $woonplaatsError = $emailError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["gebruikersnaam"])) {
        $gebruikersnaamError = "De gebruikersnaam is verplicht";
    } else {
        $gebruikersnaam = clean_input($_POST["gebruikersnaam"]);
        if (strlen($_POST["gebruikersnaam"]) < 5 || strlen($_POST["gebruikersnaam"]) > 15) {
            $gebruikersnaamError = "De gebruikersnaam dient tussen de 5 en 15 karakters te zijn";
        }
    }
    if (empty($_POST['voornaam'])) {
        $voornaamError = "Voornaam is een verplicht veld";
    } else {
        $voornaam = clean_input($_POST['voornaam']);
        if (!preg_match("/^[a-zA-Z ]*$/",$voornaam)) {
            $voornaamError = "Alleen letters en spaties toegestaan";
        }
        if (strlen($_POST["voornaam"]) < 2 || strlen($_POST["voornaam"]) > 125) {
            $voornaamError = "U heeft een ongeldige voornaam opgegeven";
        }
    }
    if (!empty($_POST['tussenvoegsels'])) {
        $tussenvoegsels = clean_input($_POST['tussenvoegsels']);
        if (!preg_match("/^[a-zA-Z ]{2,}$/",$tussenvoegsels)) {
            $tussenvoegselsError = "Alleen letters en spaties toegestaan";
        }
    }
    if (empty($_POST['achternaam'])) {
        $achternaamError = "Achternaam is een verplicht veld";
    } else {
        $achternaam = clean_input($_POST['achternaam']);
        if (!preg_match("/^[a-zA-Z ]{2,}$/",$achternaam)) {
            $achternaamError = "U heeft een ongeldige achternaam opgegeven";
        }
    }
    if (!empty($_POST['geboortedatum'])) {
        $geboortedatum = clean_input($_POST['geboortedatum']);
    }
    if (!empty($_POST['telefoon'])) {
        $telefoon = clean_input($_POST['telefoon']);
        if (!preg_match("/^\d{10}$/", $telefoon)) {
            $telefoonError = "Een telefoonnummer moet uit 10 cijfers bestaan";
        }
    }
    if (empty($_POST['straat'])) {
        $straatError = "Straatnaam is een verplicht veld";
    } else {
        $straat = clean_input($_POST['straat']);
        if (strlen($_POST["straat"]) > 125) {
            $straatError = "U heeft teveel karakters ingevuld";
        }
    }
    if (empty($_POST['huisnummer'])) {
        $huisnummerError = "Huisnummer is een verplicht veld";
    } else {
        $huisnummer = clean_input($_POST['huisnummer']);
        if (!preg_match("/^\d{1,8}$/", $huisnummer)) {
            $huisnummerError = "Een huisnummer mag alleen uit cijfers bestaan";
        }
    }
    if (empty($_POST['postcode'])) {
        $postcodeError = "Bijvoorbeeld: 1234XX";
    } else {
        $postcode = clean_input($_POST['postcode']);
        if (!preg_match("/^\d{4}[a-zA-Z]{2}$/", $postcode)) {
            $postcodeError = "Vul een geldige postcode in";
        }
    }
    if (empty($_POST['woonplaats'])) {
        $woonplaatsError = "Woonplaats is een verplicht veld";
    } else {
        $woonplaats = clean_input($_POST['woonplaats']);
        if (strlen($_POST["woonplaats"]) > 125) {
            $woonplaatsError = "U heeft teveel karakters ingevuld";
        }
    }
    if (empty($_POST['email'])) {
        $emailError = "Email is een verplicht veld";
    } else {
        $email = clean_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailError = "Ongeldige email formaat";
        }
    }
    if (empty($_POST['geslacht'])) {
        $geslachtError = "Geslacht is een verplicht veld";
    } else {
        $geslacht = clean_input($_POST['geslacht']);
    }
    if (empty($_POST['wachtwoord'])) {
        $wachtwoordError = "Wachtwoord is een verplicht veld";
    } else {
        if ($_POST['wachtwoord'] !== $_POST['wachtwoordCheck']) {
            $wachtwoordError = "De wachtwoorden komen niet overeen";
        } elseif (strlen($_POST['wachtwoord']) < 8) {
            $wachtwoordError = "Het wachtwoord dient uit minimaal 8 karakters te bestaan";
        } else {
            $wachtwoord = hash('sha512', ($_POST['wachtwoord'].getenv('SALT')));
        }
    }
    $nieuwsbrief = isset($_POST['nieuwsbrief']) ? 1 : 0;
}

if (empty($gebruikersnaamError)) {
    $bezet = false;
    /*Controleren of de gebruikersnaam en/of email al bezet is */
    while ($row = $gebruikersnamen->fetch()) {
        if ($row['GEBRUIKERSNAAM'] === $gebruikersnaam) {
            $bezet = true;
            $gebruikersnaamError = "Deze gebruikersnaam is al bezet";
        }
        if ($row['EMAIL'] === $email) {
            $bezet = true;
            $emailError = "Er is al een account geregistreerd met dit emailadres";
        }
    }
}

/* controleren of alle verplichte velden ingevuld zijn */
if (!empty($gebruikersnaam) && !empty($voornaam) && !empty($achternaam) && !empty($straat)
    && !empty($huisnummer) && !empty($postcode) && !empty($woonplaats) && !empty($email)
    && !empty($wachtwoord) && empty($gebruikersnaamError) && empty($voornaamError) && empty($achternaamError) && empty($straatError)
    && empty($huisnummerError) && empty($postcodeError) && empty($woonplaatsError) && empty($emailError)
    && empty($wachtwoordError)) {

    /* na alle checks wordt de data geaccepteerd en doorgestuurd*/
    if (!$bezet) {
        try {
            $insertQuery = $dbh->prepare(
                "INSERT INTO GEBRUIKER (GEBRUIKERSNAAM, VOORNAAM, TUSSENVOEGSEL, ACHTERNAAM, GEBOORTEDATUM, TELEFOON, STRAATNAAM, HUISNUMMER, POSTCODE, WOONPLAATS, EMAIL, SEXE, WACHTWOORD, NIEUWSBRIEF) 
        VALUES (:gebruikersnaam, :voornaam, :tussenvoegsels, :achternaam, :geboortedatum, :telefoon, :straatnaam, :huisnummer, :postcode, :woonplaats, :email, :geslacht, :wachtwoord, :nieuwsbrief)");
            $insertQuery->execute([
                ':gebruikersnaam' => $gebruikersnaam,
                ':voornaam' => $voornaam,
                ':tussenvoegsels' => $tussenvoegsels,
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
        } catch (PDOException $e) {
            echo "Er ging iets mis met het wegschrijven naar de database: <br>".$e->getMessage();
            exit;
        }
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
                       value="<?= $gebruikersnaam ?>" required/>
                <span class="form-error"><?= $gebruikersnaamError ?></span>
            </div>

            <fieldset class="legacy-form-row">
                <div class="radiogroep">
                    <input id="geslacht-1" name="geslacht" type="radio" value="M"
                        <?php if (isset($geslacht) && $geslacht=="M") echo "checked";?> required/>
                    <label for="geslacht-1" class="radio-label">Dhr.</label>
                </div>
                <div class="radiogroep">
                    <input id="geslacht-2" name="geslacht" type="radio" value="V"
                        <?php if (isset($geslacht) && $geslacht=="V") echo "checked";?>/>
                    <label for="geslacht-2" class="radio-label">Mvr.</label>
                </div>
                <span class="form-error"><?= $geslachtError ?></span>
            </fieldset>

            <div class="form-row">
                <label for="voornaam">* Voornaam</label>
                <input id="voornaam" name="voornaam" type="text" maxlength="125" value="<?= $voornaam ?>" required/>
                <span class="form-error"><?= $voornaamError ?></span>
            </div>
            <div class="form-row">
                <label for="tussenvoegsels">Tussenvoegsels</label>
                <input id="tussenvoegsels" name="tussenvoegsels" type="text" maxlength="30"
                       value="<?= $tussenvoegsels ?>"/>
            </div>
            <div class="form-row">
                <label for="achternaam">* Achternaam</label>
                <input id="achternaam" name="achternaam" type="text" maxlength="125" value="<?= $achternaam ?>" required/>
                <span class="form-error"><?= $achternaamError ?></span>
            </div>
            <div class="form-row">
                <label for="geboortedatum">Geboortedatum</label>
                <input id="geboortedatum" name="geboortedatum" type="date" value="<?= $geboortedatum ?>"/>
            </div>
            <div class="form-row">
                <label for="telefoon">Telefoonnummer</label>
                <input id="telefoon" name="telefoon" type="text" maxlength="10" value="<?= $telefoon ?>"/>
                <span class="form-error"><?= $telefoonError ?></span>
            </div>
            <div class="form-row">
                <label for="straat">* Straatnaam</label>
                <input id="straat" name="straat" type="text" maxlength="125" value="<?= $straat ?>" required/>
                <span class="form-error"><?= $straatError ?></span>
            </div>
            <div class="form-row">
                <label for="huisnummer">* Huisnummer</label>
                <input id="huisnummer" name="huisnummer" type="text" value="<?= $huisnummer ?>" required/>
                <span class="form-error"><?= $huisnummerError ?></span>
            </div>
            <div class="form-row">
                <label for="postcode">* Postcode</label>
                <input id="postcode" name="postcode" type="text" maxlength="6" value="<?= $postcode ?>" required/>
                <span class="form-error"><?= $postcodeError ?></span>
            </div>
            <div class="form-row">
                <label for="woonplaats">* Woonplaats</label>
                <input id="woonplaats" name="woonplaats" type="text" maxlength="125" value="<?= $woonplaats ?>" required/>
                <span class="form-error"><?= $woonplaatsError ?></span>
            </div>
            <div class="form-row">
                <label for="email">* Email</label>
                <input id="email" name="email" type="email" placeholder="jansen@voorbeeld.com"
                       maxlength="50" value="<?= $email ?>" required/>
                <span class="form-error"><?= $emailError ?></span>
            </div>
            <div class="form-row">
                <label for="wachtwoord1">* Wachtwoord</label>
                <input id="wachtwoord1" name="wachtwoord" type="password" required/>
                <span class="form-error"><?= $wachtwoordError ?></span>
            </div>
            <div class="form-row">
                <label for="wachtwoord2">* Herhaal wachtwoord</label>
                <input id="wachtwoord2" name="wachtwoordCheck" type="password" required/>
            </div>

            <div class="form-row">
                <label class="checkbox-label" for="nieuwsbrief">
                    <input id="nieuwsbrief" name="nieuwsbrief" type="checkbox"/>
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