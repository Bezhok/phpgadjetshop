<?php 
namespace core\views;

use Twig_Extensions\{Urls_Twig_Extension, Static_Files_Twig_Extension};
use function core\csrf_token\csrf_token;

function render($url, $vars = [])
{
    global $urlpatterns;
    $vars['csrf_token'] = csrf_token();
    $loader = new \Twig_Loader_Filesystem(BASE_DIR . '/templates/');
    $twig = new \Twig_Environment($loader, array(
        'strict_variables' => true,
        // 'cache' => TWIG_CHACHE_DIR, //кеш
    ));

    $twig->addExtension(new Urls_Twig_Extension($urlpatterns));
    $twig->addExtension(new Static_Files_Twig_Extension(STATIC_URL));

    $template = $twig->load($url);
    $template->display($vars);

}

function session_security__go_to_login()
{
    if ( !session_id() ) {
        session_start();
    }

    if (empty($_SESSION['login'])) { // если не вошел
        header("Location: /admin/login"); // редирект на логин
        exit();
    }
}

function session_security__go_to_admin()
{
    if ( !session_id() ) {
        session_start();
    }

    if (!empty($_SESSION['login'])) { // если вошел
        header("Location: /admin"); // редирект на админку
        exit();
    }
}