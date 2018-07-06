<?php
namespace Twig_Extensions;

use core\urls\UrlTrait;

class Urls_Twig_Extension extends \Twig_Extension
{
    use UrlTrait;
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