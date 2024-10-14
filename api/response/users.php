<?php
   require_once '../models/users.php';

   $users = new usersModel();

   $request = $_SERVER['REQUEST_METHOD'];
   $response;
   if($request == 'GET'){
      
      if(isset($_GET['id'])){
         $id = $_GET['id'];
         $response = $users->readById($id);
      }else{
         $response = $users->readAll();
      }
      echo json_encode($response);
   }

   if($request == 'POST'){
      $data = json_decode(file_get_contents('php://input'), true);
      $response = $users->create($data['fullname'], $data['email'], $data['pass'], $data['phone'], $data['rol']);
      echo json_encode($response);
   }

   if($request == 'PUT'){
      $data = json_decode(file_get_contents('php://input'), true);
      $id = $_GET['id'];
      $response = $users->update($id, $data['fullname'], $data['email'], $data['pass'], $data['phone'], $data['rol']);
      echo json_encode($response);
   }  
   
   if($request == 'DELETE'){
      $id = $_GET['id'];
      $response = $users->delete($id);
      echo json_encode($response);
   }
   
?>