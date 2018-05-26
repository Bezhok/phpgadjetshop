<!DOCTYPE html>
<html lang="ru">
<head>
    <title></title>

    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
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

    <div class="container">
        <form action="">
            <div class="form-froup">
                <label for="adminEmail">Login</label>
                <input type="email" class="form-control" id="adminEmail" name="adminEmail">           
            </div>
            
            <div class="form-froup">
                <label for="adminPasword">пароль</label>
                <input type="password" class="form-control" id="adminPassword" name="adminPassword">                
            </div>

            <button type="submit" class="btn btn-primary" >log in</button>
        </form>
    </div>

    <!-- <div class="container-fluid"> -->
        <div class="container-fluid">
            <div class="row text-center">

                <div class="col-xs-12 col-sm-4 col-lg-2">
                    <img src="/media/img/download.jpg" alt="" class="w-100">
                    <h3>loremdfd</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem ab sit tempore deleniti libero debitis!</p>
                </div>
                <div class="col-xs-12 col-sm-4 col-lg-2">
                    <img src="/media/img/download.jpg" alt="" class="w-100">
                    <h3>lorem</h3>
                </div>
                <div class="col-xs-12 col-sm-4 col-lg-2">
                    <img src="/media/img/download.jpg" alt="" class="w-100">
                    <h3>lorem</h3>
                </div>
                <!-- <div class="w-100"></div> -->
                <div class="col-xs-12 col-sm-4 col-lg-2">
                    <img src="/media/img/download.jpg" alt="" class="w-100">
                    <h3>lorem</h3>
                </div>
                <div class="col-xs-12 col-sm-4 col-lg-2">
                    <img src="/media/img/download.jpg" alt="" class="w-100">
                    <h3>lorem</h3>
                </div>
                <div class="col-xs-12 col-sm-4 col-lg-2">
                    <img src="/media/img/download.jpg" alt="" class="w-100">
                    <h3>lorem</h3>
                </div>

            </div>        
        </div>
    <!-- </div>    -->
    

</body>
</html>
