<?php
require_once 'includes/core.php';

$dbh = DatabaseConnect();
$sth = $dbh->query("SELECT * FROM PRODUCT");

$producten = array();
while ($row = $sth->fetchObject()) {
    $prod = "<article>
            <a href='product.php?id=$row->PRODUCTNUMMER'>
                <img src='$row->AFBEELDING_KLEIN' alt='Foto van $row->PRODUCTNUMMER'/>
                <div>
                    <h3>$row->PRODUCTNAAM</h3>
                    <p>$row->OMSCHRIJVING</p>
                    <span class='prijs'>&euro; $row->PRIJS</span>
                </div>
                <div class='hover'>
                    Bekijk product
                </div>
            </a>
        </article>";
    $producten[] = $prod;
}

$dbh = null;
$sth = null;

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FairFood</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css"
          integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
</head>
<body>

<?php include 'includes/header.php'; ?>

<main>
    <section class="row uitgelicht">
        <h2>Uitgelicht</h2>
        <article>
            <a href="product.php">
                <img src="img/7610202259422.png"/>
                <div>
                    <h3>Productnaam</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum eius illum magni quae
                        vel veritatis vero? </p>
                    <span class="prijs">&euro; 4,99</span>
                </div>
                <div class="hover">
                    Bekijk product
                </div>
            </a>
        </article>
        <article>
            <a href="product.php">
                <img src="img/7610202259422.png"/>
                <div>
                    <h3>Productnaam</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum eius illum magni quae
                        vel veritatis vero? </p>
                    <span class="prijs">&euro; 4,99</span>
                </div>
                <div class="hover">
                    Bekijk product
                </div>
            </a>
        </article>
        <article>
            <a href="product.php">
                <img src="img/7610202259422.png"/>
                <div>
                    <h3>Productnaam</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deserunt dolorum eius illum magni quae
                        vel veritatis vero? </p>
                    <span class="prijs">&euro; 4,99</span>
                </div>
                <div class="hover">
                    Bekijk product
                </div>
            </a>
        </article>
    </section>
    <aside>Wij bij FairFood staan voor kwaliteit, betrouwbaarheid en eerlijkheid.<br/>
        U bent bij ons aan het juiste adres voor FairTrade en biologische producten tegen een eerlijke prijs.
    </aside>
    <section class="row">
        <?php
        foreach ($producten as $prod) {
            echo $prod;
        }
        ?>
    </section>
</main>

<?php include 'includes/footer.html'; ?>

</body>
</html>

