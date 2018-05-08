<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="static/css/mainstyle.css">
</head>
<body>
    <a href="index">ssilrf base</a><BR />

    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get">
        <input type="text" value='' name='test'>
        <input type="submit" value="submit">
    </form>   

    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="get" name='test'>
        <input type="text" value='' name='test'>
        <input type="submit" value="submit">
    </form>    

</body>
</html>
