<?php

require_once 'settings.php';

// Composer
if (file_exists(BASE_DIR . '/core/composer/vendor/autoload.php')) {
    require_once BASE_DIR . '/core/composer/vendor/autoload.php';
}

require_once BASE_DIR . '/app/urls.php';

require_once CORE_DIR . '/csrf_token.php';
require_once CORE_DIR . '/models.php';
require_once CORE_DIR . '/views.php';
require_once CORE_DIR . '/urls.php';

// *************************foreache in core/core/notmain
require_once CORE_DIR . '/notmain/forms.php';
require_once CORE_DIR . '/notmain/pagination.php';
require_once CORE_DIR . '/notmain/admin.php';
//**************************

// ************************foreach in moduls
require_once TWIG_EXTENSIONS_DIR . '/urls.extension.php';
require_once TWIG_EXTENSIONS_DIR . '/static_files.extension.php';
// ************************

require_once BASE_DIR . '/app/models.php';
require_once BASE_DIR . '/app/views.php';
require_once BASE_DIR . '/app/views_admin.php';

// ************************foreach in app/notmain(exclude urls)
require_once BASE_DIR . '/app/notmain/admin.php';
require_once BASE_DIR . '/app/notmain/forms.php';
// ************************


$pdo = new \PDO("mysql:host=$db_host;dbname=$db_name", $db_login, $db_password, $db_options);
core\models\BaseModel::plug_pdo($pdo);


$view = new core\urls\Url($urlpatterns);
$view->call_view();