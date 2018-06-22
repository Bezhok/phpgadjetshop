<?php

namespace models;


require_once CORE_DIR . '/models.php';


/*
Допустимые типы: varchar, text, option, image, number
если добавляете поле id, то уберите автоинкремент
*/
class Product extends \basemodels\BaseModel
{

   public $mandatory_fields = [
       // 'id' => ['type' => 'number'],
       'year' => ['type' => 'number', 'verbose_name' => 'Цена'],
       'manufacturer' => ['type' => 'option', 'foreign' => '\models\Manufacturer'],
       'title' => ['type' => 'varchar'],
       'price' => ['type' => 'number'],
       'description' => ['type' => 'text'],
       'equipmenttype' => ['type' => 'option', 'foreign' => '\models\Equipmenttype'],
       'main_image' => ['type' => 'image', 'upload_to' => 'media_images'],
   ];

}

class Manufacturer extends \basemodels\BaseModel
{

    public $mandatory_fields = [
        'name' => ['type' => 'varchar'],
        'image' => ['type' => 'image', 'upload_to' => 'media']
    ];

}

class Equipmenttype extends \basemodels\BaseModel
{

    public $mandatory_fields = [
        'name' => ['type' => 'varchar'],
        'image' => ['type' => 'image', 'upload_to' => 'ms']
    ];
}