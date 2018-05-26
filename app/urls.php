<?php 

$urlpatterns = [

    // ['index', 'views\index'],
    // ['goodstypes', 'views\goodstypes', []],

    ['products', 'views\products', 'products'], // products/   /
    ['product/{product_id}', 'views\product', 'product'],

    // ['about', 'views\about'],
    // ['contacts', 'views\contacts'],

    // ['admin', 'views\admin'],product

    // ['twig', 'views\twig']
];

