<?php // todo line 39
namespace forms;

require_once BASE_DIR . '/app/models.php';

use \models as models;

function generate_option($value) // создаем непосредственно сами optionы
{   
    global $selected;
    if ($value == $selected) {
        $ch = "selected";
    } else {
        $ch = "";
    }
    $value = htmlspecialchars($value);
    return "<option $ch value='$value'>$value</option>";
}

function base_option_form($request_name, $items, $default = 'All')
{
    $request_name = htmlspecialchars($request_name);

    isset($_REQUEST[$request_name]) ? $selected = $_REQUEST[$request_name] : $selected = '';
    


    $form = generate_option($default);
    foreach ($items as $value) {
        $form .= generate_option($value['name']);
    }

    $form = '<select name="{$request_name}" class="select_list">' . $form . '</select>';

    return $form;

}


function price_form() // создаем инпуты для фильтрации по ценам
{ 
    $form = '';

    if (isset($_REQUEST['min'])) $min = htmlspecialchars($_REQUEST['min']);
    else $min = '';
    $form .= "<input class='input_area' type='number' name='min' autocomplete='off' placeholder='от' value='{$min}'>";

    if (isset($_REQUEST['max'])) $max = htmlspecialchars($_REQUEST['max']);
    else $max = '';
    $form .=  "<input class='input_area' type='number' name='max' autocomplete='off' placeholder='от' value='{$max}'>";

    return $form;
}

function equipment_type_form() // создаем инпуты для фильтрации по типам товаров
{
    models\Equipmenttype::get_all_objects();
    $items = models\Equipmenttype::make_query();

    return base_option_form('equipment_type', $items);
}

function manufacturer_form() // создаем инпуты для фильтрации по роизводителям
{
    models\Manufacturer::get_all_objects();
    $items = models\Manufacturer::make_query();

    return base_option_form('manufacturer', $items);
}

function years_form() // создаем чекбоксы для фильтрации по годам
{   
    $form = '<ul id="id_years" class="checkbox">';

    isset($_REQUEST['years']) ? $selected = $_REQUEST['years']: $selected = '';
    for ($i = 0; $i < 5; $i++) { // последнии 5 лет
        if ($selected && in_array( (date('Y') - $i), $selected )) { // добавляем cheked, если была выбрана
            $ch = "checked";
        } else {
            $ch = "";
        }
        $year = date('Y') - $i;
        $form .= "<li><label for='id_years_{$i}'><input  type='checkbox' name='years[]' value='{$year}' class='checkbox' id='id_years_{$i}' $ch ></label></li>";
        
    }

    $form .= '</ul>';

    return $form;
}