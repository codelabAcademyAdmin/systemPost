<?php

$AppRoutes->AddRoutes('POST', 'login', function() {
    require_once 'models/users.php';
    $users = new usersModel();
    $response;
    $data = json_decode(file_get_contents('php://input'), true);
    $response = $users->login($data['email'], $data['pass']);
    echo json_encode($response);
});

?>