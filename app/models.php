<?php




require_once CORE_DIR . '/models.php';
// use BaseModel;
class Product extends BaseModel {

    public static function mandatory_fields() //обязательные поля
    { 
        return [
            
            'year',
            'manufacturer',
            'title',
            'price',
            'description',
            'equipmenttype',
            'main_image'
        ];
    }


}


