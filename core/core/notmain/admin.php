<?php
namespace core\admin;

 /**
 * регистрация обЪектов для панели админситрации
 */
 class Admin
 {
     static $admin_objects;
     public function register(\core\models\BaseModel $obj, $display_columns = [])
     {
        if ($display_columns === []) {
            $display_columns = array_keys($obj->mandatory_fields);
        }
        if (!in_array('id', $display_columns)) { // добавляем поле id, если нет
            array_unshift($display_columns, 'id');
        }

        $table_name = $obj->table_name;
        if (isset($obj->verbose_name)) {
            $verbose_name = $obj->verbose_name;
        } else {
            $verbose_name = $table_name;
        }
         
        self::$admin_objects[$table_name] = ['table_name' => $table_name, 'verbose_name' => $verbose_name, 'class_name' => '\\' . get_class($obj), 'display_columns' => $display_columns];
     }
 }