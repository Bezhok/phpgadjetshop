<?php
namespace views;

use function \core\views\{render, session_security__go_to_login, session_security__go_to_admin};
use core\pagination\Pagination;
use core\admin\Admin;
use forms\{AdminForm, RegisterForm, AddObjectForm, EditObjectForm};

function admin($request)
{   
    session_security__go_to_login();
    $models_names = Admin::$admin_objects;

    return render('admin/admin.html', ['models_names' => $models_names, 'login' => $_SESSION['login']]);
}

function login($request)
{
    session_security__go_to_admin();
    $form = new AdminForm();

    return render('admin/admin_sign-in.html', [
            'admin_form' => $form
        ]);
}

function logout($request)
{
    session_security__go_to_login();
    session_unset();
    header("Location: /admin/login"); // редирект на логин
    exit();
}

function register($request)
{
    session_security__go_to_login();
    $form = new RegisterForm();

    return render('admin/admin_register.html', ['admin_form' => $form]);
}

function entries($request)
{
    session_security__go_to_login();
    $models_names = Admin::$admin_objects;
    $model_lower_name_from_request = $request['model_name']; // имя из запроса

    if (!isset($models_names[$model_lower_name_from_request])) {
        return render('404.html', []); // если такой модели нет
    } else {
        $model_name = $models_names[$model_lower_name_from_request]['class_name'];
    }

    $opertaion_log = '';
    if ( isset($_POST['csrf_token']) && $_POST['csrf_token'] == $_SESSION['csrf_token']) { // верификация пройдена [редирект на админ]
        if (isset($_POST['_delete']) && isset($_POST['entries'])) {
            $ids = $_POST['entries'];
            $del = new $model_name;
            try {
                $del->del_objects($ids);
                $opertaion_log = 'Записи удалены.';
            } catch (\PDOException $e) {
                if ($e->getCode() == 23000) {
                    $opertaion_log = 'Нельзя удалить. Этот объект(ы) связан с другими. Сначала удалите их.';
                } else {
                    throw new \PDOException; 
                }
            }
        }
    }

    $objs = new $model_name;

    $fields_names = $models_names[$model_lower_name_from_request]['display_columns'];
    $objs->get_columns($fields_names);
    $objs = $objs->order_by('id', 'DESC');
    $pagination = new Pagination($objs, 25, 7);

    $objs = $objs->make_query();

    return render('admin/entries.html', [
        'models_names' => $models_names,
        'this_model_name' => $model_lower_name_from_request,
        'objs' => $objs,
        'pagination' => $pagination,
        'opertaion_log' => $opertaion_log,
        'fields_names' => $fields_names,
        'this_verbose_model_name' => $models_names[$model_lower_name_from_request]['verbose_name'],
        'login' => $_SESSION['login']
    ]);
}

function add_entry($request)
{
    session_security__go_to_login();
    $models_names = Admin::$admin_objects;
    $model_lower_name_from_request = $request['model_name']; // имя из запроса
    
    if (!isset($models_names[$model_lower_name_from_request])) {
        return render('404.html', []); // если такой модели нет
    } else {
        $model_name = $models_names[$model_lower_name_from_request]['class_name'];
    }

    $objs = new $model_name;
    $form = new AddObjectForm($objs);

    return render('admin/add_entry.html', [
        'models_names' => $models_names,
        'form' => $form,
        'this_verbose_model_name' => $models_names[$model_lower_name_from_request]['verbose_name'],
        'login' => $_SESSION['login']
    ]);
}

function edit_entry($request)
{
    session_security__go_to_login();
    $id_from_request = $request['model_id']; // id из запроса
    $models_names = Admin::$admin_objects;
    $model_lower_name_from_request = $request['model_name']; // имя из запроса
    
    if (!isset($models_names[$model_lower_name_from_request])) {
        return render('404.html', []); // если такой модели нет
    } else {
        $model_name = $models_names[$model_lower_name_from_request]['class_name'];
    }

    $form = new EditObjectForm(new $model_name, $id_from_request);

    $obj = new $model_name;
    $obj = $obj->get_object($id_from_request);
    if (!$obj) return render('404.html', []);

    return render('admin/edit_entry.html', [
        'models_names' => $models_names,
        'form' => $form,
        'this_verbose_model_name' => $models_names[$model_lower_name_from_request]['verbose_name'],
        'login' => $_SESSION['login']
    ]);
}