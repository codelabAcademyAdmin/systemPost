<?php

require_once '../models/users.php';

$users = new usersModel();

$request = $_SERVER['REQUEST_METHOD'];
$response;

if($request == 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);

    $response = $users->login($data['email'], $data['pass']);
    echo json_encode($response);
}

?>