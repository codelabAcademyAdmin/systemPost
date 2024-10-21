<?php

class inventoriesModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function validateStatus($status) {
        if ($status !== 'activo' && $status !== 'inactivo') {
            http_response_code(400);
            return ['status' => 'error', 'message' => "El estado debe ser 'activo' o 'inactivo'."];
        }
    }
    public function readSuppliersActive($status){
        $query = "SELECT * FROM suppliers WHERE status = 'activo';";
        $result = $this->conn->query($query);
        if (!$result) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al obtener los proveedores activos: " . $this->conn->error];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_all(MYSQLI_ASSOC)];
    }

    public function readsuppliersInactive($status){
        $query = "SELECT * FROM suppliers WHERE status = 'inactivo';";
        $result = $this->conn->query($query);
        if (!$result) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al obtener los proveedores inactivos: " . $this->conn->error];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_all(MYSQLI_ASSOC)];
    }

    public function readProductsActive($status){
        $query = "SELECT * FROM products WHERE status = 'activo';";
        $result = $this->conn->query($query);
        if (!$result) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al obtener los productos activos: " . $this->conn->error];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_all(MYSQLI_ASSOC)];

    }
    public function readProductsInactive($status){
        $query = "SELECT * FROM products WHERE status = 'inactivo';";
        $result = $this->conn->query($query);
        if (!$result) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al obtener los productos inactivos: " . $this->conn->error];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_all(MYSQLI_ASSOC)];

    }

    public function readSaleAll() {
        $query = "SELECT * FROM sales;";
        $result = $this->conn->query($query);
        if (!$result) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al obtener las ventas: " . $this->conn->error];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_all(MYSQLI_ASSOC)];
    }

    public function readSaleById($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            return ['status' => 'error', 'message' => "El ID de la venta debe ser un número."];
        }
        $query = "SELECT * FROM sales WHERE id_sale = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al preparar la consulta: " . $this->conn->error];
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            http_response_code(404);
            return ['status' => 'error', 'message' => "No se encontró ninguna venta con el ID proporcionado."];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_assoc()];
    }

    public function readAllProducts() {
        $query = "SELECT * FROM products;";
        $result = $this->conn->query($query);
        if (!$result) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al obtener los productos: " . $this->conn->error];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_all(MYSQLI_ASSOC)];
    }

    public function readProductsById($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            return ['status' => 'error', 'message' => "El ID del producto debe ser un número."];
        }
        $query = "SELECT * FROM products WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al preparar la consulta: " . $this->conn->error];
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            http_response_code(404);
            return ['status' => 'error', 'message' => "No se encontró ningún producto con el ID proporcionado."];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_assoc()];
    }

    public function readAllSuppliers() {
        $query = "SELECT * FROM suppliers;";
        $result = $this->conn->query($query);
        if (!$result) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al obtener los proveedores: " . $this->conn->error];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_all(MYSQLI_ASSOC)];
    }

    public function readsuppliersById($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            return ['status' => 'error', 'message' => "El ID del proveedor debe ser un número."];
        }
        $query = "SELECT * FROM suppliers WHERE id_supplier = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            http_response_code(500);
            return ['status' => 'error', 'message' => "Error al preparar la consulta: " . $this->conn->error];
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            http_response_code(404);
            return ['status' => 'error', 'message' => "No se encontró ningún proveedor con el ID proporcionado."];
        }
        http_response_code(200);
        return ['status' => 'ok', 'data' => $result->fetch_assoc()];
    }
}
?>