<?php
namespace core\forms;


class Form
{
    public function __construct($obj)
    {
        $this->field_description = [];
        $this->form = '';
        $this->obj = $obj;
        $this->POST = $_REQUEST;

        $this->db_fields_properties = $this->obj->describe_table__make_query(); // описание обЪекта из бд
        $this->obj_fields = $this->obj->mandatory_fields; // обязательные поля, указанные в models.php

        $query = []; // запрос, который передается в add_object
        foreach ($this->POST as $name => $request_field) {
            if (isset($request_field) && $request_field !== '') // если переданные с помощью GET/POST поля существуют в бд и заданы
            $query[$name] = htmlspecialchars($request_field); // список полей со значениями, которые надо передать, некоторых может не хватать
        }
        $this->POST = $query;
        $this->generate_form_description();
    }

    private function generate_form_description()
    {
        foreach ($this->obj_fields as $this->obj_field_name => $this->obj_params) { // генерация формы
            unset($this->db_field_properties);
            isset($this->POST[$this->obj_field_name]) ? $this->POST_field_value = $this->POST[$this->obj_field_name]: $this->POST_field_value = '';
        
            foreach ($this->db_fields_properties as $db_field_properties) { // получаем параметры из базы данных
        
                if ($db_field_properties['Field'] == $this->obj_field_name) { // определяем какому полю из models это соответствует
                    $this->db_field_properties = $db_field_properties; // извлекаем описание нужного поля
                    break;
                }
            }
          
            if ($this->db_field_properties['Type']) { // если максимальная длина поля обозначена
                $this->max_length = preg_replace("/[^0-9]/", '', $this->db_field_properties['Type']);
            } else {
                $this->max_length = '';
            }
        
            $this->type = $this->obj_params['type']; // тип поля
            $this->validate();


            $this->field_description[] = [
                'field_name' => $this->obj_field_name,
                'error' => $this->error_class,
                'error_helper' => $this->error_helper,
                'params' => $this->obj_params,
                'field_value' => $this->POST_field_value,
                'max_length' => $this->max_length
            ];
        }

        $this->check_errorrs();
    }

    private function validate()
    {       
        $this->error_class = '';
        $this->error_helper = '';

        if ($this->POST_field_value !== '') {

            if (empty($this->db_field_properties)) {
                $this->add_errors();
                throw new \Exception("Неизвестное поле '$this->obj_field_name' в models.php, такого поля нет в базе данных");
            }
        
            if ($this->type == 'option') { // проверка optionов
                $foreign = new $this->obj_params['foreign'];
                $foreign = $foreign->get_column__make_query('id');
        
                $foreign_ids = [];
                foreach ($foreign as $value) {
                    $foreign_ids[] = $value['id'];
                }
                unset($value);
                $foreign_ids = array_map("strval", $foreign_ids);
        
        
                if (!in_array((string)$this->POST_field_value, $foreign_ids, true)) { // проверка на существование такой записи в бд
                    $this->error_helper .= "<p>Выберите из списка:</p>";
                    $this->add_errors();
                }
            }
        
            if ($this->type == 'number' && !is_numeric($this->POST_field_value)) {
                $this->error_helper .= "<p>Введите целое число.</p>";
                $this->add_errors();
            }
        
            if ($this->max_length !== '' && mb_strlen($this->POST_field_value) > (int)$this->max_length) {
                $this->error_helper .= "<p>Убедитесь, что это значение содержит не более {$this->max_length} символов (сейчас " . mb_strlen($this->POST_field_value) . ").</p>";
                $this->add_errors();
            }

        } else {
            $this->error_helper .= "<p><font color='red'>Обязательное поле.</font></p>";
            $this->add_errors();

        }

        if (!isset($_REQUEST['_save'])) { // если кнопка не была нажата, не выводим ошибки
            $this->error_class = '';
            $this->error_helper = '';
        
        }
    }

    private function add_errors()
    {
        $this->error_class = 'errors';
        $this->obj->errors = true;
    }
//*************************************************************************************
    private function check_errorrs() // доработать
    {
        $this->form = '<table>';
        if (isset($_REQUEST['_save'])) {
            if ($this->obj->errors) { // если ошибок нет
                $this->form .= '<tr><p class="errornote">Пожалуйста, исправьте ошибки ниже.</p></tr>';
            } else {

                try {
                    $this->obj->add_object($this->POST);
                    $this->form .= '<tr><p class="success">Запись была успешно добавлена. Вы можете отредактировать ее еще раз ниже.</p></tr>';
                    $url = strtok($_SERVER["REQUEST_URI"],'?'); // получаем адрес без query 
                    header("Location: ". $url);
                } catch (\PDOException $e) {
                    $this->form .= '<tr><p style="color:red">Упс, произошла фатальная ошибка на сервере: запись не добавлена. Попробуйте еще раз.</p></tr>';
                }

            }
        }
    }

    public function __toString()
    {

        foreach ($this->field_description as $v) {

            $field_name = $v['field_name'];
            $error = $v['error'];
            $error_helper = $v['error_helper'];
            $params = $v['params'];
            $field_value = $v['field_value'];
            $max_length = $v['max_length'];

            $type = $params['type'];


            $this->form .= "<tr>";
            $this->form .= "<td><label for='field_id_{$field_name}'>$field_name</label>";
            $this->form .= "<td class='$error'>{$error_helper}";

            switch ($type) {
                case 'text':
                    $this->form .= "<textarea name='$field_name' cols='40' rows='10' id='field_id_{$field_name}'>" . $field_value . "</textarea>";
                    break;

                case 'varchar':
                    $this->form .= "<input type='text' name='$field_name' id='field_id_{$field_name}' maxlength='$max_length' value='" . $field_value . "'>";
                    break;

                case 'number':
                    $this->form .= "<input type='number' name='$field_name' id='field_id_{$field_name}' maxlength='$max_length' value='" . $field_value . "'>";
                    break;

                case 'option':
                    $option = new \core\forms\SelectForm(new $params['foreign'], $field_name, '-------');
                    $this->form .= "{$option}";
                    break;

                case 'image':
                    $this->form .= "<input type='text' name='$field_name' id='field_id_{$field_name}' maxlength='$max_length' value='" . $field_value . "'>";
                    break;
                
                default:
                    throw new \Exception("Неизвестный тип поля");
                    break;
            }
            $this->form .= '</td></tr>';
        }
        $this->form .= '</table><button type="submit" name="_save">button</button>';
        return $this->form;
    }
}

class SelectForm
{
    public function __construct($obj, $req_name, $default)
    {
        $this->req_name = $req_name;
        $this->default = $default;
        $this->items = $obj->get_objects()->make_query();
    }

    public function __toString()
    {
        $req_name = htmlspecialchars($this->req_name);
        isset($_REQUEST[$req_name]) ? $selected = $_REQUEST[$req_name] : $selected = '';

        $form = $this->generate_option($this->default, $this->default, $selected); // default value

        foreach ($this->items as $vars) {
            $form .= $this->generate_option($vars['id'], $vars['name'], $selected);
        }
    
        $form = "<select name='{$req_name}' class='select_list'>" . $form . "</select>";
        return $form;
    }

    private function generate_option($value, $name, $selected) // создаем непосредственно сами optionы
    {
        if ((string)$value === $selected) {
            $ch = "selected";
        } else {
            $ch = "";
        }
        $value = htmlspecialchars($value);
        $name = htmlspecialchars($name);
    
        return "<option $ch value='$value'>$name</option>";
    }

}