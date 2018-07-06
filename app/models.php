<?php
namespace models;

use \core\models\BaseModel;

/*
Допустимые типы: varchar, text, option, image, number
если добавляете поле id, то уберите автоинкремент в bd
*/
class Product extends BaseModel
{
   public $mandatory_fields = [
       'year' => ['type' => 'number', 'verbose_name' => 'Год'],
       'manufacturer' => ['type' => 'option', 'foreign' => '\models\Manufacturer', 'verbose_name' => 'Производитель'],
       'title' => ['type' => 'varchar', 'verbose_name' => 'Название'],
       'price' => ['type' => 'number', 'verbose_name' => 'Цена'],
       'description' => ['type' => 'text', 'verbose_name' => 'Описание'],
       'equipmenttype' => ['type' => 'option', 'foreign' => '\models\Equipmenttype', 'verbose_name' => 'Тип товара'],
       'main_image' => ['type' => 'image', 'upload_to' => 'media_images', 'verbose_name' => 'Картинка'],
   ];
   public $verbose_name = 'Продукт';
}

class Manufacturer extends BaseModel
{
    public $mandatory_fields = [
        'name' => ['type' => 'varchar'],
        'image' => ['type' => 'image', 'upload_to' => 'media']
    ];
    public $verbose_name = 'Производители';
}

class Equipmenttype extends BaseModel
{
    public $mandatory_fields = [
        'name' => ['type' => 'varchar'],
        'image' => ['type' => 'image', 'upload_to' => 'ms']
    ];
    public $verbose_name = 'Типы товаров';
}

class Auth_User extends BaseModel
{
    public $mandatory_fields = [
        'username' => ['type' => 'varchar', 'verbose_name' => 'Логин'],
        'password' => ['type' => 'password', 'verbose_name' => 'Пароль'],
        'email' => ['type' => 'varchar', 'verbose_name' => 'Почта'],
    ];
}