<?php ?>
<div class="page-empleados">
    <div class="container-header-empleados">
        <div class="content-info-empleados">
            <div class="content-title-empleados">
            Empleados
            </div>
            <div class="content-description-empleados">
            El módulo de empleados gestiona la información y permisos de los usuarios que interactúan con el sistema, 
            permitiendo registrar, autenticar y administrar sus datos de manera segura. Facilita la creación, modificación, 
            eliminación y consulta de usuarios, y controla el acceso según roles asignados.
            </div>
        </div>
        <div class="content-counter-empleados">
            <div class="content-info-counter-empleados">
                <div class="info-counter-empleados">
                    <div class="icono-counter-empleados">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="number-counter-empleados">
                        20
                    </div>
                </div>
                <div class="title-counter-empleados">
                    Empleados Totales
                </div>
            </div>
        </div>
    </div>

    <div class="container-table-empleados">
        <div class="table-empleados">
            <?php require('./components/Table/Table.empleados.php') ?>   
        </div>
    </div>
</div>