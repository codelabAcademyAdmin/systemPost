<?php   
    $listRoutes = array(
        'index',
        'test',
        'inventories',
        'users',
        'login',
        'suppliers',
        'sales'
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>