<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'ventas',
        'proveedores',
        'inventario',
        'productos',

    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>
