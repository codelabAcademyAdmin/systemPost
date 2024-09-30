<?php   
    $listRoutes = array(
        'index',
        'test',
        
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>