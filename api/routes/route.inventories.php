<?php
   $AppRoutes->AddRoutes('GET', 'inventories', function() {
      require_once 'models/inventories.php';
      $inventories = new inventoriesModel();
      $response;
      if(isset($_GET['id'])){
         $id = $_GET['id'];
            $response = $inventories->readById($id);
      }else{
            $response = $inventories->readAll();
      }
      echo json_encode($response);
   });

      $AppRoutes->AddRoutes('POST', 'inventories', function() {
      require_once 'models/inventories.php';
      $inventories = new inventoriesModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);
      $response = $inventories->create($data['amount'],$data['sale_price']);
      echo json_encode($response);
   });

      $AppRoutes->AddRoutes('PUT', 'inventories', function() {
      require_once 'models/inventories.php';
      $inventories = new inventoriesModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);
      $id = $_GET['id'];
      $response = $inventories->update($id, $data['amount'],$data['sale_price']);
      echo json_encode($response);
   });

     $AppRoutes->AddRoutes('DELETE', 'inventories', function() {
      require_once 'models/inventories.php';
      $inventories = new inventoriesModel();
      $response;
      $id = $_GET['id'];
      $response = $inventories->delete($id);
      echo json_encode($response);
   });
   ?>