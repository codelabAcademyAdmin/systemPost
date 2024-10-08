<?php
   $AppRoutes->AddRoutes('GET', 'inventories/ventas', function() {
      require_once 'models/inventories.php';
      $inventories = new inventoriesModel();
      $response;
      if(isset($_GET['id'])){
         $id = $_GET['id'];
            $response = $inventories->readSaleById($id);
      }else{
            $response = $inventories->readSaleAll();
      }
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('GET', 'inventories/productos', function() {
      require_once 'models/inventories.php';
      $inventories = new inventoriesModel();
      $response;
      if(isset($_GET['id'])){
         $id = $_GET['id'];
            $response = $inventories->readProductoById($id);
      }else{
            $response = $inventories->readAllProductos();
      }
      echo json_encode($response);
   });

   $AppRoutes->AddRoutes('GET', 'inventories/provedores', function() {
      require_once 'models/inventories.php';
      $inventories = new inventoriesModel();
      $response;
      if(isset($_GET['id'])){
         $id = $_GET['id'];
            $response = $inventories->readProvedorById($id);
      }else{
            $response = $inventories->readAllProvedores();
      }
      echo json_encode($response);
   });
   ?>