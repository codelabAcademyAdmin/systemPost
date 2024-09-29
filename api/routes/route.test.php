<?php
   $AppRoutes->AddRoutes('GET', 'test', function() {
      require_once 'models/test.php';
      $test = new TestModel();
      $response;
      if(isset($_GET['id'])){
         $id = $_GET['id'];
            $response = $test->readById($id);
      }else{
            $response = $test->readAll();
      }
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('POST', 'test', function() {
      require_once 'models/test.php';
      $test = new TestModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);
      $response = $test->create($data['name']);
      echo json_encode($response);
   });


   $AppRoutes->AddRoutes('PUT', 'test', function() {
      require_once 'models/test.php';
   $test = new TestModel();
      $response;
      $data = json_decode(file_get_contents('php://input'), true);
      $id = $_GET['id'];
      $response = $test->update($id, $data['name']);
      echo json_encode($response);
   });


   $AppRoutes->AddRoutes('DELETE', 'test', function() {
      require_once 'models/test.php';
      $test = new TestModel();
      $response;
      $id = $_GET['id'];
      $response = $test->delete($id);
      echo json_encode($response);
   });
?>