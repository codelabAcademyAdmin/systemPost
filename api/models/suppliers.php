<?php 
class suppliersModel
{
   private $conn;

   public function __construct(){
      global $conn;
      $this->conn = $conn;
   }

   public function create($id_supplier , $fullname, $phone, $address, $description, $id_inventory){
      
      $validation = $this->readById($id_supplier);
      if($validation){
         return [
            'status' => 'Error',
            'message' => 'El proveedor ya existe'
         ];
      }

      $query = "INSERT INTO suppliers (id_supplier, fullname, phone, address, description, id_inventory) VALUES (?, ?, ?, ?, ?, ?)";
        
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("isissi", $id_supplier , $fullname, $phone, $address, $description, $id_inventory);

      if ($stmt->execute()) {

         // Llamar a readById para validar la creación del proveedor
         $validation = $this->readById($id_supplier);

         if ($validation) {
            $stmt->close(); 
            return [
               'status' => 'Success',
               'message' => 'Proveedor creado exitosamente',
               'suppliers' => $validation
            ];
         } else {
            $stmt->close(); 
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
      $query = "SELECT id_supplier, fullname, phone, address, description, id_inventory FROM suppliers WHERE id_supplier = ?";
      $stmt = $this->conn->prepare($query);
      $stmt->bind_param("i", $id_supplier);
      $stmt->execute();
      $result = $stmt->get_result();
      $supplier = $result->fetch_assoc();
      $stmt->close(); 
      return $supplier;
   }

   // Consulta para actualizar los datos de un proveedor
public function update($id_supplier, $fullname, $phone, $address, $description, $id_inventory) {
   
   $query = "UPDATE suppliers SET fullname = ?, phone = ?, address = ?, description = ?, id_inventory = ? WHERE id_supplier = ?";
   $stmt = $this->conn->prepare($query);
   $stmt->bind_param("sissii", $fullname, $phone, $address, $description, $id_inventory, $id_supplier);
   
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