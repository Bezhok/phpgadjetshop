<?php 


require_once('test_models.php');
require_once('views.php');

require_once('core/urls.php');
use core\urls as urls;



$urlpatterns = [

    ['|^index$|', "views\index"],
    ['|^goodstypes/*$|', "views\goodstypes", []],
    ['|^products/*$|', "views\products", [$products_original_list]],
    ['|^product/(?P<product_id>[-\w\d_]+)/*$|', "views\product", [$products_original_list]],
    ['|^about/*$|', "views\about"],
    ['|^contacts/*$|', "views\contacts"]

];

urls\foreach_urlpattern($urlpatterns, $url);
unset($url);

echo $url;// testtttt