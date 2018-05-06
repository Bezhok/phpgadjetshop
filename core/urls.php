<?php

$url = strtok($_SERVER["REQUEST_URI"],'?'); // получаем адрес без query 
$url = substr($url, 1); // обрезаем первый слеш

echo $url;

function url($par, $isstart=true) { // маршрутизатор. возвращает view в случае совпадения адреса. Если isstart == false, то возвращает список именованных групп 
    // $par это [$regex, $view, $url]
    $regex =& $par[0];
    $view =& $par[1];
    $url =& $par[2];
    
    if ($isstart && preg_match($regex, $url)) {
        return $view;
    } elseif (preg_match($regex, $url, $groups_names)) {
        return $groups_names;
    }

}

function foreach_urlpattern($urlpatterns) {
    foreach ($urlpatterns as $pattern) { // перебираем urls
        if (url($pattern, false)) { // проверяем совпдает ли url с существующеми
        
            if ( isset($pattern[3]) ) { // передаем аргуменыты, если они существуют

                $pattern[3][] = url($pattern, false); // добавляем $groups_names
                call_user_func_array(url($pattern), $pattern[3]);

            } else { // иначе просто вызываем функцию

                url($pattern)();

            }
            break;
        } 
    }    
}