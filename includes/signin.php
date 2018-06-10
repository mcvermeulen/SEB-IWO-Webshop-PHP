<?php
session_start();
require_once 'includes/core.php';
$dbh = DatabaseConnect();

$selectQueryGebruiker = $dbh->prepare("SELECT GEBRUIKERSNAAM, WACHTWOORD FROM GEBRUIKER");
$selectQueryGebruiker->execute();

if (!empty($_POST['gebruiker']) && !empty($_POST['wachtwoord'])){
    while ($row = $selectQueryGebruiker->fetch()) {
        if ($row['GEBRUIKERSNAAM'] == $_POST['gebruiker'] && $row['WACHTWOORD'] == $_POST['wachtwoord']){
            $_SESSION['gebruiker'] = $row['GEBRUIKERSNAAM'];
        }
    }
}

if (isset($_SESSION['gebruiker'])){
    $gebruiker = $_SESSION['gebruiker'];
    $groet = "Hallo, <a href='account.php'>$gebruiker</a>";
    $headerHTML = "<a href=\"signout.php\">Uitloggen</a>";

} else {
    $groet = "";
    $headerHTML = <<<CODE
<li class="dropdown">
            <i class="far fa-user fa-lg"></i>
            <div class="dropdown-menu">
                <h3>Inloggen</h3>
                <form action="" method="post">
                    <p><label for="gebruiker">Gebruikersnaam</label><br/>
                        <input id="gebruiker" name="gebruiker" type="text" maxlength="15"></p>
                    <p><label for="wachtwoord">Wachtwoord</label><br/>
                        <input id="wachtwoord" name="wachtwoord" type="password" maxlength="15"></p>
                    <p>
                        <input id="onthouden" type="checkbox" name="onthouden">
                        <label for="onthouden">Ingelogd blijven</label>
                    </p>
                    <p>
                        <button type="submit" class="fill space-top">Inloggen</button>
                    </p>
                    <p>
                        <a href="registreren.php">Registreren</a>
                    </p>
                </form>
            </div>
        </li>
CODE;
}

?>