<?php

   function setHttpResponseSuppliers($status) {
      switch ($status) {
         case 'Not Valid':
               http_response_code(400); // Bad Request
               break;
         case 'Success':
               http_response_code(200); // OK
               break;
         case 'Not Found':
               http_response_code(404); // Not Found
               break;
         case 'Error':
               http_response_code(500); // Internal Server Error
               break;
         case 'Conflicts':
               http_response_code(409); // Conflict
               break;
         default:
               http_response_code(500); // Por defecto a Internal Server Error
               break;
      }
   }

   $AppRoutes->AddRoutes('GET', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $response;

      if (isset($_GET['id'])) {
         $id = $_GET['id'];
         $response = $suppliers->readById($id);
         
         setHttpResponseSuppliers($response['status']); 
      } else {
         $response = $suppliers->readAll();
         setHttpResponseSuppliers('Success'); 
      }

      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('POST', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $data = json_decode(file_get_contents('php://input'), true);

      if (!$data || 
         empty($data['fullname']) || empty($data['phone']) || empty($data['address']) || 
         empty($data['description']) || empty($data['category'])) {
         
         $response = [
               'status' => 'Not Valid',
               'message' => 'Los datos no son válidos, recuerda que todos los campos son obligatorios.'
         ];
         setHttpResponseSuppliers($response['status']); 
         echo json_encode($response);
         return; 
      }

      $response = $suppliers->create($data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);
      setHttpResponseSuppliers($response['status']);
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('PUT', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $id_supplier = $_GET['id'];
      $data = json_decode(file_get_contents('php://input'), true);

      if (empty($data)) {
         $response = ['status' => 'Error', 'message' => 'Faltan datos requeridos.'];
         setHttpResponseSuppliers($response['status']); 
         echo json_encode($response);
         return;
      }

      $existingSuppliersResponse = $suppliers->readById($id_supplier);
      if ($existingSuppliersResponse['status'] !== 'Success') {
         setHttpResponseSuppliers($existingSuppliersResponse['status']); 
         echo json_encode([
               'status' => 'Error',
               'message' => $existingSuppliersResponse['message']
         ]);
         return;
      }

      $existingSupplier = $existingSuppliersResponse['supplier'];
      $updateData = [];

      // Verificamos si hay cambios para cada campo
      if (isset($data['fullname']) && $data['fullname'] !== $existingSupplier['fullname']) {
         $updateData['fullname'] = $data['fullname'];
      }
      if (isset($data['phone']) && $data['phone'] !== $existingSupplier['phone']) {
         $updateData['phone'] = $data['phone'];
      }
      if (isset($data['address']) && $data['address'] !== $existingSupplier['address']) {
         $updateData['address'] = $data['address'];
      }
      if (isset($data['description']) && $data['description'] !== $existingSupplier['description']) {
         $updateData['description'] = $data['description'];
      }
      if (isset($data['category']) && $data['category'] !== $existingSupplier['category']) {
         $updateData['category'] = $data['category'];
      }

      $response = $suppliers->update($id_supplier, $updateData);
      setHttpResponseSuppliers($response['status']); 
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('PATCH', 'suppliers/deactivate', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $id = $_GET['id']; 
      $response = $suppliers->deactivate($id); 

      setHttpResponseSuppliers($response['status']); 
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('PATCH', 'suppliers/activate', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $id = $_GET['id']; 
      $response = $suppliers->activate($id); 

      setHttpResponseSuppliers($response['status']); 
      echo json_encode($response);
   });

?>
