<?php
if (isset($_SESSION['gebruiker'])) {
    $headerHTML = <<<CODE
<li class="dropdown">
    <i class="fa fa-user fa-lg green"></i>
    <div class="dropdown-menu">
        <h3>Hallo $_SESSION[gebruiker]!</h3>
        <p>
            <a href='account.php'>Mijn profiel</a>
        </p>
        <p>
            <a class="button" href='signout.php'>Uitloggen</a>
        </p>
    </div>
</li>
CODE;
}
else {
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
                <button type="submit" class="fill">Inloggen</button>
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

<header>
    <a href="index.php"><img src="img/FairFood_logo3.png" alt="Logo" class = "logo"></a>
    <a href="winkelwagen.php"><i class='fas fa-shopping-basket'></i></a>
</header>
<nav>
    <ul>
        <li><a href="categorieen.php"><i class="fas fa-briefcase"></i><span class="text"> Categorie&euml;n</span></a></li>
        <li><a href="aanbiedingen.php"><i class="far fa-gem"></i><span class="text"> Aanbiedingen</span></a></li>
        <li><a href="over.php"><i class="far fa-envelope"></i><span class="text"> Over ons</span></a></li>
        <li class="search">
            <form method="post" action="index.php">
                <i class="fas fa-search"></i>
                <input name="zoek" placeholder="Zoek..." value="<?php echo $_SESSION['zoek'] ?? '';?>" onkeydown="this.form.submit"/>
                <?php if (isset($_SESSION['zoek'])) echo "<button type='submit' name='remove' value='remove'>x</button>"; ?>
            </form>
        </li>
        <?=$headerHTML?>
    </ul>
</nav>