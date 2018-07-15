<?php
namespace core\forms;

use core\urls\Url;
use function core\csrf_token\csrf_token;

class Form
{
    public function __construct()
    {
        $this->construct_fill();
        $this->generate_form_description();
    }

    protected function construct_fill()
    {
        $this->POST = $_POST;
        $this->form = '';
        $this->files = [];
        $this->db_fields_properties = $this->obj->describe_table__make_query(); // описание обЪекта из бд
        $this->input_style = 'form-control';
        $this->block_style = 'form-froup';

        $query = []; // запрос, который передается в add_object
        foreach ($this->POST as $name => $request_field) {
            if (isset($request_field) && $request_field !== '' && in_array($name, array_keys($this->obj->mandatory_fields))) // если переданные с помощью GET/POST поля существуют в бд и заданы
            $query[$name] = htmlspecialchars($request_field); // список полей со значениями, которые надо передать, некоторых может не хватать
        }

        $this->POST = $query;

        $this->log = '<p class="success">Запись была успешно добавлена. Вы можете отредактировать ее еще раз ниже.</p>';
        $this->method_name = 'add_object';
        $this->method_params = [$this->POST];
    }

    protected function generate_form_description()
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

            if ($this->db_field_properties === false) { // если не назначено, то ошибка в models.php
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
            $this->add_form_block();
        }
        $this->upload_files();
        $this->form .= csrf_token();
        $this->upload_data();
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
                    $this->error_helper .= "Выберите из списка:";
                    $this->add_errors();
                }
            }
        
            if ($this->type == 'number' && !is_numeric($this->POST_field_value)) {
                $this->error_helper .= "Введите целое число.";
                $this->add_errors();
            }

            if ($this->type == 'image') {
                $file_path = MEDIA_ROOT . substr($this->POST_field_value, mb_strlen(MEDIA_URL)); // путь до файла
                if (!isset($file) && !empty($this->POST_field_value) && file_exists($file_path)) { // если изображение не загруено, но его имя отправлено и такой файл существует
                   $this->error_helper .= "<p>Фото выбрано.</p>";
                } else {
                    $file =& $_FILES[$this->obj_field_name];
                    if (count($file['name']) == 1) {
                        $file_extension = pathinfo($file['name'], $options = PATHINFO_EXTENSION);

                        if ((!isset($file) || $file['error'])) { // если есть ошибки или не загружено
                            $this->error_helper .= "Загрузите фото.";
                            $this->add_errors();

                        } elseif (!in_array($file_extension, AVAILABLE_IMAGE_TYPES)) {
                            $file_extension = htmlspecialchars($file_extension);
                            $this->error_helper .= "Загрузите правильное изображение. Файл, который вы загрузили, поврежден или не является изображением. Его расширение '$file_extension'.";
                            $this->add_errors();

                        } elseif ((int)$file['size'] > MAX_IMAGE_SIZE) {
                            $size = $file['size'];
                            $this->error_helper .= "Убедитесь, что размер файла не более " . MAX_IMAGE_SIZE/1000 . " килобайт (сейчас " . $size/1000 . ").";
                            $this->add_errors();

                        } else {
                            $this->error_helper .= "<p style='color:green;'>Фото выбрано.</p>";
                        }
                    } else {
                        $this->error_helper .= "Вы попытались загрузить " . count($file['name']) . " фала(ов) через одно поле ввода.";
                        $this->add_errors();
                    }
                }
            }

            if ($this->max_length !== '' && mb_strlen($this->POST_field_value) > (int)$this->max_length) {
                $this->error_helper .= "Убедитесь, что это значение содержит не более {$this->max_length} символов (сейчас " . mb_strlen($this->POST_field_value) . ").";
                $this->add_errors();
            }

            if ($this->type == 'password' && isset($this->obj_params['repeat_password'])) {
                $POST_field_name__repeat = $this->obj_field_name . "_repeat";
                $POST_field_value__repeat = $_REQUEST[$POST_field_name__repeat];
                if ($this->POST_field_value !== $POST_field_value__repeat) {
                    $this->error_helper .= "Пароли не совпадают.";
                    $this->add_errors();
                } else {
                    $password = SECRET_KEY . $this->POST_field_value;
                    $this->POST[$this->obj_field_name] = password_hash($password, PASSWORD_DEFAULT); // хэшируем пароль
                }
            }

        } else {
            $this->error_helper .= "Обязательное поле.";
            $this->add_errors();
        }

        if (!(isset($_REQUEST['_save']) || isset($_REQUEST['_addanother']))) { // если кнопка не была нажата, не выводим ошибки
            $this->error_class = '';
            $this->error_helper = '';
        }
    }

    private function add_errors()
    {
        $this->error_class = 'alert alert-danger';
        $this->obj->errors = true;
    }

    private function add_form_block()
    {
            if (isset($this->obj_params['verbose_name'])) $field_name = $this->obj_params['verbose_name']; // если в модели задано русскоезначение
            else $field_name = $this->obj_field_name;

            $this->form .= "<div class='$this->block_style $this->error_class mt-2'>";
            if ($this->error_helper) $this->form .= "<div>{$this->error_helper}</div><hr class='my-1'>"; // если есть ошибка
            $this->form .= "<label for='field_id_{$this->obj_field_name}'>$field_name</label>";


            switch ($this->type) {
                case 'text':
                    $this->form .= "<textarea class='$this->input_style' name='$this->obj_field_name' cols='40' rows='10' id='field_id_{$this->obj_field_name}'>" . $this->POST_field_value . "</textarea>";
                    break;

                case 'varchar':
                    $this->form .= "<input class='$this->input_style' type='text' name='$this->obj_field_name' id='field_id_{$this->obj_field_name}' maxlength='$this->max_length' value='" . $this->POST_field_value . "'>";
                    break;

                case 'number':
                    $this->form .= "<input class='$this->input_style' type='number' name='$this->obj_field_name' id='field_id_{$this->obj_field_name}' maxlength='$this->max_length' value='" . $this->POST_field_value . "'>";
                    break;

                case 'option':
                    $option = new SelectForm(new $this->obj_params['foreign'], $this->obj_field_name, '-------', $this->input_style, $this->POST_field_value);
                    $this->form .= $option;
                    break;

                case 'image':
                    $this->form .= "<input class='$this->input_style' type='file' name='$this->obj_field_name'>";
                    $this->form .= "<input hidden type='text' name='$this->obj_field_name' value='$this->POST_field_value'>";
                    break;

                case 'password':
                    $this->form .= "<input class='$this->input_style' type='password' name='$this->obj_field_name' '>";
                    if (isset($this->obj_params['repeat_password'])) { // если есть проверка пароля
                        $this->form .= "<label for=''>Повторите пароль</label><input class='$this->input_style' type='password' name='{$this->obj_field_name}_repeat'>";  
                    }
                    break;

                default:
                    throw new \Exception("Неизвестный тип поля");
                    break;
            }
            $this->form .= '</div>';
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

        function check_name($dest, $filename, $directory, $file_tmp_name) // изменяем название, если такое существует
        {
            if (file_exists($dest) && hash_file('md5', $dest) != hash_file('md5', $file_tmp_name)) { // если файл с таким названием существует и загружен не такоой же, то переименовываем. в противном случае перезаписываем
                $replacement = hash('md5', time());
                $filename = preg_replace('#\.#u', "_$replacement.", $filename, 1);
                $dest = MEDIA_ROOT . "$directory/" .  $filename;
                return check_name($dest, $filename, $directory, $file_tmp_name);
            } else {
                return $filename; 
            }

        }

        if ((isset($_REQUEST['_save']) || isset($_REQUEST['_addanother'])) && !$this->obj->errors) { // переименовывание файла, перемещение в директорию и добавления в запрос путя до него
            foreach ($this->files as $v) { // перебираем все
                $field_name = $v['field_name'];
                if (isset($this->POST[$field_name])) {
                    $file_path = MEDIA_ROOT . substr($this->POST[$field_name], mb_strlen(MEDIA_URL)); // путь до файла, который указан в пост
                } else {
                    $file_path = '';
                }

                if ($_FILES[$field_name]['error'] && !empty($this->POST[$field_name]) && file_exists($file_path)) { // если изображение не загруено, но его имя отправлено и такой файл существует
                    $this->POST[$field_name] = $this->POST[$field_name];
                } else {
                    $params = $v['params'];
                    $directory = $params['upload_to'];

                    $file_tmp_name = $_FILES[$field_name]['tmp_name'];
                    $filename = $_FILES[$field_name]['name'];
                    $filename = str2url($filename);
                    

                    $media_dir = MEDIA_ROOT . "/$directory";
                    if (!file_exists($media_dir)) {
                        mkdir($media_dir);
                    }
                    $dest = "$media_dir/$filename";

                    $filename = check_name($dest, $filename, $directory, $file_tmp_name);
                    $dest = "$media_dir/$filename";

                    move_uploaded_file($file_tmp_name, $dest); // перемещаем временный файл

                    $media_url = MEDIA_URL . "/$directory/" . $filename;
                    $this->POST[$field_name] = $media_url;                    
                }

            }
        }
    }

    private function upload_data()
    {
        $this->log = '';
        if (isset($_REQUEST['_save']) || isset($_REQUEST['_addanother'])) {
            if ($this->obj->errors) { // если ошибки есть
                $this->log = '<p style="color:red">Пожалуйста, исправьте ошибки ниже.</p>';
            } else {
                try {
                    if ( isset($_POST['csrf_token']) && $_POST['csrf_token'] == $_SESSION['csrf_token']) { // верификация пройдена [редирект на админ]
                        $this->upload_data_fill();
                    } else {
                        require_once BASE_DIR . '/templates/403_csrf_error.html';
                        exit();
                    }
                } catch (\PDOException $e) {
                    $this->form .= '<p style="color:red">Упс, произошла фатальная ошибка на сервере. Попробуйте еще раз.</p>';
                }

            }
        }
    }

    protected function upload_data_fill()
    {
        $method_name = $this->method_name;
        $method_params = $this->method_params;

        $this->obj->$method_name(...$method_params);
        $this->log = $this->success_log;

        global $urlpatterns;
        $url = new Url($urlpatterns);
        if (isset($_REQUEST['_save'])) {
            $url = $url->url('entries', $this->obj->table_name);
        } elseif (isset($_REQUEST['_addanother'])) {
            $url = $url->url('add_entry', $this->obj->table_name);
        } 
        header("Location: ". $url);
        exit();
    }

    public function __toString()
    {
        $this->form = $this->log . $this->form;
        return $this->form; 
    }
}

class SelectForm
{
    public function __construct($obj, $req_name, $default, $style = '', $selected = '')
    {
        $this->selected = $selected;
        $this->style = $style;
        $this->req_name = $req_name;
        $this->default = $default;
        $this->items = $obj->get_objects()->make_query();
    }

    public function __toString()
    {
        $req_name = htmlspecialchars($this->req_name);
        if ($this->selected === '') { // если выбранное значение не передано
            isset($_REQUEST[$req_name]) ? $this->selected = $_REQUEST[$req_name] : $this->selected = '';  
        }

        $form = $this->generate_option($this->default, $this->default); // default value

        foreach ($this->items as $vars) {
            $form .= $this->generate_option($vars['id'], $vars['name']);
        }
    
        $form = "<select name='{$req_name}' class='$this->style'>" . $form . "</select>";
        return $form;
    }

    private function generate_option($value, $name) // создаем непосредственно сами optionы
    {
        if ((string)$value === (string)$this->selected) {
            $ch = "selected";
        } else {
            $ch = "";
        }
        $value = htmlspecialchars($value);
        $name = htmlspecialchars($name);
    
        return "<option $ch value='$value'>$name</option>";
    }

}