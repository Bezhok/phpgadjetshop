<?php // todo line 39
namespace forms;


require_once BASE_DIR . '/app/models.php';

use \models as models;


function price_form() // создаем инпуты для фильтрации по ценам
{ 
    $form = '';

    if (isset($_REQUEST['min'])) $min = htmlspecialchars($_REQUEST['min']);
    else $min = '';
    $form .= "<input class='input_area' type='number' name='min' autocomplete='off' placeholder='от' value='{$min}'>";

    if (isset($_REQUEST['max'])) $max = htmlspecialchars($_REQUEST['max']);
    else $max = '';
    $form .=  "<input class='input_area' type='number' name='max' autocomplete='off' placeholder='до' value='{$max}'>";

    return $form;
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

