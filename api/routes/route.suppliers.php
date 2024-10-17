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
   $data = json_decode(file_get_contents('php://input'), true);

   $response = [];

   // Verificar que los datos sean obligatorios
   if (!$data || 
      empty($data['fullname']) || empty($data['phone']) || empty($data['address']) || empty($data['description']) || empty($data['category'])) {
       
      http_response_code(400); // Bad Request
      $response = [
         'status' => 'Not Valid',
         'message' => 'Los datos no son válidos, recuerda que todos los campos son obligatorios.'
      ];
      echo json_encode($response);
      return; 
   }

   $response = $suppliers->create($data['fullname'], $data['phone'], $data['address'], $data['description'], $data['category']);

   switch ($response['status']) {
      case 'Not Valid':
         http_response_code(400); // Bad Request
         break;
      case 'Success':
         http_response_code(201); // Created
         break;
      case 'Conflicts':
         http_response_code(409); // Conflict
         break;
      case 'Error':
         http_response_code(500); // Internal Server Error
         break;
   }

   echo json_encode($response);
});

$AppRoutes->AddRoutes('PUT', 'suppliers', function () {
   require_once 'models/suppliers.php';
   $supplies = new suppliersModel();
   $id_supplier = $_GET['id'];
   $data = json_decode(file_get_contents('php://input'), true);

   //verificar que los datos no esten vacios
   if (empty($data)) {
      http_response_code(400);
      echo json_encode(['status' => 'Error', 'message' => 'Faltan datos requeridos.']);
      return; 
   }

   //traemos los datos del provedor existente para comparar
   $existingSupplierResponse = $supplies->readById($id_supplier);

   if ($existingSupplierResponse['status'] !== 'Success') {
      http_response_code(404);
      echo json_encode([
         'status' => 'Error',
         // envia el mensaje que retorna del modelo
         'message' => $existingSupplierResponse['message'] 
      ]);
      return;
   }
   $existingSupplier = $existingSupplierResponse['supplier'];

   //comprobamos que campos se editaron, no es obligatorio editarlos todos
   $updateData = [];
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

   //llamamos a la función de actualización pasando solo los campos que cambiaron
   $response = $supplies->update($id_supplier, $updateData);

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
      case 'Conflicts':
         http_response_code(409); // Conflict
         break;
      case 'Error':
         http_response_code(500); // Internal Server Error
         break;
   }

   echo json_encode($response);
});

$AppRoutes->AddRoutes('PATCH', 'suppliers/deactivate', function () {
   require_once 'models/suppliers.php';
   $suppliers = new suppliersModel();
   $id = $_GET['id']; 
   $response = $suppliers->deactivate($id); 

   //codigo de respuesta HTTP
   http_response_code(mapHttpResponseCode($response['status']));
   echo json_encode($response);
});

$AppRoutes->AddRoutes('PATCH', 'suppliers/activate', function () {
   require_once 'models/suppliers.php';
   $suppliers = new suppliersModel();
   $id = $_GET['id']; 
   $response = $suppliers->activate($id); 

   //establecer el código de respuesta HTTP
   http_response_code(mapHttpResponseCode($response['status']));
   echo json_encode($response);
});

//funcion para mapear los cóoigos de respuestas
function mapHttpResponseCode($status) {
   switch ($status) {
       case 'Success':
           return 200;
       case 'Not Found':
           return 404;
       case 'Conflict':
           return 409;
       case 'Not Valid':
           return 400;
       case 'Internal Error':
           return 500;
       default:
           return 500; 
   }
}

