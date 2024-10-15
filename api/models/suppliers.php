<?php
class suppliersModel
{
   private $conn;

   public function __construct()
   {
      global $conn;
      $this->conn = $conn;
   }

   public function create($fullname, $phone, $address, $description, $category)
   {

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

   public function readAll()
   {
      $query = "SELECT * FROM suppliers";
      $result = $this->conn->query($query);
      return $result->fetch_all(MYSQLI_ASSOC);
   }

   public function readById($id_supplier)
   {
      if(!is_numeric($id_supplier)){
         return[
            'status' => 'Not Valid',
            'message' => 'El id debe ser un numero.'
         ];
      }

      $query = "SELECT id_supplier, fullname, phone, address, description, category FROM suppliers WHERE id_supplier = ?";
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

   public function validationProductSuppliers($id_supplier)
   {
      $query = "SELECT COUNT(*) as total FROM products_suppliers WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id_supplier);
      $stmt->execute();
      $result = $stmt->get_result();
      $supplier = $result->fetch_assoc();
      $stmt->close();

      return $supplier;
   }


   public function update($id_supplier, $fullname, $phone, $address, $description, $category)
   {
      if(!is_numeric($id_supplier)){
         return[
            'status' => 'Not Valid',
            'message' => 'El id debe ser un numero.'
         ];
      }

      $validation = $this->readById($id_supplier);
      if(empty($validation['supplier'])){
         return [
            'status' => 'Not Found',
            'message' => 'No se encontro el proveedor con ese id'
         ];
      };

      $query = "UPDATE suppliers SET fullname = ?, phone = ?, address = ?, description = ?, category = ? WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);

      if (!$stmt) {
         return [
            'status' => 'Error',
            'message' => 'Error al preparar la consulta: ' . $this->conn->error
         ];
      }

      $stmt->bind_param("ssssss", $fullname, $phone, $address, $description, $category, $id_supplier);

      if ($stmt->execute()) {
         if ($stmt->error) {
            return [
               'status' => 'Error',
               'message' => 'Error al ejecutar la consulta: ' . $stmt->error
            ];
         }

         if ($stmt->affected_rows > 0) {
            $update = $this->readById($id_supplier);
            return [
               'status' => 'Success',
               'message' => 'Proveedor actualizado exitosamente.',
               'supplier' => $update['supplier']
            ];
         } else{
            return [
               'status' => 'Success',
               'message' => 'No se actulizaron datos.'
            ];
         }
      } else {
         return [
            'status' => 'Error',
            'message' => 'Error al actualizar el proveedor: ' . $stmt->error
         ];
      }
   }


   public function delete($id_supplier)
   {
      if(!is_numeric($id_supplier)){
         return[
            'status' => 'Not Valid',
            'message' => 'El id debe ser un numero.'
         ];
      }
      $supplier = $this->readById($id_supplier);
      if ($supplier['status'] == 'Not Found') {
         return [
            'status' => 'Not Found',
            'message' => 'No se puede eliminar el proveedor porque el ID ' . $id_supplier . ' no existe.'
         ];
      }

      $validation = $this->validationProductSuppliers($id_supplier);
      if ($validation['total'] > 0) {
         return [
            'status' => 'Conflict',
            'message' => 'No se puede eliminar el proveedor porque tiene productos relacionados.'
         ];
      }

      $query = "DELETE FROM suppliers WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id_supplier);

      if ($stmt->execute()) {
         if ($stmt->affected_rows > 0) {
            return [
               'status' => 'Success',
               'message' => 'Proveedor eliminado exitosamente'
            ];
         }
      }
      return [
         'status' => 'Error',
         'message' => 'No se pudo eliminar el proveedor'
      ];
   }
}
