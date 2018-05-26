<!DOCTYPE html>
<html>
<head>
    <title></title>
    <!-- <link rel="stylesheet" type="text/css" href="static/gulp/app/css/main.css"> -->
</head>
<body>
    <a href="index">ssilrf base</a><BR />
    <?php

    ?>
    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
        <input type="text" value='' name='test'>
        <input type="submit" value="submit">
    </form>   

    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name='test'>
        <input type="text" value='' name='test'>
        <input type="submit" value="submit">
        <a href='?page=1'><section class='pagination_number' type="submit" onclick="this.id='pagination_activate'">1</section></a>
    </form>    
    <?php 
    $v = 5;
    $c =& $v;
    echo $c;
    echo realpath('\static\\');
    $x = 5;
    $list = [2,3];
    function def(&$list) {
        $list[0] = 5;
    }
    def($list);
    print_r($list);
    echo trim('/dfdf/dsfs/sfdsf\\/\\', '/\\');
    $a = [1];
    $b =& $a;
    array_push($b, 2);
    print_r($b);
    print_r($a);
    $b = 6;
    echo $a;
    /**
    * 
    */
    class ClassName
    {
        public $x;
    }
    $obj = new ClassName();
    $obj->x = 5;
    $list = [$obj];
    function test($list){
        foreach ($list as $value) {
            yield $value;
        }
    }
    $iter = test($list);
    foreach ($iter as $value) {
        echo $value->x;
    }
    echo '<br/>';
    // try {
    //     $pdo = new PDO('mysql:host=php;dbname=phpmyshop_db', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    //     $query = "INSERT INTO products (id, manufacturer, years, name) VALUES (NULL, 22, 3000, 22);";
    //     $pdo->exec($query);
    //     echo 'all good!!!';
    // } catch (PDOException $e) {
    //     echo $e->getMessage();
    // }
    // $query = "INSERT INTO products (id, years, manufacturer, name) VALUES (NULL, 'h', '343', '3244', 'jj')";
    // try {
    //     $pdo->exec($query);
    // } catch (PDOException $e) {
    //     echo $e->getMessage();
    // }
    $x = [
            'name',
            'id',
            'years',
            'manufacturer'

        ];
    print_r(array_diff($x, [
            'id',
            'years',
            'manufacturer',
            'name']));


    try {
        $pdo = new PDO('mysql:host=php;dbname=phpmyshop_db', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        echo 'all good!!!';
    } catch (PDOException $e) {
        echo 'error base';
    }

    $query = "SHOW COLUMNS FROM products";
    // print_r($pdo->query($query)->fetchAll());


    $str = 'step=5
    min=0
    max=100';
    
    $output = array();
    $array = explode("\n",$str);
    foreach($array as $a){
        $output[substr($a,0,strpos($a,"="))] = substr($a,strpos($a,"=")+1);
    }
    
    echo '<pre>';
    print_r($output);
    echo '</pre>';
    ?>
    <div class="" style="height: 500px;background: blue;"><div class="">23874392</div>
    <div class="" style="height: 100%;background: green;">fdjifsdifjdsjf</div>
    </div>

</body>
</html>
