<?php
namespace core\urls;

trait UrlTrait
{
    public function url($url_name, ...$parameters) // получаем путь до страницы
    {
        $urls_path_patterns = [];
        foreach ($this->urlpatterns as $pattern) { // извлекаем урлы
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

            } elseif (!$matches_count && !count($parameters)) {

                $url_regex = $urls_path_patterns[$url_name]; // извлекаем шаблон урлов
                return '/' . $url_regex;

            } else {
                throw new \Exception('Arguments more or less than expected');
                
            }

        } else {
            throw new \Exception('This url doesn\'t exist');
        }
    }
}

class Url
{
    use UrlTrait;
    public function __construct($urlpatterns)
    {
        $this->urlpatterns = $urlpatterns;
        $this->regex_base ='#\{[-\w\d_]+\}#u';
        $url = strtok($_SERVER["REQUEST_URI"],'?'); // получаем адрес без query 
        $this->url = substr($url, 1); // обрезаем первый слеш
    }


    public function call_view() {
        $is_url_exist = false;
        foreach ($this->urlpatterns as $pattern) { // перебираем urls
            $alias = $this->convert_alias($pattern[0]);
            if (preg_match($alias, $this->url, $args)) { // проверяем совпдает ли url с существующеми
                $view_name = $pattern[1];
                $all_args = [];
                $all_args[] =  $args; // добавляем в $args именованные группы
                $is_url_exist = true;
                $view_name(...$all_args); // массив с передаваемыми view'у аргументами
                break;
            }
        }
        if (!$is_url_exist) require_once BASE_DIR . '/templates/404.html';
        exit();

    }

    private function convert_alias($alias) // преобразуем шаблон вида view/{str1}/{str2} в #^view/(?P<str1>[-\w\d_]+)/(?P<str2>[-\w\d_]+)/*$#
    {
        preg_match_all($this->regex_base, $alias, $matches);
        foreach ($matches[0] as $value) {
            $alias = preg_replace_callback(
                $this->regex_base, 
                function($matches) {
                    $edited_url = preg_replace('#[\{\}]#', '', $matches[0]);
                    $edited_url = '(?P<' . $edited_url . '>[-\w\d_]+)';
                    return $edited_url;
                },
                $alias,
                1
            );
        }
        $alias =  '#^' . $alias . '/*$#';
        return $alias;   
    }
}