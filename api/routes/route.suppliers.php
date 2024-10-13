<?php 
   $AppRoutes->AddRoutes('GET', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $supplies = new suppliersModel();
      $response;
  
      if (isset($_GET['id'])) {
         $id = $_GET['id'];
         $response = $supplies->readById($id);
  
         if ($response['status'] === 'Error') {
            http_response_code(404);
         } else {
            http_response_code(200);
         }
      } else {
         $response = $supplies->readAll();
         http_response_code(200);
      }
      
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('POST', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);

      if ($data) {
         $response = $suppliers->create($data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);
         
         if ($response) {
            http_response_code(201); 
            echo json_encode(['message' => 'Proovedor creado exitosamente']);
         } else {
            http_response_code(40); 
            echo json_encode(['message' => 'Datos de entrada no son validos']);
         }
      } else {
         http_response_code(500); 
         echo json_encode(['message' => 'No se pudo crear el proveedor']);
      }
   });

   $AppRoutes->AddRoutes('PUT', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $supplies = new suppliersModel();
  
      if (empty($_GET['id'])) {
         http_response_code(400); 
         echo json_encode(['status' => 'Error', 'message' => 'ID del proveedor es requerido.']);
         return;
      }
  
      $id_supplier = (int)$_GET['id']; 
      $data = json_decode(file_get_contents('php://input'), true);
  
      if (!isset($data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category'])) {
         http_response_code(400); 
         echo json_encode(['status' => 'Error', 'message' => 'Faltan datos requeridos.']);
         return;
      }
  
      $response = $supplies->update($id_supplier, $data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);
  
      if ($response['status'] === 'Exito') {
         http_response_code(200); 
      } else {
         http_response_code(500);
      }
      
      echo json_encode($response);
  });
  
  $AppRoutes->AddRoutes('DELETE', 'suppliers', function() {
   require_once 'models/suppliers.php';
   $suppliers = new suppliersModel();
   $id = $_GET['id'];

   if (isset($id)) {
      $response = $suppliers->delete($id);

      if ($response['status'] === 'Success') {
         http_response_code(200); 
         } else {
           http_response_code(400);
         }
      echo json_encode($response);
   } else {
      http_response_code(400); 
      echo json_encode(['status' => 'Error', 'message' => 'ID del proveedor es requerido.']);
   }

});

?>