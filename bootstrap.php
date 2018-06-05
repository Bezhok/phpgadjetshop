<?php

require_once 'settings.php';
require_once CORE_DIR . '/models.php';

// таблица 
try {
    $pdo = new \PDO('mysql:host=php;dbname=phpmyshop_db', 'root', '', [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo 'error base';
}


// $query = "SELECT * FROM products";
// print_r($pdo->query($query)->fetchAll());

basemodels\BaseModel::plug_pdo($pdo); // settings


require_once CORE_DIR . '/urls.php';
require_once 'app/urls.php';
require_once 'app/views.php';

use core\urls as urls;


// echo $url;// testtttt

urls\foreach_urlpattern($urlpatterns, $url);

