<?php
namespace core\forms;

class SelectForm
{
    public function __construct($obj, $req_name, $default)
    {
        $this->req_name = $req_name;
        $this->default = $default;
        $this->items = $obj->get_objects()->make_query();
    }

    public function form()
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
        if ($value == $selected) {
            $ch = "selected";
        } else {
            $ch = "";
        }
        $value = htmlspecialchars($value);
        $name = htmlspecialchars($name);
    
        return "<option $ch value='$value'>$name</option>";
    }

}