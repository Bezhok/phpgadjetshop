<?php
namespace core\urls;


$url = strtok($_SERVER["REQUEST_URI"],'?'); // получаем адрес без query 
$url = substr($url, 1); // обрезаем первый слеш


function url($par, $url, $isstart=true) { // маршрутизатор. возвращает view в случае совпадения адреса. Если isstart == false, то возвращает список именованных групп 
    // $par это [$regex, $view, $url]
    $regex =& $par[0];
    $view =& $par[1];
    
    if ($isstart && preg_match($regex, $url)) {
        return $view;
    } elseif (preg_match($regex, $url, $groups_names)) {
        return $groups_names;
    }

}

function foreach_urlpattern($urlpatterns, $url) {
    foreach ($urlpatterns as $pattern) { // перебираем urls
        if (url($pattern, $url, false)) { // проверяем совпдает ли url с существующеми
        
            if ( isset($pattern[2]) && $pattern[2] ) { // передаем аргуменыты, если массив с ними существует и не пустой

                $pattern[2][] = url($pattern, $url, false); // добавляем $groups_names
                url($pattern, $url)(...$pattern[2]); // массив с передаваемыми view'у аргументами

            } else { // если аргументов нет, то просто вызываем функцию

                url($pattern, $url)();

            }
            break;
        } 
    }    
}