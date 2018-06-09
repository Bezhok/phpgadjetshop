<?php
namespace basemodels;
/**
   Базовая модель
   Простейшая реализация ORM
   создание возможно таблиц только через субд!
   название класса - таблица в базе данных
*
*/
class BaseModel {
    private static $pdo, $query, $values;

    public function __construct()
    {
        $this->table_name = preg_replace('#.*\\\#', '', get_class($this));
        $this->table_name = strtolower($this->table_name);

        $this->values = [];
    }

    public static function plug_pdo($pdo)
    {
        self::$pdo = $pdo;
    }

    public function get_objects(array $foreign_colums = [])
    { 
        if ($foreign_colums) {
            $this->foreign_colums = $foreign_colums;
            $this->query = $this->foreign_colums_query();
        } else {
            $this->query = "SELECT * FROM $this->table_name";
        }

        return $this;
    }

    public function get_object_or_404(array $foreign_colums = [], string $id = '')
    { 
        if ($foreign_colums) {
            $this->foreign_colums = $foreign_colums;
            $this->query = $this->foreign_colums_query();
        } else { 
            $this->query = "SELECT * FROM $this->table_name";
        }
        if ($id) {
            $this->query .= " WHERE {$this->table_name}.id = ?";
            $this->values = [$id];
        }

        try { // выполняем запрос
            $statement = self::$pdo->prepare($this->query);
            $statement->execute($this->values);

            $object = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if ($object) return $object[0];
            else header('Location: /404');
        } catch (PDOException $e) {
            echo 'Неправильно введены данные';
            echo $e->getMessage();
        }  
    }
    public function add_object(array $fields) 
    {
        // получаем значения и ключи списка и преобразуем в sql запрос
        if ($this->fields_check($fields)) { // проверяем
            $fields = $this->fields;
            // echo $fields;
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

    public function edit_object($fields) // не работает 
    {
        $query = "UPDATE $this->table_name SET ";
    }

    public function del_object($id)
    {
        $this->query = "DELETE FROM $this->table_name WHERE id = ?";
        $this->values = $id;
    }

    public function get_total_count__make_query()
    {
        $query = "SELECT COUNT(id) FROM $this->table_name";

        try { // выполняем запрос
            $statement = self::$pdo->prepare($query);
            $statement->execute();
            return $statement->fetchAll()[0][0]; // получаем значение
        } catch (PDOException $e) {
            echo 'Такой записи нет или указан неверный идентификатор';
            echo $e->getMessage();
        }
    }

    public function get_current_count__make_query()
    {
        $query = preg_replace("#SELECT(.)+FROM#", "SELECT COUNT({$this->table_name}.id) FROM", $this->query); // опасно

        try { // выполняем запрос
            $statement = self::$pdo->prepare($query);
            // echo $query;
            $statement->execute($this->values);
            return $statement->fetchAll()[0][0]; // получаем значение
        } catch (PDOException $e) {
            echo 'Такой записи нет или указан неверный идентификатор';
            echo $e->getMessage();
        }
    }

    public function filter($column, string $sign, $value)
    {
        $column = htmlspecialchars($column);
        $comparisons = ['<', '<=', '>', '>=', '='];
        $statements = ['in'];

        if (in_array($sign, $comparisons) && is_numeric($value)) {

            $value = intval($value);

            if (strpos($this->query, ' WHERE ') === false) { // если where не применялось
                $this->query .= " WHERE {$this->table_name}.{$column} "; // добавляем
                $condition = "$sign ?";
            } else {
                $condition = " AND {$this->table_name}.{$column} $sign ?"; // если применялось, то добавляем AND
            }
            
            $this->query .= $condition;
            $this->values[] = $value;

        } elseif (in_array($sign, $statements)) {
            $sign = strtoupper($sign);

            $questions_signs_array = array_fill(0, count($value), '?');
            $query_questions_signs_for_pdo = implode(', ', $questions_signs_array );

            if (strpos($this->query, ' WHERE ') === false) { // если where не применялось
                $this->query .= " WHERE {$this->table_name}.{$column} "; // добавляем
                $condition = "$sign ( $query_questions_signs_for_pdo )";
            } else {
                $condition = " AND {$this->table_name}.{$column} $sign ( $query_questions_signs_for_pdo )"; // если применялось, то добавляем AND
            }

            $this->query .= $condition;
            $this->values = array_merge($this->values, $value);
        }

        return $this;
    }

    public function limit($LIMIT, $LIMIT_COUNT = false)
    {
        if (is_numeric($LIMIT) && isset($this->query)) {
            $this->query .= " LIMIT " . (int)$LIMIT;
            if ($LIMIT_COUNT !== false && is_numeric($LIMIT_COUNT)) $this->query .= ", " . (int)$LIMIT_COUNT;
        } else {
            throw new \Exception("Error Processing Request");
            
        }
        return $this;
    }

    public function make_query()
    {   
        try { // выполняем запрос
            $statement = self::$pdo->prepare($this->query);
            $statement->execute($this->values);
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
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

    private function foreign_colums_query()  // test. dangerous. sql injections
    {
        // получаем значения для запроса
        $keys = '';
        $concat_tables = '';
        foreach ($this->foreign_colums as $key => $value) { // key - название импортируемое таблицы
            foreach ($value as $column_name) {
                $keys .= ", {$key}.{$column_name} AS {$key}_{$column_name}";
            }
            // {название импортируемое таблицы}.{ее id} = {название таблицы}.{название импортируемое таблицы} 
            $concat_tables .= " LEFT OUTER JOIN $key ON {$key}.id = {$this->table_name}.{$key}"; 
        }
        $query = "SELECT product.* $keys FROM $this->table_name $concat_tables";
        return $query;
    }

}
