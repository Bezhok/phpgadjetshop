<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="static/css/mainstyle.css">
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
    ?>
</body>
</html>
