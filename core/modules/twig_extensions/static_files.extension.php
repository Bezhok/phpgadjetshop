<?php

namespace Twig_Extensions;

/*
** Формируем путь до статических файлов
*/
class Static_Files_Twig_Extension extends \Twig_Extension
{
    public function __construct($STATIC_URL)
    {
        $this->STATIC_URL = $STATIC_URL;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('static', [$this, 'static']),
        ];
    }

    public function static($path_name)
    {
        return  $this->STATIC_URL . '/' . $path_name;
    }

}
