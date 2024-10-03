<?php   
    $listRoutes = array(
'index',
        'test',
        'users',
        'login',
        'suppliers'
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>