<?php 


/*
регулярное выражение, представление, название урл
*/

$urlpatterns = [

    ['index', 'views\index', 'index'],
    ['goodstypes', 'views\goodstypes', 'goodstypes'],
    ['products', 'views\products', 'products'],
    ['product/{product_id}', 'views\product', 'product'],
    ['about', 'views\about', 'about'],
    ['contacts', 'views\contacts', 'contacts'],
    ['admin', 'views\admin', 'admin'],

    // ['twig', 'views\twig', 'twig']
];

