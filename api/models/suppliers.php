<?php 
class suppliersModel
{
   private $conn;

   public function __construct(){
      global $conn;
      $this->conn = $conn;
   }

   public function create($fullname, $phone, $address, $description, $category) {
      
      //consulta para insertar nuevo  proveedor
      $query = "INSERT INTO suppliers (fullname, phone, address, description, category) VALUES (?, ?, ?, ?, ?)";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("sisss", $fullname, $phone, $address, $description, $category); 

      if ($stmt->execute()) {
      
      //obtener el id autoinclementable
      $id_supplier = $this->conn->insert_id;

      //validar la creacion del proveedor usando el nuevo id
      $validation = $this->readById($id_supplier);
      if ($validation) {
         $stmt->close(); 
         return [
            'status' => 'Success',
            'message' => 'Proveedor creado exitosamente',
            'supplier' => $validation 
         ];
      } else {
         return [
            'status' => 'Error',
            'message' => 'No se pudo validar la creación del proveedor: '
         ];
      }
   } else {
      return [
         'status' => 'Error',
         'message' => 'Error al crear el proveedor: ' . $stmt->error
      ];
   }
}

    //consulta para obtener todos los registros 
   public function readAll(){
      $query = "SELECT * FROM suppliers";
      $result = $this->conn->query($query);
      return $result->fetch_all(MYSQLI_ASSOC);
   }

   //consulta para obtener registrpo por un id
   public function readById($id_supplier) {
      $query = "SELECT id_supplier, fullname, phone, address, description, category FROM suppliers WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id_supplier);
      $stmt->execute();
      $result = $stmt->get_result();
      $supplier = $result->fetch_assoc();
      $stmt->close(); 

      if ($supplier) {
         return [
             'status' => 'Success',
             'message' => 'Proveedor con ID ' . $id_supplier . ' encontrado exitosamente',
             'supplier' => $supplier
         ];
      } else {
         return [
             'status' => 'Error',
             'message' => 'No se encontró ningún proveedor con el ID ' . $id_supplier
         ];
      }
   }

   //validacion si el proovedor tiene productos relacionados
   public function validationProductSuppliers($id_supplier) {
      $query = "SELECT COUNT(*) as total FROM products_suppliers WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id_supplier);
      $stmt->execute();
      $result = $stmt->get_result();
      $supplier = $result->fetch_assoc();
      $stmt->close(); 

      return $supplier;
   }


   //consulta para actualizar los datos del proveedor
   public function update($id_supplier, $fullname, $phone, $address, $description, $category) {
   
   $query = "UPDATE suppliers SET fullname = ?, phone = ?, address = ?, description = ?, category = ? WHERE id_supplier = ?";
   $stmt = $this->conn->prepare($query);
   $stmt->bind_param("sisssi", $fullname, $phone, $address, $description,  $category, $id_supplier);
   
   if ($stmt->execute()) {
      $validation = $this->readById($id_supplier);
      if ($stmt->affected_rows > 0) {
          return [
              'status' => 'Success',
              'message' => 'Proveedor actualizado exitosamente',
              'supplier' => $validation
          ];
      }
   }
   return [
      'status' => 'Error',
      'message' => 'No se pudo actualizar el Proovedor o ID ' . $id_supplier . ' no existe.'
      ];
   }

   public function delete($id_supplier){
      $supplier = $this->readById($id_supplier);
      if ($supplier['status'] == 'Error') {
         return [
            'status' => 'Error',
            'message' => 'No se puede eliminar el proveedor porque el ID ' . $id_supplier . ' no existe.'
         ];
      }

      //validar si el proveedor tiene productos relacionados
      $validation = $this->validationProductSuppliers($id_supplier);
      if ($validation['total'] > 0) {
         return [
            'status' => 'Error',
            'message' => 'No se puede eliminar el proveedor porque tiene productos relacionados.'
         ];
      } 
      
      //sino tiene productos relacionados, procede a la eliminacion
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

?>