<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="assets/css/style.css">
   <title>Sistema de ventas</title>
</head>
<body class="poppins" >

   <?php 
        session_start();     
        if(!isset($_SESSION['user'])){
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
    if(!isset($_SESSION['user'])){
        echo '<script src="scripts/script.login.js"></script>';
    }else{
        $AppScript = new AppScript($listRoutes);
        $AppScript->loadScript();
    }
    ?>
</body>

</html>
