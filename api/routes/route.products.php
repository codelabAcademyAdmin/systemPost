<?php


$AppRoutes->AddRoutes('GET', 'products', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $products->readById($id);
        
        switch ($response['status']) {
            case 'Not Valid':
                http_response_code(400); // Bad Request
                break;
            case 'Success':
                http_response_code(200); // Ok
                break;
            case 'Not Found':
                http_response_code(404); // Not Found
                break;
            case 'Internal Error':
                http_response_code(500); // Internal Server Error
                break;
        }
        
    } else {
        $response = $products->readAll();
        
        if($response['status'] === 'Success'){
            http_response_code(200); //Ok
        }else if($response['status'] === 'Internal Error'){
            http_response_code(500); //Internal Server Error  
        }
    }
    echo json_encode($response);
});

$AppRoutes->AddRoutes('POST', 'products', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $products->create($data['name'], $data['description'], $data['stock'], $data['category'],$data['product_price'], $data['suppliers']);
    
    if($response['status'] === 'Success'){
        http_response_code(201); //Created
    }else if($response['status'] === 'Error'){
        http_response_code(500); // Internal Server Error
    }else if($response['status'] === 'Not Found'){
        http_response_code(404); // Not Found
    }
    
    echo json_encode($response);
});

$AppRoutes->AddRoutes('PUT', 'products', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'];
    $response = $products->update($id, $data['fullname'], $data['phone'], $data['address'], $data['description']);
    echo json_encode($response);
});


$AppRoutes->AddRoutes('DELETE', 'products', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    $id = $_GET['id'];
    $response = $products->delete($id);
    echo json_encode($response);
});
