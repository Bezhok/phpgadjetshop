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
        $this->files = [];
        $this->form = '<table>';
        $this->generate_form_description();

    }

    private function generate_form_description()
    {
        foreach ($this->obj_fields as $this->obj_field_name => $this->obj_params) { // генерация формы

            $this->db_field_properties = false; // обнуляем
            isset($this->POST[$this->obj_field_name]) ? $this->POST_field_value = $this->POST[$this->obj_field_name]: $this->POST_field_value = '';
        
            foreach ($this->db_fields_properties as $db_field_properties) { // получаем параметры из базы данных
                
                if ($db_field_properties['Field'] == $this->obj_field_name) { // определяем какому полю из models это соответствует
                    $this->db_field_properties = $db_field_properties; // извлекаем описание нужного поля
                    break;
                }
            }

            if ($this->db_field_properties === false) { // сли не назначено, то ошибка в models.php
                $this->add_errors();
                throw new \Exception("Неизвестное поле '$this->obj_field_name' в models.php, такого поля нет в базе данных");
            }

            if ($this->db_field_properties['Type']) { // если максимальная длина поля обозначена
                $this->max_length = preg_replace("/[^0-9]/", '', $this->db_field_properties['Type']);
            } else {
                $this->max_length = '';
            }
        
            $this->type = $this->obj_params['type']; // тип поля

            if ($this->type == 'image' || $this->type == 'file') {
                $this->files[] = [
                    'field_name' => $this->obj_field_name,
                    'params' => $this->obj_params,
                ];
            }

            $this->validate();
            $this->add_form();
        }
        $this->upload_files();
        $this->add_entry();
    }

    private function validate()
    {       
        $this->error_class = '';
        $this->error_helper = '';

        if ($this->POST_field_value !== '' || $this->type == 'image') {
        
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

            if ($this->type == 'image') {

                $file =& $_FILES[$this->obj_field_name];
                if (count($file['name']) == 1) {
                    $file_extension = pathinfo($file['name'], $options = PATHINFO_EXTENSION);

                    if ((!isset($file) || $file['error'])) { // если есть ошибки или не загружено
                        $this->error_helper .= "<p>Загрузите фото.</p>";
                        $this->add_errors();

                    } elseif (!in_array($file_extension, AVAILABLE_IMAGE_TYPES)) {
                        $file_extension = htmlspecialchars($file_extension);
                        $this->error_helper .= "<p>Загрузите правильное изображение. Файл, который вы загрузили, поврежден или не является изображением. Его расширение '$file_extension'.</p>";
                        $this->add_errors();

                    } elseif ((int)$file['size'] > MAX_IMAGE_SIZE) {
                        $size = $file['size'];
                        $this->error_helper .= "<p>Убедитесь, что размер файла не более " . MAX_IMAGE_SIZE/1000 . " килобайт (сейчас " . $size/1000 . ").</p>";
                        $this->add_errors();

                    } else {
                        $this->error_helper .= "<p>Фото выбрано.</p>";
                    }
                } else {
                    $this->error_helper .= "<p>Вы попытались загрузить " . count($file['name']) . " фала(ов) через одно поле ввода.</p>";
                    $this->add_errors();
                }

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

    private function add_form()
    {
            $this->form .= "<tr>";
            $this->form .= "<td><label for='field_id_{$this->obj_field_name}'>$this->obj_field_name</label>";
            $this->form .= "<td class='$this->error_class'>{$this->error_helper}";

            switch ($this->type) {
                case 'text':
                    $this->form .= "<textarea name='$this->obj_field_name' cols='40' rows='10' id='field_id_{$this->obj_field_name}'>" . $this->POST_field_value . "</textarea>";
                    break;

                case 'varchar':
                    $this->form .= "<input type='text' name='$this->obj_field_name' id='field_id_{$this->obj_field_name}' maxlength='$this->max_length' value='" . $this->POST_field_value . "'>";
                    break;

                case 'number':
                    $this->form .= "<input type='number' name='$this->obj_field_name' id='field_id_{$this->obj_field_name}' maxlength='$this->max_length' value='" . $this->POST_field_value . "'>";
                    break;

                case 'option':
                    $option = new \core\forms\SelectForm(new $this->obj_params['foreign'], $this->obj_field_name, '-------');
                    $this->form .= "{$option}";
                    break;

                case 'image':
                    $this->form .= "<input type='file' name='$this->obj_field_name'>";
                    break;
                
                default:
                    throw new \Exception("Неизвестный тип поля");
                    break;
            }
            $this->form .= '</td></tr>';
    }


    private function upload_files()
    {
        // транслитерация строк
        function rus2translit($string) {
            $converter = array(
                'а' => 'a',   'б' => 'b',   'в' => 'v',
                'г' => 'g',   'д' => 'd',   'е' => 'e',
                'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
                'и' => 'i',   'й' => 'y',   'к' => 'k',
                'л' => 'l',   'м' => 'm',   'н' => 'n',
                'о' => 'o',   'п' => 'p',   'р' => 'r',
                'с' => 's',   'т' => 't',   'у' => 'u',
                'ф' => 'f',   'х' => 'h',   'ц' => 'c',
                'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
                'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
                'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
                
                'А' => 'A',   'Б' => 'B',   'В' => 'V',
                'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
                'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
                'И' => 'I',   'Й' => 'Y',   'К' => 'K',
                'Л' => 'L',   'М' => 'M',   'Н' => 'N',
                'О' => 'O',   'П' => 'P',   'Р' => 'R',
                'С' => 'S',   'Т' => 'T',   'У' => 'U',
                'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
                'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
                'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
                'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
            );
            return strtr($string, $converter);
        }

        function str2url($str) {
            // переводим в транслит
            $str = rus2translit($str);
            // убираем апострофы после транслита
            $str = preg_replace('#\'#u', '', $str);
            // заменям все ненужное нам на "-"
            $str = preg_replace('#[^-a-z0-9_\.]#u', '_', $str);
            return $str;
        }
/************************************************** сделать нормальной рандом с символами ***********************************************************************/
        function check_name($dest, $filename, $directory) // изменяем название, если такое существует
        {
            if (file_exists($dest)) { // если файл с таким названием существует, то переименовываем
                $replacement = mt_rand(120202, 34024230423);
                $filename = preg_replace('#\.#u', "_$replacement.", $filename, 1);
                $dest = MEDIA_ROOT . "$directory/" .  $filename;
                echo check_name($dest, $filename, $directory);
                return check_name($dest, $filename, $directory);
            } else {
                return $filename; 
            }

        }
/**********************************************************************************************************************************/
        if (isset($_REQUEST['_save']) && !$this->obj->errors) { // переименовывание файла, перемещение в директорию и добавления в запрос путя до него
            foreach ($this->files as $v) {
                $field_name = $v['field_name'];
                $params = $v['params'];

                $file_tmp_name = $_FILES[$field_name]['tmp_name'];
                $filename = $_FILES[$field_name]['name'];
                $filename = str2url($filename);
                $directory = $params['upload_to'];

                $media_dir = MEDIA_ROOT . "/$directory";
                if (! file_exists($media_dir)) {
                    mkdir($media_dir);
                }
                $dest = "$media_dir/$filename";

                $filename = check_name($dest, $filename, $directory);
                $dest = "$media_dir/$filename";

                move_uploaded_file($file_tmp_name, $dest); // перемещаем временный файл

                $media_url = MEDIA_URL . "/$directory/" . $filename;
                $this->POST[$field_name] = $media_url;
            }
        }
    }
/************************************ доработать redirect *************************************************/
    private function add_entry()
    {
        $this->log = '';
        if (isset($_REQUEST['_save'])) {
            if ($this->obj->errors) { // если ошибки есть
                $this->log = '<tr><p class="errornote">Пожалуйста, исправьте ошибки ниже.</p></tr>';
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
/**************************************************************/
    public function __toString()
    {
        $this->form .= '</table><button type="submit" name="_save">button</button>';
        $this->form = $this->log . $this->form;
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