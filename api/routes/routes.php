<?php   
    $listRoutes = array(
        'index',
        'test',
        'inventories',
        'users',
        'sales',
        'login',
        'suppliers',
        'products',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>