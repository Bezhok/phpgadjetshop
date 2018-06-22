<?php
namespace core\urls;


$url = strtok($_SERVER["REQUEST_URI"],'?'); // получаем адрес без query 
$url = substr($url, 1); // обрезаем первый слеш


    

function foreach_urlpattern($urlpatterns, $url) {

    function url($par, $url, $method='return view') { // маршрутизатор. возвращает view в случае совпадения адреса
        // $par это [$regex, $view, $url]
        $regex = convert_url($par[0]);
        $view = $par[1];
        
        if ($method == 'return view' && preg_match($regex, $url)) {
            return $view;

        } elseif ($method == 'return args for view' && preg_match($regex, $url, $args)) {
            return $args;

        } elseif ($method == 'return true if match' && preg_match($regex, $url) ) {
            return true;
        }

    }

    foreach ($urlpatterns as $pattern) { // перебираем urls
        if (url($pattern, $url, 'return true if match')) { // проверяем совпдает ли url с существующеми
            $all_args = [];
            $all_args[] =  url($pattern, $url, 'return args for view'); // добавляем в $args именованные группы
            url($pattern, $url)(...$all_args); // массив с передаваемыми view'у аргументами
            break;
        } 
    }    
}



function convert_url($url, $pattern='#\{[-\w\d_]+\}#u') // преобразуем шаблон вида view/{str1}/{str2} в #^view/(?P<str1>[-\w\d_]+)/(?P<str2>[-\w\d_]+)/*$#
{
    preg_match_all($pattern, $url, $matches);
    $matches_count = count($matches[0]);
    for ($i=1; $i <= $matches_count ; $i++) { 
        $url = preg_replace_callback(
            $pattern, 
            function($matches) {
                $edited_url = preg_replace('#[\{\}]#', '', $matches[0] );
                $edited_url = '(?P<' . $edited_url . '>[-\w\d_]+)';
                return $edited_url;
            },
            $url,
            1
            
        );
    }
    $url =  '#^' . $url . '/*$#';   
    return $url;   
}

