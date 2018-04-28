<?php

require_once('test_models.php');
require_once('filters.php');
require_once('forms.php');


 //применяем фильтры 
$products = filter_objects_more_less($products, 'min', '>');
$products = filter_objects_more_less($products, 'max', '<');
$products = filter_objects_checkboxes($products, 'years');
$products = filter_objects_option($products, 'equipment_type');


// извлекаем часть запроса, чтобы потом использовать в пагинации 
$query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
$query = preg_replace("/page=[^&$]*&?/", '', $query); // удаляем все page
if ($query) $query = '&'.$query; 


// начало пагинации

// обекты закрепляются к определнной странице
$products_per_page = 3; 
$pages = ceil( count($products)/$products_per_page );

isset($_REQUEST['page']) ? $page = $_REQUEST['page'] : $page = 1; //по умолчанию первая страница

if ( !isset($_REQUEST['page']) || !is_numeric($page) ) { // проверка полуенных данных на существование и является ли целым числом
    $page = 1;
} elseif ($page > $pages) {
    $page = $pages;
} else {
    $page = ceil($_REQUEST['page']);
}

$products = array_slice($products, $products_per_page*($page - 1), $products_per_page);


function pagination() 
{
    global $products_per_page, $page, $pages, $query;
    $stop_generate = 0;

    if ($pages > 0) {
        if ( $page != 1 ) echo "<a id='pagination_preview' href='?page=", $page - 1, $query, "' ><section class='pagination_nextNpre' onclick=\"id='pagination_activate'\">Предыдущая</section></a>";
        
        for ($i=1; $i <= $pages; $i++) { 
        
        
            if ( $stop_generate <= 7 && $i >= $page - 3 ) { // центрируем активированную кнопку, всего их 7 
                if ( $i == $page || (!$page && $i == 1) ) $activate = 'pagination_activate';     //присвоение класса активированной ранее кнопке
                else $activate = '';
        
                echo "<a href='?page={$i}", $query ,"'><section class='pagination_number {$activate}' onclick=\"this.id='pagination_activate'\" >{$i}</section></a>";
            
                $stop_generate++;
                if ($stop_generate >= 7) break;  //заканчиваем создание, если больше 7
            }
        
        }
        
        if ( $page != $pages ) echo "<a id='pagination_next' href='?page=", $page + 1, $query, "' ><section class='pagination_nextNpre' onclick=\"id='pagination_activate'\">Следующая</section></a>";
    }

}