<?php

define('SECRET_KEY', 'y=e2%3ho+r0gx@-$zoy-@qa*+9hoi0h6gy2a$-_k36e*o(rsix');

// Главные пути
define('BASE_DIR', dirname(__FILE__));
define('CORE_DIR', BASE_DIR . '/core/core');
define('TWIG_CHACHE_DIR', BASE_DIR . '/core/cache');
define('TWIG_EXTENSIONS_DIR', BASE_DIR . '/core/modules/twig_extensions');

// Static files (CSS, JavaScript, Images)
define('STATIC_URL', '/static' );
define('STATIC_ROOT', BASE_DIR . '/static' );

define('MEDIA_URL', '/media' );
define('MEDIA_ROOT', BASE_DIR . '/media' );

// настройка загружаемых файлов
define('MAX_IMAGE_SIZE', 5242880);
define('AVAILABLE_IMAGE_TYPES', ['gif','jpg', 'jpe','jpeg','png']); // капслочные не считаются

define('MAX_FILE_SIZE', 0);
define('AVAILABLE_FILE_TYPES', []);

// Database
$db_options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$db_host = 'php';
$db_name = 'phpmyshop_db';
$db_login = 'root';
$db_password = '';

// Composer
if (file_exists(BASE_DIR . '/core/composer/vendor/autoload.php')) {
    require_once BASE_DIR . '/core/composer/vendor/autoload.php';
}