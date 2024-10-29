<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'ventas',


        'proveedores',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>