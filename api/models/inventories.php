<?php

class inventoriesModel {
   private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function create($amount,$sale_price) {
        $query = "INSERT INTO inventories (amount,sale_price) VALUES (?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $amount,$sale_price);
        
        if ($stmt->execute()) {
            $newId = $this->conn->insert_id;
            return $this->readById($newId);
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM inventories;";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT * FROM inventories WHERE id_inventory = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

 public function update($id, $amount,$sale_price) {
        $query = "UPDATE inventories SET amount = ?, sale_price = ? WHERE id_inventory = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iii", $amount, $sale_price, $id);
        
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }

    
    public function delete($id) {
        $query = "DELETE FROM inventories WHERE id_inventory = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }
}
    ?>