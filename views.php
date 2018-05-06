<?php


require_once('filters.php');
require_once('forms.php');

function render($url, $access) {
    return require_once($url);
}





function views__index() {
    return render('templates/index.php', []);
}

function views__products($products_original_list) {

    $products = $products_original_list;
     //применяем фильтры 
    $products = filters__more_less($products, 'min', '>');
    $products = filters__more_less($products, 'max', '<');
    $products = filters__checkboxes($products, 'years');
    $products = filters__options($products, 'equipment_type');
    
    
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
    
    
    function pagination($products_per_page, $page, $pages, $query) 
    {   
        // global $products_per_page, $page, $pages, $query;
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
         'price_form' => "forms__price",
         'years_form' => "forms__years",
         'equipment_type_form' => "forms__equipment_type"
    ]);
}

function views__product($products_original_list, $groups_names) {

    $id = $groups_names['product_id'];
    
    foreach ($products_original_list as $i) {
        if ($i->id == $id) {
            $product = $i;
            break;
        }
    } 
    if (!isset($product)) die('нет запрашиваемой информации 404'); 

    return render('templates/product.php', ['product' => $product]);
}


function views__goodstypes() {
    return render('templates/goodstypes.php', []);
}


function views__contacts() {
    return render('templates/contacts.php', []);
}


function views__about() {
    return render('templates/about.php', []);
}

