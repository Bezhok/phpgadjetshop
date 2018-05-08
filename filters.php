<?php 
namespace filters;

function more_less_filter($obj_mass, $req_parametr, $more_less) // фильтруем массив по цене
{ 

    $mass = [];

    if ( !isset($_REQUEST[$req_parametr]) || !is_numeric($_REQUEST[$req_parametr]) ) { // итерируем, только если значение существует и является числом
        return $obj_mass;
    } 
    if ( isset($_REQUEST[$req_parametr]) ) {
        foreach ($obj_mass as $v) {

            if ( $more_less == '>' && $v->price >=  $_REQUEST[$req_parametr] ) {
                $mass[] = $v;
            }
    
            if ( $more_less == '<'&& $v->price <= $_REQUEST[$req_parametr] ) {
                $mass[] = $v;
            }
        } 
        return $mass;
    }

}

function checkboxes_filter($products, $req_parametr) 
{
    if ( isset($_REQUEST[$req_parametr]) && is_array($_REQUEST[$req_parametr])) { // фильтрация по чекбоксам
        $mass = [];
    
        foreach ($products as $v) {
            if ( in_array($v->created_year, $_REQUEST[$req_parametr]) ) $mass[] = $v;
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
    
        foreach ($products as $v) {
            if ( $v->equipment_type == $_REQUEST[$req_parametr] ) $mass[] = $v;
        } 
        return $mass;
    } else {
        return $products;
    }

}