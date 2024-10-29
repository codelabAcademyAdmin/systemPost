<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'ventas',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>