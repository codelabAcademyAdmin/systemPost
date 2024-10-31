<?php
$AppRoutes->AddRoutes('GET', 'inventories/sales', function () {
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

$AppRoutes->AddRoutes('GET', 'inventories/products', function () {
    require_once 'models/inventories.php';
    $inventories = new inventoriesModel();
    $response;

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $response = $inventories->readProductsById($id);
    }else if (isset($_GET['status'])) {
        $status = $_GET['status'];
        if ($status === 'activo') {
            $response = $inventories->readProductsActive($status);
        } else if ($status === 'inactivo') {
            $response = $inventories->readProductsInactive($status);
        } else {
            $response = $inventories->validateStatus($status);
        }
    } else {
        $response = $inventories->readAllProducts();
    }

    echo json_encode($response);
});

$AppRoutes->AddRoutes('GET', 'inventories/suppliers', function () {
    require_once 'models/inventories.php';
    $inventories = new inventoriesModel();
    $response;

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $response = $inventories->readSuppliersById($id);
    }else if (isset($_GET['status'])) {
        $status = $_GET['status'];
        if ($status === 'activo') {
            $response = $inventories->readSuppliersActive($status);
        } else if ($status === 'inactivo') {
            $response = $inventories->readSuppliersInactive($status);
        } else {
            $response = $inventories->validateStatus($status);
        }
    } else {
        $response = $inventories->readAllSuppliers();
    }
    echo json_encode($response);
});
