<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="static/css/mainstyle.css">
</head>
<body>
    <a href="templates/products.php">ssilrf base</a><BR />
    <?php
        $list = [1,2,9,0,-1,2];
        sort($list);
        array_reverse($list);
        print_r($list);
        // $_GET['test'] = 
        $c = $_SERVER['QUERY_STRING'];
        echo $c;
    ?>
    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
        <input type="text" value='' name='test'>
        <input type="submit" value="submit">
    </form>   

    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name='test'>
        <input type="text" value='' name='test'>
        <input type="submit" value="submit">
        <a href='?page=1'><input class='pagination_number' type="submit" onclick="this.id='pagination_activate'" value='1'></a>
    </form>    
    <?php 
        // echo $_GET['test'];
        // echo $_POST['test'];
    ?>
</body>
</html>


<!-- 
// засовываем данные в куки, чтобы при переключении страниц получить значения и восстановить данные
// session_start(); //TODO


$_SESSION['filters'] = [];
$filters = $_SESSION['filters'];


function val_set($get_req)
{
    isset($_REQUEST[$get_req]) ? $value = $_REQUEST[$get_req] : $value = '';
    return $value;
}


$filters['min'] = val_set('min');
$filters['max'] = val_set('max');
$filters['years'] = val_set('years');
$filters['equipment_type'] = val_set('equipment_type');

function get_filters_values()
{
    global $filters;
    $list = '';
    foreach ($filters as $k => $v) {
        if (is_array($v)) {
            foreach ($filters['years'] as $arrv) {
                $list .= "&years%5B%5D=".$arrv;
            }
        } else {
            $list .= "&".$k."=".$v; // &filter=value
        }

    }
    echo $list;
} -->
