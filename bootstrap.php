<?php

require_once 'settings.php';
require_once CORE_DIR . '/models.php';

// таблица 

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new \PDO('mysql:host=php;dbname=phpmyshop_db', 'root', '', $opt);


basemodels\BaseModel::plug_pdo($pdo); // settings


require_once CORE_DIR . '/urls.php';
require_once 'app/urls.php';
require_once 'app/views.php';

use core\urls as urls;


urls\foreach_urlpattern($urlpatterns, $url);

