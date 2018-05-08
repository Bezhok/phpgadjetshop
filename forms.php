<?php // todo line 39
namespace forms;

function price_form() // создаем инпуты для фильтрации по ценам
{ 

    if (isset($_REQUEST['min'])) $min = $_REQUEST['min'];
    else $min = '';
    echo  "<input class='input_area' type='number' name='min' autocomplete='off' placeholder='от' value='{$min}'>";

    if (isset($_REQUEST['max'])) $max = $_REQUEST['max'];
    else $max = '';
    echo  "<input class='input_area' type='number' name='max' autocomplete='off' placeholder='от' value='{$max}'>";
}

function equipment_type_form($items) // создаем инпуты для фильтрации по типам товаров
{
    isset($_REQUEST['equipment_type']) ? $selected = $_REQUEST['equipment_type'] : $selected = '';
    $text = "";
    foreach ($items as $k => $v) {
        if ($v == $selected) {
            $ch = "selected";
        } else {
            $ch = "";
        }
        $text .= "<option $ch value='$v'>$v</option>";
    }
    echo $text;
}

function years_form() // создаем чекбоксы для фильтрации по годам
{   
    isset($_REQUEST['years']) ? $selected = $_REQUEST['years']: $selected = '';
    for ($i = 0; $i < 5; $i++) { // последнии 5 лет
        if ($selected && in_array( (date('Y') - $i), $selected )) { // добавляем cheked, если была выбрана
            $ch = "checked";
        } else {
            $ch = "";
        }
        echo "<li><label for='id_years_{$i}'><input  type='checkbox' name='years[]' value='", date('Y') - $i ,"' class='checkbox' id='id_years_{$i}' $ch ></label></li>";
    }
}
$list = ['All', 'phone', 'tablet', 'name3']; //todo