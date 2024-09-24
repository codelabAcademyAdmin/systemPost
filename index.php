<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>titulo</title>
</head>

<body>
   <?php 
        require 'core/app.php';
        $AppRoutes = new AppRoutes;
        require 'routes/routes.php';
        $listRoutes=$AppRoutes->getRoutes();
        $AppViews = new AppViews($listRoutes);
        $AppViews->loadViews();
    ?>

   <script src="assets/js/jquery.min.js"></script>
   <script src="assets/js/apiManager.js"></script>
   <script src="assets/js/app.js"></script>
   <?php 
        $AppScript = new AppScript($listRoutes);
        $AppScript->loadScript();
    ?>
</body>

</html>