<?php
namespace views;


require_once('core/views.php'); //render
require_once('forms.php');
use \forms as forms;

require_once('filters.php');
use \filters as filters;





function index() {
    return render('templates/index.php', []);
}

function products($all_products) {

    $products = $all_products;

     //применяем фильтры 
    $products = filters\more_less_filter($products, 'min', '>');
    $products = filters\more_less_filter($products, 'max', '<');
    $products = filters\checkboxes_filter($products, 'years');
    $products = filters\options_filter($products, 'equipment_type');
    
    // извлекаем часть запроса, чтобы потом использовать в пагинации 
    $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    $query = preg_replace("/page=[^&$]*&?/", '', $query); // удаляем все page
    if ($query) $query = '&'.$query; 
    
    
    // начало пагинации
    
    // обЪекты закрепляются к определнной странице
    $products_per_page = 5; 
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
    
    
    function pagination($products_per_page, $page, $pages, $query) 
    {   
        $stop_generate = 0;
        $pagination_list = [];

        if ($pages > 0) {
            if ( $page != 1 ) {
                $pagination_list[] = "<a id='pagination_preview' href='?page=" . ($page - 1) . $query. "' ><section class='pagination_nextNpre' onclick=\"id='pagination_activate'\">Предыдущая</section></a>";
            }
            for ($i=1; $i <= $pages; $i++) { 
            
            
                if ( $stop_generate <= 7 && $i >= $page - 3 ) { // центрируем активированную кнопку, всего их 7 
                    if ( $i == $page || (!$page && $i == 1) ) $activate = 'pagination_activate';     //присвоение класса активированной ранее кнопке
                    else $activate = '';
            
                    $pagination_list[] =  "<a href='?page={$i}" . $query . "'><section class='pagination_number {$activate}' onclick=\"this.id='pagination_activate'\" >{$i}</section></a>";
                
                    $stop_generate++;
                    if ($stop_generate >= 7) break;  //заканчиваем создание, если больше 7
                }
            
            }
            
            if ( $page != $pages ) {
                $pagination_list[] =  "<a id='pagination_next' href='?page=" . ($page + 1) . $query . "' ><section class='pagination_nextNpre' onclick=\"id='pagination_activate'\">Следующая</section></a>";
            }
        }
        return $pagination_list;
    }


    $pagination = pagination($products_per_page, $page, $pages, $query);

    return render('templates/products.php', 
        [
         'products' => $products,
         'pagination' => $pagination,
         'price_form' => 'forms\price_form',
         'years_form' => 'forms\years_form',
         'equipment_type_form' => 'forms\equipment_type_form'
    ]);
}

function product($all_products, $groups_names) {

    $id = $groups_names['product_id'];
    
    foreach ($all_products as $i) { // проверка id обЪекта
        if ($i->id == $id) {
            $product = $i;
            break;
        }
    } 
    if (!isset($product)) die('нет запрашиваемой информации 404'); 

    return render('templates/product.php', ['product' => $product]);
}


function goodstypes() {
    return render('templates/goodstypes.php', []);
}


function contacts() {
    return render('templates/contacts.php', []);
}


function about() {
    return render('templates/about.php', []);
}

