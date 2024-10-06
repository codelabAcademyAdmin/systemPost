<?php
$AppRoutes->AddRoutes('GET', 'products', function () {
    require_once 'models/product.php';
    $products = new productsModel();
    $response;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $products->readById($id);
    } else {
        $response = $products->readAll();
    }
    echo json_encode($response);
});

$AppRoutes->AddRoutes('POST', 'products', function () {
    require_once 'models/product.php';
    $products = new productsModel();
    $response;
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $products->create($data['id_supplier'], $data['fullname'], $data['phone'], $data['address'], $data['description']);
    echo json_encode($response);
});

$AppRoutes->AddRoutes('PUT', 'products', function () {
    require_once 'models/product.php';
    $products = new productsModel();
    $response;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'];
    $response = $products->update($id, $data['fullname'], $data['phone'], $data['address'], $data['description']);
    echo json_encode($response);
});


$AppRoutes->AddRoutes('DELETE', 'products', function () {
    require_once 'models/product.php';
    $products = new productsModel();
    $response;
    $id = $_GET['id'];
    $response = $products->delete($id);
    echo json_encode($response);
});
