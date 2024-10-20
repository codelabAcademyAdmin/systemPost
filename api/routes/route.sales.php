<?php
   $AppRoutes->AddRoutes('POST', 'sales', function() {
      require_once 'models/sales.php';
      $sales = new SalesModel();
      $data = json_decode(file_get_contents('php://input'), true);
      $response = $sales->create($data['sales']);
 
      echo json_encode($response);
   });
   ?>

