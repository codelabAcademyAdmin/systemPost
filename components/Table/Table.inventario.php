<?php
// Datos de prueba
$productos = [
   [
      'id' => 1,
      'nombre' => 'Laptop Ultradelgada',
      'descripcion' => 'Laptop de última generación ',
      'precio' => 1299.99,
      'cantidad' => 50,
      'categoria' => 'Computación',
      'fecha_creacion' => '01/03/2024'
   ],
   [
      'id' => 2,
      'nombre' => 'Smartphone 5G',
      'descripcion' => 'Teléfono inteligente con cámara de ',
      'precio' => 799.99,
      'cantidad' => 100,
      'categoria' => 'Telefonía',
      'fecha_creacion' => '15/02/2024'
   ],
   [
      'id' => 3,
      'nombre' => 'Auriculares Inalámbricos',
      'descripcion' => 'Auriculares con cancelación de ruido',
      'precio' => 199.99,
      'cantidad' => 200,
      'categoria' => 'Audio',
      'fecha_creacion' => '20/01/2024'
   ],
   [
      'id' => 4,
      'nombre' => 'Smartwatch Deportivo',
      'descripcion' => 'Reloj inteligente con GPS y monitor ',
      'precio' => 249.99,
      'cantidad' => 75,
      'categoria' => 'Dispostivos portátiles',
      'fecha_creacion' => '05/03/2024'
   ],
   [
      'id' => 5,
      'nombre' => 'Cámara DSLR Profesional',
      'descripcion' => 'Cámara de 24MP con lente intercambiable',
      'precio' => 1499.99,
      'cantidad' => 25,
      'categoria' => 'Fotografía',
      'fecha_creacion' => '10/02/2024'
   ],
   [
      'id' => 6,
      'nombre' => 'Tablet Android',
      'descripcion' => 'Tablet de 10 pulgadas con 64GB de almacenamiento',
      'precio' => 299.99,
      'cantidad' => 150,
      'categoria' => 'Computación',
      'fecha_creacion' => '05/03/2024'
   ],
   [
      'id' => 7,
      'nombre' => 'Consola de Videojuegos',
      'descripcion' => 'Consola de última generación con 1TB de almacenamiento',
      'precio' => 499.99,
      'cantidad' => 80,
      'categoria' => 'Videojuegos',
      'fecha_creacion' => '20/02/2024'
   ],
   [
      'id' => 8,
      'nombre' => 'Impresora Multifuncional',
      'descripcion' => 'Impresora, escáner y copiadora con WiFi',
      'precio' => 179.99,
      'cantidad' => 60,
      'categoria' => 'Oficina',
      'fecha_creacion' => '15/01/2024'
   ],
   [
      'id' => 9,
      'nombre' => 'Monitor 4K',
      'descripcion' => 'Monitor de 27 pulgadas con resolución 4K',
      'precio' => 349.99,
      'cantidad' => 40,
      'categoria' => 'Computación',
      'fecha_creacion' => '01/03/2024'
   ],
   [
      'id' => 10,
      'nombre' => 'Teclado Mecánico',
      'descripcion' => 'Teclado gaming con switches Cherry MX',
      'precio' => 129.99,
      'cantidad' => 100,
      'categoria' => 'Periféricos',
      'fecha_creacion' => '10/02/2024'
   ],
   [
      'id' => 11,
      'nombre' => 'Disco Duro Externo',
      'descripcion' => 'Disco duro portátil de 2TB con USB 3.0',
      'precio' => 89.99,
      'cantidad' => 200,
      'categoria' => 'Almacenamiento',
      'fecha_creacion' => '25/02/2024'
   ],
   [
      'id' => 12,
      'nombre' => 'Altavoz Bluetooth',
      'descripcion' => 'Altavoz portátil resistente al agua',
      'precio' => 79.99,
      'cantidad' => 120,
      'categoria' => 'Audio',
      'fecha_creacion' => '05/01/2024'
   ],
   [
      'id' => 13,
      'nombre' => 'Router WiFi',
      'descripcion' => 'Router de doble banda con tecnología WiFi 6',
      'precio' => 149.99,
      'cantidad' => 75,
      'categoria' => 'Redes',
      'fecha_creacion' => '18/02/2024'
   ],
   [
      'id' => 14,
      'nombre' => 'Webcam HD',
      'descripcion' => 'Cámara web 1080p con micrófono integrado',
      'precio' => 59.99,
      'cantidad' => 150,
      'categoria' => 'Periféricos',
      'fecha_creacion' => '22/01/2024'
   ],
   [
      'id' => 15,
      'nombre' => 'Batería Externa',
      'descripcion' => 'Powerbank de 20000mAh con carga rápida',
      'precio' => 49.99,
      'cantidad' => 180,
      'categoria' => 'Accesorios',
      'fecha_creacion' => '28/02/2024'
   ],
   [
      'id' => 16,
      'nombre' => 'Tarjeta Gráfica',
      'descripcion' => 'GPU de gama alta para gaming y diseño',
      'precio' => 699.99,
      'cantidad' => 30,
      'categoria' => 'Componentes',
      'fecha_creacion' => '12/03/2024'
   ],
   [
      'id' => 17,
      'nombre' => 'Silla Gaming',
      'descripcion' => 'Silla ergonómica con soporte lumbar',
      'precio' => 199.99,
      'cantidad' => 50,
      'categoria' => 'Muebles',
      'fecha_creacion' => '08/01/2024'
   ],
   [
      'id' => 18,
      'nombre' => 'Proyector HD',
      'descripcion' => 'Proyector portátil con resolución 1080p',
      'precio' => 279.99,
      'cantidad' => 35,
      'categoria' => 'Audio/Video',
      'fecha_creacion' => '03/03/2024'
   ],
   [
      'id' => 19,
      'nombre' => 'Smartband',
      'descripcion' => 'Pulsera inteligente con monitor de actividad',
      'precio' => 39.99,
      'cantidad' => 250,
      'categoria' => 'Dispostivos portátiles',
      'fecha_creacion' => '17/02/2024'
   ],
   [
      'id' => 20,
      'nombre' => 'Adaptador USB-C',
      'descripcion' => 'Hub multipuerto con HDMI y lector de tarjetas',
      'precio' => 69.99,
      'cantidad' => 100,
      'categoria' => 'Accesorios',
      'fecha_creacion' => '26/01/2024'
   ],
   [
      'id' => 21,
      'nombre' => 'Adaptador USB-C',
      'descripcion' => 'Hub multipuerto con HDMI y lector de tarjetas',
      'precio' => 69.99,
      'cantidad' => 100,
      'categoria' => 'Accesorios',
      'fecha_creacion' => '26/01/2024'
   ],
   [
      'id' => 22,
      'nombre' => 'Adaptador USB-C',
      'descripcion' => 'Hub multipuerto con HDMI y lector de tarjetas',
      'precio' => 69.99,
      'cantidad' => 100,
      'categoria' => 'Accesorios',
      'fecha_creacion' => '26/01/2024'
   ]

];


$productosPorPagina = 5;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$totalProductos = count($productos);
$totalPaginas = ceil($totalProductos / $productosPorPagina);

// Asegurarse de que la página actual es válida
$paginaActual = max(1, min($paginaActual, $totalPaginas));

// Calcular el índice de inicio para la página actual
$indiceInicio = ($paginaActual - 1) * $productosPorPagina;

// Obtener los productos para la página actual
$productosEnPagina = array_slice($productos, $indiceInicio, $productosPorPagina);


function getPaginationRange($paginaActual, $totalPaginas, $maxPaginas = 3)
{
   $mitad = floor($maxPaginas / 2);
   $inicio = max(1, min($paginaActual - $mitad, $totalPaginas - $maxPaginas + 1));
   $fin = min($totalPaginas, $inicio + $maxPaginas - 1);
   return range($inicio, $fin);
}

$paginasAMostrar = getPaginationRange($paginaActual, $totalPaginas, 3);


function formatText($text)
{
   if ($text == null) {
      return $text;
   }
   if (strlen($text) > 50) {
      return substr($text, 0, 30) . '...';
   }
   return $text;
};

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
               <th>Categoría</th>
               <th>Fecha de Creación</th>
               <th>Acciones</th>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($productosEnPagina as $producto): ?>
               <tr>
                  <td><?php echo $producto['id']; ?></td>
                  <td><?php echo $producto['nombre']; ?></td>
                  <td><?php echo  formatText($producto['descripcion']) ?></td>
                  <td><?php echo $producto['precio']; ?></td>
                  <td class="text-center"><?php echo $producto['cantidad']; ?></td>
                  <td class="text-center"><?php echo $producto['categoria']; ?></td>
                  <td class="text-center"><?php echo $producto['fecha_creacion']; ?></td>
                  <td class="text-center acciones">
                     <button class="btn-accions view">
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

   <div class="container-pagination">
      <div class="pagination">
         <a href="?pagina=<?php echo max(1, $paginaActual - 1); ?>" class="pagination-arrow <?php echo $paginaActual == 1 ? 'disabled' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-paginator">
               <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-4.28 9.22a.75.75 0 0 0 0 1.06l3 3a.75.75 0 1 0 1.06-1.06l-1.72-1.72h5.69a.75.75 0 0 0 0-1.5h-5.69l1.72-1.72a.75.75 0 0 0-1.06-1.06l-3 3Z" clip-rule="evenodd" />
            </svg>
         </a>
         <?php foreach ($paginasAMostrar as $pagina): ?>
            <a href="?pagina=<?php echo $pagina; ?>" class="pagination-number <?php echo $pagina == $paginaActual ? 'active' : ''; ?>">
               <?php echo $pagina; ?>
            </a>
         <?php endforeach; ?>
         <a href="?pagina=<?php echo min($totalPaginas, $paginaActual + 1); ?>" class="pagination-arrow <?php echo $paginaActual == $totalPaginas ? 'disabled' : ''; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon-paginator">
               <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm4.28 10.28a.75.75 0 0 0 0-1.06l-3-3a.75.75 0 1 0-1.06 1.06l1.72 1.72H8.25a.75.75 0 0 0 0 1.5h5.69l-1.72 1.72a.75.75 0 1 0 1.06 1.06l3-3Z" clip-rule="evenodd" />
            </svg>
         </a>
      </div>
   </div>

</div>
