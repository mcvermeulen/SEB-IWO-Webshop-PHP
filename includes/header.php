<header>
    <a href="index.php"><img src="img/FairFood_logo3.png" alt="Logo" class = "logo"></a>
</header>
<nav>
    <ul>
        <li><a href="categorieen.php"><i class="fas fa-briefcase"></i><span class="text"> Categorie&euml;n</span></a></li>
        <li><a href="aanbiedingen.php"><i class="far fa-gem"></i><span class="text"> Aanbiedingen</span></a></li>
        <li><a href="over.php"><i class="far fa-envelope"></i><span class="text"> Over ons</span></a></li>
        <li class="search">
            <form method="post" action="index.php">
                <i class="fas fa-search"></i>
                <input name="zoek" placeholder="Zoek..." onkeydown['enter']="this.form.submit"/>
            </form>
        </li>
        <li class="dropdown">
            <i class="far fa-user fa-lg"></i>
            <div class="dropdown-menu">
                <h3>Inloggen</h3>
                <form>
                    <p><label for="email">E-mailadres</label><br/>
                        <input id="email" name="email" type="email"></p>
                    <p><label for="wachtwoord">Wachtwoord</label><br/>
                        <input id="wachtwoord" name="wachtwoord" type="password"></p>
                    <p>
                        <input id="onthouden" type="checkbox" name="onthouden">
                        <label for="onthouden">Ingelogd blijven</label>
                    </p>
                    <p>
                        <button type="submit" class="fill space-top">Inloggen</button>
                    </p>
                    <p>
                        <a href="registreren.html">Registreren</a>
                    </p>
                </form>
            </div>
        </li>
    </ul>
</nav>