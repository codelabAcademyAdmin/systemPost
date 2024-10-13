<?php

$AppRoutes->AddRoutes('POST', 'login', function() {
    require_once 'models/users.php';
    $users = new usersModel();
    
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['email']) || !isset($data['pass'])) {
        http_response_code(400); // Bad Request
        echo json_encode([
            'status' => 'error',
            'message' => 'Email o contraseña faltantes'
        ]);
        return;
    }

    $response = $users->login($data['email'], $data['pass']);

    if ($response['status'] === 'success') {
        http_response_code(200); // Ok
    } else if ($response['status'] === 'unauthorized') {
        http_response_code(401); // Unauthorized
    } else {
        http_response_code(500); // Internal Server Error
    }

    echo json_encode($response);
});
?>
