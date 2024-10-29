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
            ],
            [
                'id' => 6,
                'nombre' => 'Tablet Android',
                'descripcion' => 'Tablet de 10 pulgadas con 64GB de almacenamiento',
                'precio' => 299.99,
                'cantidad' => 150,
                'activo' => true,
                'fecha_creacion' => '05/03/2024'
            ],
            [
                'id' => 7,
                'nombre' => 'Consola de Videojuegos',
                'descripcion' => 'Consola de última generación con 1TB de almacenamiento',
                'precio' => 499.99,
                'cantidad' => 80,
                'activo' => true,
                'fecha_creacion' => '20/02/2024'
            ],
            [
                'id' => 8,
                'nombre' => 'Impresora Multifuncional',
                'descripcion' => 'Impresora, escáner y copiadora con WiFi',
                'precio' => 179.99,
                'cantidad' => 60,
                'activo' => false,
                'fecha_creacion' => '15/01/2024'
            ],
            [
                'id' => 9,
                'nombre' => 'Monitor 4K',
                'descripcion' => 'Monitor de 27 pulgadas con resolución 4K',
                'precio' => 349.99,
                'cantidad' => 40,
                'activo' => true,
                'fecha_creacion' => '01/03/2024'
            ],
            [
                'id' => 10,
                'nombre' => 'Teclado Mecánico',
                'descripcion' => 'Teclado gaming con switches Cherry MX',
                'precio' => 129.99,
                'cantidad' => 100,
                'activo' => true,
                'fecha_creacion' => '10/02/2024'
            ],
            [
                'id' => 11,
                'nombre' => 'Disco Duro Externo',
                'descripcion' => 'Disco duro portátil de 2TB con USB 3.0',
                'precio' => 89.99,
                'cantidad' => 200,
                'activo' => true,
                'fecha_creacion' => '25/02/2024'
            ],
            [
                'id' => 12,
                'nombre' => 'Altavoz Bluetooth',
                'descripcion' => 'Altavoz portátil resistente al agua',
                'precio' => 79.99,
                'cantidad' => 120,
                'activo' => true,
                'fecha_creacion' => '05/01/2024'
            ],
            [
                'id' => 13,
                'nombre' => 'Router WiFi',
                'descripcion' => 'Router de doble banda con tecnología WiFi 6',
                'precio' => 149.99,
                'cantidad' => 75,
                'activo' => false,
                'fecha_creacion' => '18/02/2024'
            ],
            [
                'id' => 14,
                'nombre' => 'Webcam HD',
                'descripcion' => 'Cámara web 1080p con micrófono integrado',
                'precio' => 59.99,
                'cantidad' => 150,
                'activo' => true,
                'fecha_creacion' => '22/01/2024'
            ],
            [
                'id' => 15,
                'nombre' => 'Batería Externa',
                'descripcion' => 'Powerbank de 20000mAh con carga rápida',
                'precio' => 49.99,
                'cantidad' => 180,
                'activo' => true,
                'fecha_creacion' => '28/02/2024'
            ],
            [
                'id' => 16,
                'nombre' => 'Tarjeta Gráfica',
                'descripcion' => 'GPU de gama alta para gaming y diseño',
                'precio' => 699.99,
                'cantidad' => 30,
                'activo' => true,
                'fecha_creacion' => '12/03/2024'
            ],
            [
                'id' => 17,
                'nombre' => 'Silla Gaming',
                'descripcion' => 'Silla ergonómica con soporte lumbar',
                'precio' => 199.99,
                'cantidad' => 50,
                'activo' => true,
                'fecha_creacion' => '08/01/2024'
            ],
            [
                'id' => 18,
                'nombre' => 'Proyector HD',
                'descripcion' => 'Proyector portátil con resolución 1080p',
                'precio' => 279.99,
                'cantidad' => 35,
                'activo' => false,
                'fecha_creacion' => '03/03/2024'
            ],
            [
                'id' => 19,
                'nombre' => 'Smartband',
                'descripcion' => 'Pulsera inteligente con monitor de actividad',
                'precio' => 39.99,
                'cantidad' => 250,
                'activo' => true,
                'fecha_creacion' => '17/02/2024'
            ],
            [
                'id' => 20,
                'nombre' => 'Adaptador USB-C',
                'descripcion' => 'Hub multipuerto con HDMI y lector de tarjetas',
                'precio' => 69.99,
                'cantidad' => 100,
                'activo' => true,
                'fecha_creacion' => '26/01/2024'
            ],
            [
                'id' => 21,
                'nombre' => 'Adaptador USB-C',
                'descripcion' => 'Hub multipuerto con HDMI y lector de tarjetas',
                'precio' => 69.99,
                'cantidad' => 100,
                'activo' => true,
                'fecha_creacion' => '26/01/2024'
            ],
            [
                'id' => 22,
                'nombre' => 'Adaptador USB-C',
                'descripcion' => 'Hub multipuerto con HDMI y lector de tarjetas',
                'precio' => 69.99,
                'cantidad' => 100,
                'activo' => true,
                'fecha_creacion' => '26/01/2024'
            ]

        ];



        $productosEnCarrito = [
            [
                'nombre' => 'Helado vainilla',
                'precio' => 1.000,
                'cantidad' => 123
            ],
            [
                'nombre' => 'Hamburguesa mix',
                'precio' => 8.000,
                'cantidad' => 13
            ],
            [
                'nombre' => 'Pizza familiar',
                'precio' => 12.000,
                'cantidad' => 5
            ],
            [
                'nombre' => 'Papas fritas',
                'precio' => 3.500,
                'cantidad' => 8
            ],
            [
                'nombre' => 'Refresco cola',
                'precio' => 2.000,
                'cantidad' => 15
            ],
            [
                'nombre' => 'Hot dog especial',
                'precio' => 5.000,
                'cantidad' => 10
            ],
            [
                'nombre' => 'Ensalada César',
                'precio' => 6.500,
                'cantidad' => 3
            ],
            [
                'nombre' => 'Nuggets (12 unidades)',
                'precio' => 7.000,
                'cantidad' => 7
            ],
            [
                'nombre' => 'Batido chocolate',
                'precio' => 4.000,
                'cantidad' => 9
            ],
            [
                'nombre' => 'Wrap de pollo',
                'precio' => 6.000,
                'cantidad' => 4
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


    function getPaginationRange($paginaActual, $totalPaginas, $maxPaginas = 3){
        $mitad = floor($maxPaginas / 2);
        $inicio = max(1, min($paginaActual - $mitad, $totalPaginas - $maxPaginas + 1));
        $fin = min($totalPaginas, $inicio + $maxPaginas - 1);
        return range($inicio, $fin);
    }

    $paginasAMostrar = getPaginationRange($paginaActual, $totalPaginas, 3);


    function formatText($text){
        if ($text == null) {
            return $text;
        }
        if (strlen($text) > 50) {
            return substr($text, 0, 30) . '...';
        }
        return $text;
    };

?>

<div class="content-sales">
    <div class="header-sales">Realizar una venta</div>
    <div class="content-info">
        <div class="content-1">
            <div class="container">
                <div class="table-container table-sales">
                    <table class="product-table">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productosEnPagina as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><?php echo $producto['nombre']; ?></td>
                                <td><?php echo $producto['cantidad'] ?></td>
                                <td><?php echo $producto['precio']; ?></td>
                                <td class="text-center acciones">
                                    <button class="btn-accions">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v2.25H9a.75.75 0 0 0 0 1.5h2.25V15a.75.75 0 0 0 1.5 0v-2.25H15a.75.75 0 0 0 0-1.5h-2.25V9Z" clip-rule="evenodd" />
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

        </div>
        <div class="content-2">
            <div class="content-2-header">Detalle de venta</div>
            <div class="product-info">
                <table class="sale-details-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio $</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($productosEnCarrito as $producto): ?>
                <tr>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td><?php echo number_format($producto['precio'], 3, '.', ','); ?></td>
                    <td class="action-buttons">
                            <button class="decrease-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                                    <circle cx="12" cy="12" r="10" fill="#5555AD"/>
                                    <path d="M7 12h10" stroke="white" stroke-width="2"/>
                                </svg>
                            </button>
                            <span class="quantity"><?php echo $producto['cantidad']; ?></span>
                            <button class="increase-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                                <circle cx="12" cy="12" r="10" fill="#5555AD"/>
                                    <path d="M12 7v10M7 12h10" stroke="white" stroke-width="2"/>
                                    </svg>  
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="content-info-total">
                <div class="content-2-total">
                    <div class="content-subtotal">
                        <div class="">Subtotal</div>
                        <div class="">$21.000</div>
                    </div>
                    <div class="content-iva">
                        <div class="">Iva</div>
                        <div class="">0.0</div>
                    </div>
                    <div class="content-total">
                        <div class="">Total</div>
                        <div class="">$21.000</div>
                    </div>
                </div>
                <div class="content-2-button">
                    <button  class="btn-cancelar btn">Cancelar</button>
                    <button  class="btn-confirmar btn">Confirmar Venta</button>
                </div>
            </div>
        </div>
    </div>
</div>
