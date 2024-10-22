<?php
require_once '../models/products.php';

$product = new productsModel();

$request = $_SERVER['REQUEST_METHOD'];
$response;
if ($request == 'GET') {  

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $product->readById($id);
    } else if (isset($_GET['status'])){
        $status = $_GET['status'];
        $response = $products->getFiltredProducts($status);
    }else {
        $response = $product->readAll();
    }
    echo json_encode($response);
}

if ($request == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $product->create($data['name'], $data['description'], $data['stock'], $data['category'],$data['product_price'], $data['suppliers']);
    echo json_encode($response);
}

if ($request == 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'];
    $response = $products->update($id,$data['name'], $data['description'], $data['stock'], $data['category'], $data['product_price'], $data['suppliers']);
    echo json_encode($response);
}

if ($request == 'PATCH') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        
        if ($_SERVER['REQUEST_URI'] === '/products/activate') {
            $response = $product->activate($id);
        } elseif ($_SERVER['REQUEST_URI'] === '/products/deactivate') {
            $response = $product->deactivate($id); 
        } else {
            $response = [
                'status' => 'Not Found',
                'message' => 'Ruta no válida.'
            ];
        }
        
        echo json_encode($response);
    } else {
        echo json_encode([
            'status' => 'Not Valid',
            'message' => 'Se debe proporcionar un ID.'
        ]);
    }
}
