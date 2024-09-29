<?php

class TestModel {
   private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function create($name) {
        $query = "INSERT INTO test (name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $name);
        
        if ($stmt->execute()) {
            $newId = $this->conn->insert_id;
            return $this->readById($newId);
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM test";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readById($id) {
        $query = "SELECT * FROM test WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $name) {
        $query = "UPDATE test SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $name, $id);
        
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM test WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }
}

?>