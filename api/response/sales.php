<?php
 require_once '../models/sales.php';
$sales = new SalesModel();
$request = $_SERVER['REQUEST_METHOD'];
$response;
if($request == 'GET'){
   
   if(isset($_GET['id'])){
      $id = $_GET['id'];
      $response = $sales->readSalesById($id);
   }else{
      $response = $sales->readSalesAll();
   }
   echo json_encode($response);
}
if($request == 'POST'){
   $data = json_decode(file_get_contents('php://input'), true);
   $response = $sales->create($data['sales']);
   echo json_encode($response);
}
?>