<?php   
    $listRoutes = array(
        'prueba',
        'index',
        'ventas',
        'proveedores',
        'inventario',
        'productos',
        'empleados',

    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>
