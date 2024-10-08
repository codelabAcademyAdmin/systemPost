<?php   
    $listRoutes = array(
        'index',
        'test',
        'inventories',
        'users',
        'login',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>