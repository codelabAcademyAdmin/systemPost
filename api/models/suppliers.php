<?php
class suppliersModel
{
   private $conn;

   public function __construct() {
      global $conn;
      $this->conn = $conn;
   }
   //funcion para verificar y asegurar de que el nombre no este siendo usado 
   public function validationName($fullname) {
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
   public function validationPhone($phone) {
      if (!preg_match('/^\d{10}$/', $phone)) {
          return [
              'status' => 'Not Valid',
              'message' => 'El número de teléfono debe ser exactamente 10 dígitos numéricos.'
          ];
      }

      $query = "SELECT * FROM suppliers WHERE phone = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("s", $phone);
      $stmt->execute();
      $result = $stmt->get_result();
      $stmt->close();

      if ($result->num_rows > 0) {
          return [
              'status' => 'Conflicts',
              'message' => 'Este número de teléfono ya está siendo usado por otro usuario.'
          ];
      }
      return [
          'status' => 'Success'
      ]; 
  }
  
   public function create($fullname, $phone, $address, $description, $category) {

      //Llamamos a la funciones de validacion para varificar los datos 
      $response = $this->validationName($fullname);
         if ($response['status'] !== 'Success') {
         return $response; 
      }
      $response = $this->validationPhone($phone);
         if ($response['status'] !== 'Success') {
         return $response;
      }

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

   public function readAll() {
      $query = "SELECT * FROM suppliers";
      $result = $this->conn->query($query);
      return $result->fetch_all(MYSQLI_ASSOC);
   }

   public function readById($id_supplier) {
      if(!is_numeric($id_supplier)){
         return[
            'status' => 'Not Valid',
            'message' => 'El id debe ser solo números.'
         ];
      }

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

   public function update($id_supplier, $data) {
      
      if (!is_numeric($id_supplier)) {
         return [
            'status' => 'Not Valid',
            'message' => 'El id debe ser solo números.'
         ];
      }
  
      $validation = $this->readById($id_supplier);
      if (empty($validation['supplier'])) {
         return [
            'status' => 'Not Found',
            'message' => 'No se encontró un proveedor con ese ID.'
         ];
      }
  
      $existingSupplier = $validation['supplier'];
  
      $updateFields = [];
      $params = [];
  
      //solo actualizamos los campos que han cambiado
      if (isset($data['fullname']) && $data['fullname'] !== $existingSupplier['fullname']) {
         // aqui traemos la funcion de validar el nuevo nombre si ya esta en uso
         $response = $this->validationName($data['fullname']);
         if ($response['status'] !== 'Success') {
            return $response; 
         }
         $updateFields[] = "fullname = ?";
         $params[] = $data['fullname'];
      }
      if (isset($data['phone']) && $data['phone'] !== $existingSupplier['phone']) {
         // aqui traemos la funcion de validar el numero si ya esta en uso
         $response = $this->validationPhone($data['phone']);   
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
  
      if (empty($updateFields)) {
         return [
            'status' => 'Success',
            'message' => 'No se realizaron cambios en la actualización.'
         ];
      }
  
      $params[] = $id_supplier;
  
      $query = "UPDATE suppliers SET " . implode(", ", $updateFields) . " WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      
      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }
  
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

      if ($existingSupplier['supplier']['status'] == 'inactivo') {
         return [
            'status' => 'Conflict',
            'message' => 'El proveedor ya está inactivo.'
         ];
      }

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
