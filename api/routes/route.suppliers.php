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
      } else if (isset($_GET['status'])) {
         
        $status = $_GET['status'];
        $response = $suppliers->readByStatus($status);
      } else {
         $response = $suppliers->readAll();
      }
      
      setHttpResponseSuppliers($response['status']); 
      echo json_encode($response);
   });
  
   $AppRoutes->AddRoutes('POST', 'suppliers', function() {
      require_once 'models/suppliers.php';
      $suppliers = new suppliersModel();
      $data = json_decode(file_get_contents('php://input'), true);
      $response;
      if (!$data || 
         empty($data['fullname']) || empty($data['phone']) || empty($data['address']) || 
         empty($data['description']) || empty($data['category'])) {
            $response = [
               'status' => 'Not Valid',
               'message' => 'Los datos no son válidos, recuerda que todos los campos son obligatorios.'
            ];
            setHttpResponseSuppliers($response['status']); 
            echo json_encode($response);
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

      if (
         !$data ||
         empty($data['fullname']) || empty($data['phone']) || empty($data['address']) || empty($data['description']) || empty($data['category'])
         ) {
            $response = [
               'status' => 'Not Valid',
               'message' => 'Los datos no son válidos, recuerda que todos los campos son obligatorios.'
            ];
            setHttpResponseSuppliers($response['status']); 
            echo json_encode($response);
         }

         $response = $suppliers->update($id_supplier, $data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);

      setHttpResponseUsers($response['status']);
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
