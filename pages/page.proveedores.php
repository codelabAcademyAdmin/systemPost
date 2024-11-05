<?php ?>
<div class="page-proveedores">
    <div class="container-header-proveedores">
        <div class="content-info-proveedores">
            <div class="content-title-proveedores">
                Proveedores
            </div>
            <div class="content-description-proveedores">
                El módulo de proveedores gestiona la información y relaciones de los proveedores, permitiendo su registro, modificación, eliminación y consulta. Facilita el seguimiento de productos y servicios, así como la gestión de contratos, asegurando un control adecuado de la cadena de suministro.
            </div>
            <div class="container-create-button">
                <button class="create-button" onclick="toggleModal()" >+ Crear</button>
            </div>

        </div>
        <div class="content-counter-proveedores">
            <div class="content-info-counter-proveedores">
                <div class="info-counter-proveedores">
                    <div class="icono-counter-proveedores">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="number-counter-proveedores">
                        30
                    </div>
                </div>
                <div class="title-counter-proveedores">
                    Proveedores Totales
                </div>
            </div>
        </div>
    </div>

    <div class="container-table-proveedores">
        <div class="table-proveedores">
            <?php require('./components/Table/Table.proveedores.php') ?>   
        </div>
    </div>
</div>

<?php require('./components/Modal/Modal.proveedor.php') ?>