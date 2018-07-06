<?php 
/*
[регулярное выражение, представление, название урл]
*/
$urlpatterns = [
    ['', 'views\index', 'index'],
    ['products', 'views\products', 'products'],
    ['product/{product_id}', 'views\product', 'product'],
    ['goodstypes', 'views\goodstypes', 'goodstypes'],
    ['about', 'views\about', 'about'],
    ['contacts', 'views\contacts', 'contacts'],

    ['admin', 'views\admin', 'admin'],
    ['admin/login', 'views\login', 'login'],
    ['admin/logout', 'views\logout', 'logout'],
    ['admin/register', 'views\register', 'register'],
    ['admin/entries/{model_name}', 'views\entries', 'entries'],
    ['admin/entries/{model_name}/add', 'views\add_entry', 'add_entry'],
    ['admin/entries/{model_name}/{model_id}/change', 'views\edit_entry', 'edit_entry']
];