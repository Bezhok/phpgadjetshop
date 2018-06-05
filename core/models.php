<?php

namespace basemodels;


/**
   Базовая модель
   Простейшая реализация ORM
   создание возмодно таблиц только через субд!
   название класса - таблица в базе данных
*
*/
class BaseModel {
    private static $pdo, $query, $values;


    public function __construct()
    {
        $this->table_name = preg_replace('#.*\\\#', '', get_class($this));
        $this->table_name = strtolower($table_name);
        $this->table_name = strtolower($model_name);
    }

    private static function table_name()
    {
        $table_name = preg_replace('#.*\\\#', '', static::class);
        $table_name = strtolower($table_name);

        return $table_name;
    }

    public static function plug_pdo($pdo)
    {
        self::$pdo = $pdo;
    }

    public static function get_all_objects($id = '')
    {
        $table_name = self::table_name();
        self::$query = "SELECT * FROM $table_name";

        if ($id) self::$query .= " WHERE id = ?";
        self::$values = [$id];
    }

    public static function del_object($id)
    {
        $table_name = self::table_name();
        self::$query = "DELETE FROM $table_name WHERE id = ?";
        self::$values = $id;
    }

    public static function get_with_foreign_columns(array $columns_with_namesNvalues = []) // test dangerous sql injections
    {
        $table_name = self::table_name();
        // $columns_with_namesNvalues = [
        //     'manufacturer' => ['name', 'id']

        // ];

        // получаем значения для запроса
        $keys = '';
        $concat_tables = '';
        foreach ($columns_with_namesNvalues as $key => $value) { // key - название импортируемое таблицы

            foreach ($value as $column_name) {
                $keys .= ", {$key}.{$column_name} AS {$key}_{$column_name}";
            }

            // {название импортируемое таблицы}.{ее id} = {название таблицы}.{название импортируемое таблицы} 
            $concat_tables .= " LEFT OUTER JOIN $key ON {$key}.id = {$table_name}.{$key}"; 

        }

        self::$query = "SELECT product.* $keys FROM $table_name $concat_tables";

    }

    public static function limit($LIMIT, $LIMIT_COUNT = false)
    {
        if (is_numeric($LIMIT) && isset(self::$query)) {
            self::$query .= " LIMIT " . (int)$LIMIT;
            if ($LIMIT_COUNT !== false && is_numeric($LIMIT_COUNT)) self::$query .= ", " . (int)$LIMIT_COUNT;
        } else {
            throw new \Exception("Error Processing Request");
            
        }
        
    }

    public static function make_query()
    {   
        if (empty(self::$values)) self::$values = [];

        try { // выполняем запрос
            $statement = self::$pdo->prepare(self::$query);
            $statement->execute(self::$values);

            return $statement->fetchAll(\PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo 'Такой записи нет или указан неверный идентификатор';
            echo $e->getMessage();
        }
    }

    public static function get_countNmake_query()
    {
        $table_name = self::table_name();
        $query = "SELECT COUNT(id) FROM $table_name";

        try { // выполняем запрос
            $statement = self::$pdo->prepare($query);
            $statement->execute();

            return $statement->fetchAll()[0][0]; // получаем значение

        } catch (PDOException $e) {
            echo 'Такой записи нет или указан неверный идентификатор';
            echo $e->getMessage();
        }
    }

    private function fields_check(array $fields)
    {
        if ( !array_diff(array_keys($fields), $this->mandatory_fields()) ) $this->fields = $fields;
        else false;
    }

    public function add_object(array $fields) 
    {
        // получаем значения и ключи списка и преобразуем в sql запрос
        if ($this->fields_check($fields)) { // проверяем

            $fields = $this->fields;
            echo $fields;

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
        } else {
            echo 'Неправильно введены данные';
        }

    }

    public static function get_object()
    {
        $query = "SELECT * FROM $this->table_name ";
    }

    public function edit_object($fields) // не работает 
    {
        $query = "UPDATE $this->table_name SET ";
    }

}
