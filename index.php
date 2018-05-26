<!DOCTYPE html>
<html>
<head>
    <title></title>
    <!-- <link rel="stylesheet" type="text/css" href="static/gulp/app/css/main.css"> -->
</head>
<body>
    <a href="index">ssilrf base</a><BR />
  
<?php 
    // die('аргументов передано больше, чем ожидалось');
    // throw new \Exception("Error Processing Request");
    function t(...$args){
        echo count($args);
    }
    t();
?>
</body>
</html>
