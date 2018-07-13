<?php
namespace admin;

use core\admin\Admin;
use models\{Product, Manufacturer, Equipmenttype};

$admin = new Admin();
$admin->register(new Product(), ['title']);
$admin->register(new Manufacturer());
$admin->register(new Equipmenttype());