<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'ventas',

        'productos',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>