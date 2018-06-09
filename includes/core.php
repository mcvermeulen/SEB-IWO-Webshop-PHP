<?php

//SET ROOT DIR
define ('ROOT', dirname(__DIR__));

require_once ROOT.'/config/config.php';
require_once __DIR__ . '/database.php';

function genereerArtikel($row)
{
    if ($row === null) {
        die('Oops...er ging iets mis');
    }

    $prod = "<article>
            <a href='product.php?id=$row->PRODUCTNUMMER'>
                <img src='$row->AFBEELDING_KLEIN' alt='Foto van $row->PRODUCTNUMMER'/>
                <div>
                    <h3>$row->PRODUCTNAAM</h3>
                    <p>$row->OMSCHRIJVING</p>";

    if (!empty($row->ACTIEPRIJS)) {
        $prod .= "<span class='prijs actie'>&euro; 3,99</span>
                  <span class='prijs niet-actie'>&euro; 4,99</span>";
    } else {
        $prod .= "<span class='prijs'>&euro; $row->PRIJS</span>";
    }

    $prod .= "</div>
                <div class='hover'>
                    Bekijk product
                </div>
            </a>
        </article>";

    return $prod;
}