<?php
require_once '../models/inventories.php';

$inventories = new inventoriesModel();

$request = $_SERVER['REQUEST_METHOD'];
$response;

if ($request == 'GET') {
    $uri = $_SERVER['REQUEST_URI'];
    $path = parse_url($uri, PHP_URL_PATH);
    $pathSegments = explode('/', trim($path, '/'));

    // Asumiendo que la estructura de la URL es /inventories/{action}
    if (count($pathSegments) >= 2 && $pathSegments[0] == 'inventories') {
        $action = $pathSegments[1];
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        switch ($action) {
            case 'sales':
                if ($id) {
                    $response = $inventories->readSaleById($id);
                } else {
                    $response = $inventories->readSaleAll();
                }
                break;
            case 'products':
                if ($id) {
                    $response = $inventories->readProductsById($id);
                } else {
                    $response = $inventories->readAllProducts();
                }
                break;
            case 'suppliers':
                if ($id) {
                    $response = $inventories->readsuppliersById($id);
                } else {
                    $response = $inventories->readAllSuppliers();
                }
                break;
                default:
                $response = ['status' => 400, 'error' => 'Acción no reconocida'];
                break;
        }
    } else {
        $response = ['status' => 400, 'error' => 'Ruta no válida'];
    }
    echo json_encode($response);
}
?>