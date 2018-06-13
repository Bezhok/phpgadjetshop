<?php

namespace models;


require_once CORE_DIR . '/models.php';


/*
Допустимые типы: varchar, text, option, image, number
*/
class Product extends \basemodels\BaseModel
{
    public function __construct()
    {
        parent::__construct();

        $this->mandatory_fields = [
            // 'id' => ['type' => 'number'],
            'year' => ['type' => 'number', 'verbose_name' => 'Цена'],
            'manufacturer' => ['type' => 'option', 'foreign' => '\models\Manufacturer'],
            'title' => ['type' => 'varchar'],
            'price' => ['type' => 'number'],
            'description' => ['type' => 'text'],
            'equipmenttype' => ['type' => 'option', 'foreign' => '\models\Equipmenttype'],
            'main_image' => ['type' => 'image', 'url' => 'media/']
        ];
    }
}

class Manufacturer extends \basemodels\BaseModel
{
    public function __construct()
    {
        parent::__construct();

        $this->mandatory_fields = [
            'name' => ['type' => 'varchar'],
            'image' => ['type' => 'image', 'url' => 'media/']
        ];
    }
}

class Equipmenttype extends \basemodels\BaseModel
{
    public function __construct()
    {
        parent::__construct();

        $this->mandatory_fields = [
            'name' => ['type' => 'varchar'],
            'image' => ['type' => 'image', 'url' => 'media/']
        ];
    }
}