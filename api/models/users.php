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
        $validation = $this->readById($id_user);
        if($validation){
            return [
                'status' => 'Error',
                'message' => 'El usuario ya existe'
            ];
        }

        $emailValidation = $this->readByEmial($email);
        if($emailValidation){
            return [
                'status' => 'Error',
                'message' => 'El email ya existe'
            ];
        }
        $query = "INSERT INTO users (id_user, fullname, email, pass, phone, rol) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("isssis", $id_user, $fullname, $email, $pass, $phone, $rol);

        if ($stmt->execute()) {
            // Llamar a readById para validar la creación del usuario
            $validation = $this->readById($id_user);

            if ($validation) {
                return [
                    'status' => 'Success',
                    'message' => 'Usuario creado exitosamente',
                    'user' => $validation
                ];
            } else {
                return [
                    'status' => 'Error',
                    'message' => 'No se pudo validar la creación del usuario'.$stmt->error
                ];
            }
        
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
        $query = "SELECT id_user, fullname, email phone, rol, create_date, update_date FROM users WHERE id_user = ?";
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
            $validation = $this->readById($id_user);
            if ($stmt->affected_rows > 0) {
                return [
                    'status' => 'Success',
                    'message' => 'Usuario actualizado exitosamente',
                    'user' => $validation
                ];
            }
        }
        return [
            'status' => 'Error',
            'message' => 'No se pudo actualizar el usuario'
        ];
    }

    public function delete($id_user)
    {
        $query = "DELETE FROM users WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_user);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return [
                    'status' => 'Success',
                    'message' => 'Usuario eliminado exitosamente'
                ];
            }
        }
        return [
            'status' => 'Error',
            'message' => 'No se pudo eliminar el usuario'
        ];
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
                return [
                    'status' => 'Error',
                    'message' => 'Usuario y/o Contraseña incorrectos',
                ];
            } else {
                return [
                    'status' => 'success',
                    'message' => 'Sesión iniciada exitosamente',
                    'user' => [
                        'id' => $user['id_user'],
                        'fullname' => $user['fullname'],
                        'email' => $user['email'],
                        'phone' => $user['phone'],
                        'rol' => $user['rol']
                    ]
                ];
            }
        } else {
            return [
                'status' => 'Error',
                'message' => 'Error en la consulta' . $stmt->error,
            ];
        }
    }

    public function readByEmial($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

