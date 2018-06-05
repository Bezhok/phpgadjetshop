<?php

namespace models;


require_once CORE_DIR . '/models.php';



class Product extends \basemodels\BaseModel {

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

class Manufacturer extends \basemodels\BaseModel {

    public static function mandatory_fields() //обязательные поля
    { 
        return [
            
            'name',
            'image'

        ];
    }

}

class Equipmenttype extends \basemodels\BaseModel {

    public static function mandatory_fields() //обязательные поля
    { 
        return [
            
            'name',
            'image'

        ];
    }

}