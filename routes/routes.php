<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'ventas',
        'inventario'
        'productos',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>