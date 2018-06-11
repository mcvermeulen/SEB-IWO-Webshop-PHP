<?php
//SET ROOT DIR
define ('ROOT', dirname(__DIR__));

require_once ROOT.'/config/config.php';
require_once __DIR__ . '/database.php';
include __DIR__ . '/signin.php';

function genereerArtikel($row, $prijs = true)
{
    if ($row === null) {
        die('Oops...er ging iets mis');
    }

    if ($prijs) {
        $prod = "<article>";
    } else {
        $prod = "<article class='geenprijs'>";
    }
    $prod .= "      <a href='product.php?id=$row->PRODUCTNUMMER'>
                <img src='$row->AFBEELDING_KLEIN' alt='Foto van $row->PRODUCTNUMMER'/>
                <div>
                    <h3>$row->PRODUCTNAAM</h3>
                    <p>$row->OMSCHRIJVING</p>";
    if ($prijs) {
        if (!empty($row->ACTIEPRIJS)) {
            $prod .= "<span class='prijs actie'>&euro; $row->ACTIEPRIJS</span>
                  <span class='prijs niet-actie'>&euro; $row->PRIJS</span>";
        } else {
            $prod .= "<span class='prijs'>&euro; $row->PRIJS</span>";
        }
    }
    $prod .= "</div>
                <div class='hover'>
                    Bekijk product
                </div>
            </a>
        </article>";

    return $prod;
}

function genereerPagination($page = 1, $limit = 10, $total = 0, $zoek = null){
    $nPages = ceil($total/$limit);

    $pagination = "<ul class='pagination'>";
    $pagination .= ($page <= 1) ? "<li><span>&laquo;</span></li>" : "<li><a href='?page=".($page-1)."&limit=$limit&zoek=$zoek'>&laquo;</a></li>";
    for ($i = 1; $i <= $nPages; $i++) {
        $pagination .= ($i == $page) ? "<li><a class='active' href='?page=$i&limit=$limit&zoek=$zoek'>$i</a></li>" : "<li><a href='?page=$i&limit=$limit&zoek=$zoek'>$i</a></li>";
    }
    $pagination .= ($page >= $nPages) ? "<li><span>&raquo;</span></li>" : "<li><a href='?page=".($page+1)."&limit=$limit&zoek=$zoek'>&raquo;</a></li>";
    $pagination .= "</ul><br>";

    $pagination .=
    "<form style='margin-left: auto;margin-right: auto'>
    Producten per pagina:
    <select name='limit' onchange='this.form.submit()'>";
        $pagination .= ($limit == 10) ? "<option selected value='10'>10</option>" : "<option value='10'>10</option>";
        $pagination .= ($limit == 20) ? "<option selected value='20'>20</option>" : "<option value='20'>20</option>";
        $pagination .= ($limit == 30) ? "<option selected value='20'>30</option>" : "<option value='30'>30</option>";
        $pagination .= ($limit == 9999) ? "<option selected value='9999'>alles</option>" : "<option value='9999'>alles</option>";

    $pagination .= "</select>
    </form>";

    return $pagination;
}