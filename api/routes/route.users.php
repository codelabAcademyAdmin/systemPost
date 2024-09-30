<?php
   $AppRoutes->AddRoutes('GET', 'users', function() {
      require_once 'models/users.php';
      $users = new usersModel();
      $response;
      if(isset($_GET['id'])){
         $id = $_GET['id'];
            $response = $users->readById($id);
      }else{
            $response = $users->readAll();
      }
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('POST', 'users', function() {
      require_once 'models/users.php';
      $users = new usersModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);
      $response = $users->create($data['id_user'], $data['fullname'], $data['email'], $data['pass'], $data['phone'], $data['rol']);
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('PUT', 'users', function() {
      require_once 'models/users.php';
      $users = new usersModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);
      $id = $_GET['id'];
      $response = $users->update($id, $data['fullname'], $data['email'], $data['pass'], $data['phone'], $data['rol']);
      echo json_encode($response);
   });


   $AppRoutes->AddRoutes('DELETE', 'users', function() {
      require_once 'models/users.php';
      $users = new usersModel();
      $response;
      $id = $_GET['id'];
      $response = $users->delete($id);
      echo json_encode($response);
   });
?>