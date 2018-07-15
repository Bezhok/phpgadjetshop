<?php
namespace admin\models;

use \core\models\BaseModel;

/*
Допустимые типы: varchar, text, option, image, number
если добавляете поле id, то уберите автоинкремент в bd
*/

class Auth_User extends BaseModel
{
    public $mandatory_fields = [
        'username' => ['type' => 'varchar', 'verbose_name' => 'Логин'],
        'password' => ['type' => 'password', 'verbose_name' => 'Пароль'],
        'email' => ['type' => 'varchar', 'verbose_name' => 'Почта'],
    ];
}