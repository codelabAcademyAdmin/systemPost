<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'ventas',
        'inventario'
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>