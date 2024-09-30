<?php

class usersModel {
   private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function create($id_user, $fullname, $email, $pass, $phone, $rol) {
        $query = "INSERT INTO users (id_user, fullname, email, pass, phone, rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssis", $id_user, $fullname, $email, $pass, $phone, $rol);
        
        if ($stmt->execute()) {
            $newId = $this->conn->insert_id;
            return $newId;
        }
        return false;
    }

    public function readAll() {
        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readById($id_user) {
        $query = "SELECT * FROM users WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id_user, $fullname, $email, $pass, $phone, $rol) {
        $query = "UPDATE users SET fullname = ?, email = ?, pass = ?, phone = ?, rol = ? WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssisi", $id_user, $fullname, $email, $pass, $phone, $rol);
        
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }

    public function delete($id_user) {
        $query = "DELETE FROM users WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        
        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }
}

?>