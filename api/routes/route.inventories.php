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
    echo json_encode($response);
});

$AppRoutes->AddRoutes('GET', 'inventories/products', function() {
    require_once 'models/inventories.php';
    $inventories = new inventoriesModel();
    $response;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $inventories->readProductsById($id);
    } else {
        $response = $inventories->readAllProducts();
    }

    echo json_encode($response);
});

$AppRoutes->AddRoutes('GET', 'inventories/suppliers', function() {
    require_once 'models/inventories.php';
    $inventories = new inventoriesModel();
    $response;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $inventories->readsuppliersById($id);
    } else {
        $response = $inventories->readAllSuppliers();
    }
    echo json_encode($response);
});

?>