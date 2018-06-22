<?php 
// namespace core\views;
require_once BASE_DIR . '/app/urls.php';
require_once TWIG_EXTENSIONS_DIR . '/urls.extension.php';
require_once TWIG_EXTENSIONS_DIR . '/static_files.extension.php';

function render($url, $vars = [])
{

    global $urlpatterns;
    $loader = new Twig_Loader_Filesystem(BASE_DIR . '/templates/app/');
    $twig = new Twig_Environment($loader, array(
        'strict_variables' => true
        // 'cache' => TWIG_CHACHE_DIR, //кеш
    ));

    $twig->addExtension(new \Twig_Extensions\Urls_Twig_Extension($urlpatterns));
    $twig->addExtension(new \Twig_Extensions\Static_Files_Twig_Extension(STATIC_URL));

    $template = $twig->load($url);
    $template->display($vars);

}



