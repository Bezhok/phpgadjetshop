<?php

namespace Twig_Extensions;


class Urls_Twig_Extension extends \Twig_Extension
{
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

    public function url($url_name, ...$parameters) // 
    {
        $urlpatterns =& $this->urlpatterns;
        $urls_path_patterns = [];
        foreach ($urlpatterns as $pattern) { // извлекаем урлы
            $urls_path_patterns[$pattern[2]] = $pattern[0];
        }

        if (isset($urls_path_patterns[$url_name])) {

            $pattern = '#\{[-\w\d_]+\}#';
            $url_regex = $urls_path_patterns[$url_name];

            preg_match_all($pattern, $url_regex, $matches);
            $matches_count = count($matches[0]);

            if ($matches_count == count($parameters)) {

                $url_regex = $urls_path_patterns[$url_name]; // извлекаем шаблон урлов
                foreach ($parameters as $parameter) { // подставляем значения
                    $url_regex = preg_replace($pattern, $parameter, $url_regex, 1);
                }
                return '/' . $url_regex;

            } elseif (!$matches_count) {
                $url_regex = $urls_path_patterns[$url_name]; // извлекаем шаблон урлов
                return '/' . $url_regex;

            } else {
                die('аргументов передано больше, чем ожидалось');
            }

        } else {
            die('такой урл не существует');
        }
    }

}