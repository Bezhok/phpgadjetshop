<?php

$urlpatterns = [];

$urlpatterns = array_merge($urlpatterns, require_once(BASE_DIR . '/app/urls.php'));
$urlpatterns = array_merge($urlpatterns, require_once(BASE_DIR . '/admin/urls.php'));