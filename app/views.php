<?php
namespace app\views;

use function \core\views\render;
use \core\forms\SelectForm;
use \core\pagination\Pagination;
use function \app\forms\{price_form, years_form};
use \app\models\{Product, Manufacturer, Equipmenttype};

function index($request)
{
    $manufacturers = new Manufacturer();
    $manufacturers = $manufacturers->get_objects()->make_query();

    return render('app/index.html', ['manufacturers' => $manufacturers]);
}

function products($request)
{
    $products = new Product();
    $products->get_objects();

    // генерируем часть форм
    $M_form = new SelectForm(new Manufacturer(), 'manufacturer', 'Все', 'select_list');
    $ET_form = new SelectForm(new Equipmenttype(), 'equipment_type', 'Все', 'select_list');

    $req = $_REQUEST;
    //применяем фильтры 
    if (isset($_REQUEST['min']))
        $products->filter('price', '>=', $_REQUEST['min']);

    if (isset($_REQUEST['max']))
        $products->filter('price', '<=', $_REQUEST['max']);

    if (isset($_REQUEST[$ET_form->req_name])  && $_REQUEST[$ET_form->req_name] != $ET_form->default)
        $products->filter('equipmenttype', '=', $_REQUEST[$ET_form->req_name]);

    if (isset($_REQUEST[$M_form->req_name]) && $_REQUEST[$M_form->req_name] != $M_form->default)
        $products->filter('manufacturer', '=', $_REQUEST[$M_form->req_name]);

    if (isset($_REQUEST['years']) && is_array($_REQUEST['years']))
        $products->filter('year', 'in', $_REQUEST['years']);

    
    // извлекаем часть запроса, чтобы потом использовать в пагинации 
    $query = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    $query = preg_replace("/page=[^&$]*&?/", '', $query); // удаляем все page
    if ($query) $query = '&'.$query; 
    
    $products = $products->order_by('id', 'DESC');
    $pagination = new Pagination($products, 5, 7);

    $products = $products->make_query();

    return render('app/products.html', 
        [
         'products' => $products,
         'pagination' => $pagination,
         'query' => $query,
         'price_form' => price_form(),
         'years_form' => years_form(),
         'equipment_type_form' => $M_form,
         'manufacturer_form' => $ET_form
    ]);
}

function product($request)
{
    $id_from_request = $request['product_id']; // id из запроса
    $columns_with_namesNvalues = [ // получаем foreign поля
            'manufacturer' => ['name']
    ];

    $product = new Product();
    $product = $product->get_object($id_from_request, $columns_with_namesNvalues);
    if (!$product) return render('404.html', []);

    return render('app/product.html', ['product' => $product]);
}


function goodstypes($request)
{
    $equipmenttypes = new Equipmenttype();
    $equipmenttypes = $equipmenttypes->get_objects()->make_query();

    $info = [];
    foreach ($equipmenttypes as $value) { // минимальная цена, количество товаров
        $products = new Product();
        $products->get_objects()->filter('equipmenttype', '=', $value['id']);
        
        $info[$value['id']]['count'] = $products->get_current_count__make_query();
        $products = $products->make_query();

        $prices = [];
        foreach ($products as $product) {
            $prices[] = $product['price'];
        }
        if (!$prices) $prices = [0]; // если у типа товаров нет товаров ставим цену ноль
        $info[$value['id']]['min_price'] = min($prices);
    }

    return render('app/goodstypes.html', ['equipmenttypes' => $equipmenttypes, 'info' => $info]);
}

function about($request)
{
    return render('app/about.html', []);
}

function contacts($request)
{
    return render('app/contacts.html', []);
}
