<?php

class usersModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function create($fullname, $email, $pass, $phone, $rol) {
        $emailValidation = $this->readByEmail($email);
        if ($emailValidation) {
            return [
                'status' => 'Error',
                'message' => 'Este email ya existe.'
            ];
        }
        $query = "INSERT INTO users ( fullname, email, pass, phone, rol) VALUES ( ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if(!$stmt) {
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
                    'user' => $validation['users']
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
    public function readByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        return $user;
    }


    public function readAll()
    {
        $query = "SELECT * FROM users";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function readById($id_user) {
        if(!is_numeric($id_user)){
            return[
                'status' => 'Not Valid',
                'message' => 'El id debe ser solo numeros.'
            ];
        }

        $query = "SELECT id_user, fullname, email, phone, rol, create_at, update_at FROM users WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt){
            return [
                'status' => 'Error',
                'message' => 'Error al preparar la consulta: ' .$this->conn->error
            ];
        }

        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if(!$user) {
            return[
                'status' => 'Not Found',
                'message' => 'No se encontro ningun usuario con el id ' . $id_user
            ];
        }
        return [
            'status' => 'Success',
            'users' => $user
        ];
    }

    public function update($id_user, $fullname, $email, $pass, $phone, $rol)
    {
        if (!is_numeric($id_user)) {
            return [
                'status' => 'Not Valid',
                'message' => 'El id debe ser solo numeros.'
            ];
        }
        
        $validation = $this->readById($id_user);
        if (empty($validation['users'])) {
            return [
                'status' => 'Not Found',
                'message' => 'No se encontró el usuario con ese id.'
            ];
        }        
    
        //Esta consulta es para validar la actualizacion del usuario, ya que no se puede usar un email que ya este en uso, tengo que verificar si el email existe o no para poder hacer la actualizacion del usuario (si algo preguntas al DM antes de rechazar la pr manito 🤣)
        $queryEmail = "SELECT * FROM users WHERE email = ? AND id_user != ?";
        $stmtEmail = $this->conn->prepare($queryEmail);
        $stmtEmail->bind_param("si", $email, $id_user);
        $stmtEmail->execute();
        $resultEmail = $stmtEmail->get_result();

        if ($resultEmail->num_rows > 0) {
            return [
                'status' => 'Not Valid',
                'message' => 'El correo no es valido, ya está siendo usado por otro usuario.'
            ];
        }

        $query = "UPDATE users SET fullname = ?, email = ?, pass = ?, phone = ?, rol = ? WHERE id_user = ?";
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            return [
                'status' => 'Error',
                'message' => 'Error al preparar la consulta: ' . $this->conn->error
            ];
        }
    
        $stmt->bind_param("sssisi", $fullname, $email, $pass, $phone, $rol, $id_user);
    
        if ($stmt->execute()) {
            if ($stmt->error) {
                return [
                    'status' => 'Error',
                    'message' => 'Error al ejecutar la consulta: ' . $stmt->error
                ];
            }
    
            if ($stmt->affected_rows > 0) {
                $update = $this->readById($id_user);
                return [
                    'status' => 'Success',
                    'message' => 'Usuario actualizado exitosamente.'
                ];
            } else {
                return [
                    'status' => 'Success',
                    'message' => 'No se actualizaron datos del usuario.'
                ];
            }
        } else {
            return [
                'status' => 'Error',
                'message' => 'Error al actualizar el usuario: ' . $stmt->error
            ];
        }
    }
    

    public function delete($id_user) {

        if(!is_numeric($id_user)){
            return[
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
