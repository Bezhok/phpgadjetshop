<?php
/**
*  product 
*/
class Product
{
    public 
        $id,
        $name,
        $description,
        $image,
        $price,
        $manufacturer,
        $equipmenttype;

}

$pr1 = new Product;

$pr1->id = 1;
$pr1->created_year = 2018;
$pr1->name = 'test1';
$pr1->description = 'spam eggs spam eggs spam eggs spam eggs spam eggs';
$pr1->image = '';
$pr1->price = 300;
$pr1->manufacturer = 'sumsung';
$pr1->equipment_type = 'phone';

$pr2 = new Product;

$pr2->id = 2;
$pr2->created_year = 2017;
$pr2->name = 'test2';
$pr2->description = 'spam eggs spam eggs spam eggs spam eggs spam eggs';
$pr2->image = '';
$pr2->price = 300;
$pr2->manufacturer = 'sumsung';
$pr2->equipment_type = 'tablet';

$pr3 = new Product;

$pr3->id = 3;
$pr3->created_year = 2016;
$pr3->name = 'test3';
$pr3->description = 'spam eggs spam eggs spam eggs spam eggs spam eggs';
$pr3->image = '';
$pr3->price = 200;
$pr3->manufacturer = 'sumsung';
$pr3->equipment_type = 'phone';

$pr4 = new Product;

$pr4->id = 4;
$pr4->created_year = 2015;
$pr4->name = 'test4';
$pr4->description = 'spam eggs spam eggs spam eggs spam eggs spam eggs';
$pr4->image = '';
$pr4->price = 200;
$pr4->manufacturer = 'sumsung';
$pr4->equipment_type = 'phone';

$products_original_list = [
    $pr1,
    $pr2,
    $pr3,
    $pr4,
    $pr1,
    $pr2,
    $pr3,
        $pr4,
    $pr1,
    $pr2,
    $pr3,
        $pr4,
    $pr1,
    $pr2,
    $pr3,
        $pr4,
    $pr1,
    $pr2,
    $pr3,
        $pr4,
    $pr1,
    $pr2,
    $pr3
];
