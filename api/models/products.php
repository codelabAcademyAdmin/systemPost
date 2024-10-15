<?php
class productsModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }


    public function create($name, $description, $stock, $category, $product_price, $suppliers)
    {
        $existingProduct = $this->checkIfProductExists($name);
        if ($existingProduct) {
            return [
                'status' => 'Conflict',
                'message' => 'Ya existe un producto con el nombre: ' . $name
            ];
        }

        $validationResult = $this->validateSuppliers($suppliers);
        if ($validationResult['status'] !== 'Success') {
            return $validationResult;
        }

        $query = "INSERT INTO products (name, description, stock, category, product_price) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }

        $stmt->bind_param("ssisd", $name, $description, $stock, $category, $product_price);

        if ($stmt->execute()) {
            $id_product = $this->conn->insert_id; // Obtener el ID del producto recién insertado

            foreach ($suppliers as $supplier) {
                $id_supplier = $supplier['id_supplier'];
                $this->associateSupplier($id_product, $id_supplier);
            }

            $validation = $this->readById($id_product);
            if ($validation) {
                $stmt->close();
                return [
                    'status' => 'Success',
                    'message' => 'Producto creado exitosamente',
                    'product' => $validation['product']
                ];
            } else {
                return [
                    'status' => 'Error',
                    'message' => 'No se pudo validar la creación del producto: ' . $stmt->error
                ];
            }
        } else {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al ejecutar la consulta: ' . $stmt->error
            ];
        }
    }

    private function checkIfProductExists($name)
    {
        $query = "SELECT id_product FROM products WHERE name = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return true;
        }

        return false;
    }

    private function validateSuppliers($suppliers)
    {
        require_once 'models/suppliers.php';
        $suppliersModel = new suppliersModel();

        foreach ($suppliers as $supplier) {
            $id_supplier = $supplier['id_supplier'];
            $data = $suppliersModel->readById($id_supplier);

            if (!$data || $data['status'] === 'Not Found') {
                return [
                    'status' => 'Not Found',
                    'message' => 'El proveedor con ID: ' . $id_supplier . ' no existe.'
                ];
            }
        }

        return ['status' => 'Success'];
    }

    private function associateSupplier($id_product, $id_supplier)
    {
        $query = "INSERT INTO products_suppliers (id_product, id_supplier) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }
        $stmt->bind_param("ii", $id_product, $id_supplier);
        if ($stmt->execute()) {
            return [
                'status' => 'Success',
                'message' => 'Proveedor asociado al producto exitosamente'
            ];
        } else {
            return [
                'status' => 'Error',
                'message' => 'Error al asociar el proveedor con el producto: ' . $stmt->error
            ];
        }
    }

    public function readAll()
    {
        $query = "SELECT * FROM products";
        $result = $this->conn->query($query);

        if ($result === false) {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al ejecutar la consulta: ' . $this->conn->error
            ];
        }

        if ($result->num_rows > 0) {
            return [
                'status' => 'Success',
                'products' => $result->fetch_all(MYSQLI_ASSOC)
            ];
        }
    }



    public function readById($id_product)
    {
        if (!is_numeric($id_product)) {
            return [
                'status' => 'Not Valid',
                'message' => "El ID del producto debe ser un numero."
            ];
        }

        $query = "SELECT * FROM products WHERE id_product = ?";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return [
                'status' => 'Internal Error',
                'message' => "Error al preparar la consulta" . $this->conn->error
            ];
        }
        $stmt->bind_param("i", $id_product);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return [
                'status' => 'Not Found',
                'message' => "No se encontró ningún producto con el ID proporcionado."
            ];
        }
        $product = $result->fetch_assoc();
        return [
            'status' => 'Success',
            'product' => $product
        ];
    }

    public function update($id_product, $name, $description, $stock, $category, $product_price, $suppliers)
    {
        if (!is_numeric($id_product)) {
            return [
                'status' => 'Not Valid',
                'message' => "El ID del producto debe ser un numero."
            ];
        }

        $existingProduct = $this->readById($id_product);
        if ($existingProduct['status'] !== 'Success') {
            return $existingProduct;
        }

        $validationResult = $this->validateSuppliers($suppliers);
        if ($validationResult['status'] !== 'Success') {
            return $validationResult;
        }
        $query = "UPDATE products SET name = ?, description = ?, stock = ?, category = ?, product_price = ? WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }
        $stmt->bind_param("ssisdi", $name, $description, $stock, $category, $product_price, $id_product);

        $productUpdated = $stmt->execute();
        if (!$productUpdated) {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al ejecutar la actualización: ' . $stmt->error
            ];
        }

        $productAffectedRows = $stmt->affected_rows;

        $this->deleteProductSuppliers($id_product);
        foreach ($suppliers as $supplier) {
            $id_supplier = $supplier['id_supplier'];
            $this->associateSupplier($id_product, $id_supplier);
        }

        if ($productUpdated || count($suppliers) > 0) {
            $updatedProduct = $this->readById($id_product); 
            $message = ($productAffectedRows > 0) ? 'Producto actualizado exitosamente.' : 'Proveedores actualizados exitosamente, sin cambios en el producto.';
            return [
                'status' => 'Success',
                'message' => $message,
                'product' => $updatedProduct['product']
            ];
        } else {
            return [
                'status' => 'Success',
                'message' => 'No se realizaron cambios ni en el producto ni en los proveedores.'
            ];
        }
    }

    private function deleteProductSuppliers($id_product)
    {
        if (!is_numeric($id_product)) {
            return [
                'status' => 'Not Valid',
                'message' => "El ID del producto debe ser un numero."
            ];
        }
        $query = "DELETE FROM products_suppliers WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }
        $stmt->bind_param("i", $id_product);
        $stmt->execute();
        $stmt->close();
    }

    public function delete($id_product)
    {
        if (!is_numeric($id_product)) {
            return [
                'status' => 'Not Valid',
                'message' => "El ID del producto debe ser un numero."
            ];
        }
        $this->deleteProductSuppliers($id_product);

        $query = "DELETE FROM products WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }
        $stmt->bind_param("i", $id_product);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return [
                    'status' => 'Success',
                    'message' => 'Producto eliminado exitosamente'
                ];
            }
        }
        return [
            'status' => 'Error',
            'message' => 'No se pudo eliminar el producto'
        ];
    }
}
