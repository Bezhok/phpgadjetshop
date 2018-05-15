<?php 
namespace filters;



function more_less_filter($obj_mass, $req_parametr, $more_less) // фильтруем массив по цене
{ 

    $mass = [];

    if ( isset($_REQUEST[$req_parametr]) && is_numeric($_REQUEST[$req_parametr]) ) { // итерируем, только если значение существует и является числом
        
        if ( $more_less == '>') {
            foreach ($obj_mass as $value) {
    
                if ( $value->price >=  $_REQUEST[$req_parametr] ) $mass[] = $value;

            } 
        }
        if ( $more_less == '<') {
            foreach ($obj_mass as $value) {

                if ( $value->price <= $_REQUEST[$req_parametr] ) $mass[] = $value;
            } 
        }


        return $mass;

    } else {
        return $obj_mass;
    }

}

function checkboxes_filter($products, $req_parametr) 
{
    if ( isset($_REQUEST[$req_parametr]) && is_array($_REQUEST[$req_parametr])) { // фильтрация по чекбоксам
        $mass = [];
    
        foreach ($products as $value) {
            if ( in_array($value->created_year, $_REQUEST[$req_parametr]) ) $mass[] = $value;
        } 
        return $mass;
    } else {
        return $products;
    }

}

function options_filter($products, $req_parametr) 
{
    if ( isset($_REQUEST[$req_parametr]) && $_REQUEST[$req_parametr] != 'All' && $_REQUEST[$req_parametr] ) { // фильтрация параметру. Если выбран пункт все, то не фильтруем
        $mass = [];
    
        foreach ($products as $value) {
            if ( $value->equipment_type == $_REQUEST[$req_parametr] ) $mass[] = $value;
        } 
        return $mass;
    } else {
        return $products;
    }

}