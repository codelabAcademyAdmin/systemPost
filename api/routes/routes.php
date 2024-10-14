<?php   
    $listRoutes = array(
        'index',
        'test',
        'inventories',
        'users',
        'sales',
        'login',
        'suppliers'
    );      
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>