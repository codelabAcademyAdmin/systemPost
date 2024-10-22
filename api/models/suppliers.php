<?php
class suppliersModel
{
   private $conn;

   public function __construct()
   {
      global $conn;
      $this->conn = $conn;
   }

   public function validationIfExist($phone) {
      if (!preg_match('/^\d{10}$/', $phone)) {
         return [
            'status' => 'Not Valid',
            'message' => 'El número de teléfono debe cumplir con solo 10 dígitos y debe ser solo números.'
         ];
      }
      $query = "SELECT * FROM users WHERE phone = ?";
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }
      $stmt->bind_param("s", $phone);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
         return [
            'status' => 'Conflicts',
            'message' => 'El numero de telefono ya esta en uso'
         ];
      }
      return [
         'status' => 'Success'
      ];
   }

   public function validationIfExistForUpdate($phone, $id_user) {
      if (!preg_match('/^\d{10}$/', $phone)) {
         return [
            'status' => 'Not Valid',
            'message' => 'El número de teléfono debe cumplir con solo 10 dígitos y debe ser solo números.'
         ];
      }
      $query = "SELECT * FROM suppliers WHERE phone = ? AND id_supplier != ?";
      $stmt = $this->conn->prepare($query);
      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }
      $stmt->bind_param("si", $phone, $id_user);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
         return [
            'status' => 'Conflicts',
            'message' => 'El numero de telefono ya esta en uso'
         ];
      }
      return [
         'status' => 'Success'
      ];
   }

   public function create($fullname, $phone, $address, $description, $category) {

      $response = $this->validationIfExist($phone);
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

      if ($result === false) {
         return [
            'status' => 'Internal Error',
            'message' => 'Error al ejecutar la consulta: ' . $this->conn->error
         ];
      }

      if ($result->num_rows > 0) {
         return [
            'status' => 'Success',
            'suppliers' => $result->fetch_all(MYSQLI_ASSOC)
         ];
      }
   }

   public function readById($id_supplier) {
      if (!is_numeric($id_supplier)) {
         return [
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

      if (!$supplier) {
         return [
            'status' => 'Not Found',
            'message' => 'No se encontró ningún proveedor con el id ' . $id_supplier
         ];
      }

      return [
         'status' => 'Success',
         'supplier' => $supplier
      ];
   }

   public function readByStatus($status) {

      if ($status !== 'activo' && $status !== 'inactivo') {
         return [
            'status' => 'Not Valid',
            'message' => 'El estado proporcionado no es válido. Debes usar "activo" o "inactivo"'
         ];
      }
      
      $query = "SELECT * FROM suppliers WHERE status = ?";
      $stmt = $this->conn->prepare($query);

      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }

      $stmt->bind_param("s", $status);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows === 0) {
         return [
            'status' => 'Not Found',
            'message' => 'No se encontró ningún provedor con ese estado: ' . $status
         ];
      }

      return [
         'status' => 'Success',
         'supplier' => $result->fetch_all(MYSQLI_ASSOC)
      ];
   }

   public function update($id_supplier, $fullname, $phone, $address, $description, $category) {
      if (!is_numeric($id_supplier)) {
         return [
            'status' => 'Not Valid',
            'message' => 'El id debe ser solo números.'
         ];
      }

      $validation = $this->readById($id_supplier);
      if ($validation['status'] !== 'Success') {
         return $validation;
      }

      $response = $this->validationIfExistForUpdate($phone, $id_supplier);
      if ($response['status'] !== 'Success') {
         return $response;
      }

      $query = "UPDATE suppliers SET fullname = ?, phone = ?, address = ?, description = ?, category = ? WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);

      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }

      $stmt->bind_param("sssssi", $fullname, $phone, $address, $description, $category, $id_supplier);

      if (!$stmt->execute()) {
         return [
            'status' => 'Error',
            'message' => 'Error al actualizar el proveedor: ' . $stmt->error
         ];
      }

      if ($stmt->affected_rows > 0) {
         return [
            'status' => 'Success',
            'message' => 'Proveedor actualizado exitosamente.',
            'supplier' => $this->readById($id_supplier)['supplier']
         ];
      } else {
         return [
            'status' => 'Success',
            'message' => 'No se evidenciaron cambios',
            'supplier' => $this->readById($id_supplier)['supplier']
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
