<?php

require_once 'inventories.php';
require_once 'users.php';

class SalesModel
{
    private $conn;
    private $inventoriesModel;
    private $usersModel;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
        $this->usersModel = new usersModel();
        $this->inventoriesModel = new inventoriesModel();
    }

    public function create($id_user, $sales)
    {
        $total = 0;

        // Validar si el usuario existe
        $userValidation = $this->usersModel->readById($id_user);
        if ($userValidation["status"] === "Not Found") {
            http_response_code(404);
            return $userValidation;
        }
        if ($userValidation["status"] === "Not Valid") {
            http_response_code(400);
            return $userValidation;
        }
        if ($userValidation["status"] === "Error") {
            http_response_code(500);
            return $userValidation;
        }

        // Validar el stock de cada producto y calcular el total
        $products = [];
        foreach ($sales as $sale) {
            $id_product = $sale["id_product"];
            $quantity = $sale["quantity"];

            if($quantity < 1){
                http_response_code(400);
                return ["status" => "error", "message" => "La cantidad debe ser mayor a 0."];
            }

            $stockValidation = $this->validateStock($id_product, $quantity);
            if ($stockValidation["status"] !== "ok") {
                return $stockValidation;
            }

            if (!isset($products[$id_product])) {
                $product = $this->inventoriesModel->readProductsById($id_product);
                if ($product["status"] !== "ok") {
                    return $product;
                }
                $products[$id_product] = $product["data"];
            }

            $product_price = $products[$id_product]["product_price"];
            $total += $product_price * $quantity;
        }

        if (empty($id_user) || empty($id_product) || empty($quantity)) {
            http_response_code(400);
            return ["status" => "error", "message" => "Campo es requerido."];
        }

        // Iniciar transacción
        $this->conn->begin_transaction();

        try {
            // Insertar la venta
            $query = "INSERT INTO sales (id_user, total) VALUES (?, ?);";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->conn->error);
            }
            $stmt->bind_param("ii", $id_user, $total);
            $stmt->execute();
            if ($stmt->affected_rows === 0) {
                throw new Exception("Error al crear la venta: " . $stmt->error);
            }
            $id_sale = $stmt->insert_id;

            // Insertar los detalles de la venta y actualizar el stock
            foreach ($sales as $sale) {
                $id_product = $sale["id_product"];
                $quantity = $sale["quantity"];
                $product_price = $products[$id_product]["product_price"];

                $query = "INSERT INTO sale_details (id_sale, id_product, quantity, product_price) VALUES (?, ?, ?, ?);";
                $stmt = $this->conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Error al preparar la consulta: " . $this->conn->error);
                }
                $stmt->bind_param("iiii", $id_sale, $id_product, $quantity, $product_price);
                $stmt->execute();
                if ($stmt->affected_rows === 0) {
                    throw new Exception("Error al crear el detalle de la venta: " . $stmt->error);
                }

                $updateStock = $this->updateProductStock($id_product, $quantity);
                if ($updateStock["status"] !== "ok") {
                    throw new Exception($updateStock["message"]);
                }
            }

            // Obtener la venta y sus detalles
            $sale = $this->inventoriesModel->readSaleById($id_sale);
            $details = $this->readSalesDetailsById($id_sale);

            if ($sale["status"] !== "ok" || $details["status"] !== "ok") {
                throw new Exception("Error al obtener la venta o sus detalles.");
            }

            // Confirmar transacción
            $this->conn->commit();
            http_response_code(201);
            return [
                "status" => "ok",
                "message" => "Venta creada correctamente.",
                "sale" => $sale["data"],
                "details" => $details["data"],
            ];
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $this->conn->rollback();
            http_response_code(500);
            return [
                "status" => "error",
                "message" => $e->getMessage(),
            ];
        }
    }

    public function readSalesDetailsById($id)
    {
        $query = "SELECT * FROM sale_details WHERE id_sale = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            http_response_code(500);
            return [
                "status" => "error",
                "message" => "Error al preparar la consulta: " . $this->conn->error,
            ];
        }
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            http_response_code(404);
            return [
                "status" => "error",
                "message" => "No se encontró ningún detalle de venta con el ID proporcionado.",
            ];
        } else {
            http_response_code(200);
            return [
                "status" => "ok",
                "data" => $result->fetch_all(MYSQLI_ASSOC),
            ];
        }
    }

    public function validateStock($id_product, $quantity)
    {
        $product = $this->inventoriesModel->readProductsById($id_product);
        if ($product["status"] !== "ok") {
            return $product;
        }

        $stock = $product["data"]["stock"];
        if ($stock < $quantity) {
            http_response_code(409);
            return [
                "status" => "error",
                "message" => "No hay stock disponible para el producto con ID $id_product.",
            ];
        }

        return ["status" => "ok", "message" => "Stock suficiente."];
    }

    public function updateProductStock($id_product, $quantity)
    {
        $stockValidation = $this->validateStock($id_product, $quantity);
        if ($stockValidation["status"] !== "ok") {
            return $stockValidation;
        }

        $product = $this->inventoriesModel->readProductsById($id_product);
        if ($product["status"] !== "ok") {
            return $product;
        }

        $new_stock = $product["data"]["stock"] - $quantity;

        $query = "UPDATE products SET stock = ? WHERE id_product = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            http_response_code(500);
            return [
                "status" => "error",
                "message" => "Error al preparar la consulta: " . $this->conn->error,
            ];
        }
        $stmt->bind_param("ii", $new_stock, $id_product);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            http_response_code(500);
            return [
                "status" => "error",
                "message" => "Error al actualizar el stock del producto: " . $stmt->error,
            ];
        }

        return [
            "status" => "ok",
            "message" => "Stock actualizado correctamente.",
        ];
    }
}