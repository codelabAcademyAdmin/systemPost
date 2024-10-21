<?php

class usersModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }
    
    //funcion para verificar de que el gmail no este siendo usado 
    public function validationIfExist($email, $phone)
    {
        if (!preg_match('/^\d{10}$/', $phone)) {
            return [
                'status' => 'Not Valid',
                'message' => 'El número de teléfono debe cumplir con solo 10 dígitos y debe ser solo números.'
            ];
        }
        $queryEmail = "SELECT * FROM users WHERE email = ? AND phone = ?";
        $stmtEmail = $this->conn->prepare($queryEmail);
        $stmtEmail->bind_param("ss", $email, $phone);
        $stmtEmail->execute();
        $resultEmail = $stmtEmail->get_result();

        if ($resultEmail) {
            return [
                'status' => 'Conflicts',
                'message' => 'El correo o numero de telefono ya esta en uso'
            ];
        }
        return [
            'status' => 'Success'
        ];
    }

    //funcion para una password segura
    public function validationPassword($password)
    {
        $minLength = 8;
        $uppercase = preg_match('/[A-Z]/', $password);
        $lowercase = preg_match('/[a-z]/', $password);
        $number = preg_match('/[0-9]/', $password);
        $specialChar = preg_match('/[!@#$%^&*().]/', $password);

        if (strlen($password) < $minLength) {
            return [
                'status' => 'Not Valid',
                'message' => 'La contraseña debe tener al menos ' . $minLength . ' caracteres.'
            ];
        }

        if (!$uppercase || !$lowercase || !$number || !$specialChar) {
            return [
                'status' => 'Not Valid',
                'message' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.'
            ];
        }
        return [
            'status' => 'Success'
        ];
    }
    

    public function create($fullname, $email, $pass, $phone, $rol)
    {

        $response = $this->validationIfExist($email, $phone);
        if ($response['status'] !== 'Success') {
            return $response;
        }
        $response = $this->validationPassword($pass);
        if ($response['status'] !== 'Success') {
            return $response;
        }

        $query = "INSERT INTO users ( fullname, email, pass, phone, rol) VALUES ( ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'status' => 'Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }
        $stmt->bind_param("sssis", $fullname, $email, $pass, $phone, $rol);

        if ($stmt->execute()) {
            $id_user = $this->conn->insert_id;

            $validation = $this->readById($id_user);
            if ($validation) {
                $stmt->close();
                return [
                    'status' => 'Success',
                    'message' => 'Usuario creado exitosamente',
                    'user' => $validation['user']
                ];
            } else {
                return [
                    'status' => 'Error',
                    'message' => 'No se pudo validar la creación del usuario'
                ];
            }
        } else {
            return [
                'status' => 'Error',
                'message' => 'Error al crear el usuario: ' . $stmt->error
            ];
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
        if (!is_numeric($id_user)) {
            return [
                'status' => 'Not Valid',
                'message' => 'El id debe ser solo números.'
            ];
        }

        $query = "SELECT id_user, fullname, email, pass, phone, rol, create_at, update_at FROM users WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [
                'status' => 'Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }

        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            return [
                'status' => 'Not Found',
                'message' => 'No se encontró ningún usuario con el id ' . $id_user
            ];
        }

        return [
            'status' => 'Success',
            'user' => $user
        ];
    }

    public function update($id_user, $fullname, $email, $pass, $phone, $rol)
    {
        if (!is_numeric($id_user)) {
            return [
                'status' => 'Not Valid',
                'message' => 'El id debe ser solo números.'
            ];
        }

        $validation = $this->readById($id_user);
        if (empty($validation['user'])) {
            return [
                'status' => 'Not Found',
                'message' => 'No se encontró el usuario con ese id.'
            ];
        }

        $response = $this->validationIfExist($email, $phone);
        if ($response['status'] !== 'Success') {
            return $response;
        }
        
        $response = $this->validationPassword($pass);
        if ($response['status'] !== 'Success') {
            return $response;
        }

        // $existingUser = $validation['user'];

        // $updateFields = [];
        // $params = [];

        // if (isset($data['fullname']) && $data['fullname'] !== $existingUser['fullname']) {
        //     $response = $this->validationName($data['fullname']);
        //     if ($response['status'] !== 'Success') {
        //         return $response;
        //     }
        //     $updateFields[] = "fullname = ?";
        //     $params[] = $data['fullname'];
        // }

        // if (isset($data['email']) && $data['email'] !== $existingUser['email']) {
        //     $response = $this->validationEmail($data['email']);
        //     if ($response['status'] !== 'Success') {
        //         return $response;
        //     }
        //     $updateFields[] = "email = ?";
        //     $params[] = $data['email'];
        // }

        // if (isset($data['pass']) && !empty($data['pass'])) {
        //     $response = $this->validationPassword($data['pass']);
        //     if ($response['status'] !== 'Success') {
        //         return $response;
        //     }

        //     $updateFields[] = "pass = ?";
        //     $params[] = $data['pass'];
        // }

        // if (isset($data['phone']) && $data['phone'] !== $existingUser['phone']) {
        //     $response = $this->validationPhone($data['phone']);
        //     if ($response['status'] !== 'Success') {
        //         return $response;
        //     }
        //     $updateFields[] = "phone = ?";
        //     $params[] = $data['phone'];
        // }

        // if (empty($updateFields)) {
        //     return [
        //         'status' => 'Success',
        //         'message' => 'No se realizaron cambios en la actualización.'
        //     ];
        // }

        // $params[] = $id_user;

        $query = "UPDATE users SET fullname = ?, email = ?, pass ?, phone ?, rol ? , WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            return [
                'status' => 'Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }

        $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

        if ($stmt->execute()) {
            return [
                'status' => 'Success',
                'message' => 'Usuario actualizado exitosamente.',
                'user' => $this->readById($id_user)['user']
            ];
        } else {
            return [
                'status' => 'Error',
                'message' => 'Error al actualizar el usuario: ' . $stmt->error
            ];
        }
    }


    public function delete($id_user)
    {

        if (!is_numeric($id_user)) {
            return [
                'status' => 'Not Valid',
                'message' => 'El id debe ser solo numeros.'
            ];
        }
        $user = $this->readById($id_user);
        if ($user['status'] == 'Not Found') {
            return [
                'status' => 'Not Found',
                'message' => 'No se puede eliminar el usuario porque el ID ' . $id_user . ' no existe.'
            ];
        }

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
            $stmt->close();

            if (!$user) {
                return [
                    'status' => 'unauthorized',
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
            $stmt->close();
            return [
                'status' => 'error',
                'message' => 'Error en la consulta: ' . $stmt->error,
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
