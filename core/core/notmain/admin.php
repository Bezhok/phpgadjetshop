<?php
namespace core\admin;

 /**
 * регистрация обЪектов для панели админситрации
 */
 class Admin
 {
     static $admin_objects;
     public function register(\core\models\BaseModel $obj)
     {
         $table_name = $obj->table_name;
         if (isset($obj->verbose_name)) {
            $verbose_name = $obj->verbose_name;
        } else {
            $verbose_name = $table_name;
        }
         
        self::$admin_objects[$table_name] = ['table_name' => $table_name, 'verbose_name' => $verbose_name, 'class_name' => '\\' . get_class($obj)];
     }
 }