<?php
$AppRoutes->AddRoutes('GET', 'inventories/sales', function() {
    require_once 'models/inventories.php';
    $inventories = new inventoriesModel();
    $response;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $inventories->readSaleById($id);
    } else {
        $response = $inventories->readSaleAll();
    }

    if (isset($response['status'])) {
        http_response_code($response['status']);
    } else {
        http_response_code(500); // Código de estado por defecto en caso de error inesperado
        $response = ['status' => 500, 'error' => 'Error inesperado'];
    }

    echo json_encode($response);
});

$AppRoutes->AddRoutes('GET', 'inventories/products', function() {
    require_once 'models/inventories.php';
    $inventories = new inventoriesModel();
    $response;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $inventories->readProductoById($id);
    } else {
        $response = $inventories->readAllProductos();
    }

    if (isset($response['status'])) {
        http_response_code($response['status']);
    } else {
        http_response_code(500); // Código de estado por defecto en caso de error inesperado
        $response = ['status' => 500, 'error' => 'Error inesperado'];
    }

    echo json_encode($response);
});

$AppRoutes->AddRoutes('GET', 'inventories/suppliers', function() {
    require_once 'models/inventories.php';
    $inventories = new inventoriesModel();
    $response;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $inventories->readProvedorById($id);
    } else {
        $response = $inventories->readAllProvedores();
    }

    if (isset($response['status'])) {
        http_response_code($response['status']);
    } else {
        http_response_code(500); // Código de estado por defecto en caso de error inesperado
        $response = ['status' => 500, 'error' => 'Error inesperado'];
    }

    echo json_encode($response);
});

?>