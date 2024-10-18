<?php
    function setHttpResponseUsers($status) {
        switch ($status) {
            case 'Not Valid':
                http_response_code(400); // Bad Request
                break;
            case 'Success':
                http_response_code(200); // Ok
                break;
            case 'Not Found':
                http_response_code(404); // Not Found
                break;
            case 'Error':
                http_response_code(500); // Internal Server Error
                break;
            case 'Conflicts':
                http_response_code(409); // Conflict
                break;
            default:
                http_response_code(500); // Por defecto a Internal Server Error
                break;
        }
    }

    $AppRoutes->AddRoutes('GET', 'users', function() {
        require_once 'models/users.php';
        $users = new usersModel();
        $response;

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $response = $users->readById($id);
            
            setHttpResponseUsers($response['status']);
        } else {
            $response = $users->readAll();
            setHttpResponseUsers('Success'); 
        }

        echo json_encode($response);
    });

    $AppRoutes->AddRoutes('POST', 'users', function() {
        require_once 'models/users.php';
        $users = new usersModel();
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || 
            empty($data['fullname']) || empty($data['email']) || empty($data['pass']) || empty($data['phone']) || empty($data['rol'])) {
            
            $response = [
                'status' => 'Not Valid',
                'message' => 'Los datos no son válidos, recuerda que todos los campos son obligatorios.'
            ];
            setHttpResponseUsers('Not Valid'); // Cambiar a Not Valid
            echo json_encode($response);
            return; 
        }

        $response = $users->create($data['fullname'], $data['email'], $data['pass'], $data['phone'], $data['rol']);

        setHttpResponseUsers($response['status']);
        echo json_encode($response);
    });

    $AppRoutes->AddRoutes('PUT', 'users', function() {
        require_once 'models/users.php';
        $users = new usersModel();
        $id_user = $_GET['id'];
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data)) {
            setHttpResponseUsers('Not Valid');
            echo json_encode(['status' => 'Error', 'message' => 'Faltan datos requeridos.']);
            return;
        }

        $existingUsersResponse = $users->readById($id_user);
        if ($existingUsersResponse['status'] !== 'Success') {
            setHttpResponseUsers('Not Found');
            echo json_encode([
                'status' => 'Error',
                'message' => $existingUsersResponse['message']
            ]);
            return;
        }

        $existingUser = $existingUsersResponse['user'];

        $updateData = [];
        // verificamos si hay cambios para cada campo
        if (isset($data['fullname']) && $data['fullname'] !== $existingUser['fullname']) {
            $updateData['fullname'] = $data['fullname'];
        }
        if (isset($data['email']) && $data['email'] !== $existingUser['email']) {
            $updateData['email'] = $data['email'];
        }
        if (isset($data['pass']) && !empty($data['pass']) && $data['pass'] !== $existingUser['pass']) {
            $updateData['pass'] = $data['pass'];
        } 
        if (isset($data['phone']) && $data['phone'] !== $existingUser['phone']) {
            $updateData['phone'] = $data['phone'];
        }
        if (isset($data['rol']) && $data['rol'] !== $existingUser['rol']) {
            $updateData['rol'] = $data['rol'];
        }

        $response = $users->update($id_user, $updateData);

        setHttpResponseUsers($response['status']);
        echo json_encode($response);
    });

    $AppRoutes->AddRoutes('DELETE', 'users', function() {
        require_once 'models/users.php';
        $users = new usersModel();
        $id = $_GET['id'];

        if (empty($id)) {
            setHttpResponseUsers('Not Found');
            echo json_encode(['status' => 'Error', 'message' => 'ID de usuario no proporcionado.']);
            return;
        }

        $response = $users->delete($id);
        if ($response['status'] !== 'Success') {
            setHttpResponseUsers('Not Found'); 
            echo json_encode($response);
            return;
        }

        setHttpResponseUsers($response['status']);
        echo json_encode($response);
    });

?>
