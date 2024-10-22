<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'productos',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>