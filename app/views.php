<?php
namespace views;


require_once CORE_DIR . '/views.php'; //render
require_once BASE_DIR . '/app/models.php';
require_once 'forms.php';
require_once 'filters.php';

use \models as models;
use \forms as forms;
use \filters as filters;





function index($request) {

    models\Manufacturer::get_all_objects();
    $manufacturers = models\Manufacturer::make_query();

    return render('index.html', ['manufacturers' => $manufacturers]);
}

function products($request ) {

    models\Product::get_all_objects();
     //применяем фильтры 
    // $products = filters\more_less_filter($products, 'min', '>'); //
    // $products = filters\more_less_filter($products, 'max', '<');
    // $products = filters\checkboxes_filter($products, 'years');
    // $products = filters\options_filter($products, 'equipment_type');
    
    // извлекаем часть запроса, чтобы потом использовать в пагинации 
    $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    $query = preg_replace("/page=[^&$]*&?/", '', $query); // удаляем все page
    if ($query) $query = '&'.$query; 
    
    
    // начало пагинации
    
    // обЪекты закрепляются к определнной странице
    $products_per_page = 5; 
    $pages = ceil( models\Product::get_countNmake_query()/$products_per_page );
    isset($_REQUEST['page']) ? $page = $_REQUEST['page'] : $page = 1; //по умолчанию первая страница
    
    if ( !isset($_REQUEST['page']) || !is_numeric($page) ) { // проверка полуенных данных на существование и является ли целым числом
        $page = 1;
    } elseif ($page > $pages) {
        $page = $pages;
    } else {
        $page = ceil($_REQUEST['page']);
    }

    models\Product::limit($products_per_page*($page - 1), $products_per_page);
    $products = models\Product::make_query();

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

    return render('products.html', 
        [
         'products' => $products,
         'pagination' => $pagination,
         'price_form' => forms\price_form(),
         'years_form' => forms\years_form(),
         'equipment_type_form' => forms\equipment_type_form(),
         'manufacturer_form' => forms\manufacturer_form(),
    ]);
}

function product($request) {
    
    $id_from_request = $request['product_id'];
    models\Product::get_all_objects($id_from_request);
    $product = models\Product::make_query()[0];

    if (!$product) die('нет запрашиваемой информации 404'); 

    return render('product.html', ['product' => $product]);
}


function goodstypes($request) {

    models\Equipmenttype::get_all_objects();
    $equipmenttypes = models\Equipmenttype::make_query();

    return render('goodstypes.html', ['equipmenttypes' => $equipmenttypes]);
}


function contacts($request) {
    return render('contacts.html', []);
}


function about($request) {
    return render('about.html', []);
}

function admin($request) {
    return render('static/gulp/app/admin_sign-in.php', []);
}
