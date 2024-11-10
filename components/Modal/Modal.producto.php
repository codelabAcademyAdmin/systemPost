<link rel="stylesheet" href="./assets/css/modal.producto.css">
<div class="content-modal" style="display: none;" >
    <div class="content-modal-container">
        <div class="content-form">
            <div class="header-modal">
            <div class="content-title">
                <div class="title">Agregar Proveedor</div>
                <div class="close-modal" onclick='toggleModal()' >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="cancel-icon-modal">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>
        <div class="content-info">
            <form id="product-form">
               <div  class="container-form-modal">
                  <input type="int" placeholder="Id Producto" name="id_producto " class="input-modal-product">
                  <input type="text" placeholder="Descriptión" name="descriptión" class="input-modal-product">
                  <input type="text" placeholder="Nombre" name="Nombre" class="input-modal-product">
                  <input type="num" placeholder="Stock" name="stock" class="input-modal-product">
               </div>
               <div class="container-form-modal">
                  <input type="num" placeholder="Precio" name="precio" class="input-modal-product">
                  <input type="text" placeholder="Categoria" name="categoria" class="input-modal-product">
                  <input type="date" placeholder="Fecha de Actualización" name="descripcion" class="input-modal-product">
               </div>
            </form>
        </div>
        <div class="content-buttons-product">
            <div class="btn-modal button-cancel-product" onclick='toggleModal()'>Cancelar</div>
            <div class="btn-modal button-confirm-product">Agregar</div>
            </div>
        </div>
    </div>
</div>