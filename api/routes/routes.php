<?php   
    $listRoutes = array(
        'index',
        'test',
        'users',
        'login',
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>