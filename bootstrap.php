<?php


// *************************** Cобственный быдло-автолоудер*********************************************************************
function require_all($path) {
   try {
        $handle = opendir($path);
        readdir($handle);
        
        $required = [];
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $required[] = $entry;
            }
        }
    
        foreach ($required as $file_name) {
            if (is_file($path . "/" . $file_name)) {
                require_once $path . "/" . $file_name;
            } else {
                require_all($path . "/" . $file_name);
            }
        }
    } finally {
        closedir($handle);
    } 
}

// The most standart
require_once 'settings.php';
require_once 'urls.php';

// Composer
if (file_exists(BASE_DIR . '/core/composer/vendor/autoload.php')) {
    require_once BASE_DIR . '/core/composer/vendor/autoload.php';
}

// foreache in core/core/
require_all(CORE_DIR);

// foreach in modules
require_all(BASE_DIR . "/core/modules");

// Plug apps
foreach ($apps as $dir_name) {
    $mandatory = [
        'models.php',
        'views.php',
        'forms.php'
    ];

    foreach ($mandatory as $v) {
        require_once BASE_DIR . "/$dir_name/" . $v;
    }

    try {
        $handle = opendir(BASE_DIR . "/$dir_name");
        readdir($handle);
        
        $required = [];
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $required[] = $entry;
            }
        }

        $required = array_diff($required, $mandatory);

        foreach ($required as $file_name) {
            if (is_file(BASE_DIR . "/$dir_name/" . $file_name)) {
                require_once BASE_DIR . "/$dir_name/" . $file_name;
            } else {
                require_all(BASE_DIR . "/$dir_name/" . $file_name);
            }
        }

    } finally {
        closedir($handle);
    }
}
// *************************** Cобственный быдло-автолоудер*********************************************************************


// Plug pdo
$pdo = new \PDO("mysql:host=$db_host;dbname=$db_name", $db_login, $db_password, $db_options);
core\models\BaseModel::plug_pdo($pdo);


$view = new core\urls\Url($urlpatterns);
$view->call_view();