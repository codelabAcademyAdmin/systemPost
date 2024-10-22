<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'proveedores',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>