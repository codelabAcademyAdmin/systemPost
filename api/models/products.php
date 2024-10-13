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

        $query = "INSERT INTO products (name, description, stock, category, product_price) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssisd", $name, $description, $stock, $category, $product_price);

        if ($stmt->execute()) {
            $id_product = $this->conn->insert_id; //get id newly insert

            foreach ($suppliers as $id_supplier) {
                $this->associateSupplier($id_product, $id_supplier);
            }

            $validation = $this->readById($id_product);
            if ($validation) {
                $stmt->close();
                return [
                    'status' => 'Success',
                    'message' => 'Producto creado exitosamente',
                    'product' => $validation
                ];
            } else {
                return [
                    'status' => 'Error',
                    'message' => 'No se pudo validar la creación del producto: ' . $stmt->error
                ];
            }
        }
    }

    private function associateSupplier($id_product, $id_supplier)
    {
        $suppliers = new suppliersModel();
        $data = $suppliers->readById($id_supplier);

        if (!$data) {
            return [
                'status' => 'Not Found',
                'message' => 'El Proveedor con Id: ' . $id_supplier . ' no existe existe'
            ];
        }

        $query = "INSERT INTO products_suppliers (id_product, id_supplier) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_product, $id_supplier);
        if ($stmt->execute()) {
            $stmt->close();
            return [
                'status' => 'Success',
                'message' => 'Proveedor asociado al producto exitosamente'
            ];
        } else {
            $stmt->close();
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
        $query = "UPDATE products SET name = ?, stock = ?, description = ?, category = ?, product_price = ? WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sissd", $name, $description, $stock, $category, $product_price, $id_product);

        if ($stmt->execute()) {
            $this->deleteProductSuppliers($id_product);

            foreach ($suppliers as $id_supplier) {
                $this->associateSupplier($id_product, $id_supplier);
            }

            $validation = $this->readById($id_product);
            if ($stmt->affected_rows > 0) {
                return [
                    'status' => 'Success',
                    'message' => 'Producto actualizado exitosamente',
                    'product' => $validation
                ];
            }
        }
        return [
            'status' => 'Error',
            'message' => 'No se pudo actualizar el producto'
        ];
    }

    private function deleteProductSuppliers($id_product)
    {
        $query = "DELETE FROM products_suppliers WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
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
