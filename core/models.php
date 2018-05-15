<?php

namespace BaseModel;
// namespace BaseModel;

/**
   Базовая модель
   Простейшая реализация ORM
   создание возмодно таблиц только через субд!
   название класса - таблица в базе данных
*
*/
class BaseModel {
    private static $pdo;

    public function __construct($values = false)
    {
        $model_name = get_class($this);
        $this->table_name = strtolower($model_name);

        // if ($values) add_object($values);
        // if ( object not exist ) add_object(this->fields)
    }

    private function fields_check($fields)
    {

        if ( !array_diff(array_keys($fields), $this->mandatory_fields()) ) $this->fields = $fields;
        else echo 55;
    }

    public function add_object($fields) 
    {
        // получаем значения и ключи списка и преобразуем в sql запрос
        $this->fields_check($fields); // проверяем 
        $fields = $this->fields;

        $keys = array_keys($fields);
        $keys = implode(', ', $keys);
        $values = array_values($fields);
        $questions_signs_array = array_fill(0, count($values), '?');
        $query_questions_signs_for_pdo = implode(', ', $questions_signs_array );

        $query = "INSERT INTO $this->table_name ($keys) VALUES ( $query_questions_signs_for_pdo )";

        try { // выполняем запрос
            $statement = self::$pdo->prepare($query);
            $statement->execute($values);
        } catch (PDOException $e) {
            echo 'Неправильно введены данные';
            echo $e->getMessage();
        }

    }

    public static function get_all_objects()
    {
        $table_name = strtolower(static::class);
        $query = "SELECT * FROM $table_name";
        
        return self::$pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function plug_pdo($pdo)
    {
        self::$pdo = $pdo;
    }

    public static function del_object($id)
    {
        $table_name = strtolower(static::class); // получаем имя вызвывшего класса, узнаем таблицу в бд

        $query = "DELETE FROM $table_name WHERE id = ?";

        try { // выполняем запрос
            $statement = self::$pdo->prepare($query);
            $statement->execute([$id]);
            echo $query;
        } catch (PDOException $e) {
            echo 'Такой записи нет или указан неверный идентификатор';
            echo $e->getMessage();
        }
    }




    public function edit_object($fields) // не работает 
    {

        $query = "UPDATE $this->table_name SET ";

        try { // выполняем запрос
            $statement = self::$pdo->prepare($query);
            $statement->execute([$id]);
            echo $query;
        } catch (PDOException $e) {
            echo 'Такой записи нет или указан неверный идентификатор';
            echo $e->getMessage();
        }
    }




}