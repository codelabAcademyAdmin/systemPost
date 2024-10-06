<?php
require_once '../models/product.php';

$product = new productsModel();

$request = $_SERVER['REQUEST_METHOD'];
$response;
if ($request == 'GET') {  

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $product->readById($id);
    } else {
        $response = $product->readAll();
    }
    echo json_encode($response);
}

if ($request == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $response = $product->create($data['id_product'], $data['name'], $data['stock'], $data['unit_price'], $data['description'],);
    echo json_encode($response);
}

if ($request == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'];
    $response = $product->update($id, $data['fullname'], $data['phone'], $data['address'], $data['description']);
    echo json_encode($response);
}

if ($request == 'DELETE') {
    $id = $_GET['id'];
    $response = $product->delete($id);
    echo json_encode($response);
}
