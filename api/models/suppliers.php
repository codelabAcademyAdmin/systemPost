<?php 
class suppliersModel
{
   private $conn;

   public function __construct(){
      global $conn;
      $this->conn = $conn;
   }

   public function create($id_supplier, $fullname, $phone, $address, $description) {

      // Verificar si el proveedor ya existe
      $validation = $this->readById($id_supplier);
      if ($validation) {
          return [
            'status' => 'Error',
            'message' => 'El proveedor ya existe'
         ];
      }
      
      //consulta para insertar nuevo  proveedor
      $query = "INSERT INTO suppliers (id_supplier, fullname, phone, address, description) VALUES (?, ?, ?, ?, ?)";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("isiss", $id_supplier, $fullname, $phone, $address, $description); 

      if ($stmt->execute()) {
      //validar la creación del proveedor
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
      $query = "SELECT id_supplier, fullname, phone, address, description FROM suppliers WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id_supplier);
      $stmt->execute();
      $result = $stmt->get_result();
      $supplier = $result->fetch_assoc();
      $stmt->close(); 
      return $supplier;
   }

   // Consulta para actualizar los datos del proveedor
   public function update($id_supplier, $fullname, $phone, $address, $description) {
   
   $query = "UPDATE suppliers SET fullname = ?, phone = ?, address = ?, description = ? WHERE id_supplier = ?";
   $stmt = $this->conn->prepare($query);
   $stmt->bind_param("sissi", $fullname, $phone, $address, $description,  $id_supplier);
   
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