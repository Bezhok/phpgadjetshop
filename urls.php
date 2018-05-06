<?php 

require_once('core/urls.php');
require_once('test_models.php');
require_once('views.php');



$urlpatterns = [

    ['/^index$/', "views__index", $url],
    ['/^goodstypes$/', "views__goodstypes", $url, [$products_original_list]],
    ['/^products$/', "views__products", $url, [$products_original_list]],
    ['/^product\/(?P<product_id>[-\w\d_]+)$/', "views__product", $url, [$products_original_list]],
    ['/^about$/', "views__about", $url],
    ['/^contacts$/', "views__contacts", $url]

];

foreach_urlpattern($urlpatterns);

// preg_match($regex, $url, $group_names)