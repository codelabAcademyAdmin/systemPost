<?php 
   $AppRoutes->AddRoutes('GET', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $supplies = new suppliersModel();
      $response;
      if(isset($_GET['id'])){
         $id = $_GET['id'];
            $response = $supplies->readById($id);
      }else{
            $response = $supplies->readAll();
      }
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('POST', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);
      $response = $suppliers->create($data['id_supplier'], $data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('PUT', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);
      $id = $_GET['id'];
      $response = $suppliers->update($id, $data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);
      echo json_encode($response);
   });


   $AppRoutes->AddRoutes('DELETE', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $response;
      $id = $_GET['id'];
      $response = $suppliers->delete($id);
      echo json_encode($response);
   });

?>