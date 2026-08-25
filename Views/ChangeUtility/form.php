<div class="card_body">
    <input type="hidden" name="id" id="id" value=<?= $valor ?? ''?>>    
    <div class="row">
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="fecha">Fecha</label>
            <input type="datetime-local" step="1" class="form-control text-xs" id="fecha" name="fecha" required>
        </div>
        <div class="form-group col-md-3 col-sm-3 col-xs-12">
            <label for="id_prod">Producto</label>
            <input type="hidden" name="id_prod" id="id_prod" class="text-xs">
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_prod" name="nom_prod" readonly>
                <div class="input-group-append">
                    <span class="input-group-text text-xs">
                        <a href="#" id="btn-buscar-prod" data-toggle="modal" data-target="#modal-productos" title="Buscar y seleccionar productos"><i class="fas fa-search text-xs"></i></a>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="id_fab">Marca</label>
            <select name="id_fab" id="id_fab" class="select2 select2bs4 text-xs" multiple style="width:100%"></select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="utilidad">Nueva Utilidad Recargo</label>
            <div class="input-group">
                <input type="text" id="utilidad" name="utilidad" class="form-control camponumero text-right" placeholder="0,00" required>
                <!-- Icono a la derecha del input -->
                <!-- Botón con icono a la derecha -->
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="btnChangeUtility" title="Calcular cambio de Utilidad">
                        <i class="fas fa-calculator"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Estatus</label>
            <select class="form-control text-xs custom-select rounded-0" id="status" name="status" required></select>
        </div>
    </div>
</div>
<div class="card_footer">
    <div id="contenedor-tabla" class="table-responsive"></div>
</div>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Productos/modal_Productos.php'; ?>