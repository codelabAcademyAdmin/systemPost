<?php
class suppliersModel
{
   private $conn;

   public function __construct() {
      global $conn;
      $this->conn = $conn;
   }
   //funcion para verificar y asegurar de que el nombre no este siendo usado 
   public function validationDataName($fullname) {
      $query = "SELECT * FROM suppliers WHERE fullname = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("s", $fullname);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
  
      if ($result->num_rows > 0) {
          return [
              'status' => 'Conflicts',
              'message' => 'Este nombre del proveedor ya está siendo usado por otro proveedor.'
          ];
      }
      return [
         'status' => 'Success'
     ]; 
   }
   
   // funcion verificar si el phone cumple con los requisitos correspondientes
   public function validationDataPhone($phone) {
      if (!preg_match('/^\d{10}$/', $phone)) {
          return [
              'status' => 'Not Valid',
              'message' => 'El número de teléfono debe cumplir con los 10 dígitos y debe ser solo números.'
          ];
      }
      return [
         'status' => 'Success'
     ]; 
   }
   
    //funcion para verificar y asegurar de que el numero no este siendo usado 
   public function validationNumberPhone($phone) {
      $query = "SELECT * FROM suppliers WHERE phone = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("s", $phone);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();
  
      if ($result->num_rows > 0) {
          return [
              'status' => 'Conflicts',
              'message' => 'Este número de teléfono del proveedor ya está siendo usado por otro proveedor.'
          ];
      }
      
      return [
         'status' => 'Success'
     ]; 
   }

   //funcion para el metodo POST
   public function create($fullname, $phone, $address, $description, $category) {

      //Llamamos a la funciones de validacion para varificar los datos 
      $response = $this->validationDataName($fullname);
         if ($response['status'] !== 'Success') {
         return $response; 
      }$response = $this->validationDataPhone($phone);
         if ($response['status'] !== 'Success') {
         return $response; 
      }
      $response = $this->validationNumberPhone($phone);
         if ($response['status'] !== 'Success') {
         return $response; 
      }

      //si pasa la validacion, procedemos a crear el nuevo proveedor
      $query = "INSERT INTO suppliers (fullname, phone, address, description, category) VALUES (?, ?, ?, ?, ?)";
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }
      $stmt->bind_param("sssss", $fullname, $phone, $address, $description, $category);

      if ($stmt->execute()) {
         $id_supplier = $this->conn->insert_id;

         $validation = $this->readById($id_supplier);
         if ($validation) {
            $stmt->close();
            return [
               'status' => 'Success',
               'message' => 'Proveedor creado exitosamente',
               'supplier' => $validation['supplier']
            ];
         } else {
            return [
               'status' => 'Error',
               'message' => 'No se pudo validar la creación del proveedor'
            ];
         }
      } else {
         return [
            'status' => 'Error',
            'message' => 'Error al crear el proveedor: ' . $stmt->error
         ];
      }
   }

   //funcion para el metodo GET
   public function readAll() {
      $query = "SELECT * FROM suppliers";
      $result = $this->conn->query($query);
      return $result->fetch_all(MYSQLI_ASSOC);
   }

   //uncion para el metodo GET por id
   public function readById($id_supplier) {
      if(!is_numeric($id_supplier)){
         return[
            'status' => 'Not Valid',
            'message' => 'El id debe ser solo números.'
         ];
      }

      // Asegurarse de que el campo status sea parte de la selección
      $query = "SELECT id_supplier, fullname, phone, address, description, category, status FROM suppliers WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }

      $stmt->bind_param("i", $id_supplier);
      $stmt->execute();
      $result = $stmt->get_result();
      $supplier = $result->fetch_assoc();
      $stmt->close();

      if (!$supplier) {
         return [
            'status' => 'Not Found',
            'message' => 'No se encontró ningún proveedor con el ID ' . $id_supplier
         ];
      }

      return [
         'status' => 'Success',
         'supplier' => $supplier
      ];
   }

   //funcion para el metodo UPDATE
   public function update($id_supplier, $data) {
      
      // verifica si el ID es numérico
      if (!is_numeric($id_supplier)) {
         return [
            'status' => 'Not Valid',
            'message' => 'El id debe ser solo números.'
         ];
      }
  
      // validar que el proveedor existe
      $validation = $this->readById($id_supplier);
      if (empty($validation['supplier'])) {
         return [
            'status' => 'Not Found',
            'message' => 'No se encontró un proveedor con ese ID.'
         ];
      }
  
      //obtener los datos existentes del proveedor
      $existingSupplier = $validation['supplier'];
  
      // Comprobar si los campos han cambiado
      $updateFields = [];
      $params = [];
  
      // Solo actualizamos los campos que han cambiado
      if (isset($data['fullname']) && $data['fullname'] !== $existingSupplier['fullname']) {
         // aqui traemos la funcion de validar el nuevo nombre si ya esta en uso
         $response = $this->validationDataName($data['fullname']);
         if ($response['status'] !== 'Success') {
            return $response; 
         }
         $updateFields[] = "fullname = ?";
         $params[] = $data['fullname'];
      }
      if (isset($data['phone']) && $data['phone'] !== $existingSupplier['phone']) {
         // aqui traemos la funcion de validar el numero si ya esta en uso
         $response = $this->validationDataPhone($data['phone']);
         if ($response['status'] !== 'Success') {
            return $response; 
         }
         //funcion para verificar que el numero cumpla con los caracteres correspondientes
         $response = $this->validationNumberPhone($data['phone']);
         if ($response['status'] !== 'Success') {
            return $response; 
         }
          $updateFields[] = "phone = ?";
          $params[] = $data['phone'];
      }
      if (isset($data['address']) && $data['address'] !== $existingSupplier['address']) {
         $updateFields[] = "address = ?";
         $params[] = $data['address'];
      }
      if (isset($data['description']) && $data['description'] !== $existingSupplier['description']) {
         $updateFields[] = "description = ?";
         $params[] = $data['description'];
      }
      if (isset($data['category']) && $data['category'] !== $existingSupplier['category']) {
         $updateFields[] = "category = ?";
         $params[] = $data['category'];
      }
  
      //si no se han proporcionado cambios, mandar este mensaje 
      if (empty($updateFields)) {
         return [
            'status' => 'Success',
            'message' => 'No se realizaron cambios en la actualización.'
         ];
      }
  
      // Agregar el ID del proveedor a los parámetros
      $params[] = $id_supplier;
  
      // Crear la consulta dinámica
      $query = "UPDATE suppliers SET " . implode(", ", $updateFields) . " WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      
      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }
  
      // Bind de parámetros
      $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);
      if ($stmt->execute()) {
         return [
            'status' => 'Success',
            'message' => 'Proveedor actualizado exitosamente.',
            'supplier' => $this->readById($id_supplier)['supplier'] 
         ];
      } else {
         return [
            'status' => 'Error',
            'message' => 'Error al actualizar el proveedor: ' . $stmt->error
         ];
      }
  }

   public function activate($id_supplier) {
      if (!is_numeric($id_supplier)) {
         return [
            'status' => 'Not Valid',
            'message' => "El ID del proveedor debe ser un número."
         ];
      }

      // validar que el proveedor exista
      $existingSupplier = $this->readById($id_supplier);
      if ($existingSupplier['status'] !== 'Success') {
         return [
            'status' => 'Not Found',
            'message' => 'No se puede inactivar el proveedor porque el ID ' . $id_supplier . ' no existe.'
         ];
      }

      if ($existingSupplier['supplier']['status'] == 'activo') {
         return [
            'status' => 'Conflict',
            'message' => 'El proveedor ya está activo.'
         ];
      }

      // funcion para ativar el proveedor
      $query = "UPDATE suppliers SET status = 'activo' WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
         return [
            'status' => 'Internal Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }

      $stmt->bind_param("i", $id_supplier);

      if ($stmt->execute()) {
         if ($stmt->affected_rows > 0) {
            return [
               'status' => 'Success',
               'message' => 'Proveedor activado exitosamente.'
            ];
         } else {
            return [
               'status' => 'Not Found',
               'message' => 'No se encontró el proveedor para activar.'
            ];
         }
      } else {
         return [
            'status' => 'Error',
            'message' => 'Error al activar el proveedor: ' . $stmt->error
         ];
      }
   }

   //funcion para desactivar el provedor
   public function deactivate($id_supplier) {
      if (!is_numeric($id_supplier)) {
         return [
            'status' => 'Not Valid',
            'message' => "El ID del proveedor debe ser solo números."
         ];
      }

      $existingSupplier = $this->readById($id_supplier);
      if ($existingSupplier['status'] === 'Not Found') {
         return [
            'status' => 'Not Found',
            'message' => 'No se puede inactivar el proveedor porque el ID ' . $id_supplier . ' no existe.'
         ];
      }

      // verificar si ya esta activo
      if ($existingSupplier['supplier']['status'] == 'inactivo') {
         return [
            'status' => 'Conflict',
            'message' => 'El proveedor ya está inactivo.'
         ];
      }

      // inactivar el proveedor
      $query = "UPDATE suppliers SET status = 'inactivo' WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
         return [
            'status' => 'Internal Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }

      $stmt->bind_param("i", $id_supplier);

      if ($stmt->execute()) {
         if ($stmt->affected_rows > 0) {
            return [
               'status' => 'Success',
               'message' => 'Proveedor inactivado exitosamente.'
            ];
         } else {
            return [
               'status' => 'Not Found',
               'message' => 'No se encontró el proveedor para inactivar.'
            ];
         }
      } else {
         return [
            'status' => 'Error',
            'message' => 'Error al inactivar el proveedor: ' . $stmt->error
         ];
      }
   }

}
?>
