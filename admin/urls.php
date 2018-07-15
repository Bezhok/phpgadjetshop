<?php
/*
[регулярное выражение, представление, название урл]
*/
return [
    ['admin', 'admin\views\admin', 'admin'],
    ['admin/login', 'admin\views\login', 'login'],
    ['admin/logout', 'admin\views\logout', 'logout'],
    ['admin/register', 'admin\views\register', 'register'],
    ['admin/entries/{model_name}', 'admin\views\entries', 'entries'],
    ['admin/entries/{model_name}/add', 'admin\views\add_entry', 'add_entry'],
    ['admin/entries/{model_name}/{model_id}/change', 'admin\views\edit_entry', 'edit_entry']
];