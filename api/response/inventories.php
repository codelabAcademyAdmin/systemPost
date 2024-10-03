<?php
   require_once '../models/inventories.php';

   $inventories = new inventoriesModel();

$request = $_SERVER['REQUEST_METHOD'];
$response;
if($request == 'GET'){
   
   if(isset($_GET['id'])){
      $id = $_GET['id'];
      $response = $inventories->readById($id);
   }else{
      $response = $inventories->readAll();
   }
   echo json_encode($response);
}
if($request == 'POST'){
   $data = json_decode(file_get_contents('php://input'), true);
   $response = $inventories->create($data['amount'],$data['sale_price']);
   echo json_encode($response);
}
if($request == 'PUT'){
   $data = json_decode(file_get_contents('php://input'), true);
   $id = $_GET['id'];
   $response = $inventories->update($id, $data['amount'], $data['sale_price']);
   echo json_encode($response);
} 
if($request == 'DELETE'){
   $id = $_GET['id'];
   $response = $inventories->delete($id);
   echo json_encode($response);
}


?>