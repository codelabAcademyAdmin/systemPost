<?php   
    $listRoutes = array(
        'index',
        'test',
        'inventories',
        'users',
        'login',
        'suppliers',
        'products',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>