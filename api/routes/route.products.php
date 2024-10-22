<?php

function setHttpResponseCode($status)
{
    switch ($status) {
        case 'Not Valid':
            http_response_code(400); // Bad Request
            break;
        case 'Success':
            http_response_code(200); // OK
            break;
        case 'Not Found':
            http_response_code(404); // Not Found
            break;
        case 'Internal Error':
            http_response_code(500); // Internal Server Error
            break;
        case 'Conflict':
            http_response_code(409); // Internal Server Error
            break;
        default:
            http_response_code(500); // Default to Internal Server Error
            break;
    }
}

$AppRoutes->AddRoutes('GET', 'products', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $response = $products->readById($id);
    } else if (isset($_GET['status'])) {
        $status = $_GET['status'];
        $response = $products->getFiltredProducts($status);
    } else {
        $response = $products->readAll();
    }

    setHttpResponseCode($response['status']);
    echo json_encode($response);
});

$AppRoutes->AddRoutes('POST', 'products', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $products->create($data['name'], $data['description'], $data['stock'], $data['category'], $data['product_price'], $data['suppliers']);

    setHttpResponseCode($response['status']);
    echo json_encode($response);
});

$AppRoutes->AddRoutes('PUT', 'products', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $_GET['id'];
    $response = $products->update($id, $data['name'], $data['description'], $data['stock'], $data['category'], $data['product_price'], $data['suppliers']);

    setHttpResponseCode($response['status']);
    echo json_encode($response);
});


$AppRoutes->AddRoutes('PATCH', 'products/deactive', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    $id = $_GET['id'];
    $response = $products->deactivate($id);

    setHttpResponseCode($response['status']);
    echo json_encode($response);
});

$AppRoutes->AddRoutes('PATCH', 'products/active', function () {
    require_once 'models/products.php';
    $products = new productsModel();
    $response;
    $id = $_GET['id'];
    $response = $products->activate($id);

    setHttpResponseCode($response['status']);
    echo json_encode($response);
});
