<header>
    <a href="index.php"><img src="img/FairFood_logo3.png" alt="Logo" class = "logo"></a>
    <span class="right"><?=$groet?></span>
</header>
<nav>
    <ul>
        <li><a href="categorieen.php"><i class="fas fa-briefcase"></i><span class="text"> Categorie&euml;n</span></a></li>
        <li><a href="aanbiedingen.php"><i class="far fa-gem"></i><span class="text"> Aanbiedingen</span></a></li>
        <li><a href="over.php"><i class="far fa-envelope"></i><span class="text"> Over ons</span></a></li>
        <li class="search">
            <form method="post" action="index.php">
                <i class="fas fa-search"></i>
                <input name="zoek" placeholder="Zoek..." onkeydown="this.form.submit"/>
            </form>
        </li>
        <?=$headerHTML?>
    </ul>
</nav>