<?php

namespace Twig_Extensions;

/*
** Формируем путь до статических файлов
*/
class Static_Files_Twig_Extension extends \Twig_Extension
{
    public function __construct($STATIC_ROOT)
    {
        $this->STATIC_ROOT = $STATIC_ROOT;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('static', [$this, 'static']),
        ];
    }

    public function static($path_name)
    {
        return  $this->STATIC_ROOT . '/' . $path_name;
    }

}
