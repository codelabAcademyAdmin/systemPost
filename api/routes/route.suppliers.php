<?php
$AppRoutes->AddRoutes('GET', 'suppliers', function () {
   require_once 'models/suppliers.php';
   $supplies = new suppliersModel();
   $response;

   if (isset($_GET['id'])) {
      $id = $_GET['id'];
      $response = $supplies->readById($id);

      switch ($response['status']) {
         case 'Not Valid':
            http_response_code(400); // Bad Request
            break;
         case 'Success':
            http_response_code(200); // Ok
            break;
         case 'Not Found':
            http_response_code(404); // Not Found
            break;
         case 'Error':
            http_response_code(500); // Internal Server Error
            break;
      }
   } else {
      $response = $supplies->readAll();
      http_response_code(200);
   }

   echo json_encode($response);
});

$AppRoutes->AddRoutes('POST', 'suppliers', function () {
   require_once 'models/suppliers.php';
   $suppliers = new suppliersModel();
   $response;
   $data = json_decode(file_get_contents('php://input'), true);

   if ($data) {
      $response = $suppliers->create($data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);

      if ($response['status'] === 'Success') {
         http_response_code(201);
      } else if ($response['status'] === 'Error') {
         http_response_code(500);
      }
   }

   echo json_encode($response);
});

$AppRoutes->AddRoutes('PUT', 'suppliers', function () {
   require_once 'models/suppliers.php';
   $supplies = new suppliersModel();

   $id_supplier = $_GET['id'];
   $data = json_decode(file_get_contents('php://input'), true);

   if (!isset($data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category'])) {
      http_response_code(400);
      echo json_encode(['status' => 'Error', 'message' => 'Faltan datos requeridos.']);
   }

   $response = $supplies->update($id_supplier, $data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);

   switch ($response['status']) {
      case 'Not Valid':
         http_response_code(400); // Bad Request
         break;
      case 'Success':
         http_response_code(200); // Ok
         break;
      case 'Not Found':
         http_response_code(404); // Not Found
         break;
      case 'Error':
         http_response_code(500); // Internal Server Error
         break;
      
   }

   echo json_encode($response);
});

$AppRoutes->AddRoutes('DELETE', 'suppliers', function () {
   require_once 'models/suppliers.php';
   $suppliers = new suppliersModel();
   $id = $_GET['id'];

   $response = $suppliers->delete($id);

   switch ($response['status']) {
      case 'Not Valid':
         http_response_code(400); // Bad Request
         break;
      case 'Success':
         http_response_code(200); // Ok
         break;
      case 'Not Found':
         http_response_code(404); // Not Found
         break;
      case 'Error':
         http_response_code(500); // Internal Server Error
         break;
      case 'Conflict':
         http_response_code(409); //Conflict
         break;
   }

   echo json_encode($response);

});
