response
<?php
   require_once '../models/test.php';

   $test = new TestModel();

$request = $_SERVER['REQUEST_METHOD'];
$response;
if($request == 'GET'){
   
   if(isset($_GET['id'])){
      $id = $_GET['id'];
      $response = $test->readById($id);
   }else{
      $response = $test->readAll();
   }
   echo json_encode($response);
}

if($request == 'POST'){
   $data = json_decode(file_get_contents('php://input'), true);
   $response = $test->create($data['name']);
   echo json_encode($response);
}

if($request == 'PUT'){
   $data = json_decode(file_get_contents('php://input'), true);
   $id = $_GET['id'];
   $response = $test->update($id, $data['name']);
   echo json_encode($response);
}  

if($request == 'DELETE'){
   $id = $_GET['id'];
   $response = $test->delete($id);
   echo json_encode($response);
}


?>