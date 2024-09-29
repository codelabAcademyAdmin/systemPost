
<?php 
    require 'core/app.php';
    $AppRoutes = new AppRoutes;
    require 'routes/routes.php';
    require 'headers.php';
    require 'db/conexion.php';
    $listRoutes=$AppRoutes->getRoutes();
    $AppResponse = new AppResponse($listRoutes);
    $AppResponse->loadResponse();
?>
  