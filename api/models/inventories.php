<?php

class inventoriesModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function readSaleAll() {
        $query = "SELECT * FROM sales;";
        $result = $this->conn->query($query);
        if (!$result) {
            return ['status' => 500, 'error' => "Error al obtener las ventas: " . $this->conn->error];
        }
        return ['status' => 200, 'data' => $result->fetch_all(MYSQLI_ASSOC)];
    }

    public function readSaleById($id) {
        if (!is_numeric($id)) {
            return ['status' => 400, 'error' => "El ID de la venta debe ser un número."];
        }
        $query = "SELECT * FROM sales WHERE id_sale = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ['status' => 500, 'error' => "Error al preparar la consulta: " . $this->conn->error];
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['status' => 404, 'error' => "No se encontró ninguna venta con el ID proporcionado."];
        }
        return ['status' => 200, 'data' => $result->fetch_assoc()];
    }

    public function readAllProducts() {
        $query = "SELECT * FROM products;";
        $result = $this->conn->query($query);
        if (!$result) {
            return ['status' => 500, 'error' => "Error al obtener los productos: " . $this->conn->error];
        }
        return ['status' => 200, 'data' => $result->fetch_all(MYSQLI_ASSOC)];
    }

    public function readProductsById($id) {
        if (!is_numeric($id)) {
            return ['status' => 400, 'error' => "El ID del producto debe ser un número."];
        }
        $query = "SELECT * FROM products WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ['status' => 500, 'error' => "Error al preparar la consulta: " . $this->conn->error];
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['status' => 404, 'error' => "No se encontró ningún producto con el ID proporcionado."];
        }
        return ['status' => 200, 'data' => $result->fetch_assoc()];
    }

    public function readAllSuppliers() {
        $query = "SELECT * FROM suppliers;";
        $result = $this->conn->query($query);
        if (!$result) {
            return ['status' => 500, 'error' => "Error al obtener los proveedores: " . $this->conn->error];
        }
        return ['status' => 200, 'data' => $result->fetch_all(MYSQLI_ASSOC)];
    }

    public function readsuppliersById($id) {
        if (!is_numeric($id)) {
            return ['status' => 400, 'error' => "El ID del proveedor debe ser un número."];
        }
        $query = "SELECT * FROM suppliers WHERE id_supplier = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return ['status' => 500, 'error' => "Error al preparar la consulta: " . $this->conn->error];
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['status' => 404, 'error' => "No se encontró ningún proveedor con el ID proporcionado."];
        }
        return ['status' => 200, 'data' => $result->fetch_assoc()];
    }
}
?>