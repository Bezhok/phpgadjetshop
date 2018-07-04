<?php
namespace Twig_Extensions;

require_once CORE_DIR . '/urls.php';

class Urls_Twig_Extension extends \Twig_Extension
{
    use \core\urls\UrlTrait;
    public function __construct($urlpatterns)
    {
        $this->urlpatterns = $urlpatterns;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('url', [$this, 'url']),
        ];
    }
}