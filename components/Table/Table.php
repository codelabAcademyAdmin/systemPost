<?php
// Datos de prueba
$productos = [
   [
      'id' => 1,
      'nombre' => 'Laptop Ultradelgada',
      'descripcion' => 'Laptop de última generación ',
      'precio' => 1299.99,
      'cantidad' => 50,
      'activo' => true,
      'fecha_creacion' => '01/03/2024'
   ],
   [
      'id' => 2,
      'nombre' => 'Smartphone 5G',
      'descripcion' => 'Teléfono inteligente con cámara de ',
      'precio' => 799.99,
      'cantidad' => 100,
      'activo' => true,
      'fecha_creacion' => '15/02/2024'
   ],
   [
      'id' => 3,
      'nombre' => 'Auriculares Inalámbricos',
      'descripcion' => 'Auriculares con cancelación de ruido',
      'precio' => 199.99,
      'cantidad' => 200,
      'activo' => true,
      'fecha_creacion' => '20/01/2024'
   ],
   [
      'id' => 4,
      'nombre' => 'Smartwatch Deportivo',
      'descripcion' => 'Reloj inteligente con GPS y monitor ',
      'precio' => 249.99,
      'cantidad' => 75,
      'activo' => false,
      'fecha_creacion' => '05/03/2024'
   ],
   [
      'id' => 5,
      'nombre' => 'Cámara DSLR Profesional',
      'descripcion' => 'Cámara de 24MP con lente intercambiable',
      'precio' => 1499.99,
      'cantidad' => 25,
      'activo' => true,
      'fecha_creacion' => '10/02/2024'
   ]
];
?>

<div class="container">
   <div class="table-container">
      <table class="product-table">
         <thead>
            <tr>
               <th>ID</th>
               <th>Nombre</th>
               <th>Descripción</th>
               <th>Precio</th>
               <th>Cantidad</th>
               <th>Activo</th>
               <th>Fecha de Creación</th>
               <th>Acciones</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($productos as $producto): ?>
               <tr>
                  <td><?php echo $producto['id']; ?></td>
                  <td><?php echo $producto['nombre']; ?></td>
                  <td><?php echo $producto['descripcion']; ?></td>
                  <td><?php echo $producto['precio']; ?></td>
                  <td class="text-center"><?php echo $producto['cantidad']; ?></td>
                  <td class="text-center">
                     <span class="<?php echo $producto['activo'] ? 'status-active' : 'status-inactive'; ?>">
                        <?php echo $producto['activo'] ? 'Sí' : 'No'; ?>
                     </span>
                  </td>
                  <td class="text-center"><?php echo $producto['fecha_creacion']; ?></td>
                  <td class="text-center acciones">
                     <button class="btn-edit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                           <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                           <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                        </svg>
                     </button>
                     <button class="btn-delete">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                           <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                        </svg>
                     </button>
                     <button class="btn-view">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                           <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                           <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd" />
                        </svg>
                     </button>
                  </td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>

   <style>
      .container {
         display: flex;
         justify-content: center;
         align-items: center;
         height: calc(100vh - 100px);
      }

      .table-container {
         /* min-width: 100%; */
         max-width: 1200px;
         overflow-y: auto;
         overflow-x: auto;
         max-height: 500px;
         box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
         margin-top: 8px;
         border-radius: 8px;
      }

      .product-table {
         min-width: 100%;
         table-layout: auto;
         background-color: white;
         border-radius: 8px;
      }

      .product-table thead tr {
         background-color: #e5e7eb;
         text-align: left;
         color: #4b5563;
         text-transform: uppercase;
         font-size: 14px;
         line-height: 20px;
      }

      .product-table th {
         padding: 8px 24px;
      }

      .product-table td {
         padding: 12px 24px;
      }

      .product-table tbody tr {
         border-bottom: 1px solid #e5e7eb;
      }

      .product-table tbody tr:hover {
         background-color: #f3f4f6;
      }

      .text-center {
         text-align: center;
      }

      .status-active {
         background-color: #99A5E0;
         color: white;
         padding: 8px 16px;
         border-radius: 9999px;
         font-size: 14px;
      }

      .status-inactive {
         background-color: #fecaca;
         color: #b91c1c;
         padding: 4px 12px;
         border-radius: 9999px;
         font-size: 12px;
      }

      .acciones {
         display: flex;
         flex-direction: row;
         gap: 6px;
      }

      .icon {
         width: 24px;
         height: 24px;
      }

      .btn-edit,
      .btn-delete,
      .btn-view {
         width: 40px;
         height: 40px;
         border-radius: 9999px;
         border: none;
         cursor: pointer;
         background-color: #E6EBF9;
         display: flex;
         justify-content: center;
         align-items: center;
         color: #6668C5;
         transition: all 0.3s ease;
      }

      .btn-delete:hover,
      .btn-edit:hover,
      .btn-view:hover {
         background-color: #6668C5;
         color: #E6EBF9;
      }
   </style>