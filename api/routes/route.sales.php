<?php
   $AppRoutes->AddRoutes('GET', 'sales', function() {
      require_once 'models/sales.php';
      $sales = new SalesModel();
      $response;
      if(isset($_GET['id'])){
         $id = $_GET['id'];
            $response = $sales->readSalesById($id);
      }else{
            $response = $sales->readSalesAll();
      }

      if (isset($response['status'])) {
         http_response_code($response['status']);
     } else {
         http_response_code(500); // Código de estado por defecto en caso de error inesperado
         $response = ['status' => 500, 'error' => 'Error inesperado'];
     }

      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('POST', 'sales', function() {
      require_once 'models/sales.php';
      $sales = new SalesModel();
      $data = json_decode(file_get_contents('php://input'), true);
      $response = $sales->create($data['sales']);
 
      echo json_encode($response);
   });

   ?>