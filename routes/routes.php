<?php   
    $listRoutes = array(
        'prueba',
        'index',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>