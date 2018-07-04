<?php

require_once BASE_DIR . '/settings.php';
require_once BASE_DIR . '/app/urls.php';
require_once CORE_DIR . '/models.php';


require_once CORE_DIR . '/urls.php';
require_once CORE_DIR . '/views.php';
require_once CORE_DIR . '/forms.php';
require_once CORE_DIR . '/pagination.php';
require_once CORE_DIR . '/admin.php';


require_once BASE_DIR . '/app/models.php';


require_once TWIG_EXTENSIONS_DIR . '/urls.extension.php';
require_once TWIG_EXTENSIONS_DIR . '/static_files.extension.php';
require_once BASE_DIR . '/app/views.php';
require_once BASE_DIR . '/app/admin.php';
require_once BASE_DIR . '/app/views_admin.php';
require_once BASE_DIR . '/app/forms.php';



$pdo = new \PDO("mysql:host=$db_host;dbname=$db_name", $db_login, $db_password, $db_options);
core\models\BaseModel::plug_pdo($pdo);


$view = new core\urls\Url($urlpatterns);
$view->call_view();