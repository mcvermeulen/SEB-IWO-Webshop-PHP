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

function genereerPagination($page = 1, $limit = 10, $total = 0){
    $nPages = ceil($total/$limit);

    $pagination = "<ul class='pagination'>";
    $pagination .= ($page <= 1) ? "<li><span>&laquo;<span></span></li>" : "<li><a href='?page=".($page-1)."'>&laquo;</a></li>";
    for ($i = 1; $i <= $nPages; $i++) {
        $pagination .= ($i == $page) ? "<li><a class='active' href='?page=$i'>$i</a></li>" : "<li><a href='?page=$i'>$i</a></li>";
    }
    $pagination .= ($page >= $nPages) ? "<li><span>&raquo;<span></span></li>" : "<li><a href='?page=".($page+1)."'>&raquo;</a></li>";
    $pagination .= "</ul>";

    return $pagination;
}