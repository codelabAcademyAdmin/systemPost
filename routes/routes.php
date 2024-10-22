<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'inventario',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>