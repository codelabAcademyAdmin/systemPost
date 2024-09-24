<?php
    $AppRoutes->AddRoutes('GET', '/', function() {
        require 'pages/page.index.php';
    });
    $AppRoutes->AddRoutes('GET', 'index', function() {
        require 'pages/page.index.php';
    });
    $AppRoutes->AddRoutes('GET', '', function() {
        require 'pages/page.index.php';
    });
?>