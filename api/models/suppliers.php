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
      
      // Obtener el ID autogenerado
      $id_supplier = $this->conn->insert_id;

      // Validar la creación del proveedor usando el nuevo ID
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
            'message' => 'No se pudo validar la creación del proveedor: ' . $stmt->error
         ];
      }
   } else {
      return [
         'status' => 'Error',
         'message' => 'Error al crear el proveedor: ' . $stmt->error
      ];
   }
}

    // Consulta para obtener todos los registros 
   public function readAll(){
      $query = "SELECT * FROM suppliers";
      $result = $this->conn->query($query);
      return $result->fetch_all(MYSQLI_ASSOC);
   }

   // Consulta para obtener por el ID un solo registro 
   public function readById($id_supplier) {
      $query = "SELECT id_supplier, fullname, phone, address, description, category FROM suppliers WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id_supplier);
      $stmt->execute();
      $result = $stmt->get_result();
      $supplier = $result->fetch_assoc();
      $stmt->close(); 
      return $supplier;
   }

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


   // Consulta para actualizar los datos del proveedor
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
      'message' => 'No se pudo actualizar el Proovedor'
      ];
   }

   public function delete($id_supplier){
      //Validacion si el proovedor tiene productos relaciondos
      if($this->validationProductSuppliers($id_supplier)){
         return [
            'status' => 'Error',
            'message' => 'No se puede eliminar el proveedor porque tiene productos relacionados. '
         ];
      }
      
      //Si no tiene productos relacionados, procede a la eliminacion
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