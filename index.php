<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="assets/css/style.css">
   <title>Sistema de ventas</title>
</head>
<body>
   <?php 
        session_start();     
        if(isset($_SESSION['user'])){
            require 'pages/page.login.php';
        }else{
            require 'core/app.php';
            $AppRoutes = new AppRoutes;
            require 'routes/routes.php';
            $listRoutes=$AppRoutes->getRoutes();
            $AppViews = new AppViews($listRoutes);
            require 'layout/layout.php';
            
        }
    ?>
   <script src="assets/js/jquery.min.js"></script>
   <script src="assets/js/apiManager.js"></script>
   <script src="assets/js/app.js"></script>
   <?php 
    if(isset($_SESSION['user'])){
        echo '<script src="assets/js/script.login.js"></script>';
    }else{
        $AppScript = new AppScript($listRoutes);
        $AppScript->loadScript();
    }
    ?>
</body>

</html>