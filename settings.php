<?php



define('BASE_DIR', dirname(__FILE__));

define('CORE_DIR', BASE_DIR . '/core');

define('TWIG_CHACHE_DIR', CORE_DIR . '/cache');
define('TWIG_EXTENSIONS_DIR', CORE_DIR . '/modules/twig_extensions');


// Static files (CSS, JavaScript, Images)
define('STATIC_URL', '/static' );
define('STATIC_ROOT', BASE_DIR . '/static' );

define('MEDIA_URL', '/media' );
define('MEDIA_ROOT', BASE_DIR . '/media' );



// define('SQL', value)


if (file_exists(CORE_DIR . '/composer/vendor/autoload.php')) {
    require_once CORE_DIR . '/composer/vendor/autoload.php';
}