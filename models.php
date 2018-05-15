<?php
// аблица 
try {
    $pdo = new PDO('mysql:host=php;dbname=phpmyshop_db', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo 'error base';
}

// $query = "SELECT * FROM products";
// print_r($pdo->query($query)->fetchAll());



require_once('core/models.php');
// use BaseModel;
class Products extends BaseModel {

    public function mandatory_fields() //обязательные поля
    { 
        return [
            'id',
            'years',
            'manufacturer',
            'name'
        ];
    }


}
BaseModel::plug_pdo($pdo); // settings



$products = new Products;
$products->add_object(['id'=>'NULL','years'=>2007, 'manufacturer'=>'apple','name'=>'last1']);
print_r(Products::get_all_objects()[0]);
// Products::del_object(32);
