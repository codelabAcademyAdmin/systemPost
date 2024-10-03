<?php   
    $listRoutes = array(
        'index',
        'test',
        'inventories',
        
    );
    foreach($listRoutes as $route) {
        require 'route.'.$route.'.php';
    }
?>