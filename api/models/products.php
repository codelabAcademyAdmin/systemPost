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

        if (empty($name) || empty($stock) || empty($category) || empty($product_price)) {
            return [
                'status' => 'Not Valid',
                'message' => 'Ay campos vacios'
            ];
        }

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

        if ($stock < 0) {
            return [
                'status' => 'Not Valid',
                'message' => 'Cantidad ingresada en el Stock no valida'
            ];
        }

        if ($product_price < 0) {
            return [
                'status' => 'Not Valid',
                'message' => 'Precio del producto no valido'
            ];
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
            return $stmt->affected_rows;
        } else {
            return [
                'status' => 'Error',
                'message' => 'Error al asociar el proveedor con el producto: ' . $stmt->error
            ];
        }
    }

    private function removeSupplierFromProduct($id_product, $id_supplier)
    {
        $query = "DELETE FROM products_suppliers WHERE id_product = ? AND id_supplier = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_product, $id_supplier);
        return $stmt->execute();
    }

    private function getSuppliersByProductId($id_product)
    {
        $query = "SELECT id_supplier FROM products_suppliers WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_product);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    private function updateProductSuppliers($id_product, $newSuppliers)
    {
        // Obtener los proveedores actuales asociados al producto
        $currentSuppliers = $this->getSuppliersByProductId($id_product);

        // Crear arrays de IDs de proveedores
        $newSupplierIds = array_column($newSuppliers, 'id_supplier');
        $currentSupplierIds = array_column($currentSuppliers, 'id_supplier');

        // Determinar proveedores a eliminar y a agregar
        $suppliersToDelete = array_diff($currentSupplierIds, $newSupplierIds);
        $suppliersToAdd = array_diff($newSupplierIds, $currentSupplierIds);

        // Eliminar proveedores que ya no están en la lista
        foreach ($suppliersToDelete as $supplierId) {
            $this->removeSupplierFromProduct($id_product, $supplierId);
        }

        // Agregar nuevos proveedores que no estaban antes
        foreach ($suppliersToAdd as $supplierId) {
            $this->associateSupplier($id_product, $supplierId);
        }

        return count($suppliersToDelete) + count($suppliersToAdd);
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

    public function getFiltredProducts($status)
    {

        if($status !== 'activo' && $status !== 'inactivo'){
            return [
                'status' => 'Not Valid',
                'message' => 'El estado ingresado no es valido'
            ];
        }
        $query = "SELECT * FROM products WHERE status = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'status' => 'Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return [
                'status' => 'Not Found',
                'message' => "No se encontró ningún producto con status = ".$status
            ];
        }

        return [
            'status' => 'Success',
            'products' => $result->fetch_all(MYSQLI_ASSOC)
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

        if ($stock < 0) {
            return [
                'status' => 'Not Valid',
                'message' => 'Cantidad ingresada en el Stock no valida'
            ];
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

        if (!$stmt->execute()) {
            return [
                'status' => 'Internal Error',
                'message' => 'Error al ejecutar la actualización: ' . $stmt->error
            ];
        }

        $productAffectedRows = $stmt->affected_rows;
        $suppliersAffectedRows = $this->updateProductSuppliers($id_product, $suppliers);

        // $this->deleteProductSuppliers($id_product);
        // $isAffect = 0;

        // foreach ($suppliers as $supplier) {
        //     $id_supplier = $supplier['id_supplier'];
        //     $isAffect = $this->associateSupplier($id_product, $id_supplier);
        // }

        if ($productAffectedRows > 0 || $suppliersAffectedRows > 0) {
            $updatedProduct = $this->readById($id_product);
            return [
                'status' => 'Success',
                'message' => 'Datos del producto actualizado exitosamente.',
                'product' => $updatedProduct['product']
            ];
        } else {
            return [
                'status' => 'Success',
                'message' => 'No se evidenciaron cambios en los datos del producto.'
            ];
        }
    }

    public function activate($id_product)
    {
        if (!is_numeric($id_product)) {
            return [
                'status' => 'Not Valid',
                'message' => "El ID del producto debe ser un número."
            ];
        }

        $existingProduct = $this->readById($id_product);
        if ($existingProduct['status'] !== 'Success') {
            return $existingProduct;
        }

        if ($existingProduct['product']['status'] == 'activo') {
            return [
                'status' => 'Conflict',
                'message' => 'El producto ya está activo.'
            ];
        }

        $query = "UPDATE products SET status = 'activo' WHERE id_product = ?";
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
                    'message' => 'Producto activado exitosamente.'
                ];
            } else {
                return [
                    'status' => 'Not Found',
                    'message' => 'No se encontró el producto para activar.'
                ];
            }
        } else {
            return [
                'status' => 'Error',
                'message' => 'Error al activar el producto: ' . $stmt->error
            ];
        }
    }


    public function deactivate($id_product)
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

        if ($existingProduct['product']['status'] == 'inactivo') {
            return [
                'status' => 'Conflict',
                'message' => 'El producto ya está inactivo.'
            ];
        }

        $query = "UPDATE products SET status = 'inactivo' WHERE id_product = ?";
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
                    'message' => 'Producto inactivado exitosamente.'
                ];
            }
        } else {
            return [
                'status' => 'Error',
                'message' => 'Error al inactivar el producto.' . $stmt->error
            ];
        }
    }

    
}
