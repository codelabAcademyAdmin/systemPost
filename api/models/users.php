<?php

class usersModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function create($id_user, $fullname, $email, $pass, $phone, $rol)
    {
        $query = "INSERT INTO users (id_user, fullname, email, pass, phone, rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssis", $id_user, $fullname, $email, $pass, $phone, $rol);

        if ($stmt->execute()) {
            $newId = $this->conn->insert_id;
            return json_encode([
                'status' => 'Success',
                'message' => 'Usuario creado exitosamente',
                'user' => [
                    'id' => $newId,
                    'username' => $fullname,
                    'email' => $email,
                    'phone' => $phone,
                    'rol' => $rol
                ]
            ]);
        } else {
            return json_encode([
                'status' => 'Error' . ' ',
                'message' => 'No se pudo crear el usuario: ' . $stmt->error
            ]);
        }
    }

    public function readAll()
    {
        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readById($id_user)
    {
        $query = "SELECT * FROM users WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id_user, $fullname, $email, $pass, $phone, $rol)
    {
        $query = "UPDATE users SET fullname = ?, email = ?, pass = ?, phone = ?, rol = ? WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssisi", $fullname, $email, $pass, $phone, $rol,  $id_user);

        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }

    public function delete($id_user)
    {
        $query = "DELETE FROM users WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);

        if ($stmt->execute()) {
            return $stmt->affected_rows > 0;
        }
        return false;
    }

    public function login($username, $password)
    {
        $query = "SELECT * FROM users WHERE email = ? AND pass = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {

            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (!$user) {
                return json_encode([
                    'status' => 'Error',
                    'message' => 'Usuario y/o Contraseña incorrecto',
                ]);
            } else {
                return json_encode([
                    'status' => 'success',
                    'message' => 'Sesión iniciada exitosamente',
                    'user' => [
                        'id' => $user['id_user'],
                        'fullname' => $user['fullname'],
                        'email' => $user['email'],
                        'phone' => $user['phone'],
                        'rol' => $user['rol']
                    ]
                ]);
            }
        } else {
            return json_encode([
                'status' => 'Error',
                'message' => 'Error en la consulta' . $stmt->error,
            ]);
        }
    }
}
