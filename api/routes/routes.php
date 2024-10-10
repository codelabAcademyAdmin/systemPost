<?php   
    $listRoutes = array(
        'index',
        'test',
        'inventories',
        'users',
        'login',
        'suppliers'
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>