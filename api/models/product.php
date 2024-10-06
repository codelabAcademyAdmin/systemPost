<?php
class productsModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function create($id_product, $name, $stock, $unit_price, $description, $id_category, $suppliers)
    {

        $validation = $this->readById($id_product);
        if ($validation) {
            return [
                'status' => 'Error',
                'message' => 'El producto ya existe'
            ];
        }

        //PENDIENTE:
        // Validacion si existe la categoria
        // Cuando se realize el endpoint categoria


        $query = "INSERT INTO products (id_product, name, stock, unit_price, description, id_category) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isidit", $id_product, $name, $stock, $unit_price, $description, $id_category);

        if ($stmt->execute()) {

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
                'status' => 'Error',
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
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readById($id_product)
    {
        $query = "SELECT p.id_product, p.name, p.stock, p.unit_price, p.sale_price, p.date_sale, p.id_inventory, p.id_category, 
                       GROUP_CONCAT(ps.id_supplier) AS suppliers 
                FROM products p
                LEFT JOIN products_suppliers ps ON p.id_product = ps.id_product
                WHERE p.id_product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_product);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }

    public function update($id_product, $name, $stock, $unit_price, $sale_price, $date_sale, $id_inventory, $id_category, $suppliers)
    {
        $query = "UPDATE products SET name = ?, stock = ?, unit_price = ?, sale_price = ?, date_sale = ?, id_inventory = ?, id_category = ? 
                WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("siddsiii", $name, $stock, $unit_price, $sale_price, $date_sale, $id_inventory, $id_category, $id_product);

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
