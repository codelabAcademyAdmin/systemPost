<?php 
   require_once '../models/suppliers.php';

   $suppliers = new suppliersModel();

   $request = $_SERVER['REQUEST_METHOD'];
   $response;
   if($request == 'GET'){
 
      if(isset($_GET['id'])){
         $id = $_GET['id'];
         $response = $suppliers->readById($id);
      }else{
         $response = $suppliers->readAll();
      }
      echo json_encode($response);
   }
   
   if($request == 'POST'){
      $data = json_decode(file_get_contents('php://input'), true);

      $response = $suppliers->create($data['id_supplier'], $data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);
      echo json_encode($response);
   }

   if($request == 'PUT'){
      $data = json_decode(file_get_contents('php://input'), true);
      $id = $_GET['id'];
      $response = $suppliers->update($id, $data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);
      echo json_encode($response);
   }  
   
   if($request == 'DELETE'){
      $id = $_GET['id'];
      $response = $suppliers->delete($id);
      echo json_encode($response);
   }
?>