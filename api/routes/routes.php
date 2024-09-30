<?php   
    $listRoutes = array(
'index',
        'test',
        'users'

    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>