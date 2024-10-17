<?php
   $AppRoutes->AddRoutes('GET', 'users', function() {
        require_once 'models/users.php';
        $users = new usersModel();
  
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
          
            $response = $users->readById($id);
            
            if ($response['status'] === 'Success') {
                http_response_code(200); // OK
            } else if ($response['status'] === 'Not Valid') {
                http_response_code(400); // Bad Request
            } else if ($response['status'] === 'Not Found') {
                http_response_code(404); // Not Found
            } else if ($response['status'] === 'Error') {
                http_response_code(500); // Internal Server Error
            }
        } else {
            $response = $users->readAll();
            http_response_code(200); // OK
        }
  
        echo json_encode($response);
    });
  

    $AppRoutes->AddRoutes('POST', 'users', function() {
        require_once 'models/users.php';
        $users = new usersModel();
        $response;
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'Error', 'message' => 'No se proporcionaron datos.']);
            return;
        }

        $response = $users->create($data['fullname'], $data['email'], $data['pass'], $data['phone'], $data['rol']);

        switch ($response['status']) {
            case 'Success':
                http_response_code(201); // Created
                break;
            case 'Error':
                if ($response['message'] === 'El email ya existe') {
                    http_response_code(400); // Bad Request
                } else {
                    http_response_code(500); // Internal Server Error
                }
                break;
            }

        echo json_encode($response);
    });

    $AppRoutes->AddRoutes('PUT', 'users', function() {
        require_once 'models/users.php';
        $users = new usersModel();

        $id_user = $_GET['id'];
        $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['fullname'], $data['email'], $data['pass'], $data['phone'], $data['rol'])) {
        http_response_code(400); 
        echo json_encode(['status' => 'Error', 'message' => 'Faltan datos requeridos.']);
        exit();
        }

    $response = $users->update($id_user, $data['fullname'], $data['email'], $data['pass'], $data['phone'], $data['rol']);

    switch ($response['status']) {
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
        }

    echo json_encode($response);
    });

    $AppRoutes->AddRoutes('DELETE', 'users', function() {
        require_once 'models/users.php';
        $users = new usersModel();
        $id = $_GET['id'];

        $response = $users->delete($id);

        switch ($response['status']) {
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
            case 'Conflict':
            http_response_code(409); //Conflict
            break;
        }

        echo json_encode($response);
    });
?>