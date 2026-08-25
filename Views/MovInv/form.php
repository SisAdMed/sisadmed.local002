<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title text-sm font-weight-bold">Movimiento de Inventario</h3>
            </div>
            <div class="card-body">
                <input type="text" id="id" name="id" value="<?= $r['id_movinv'] ?? '' ?>" hidden>
                <input type="text" name="item" id="item" hidden>

                <!-- Sección Cabecera del Movimiento -->
                <div class="row">
                    <div class="form-group col-md-4 col-sm-4 col-xs-12">
                        <label for="id_emp">Empresa <span class="text-danger">*</span></label>
                        <select class="form-control custom-select rounded-0 text-xs" name="id_emp" id="id_emp"></select>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="id_tmovinv">Tipo de Movimiento</label>
                        <select class="form-control custom-select rounded-0 text-xs" name="id_tmovinv" id="id_tmovinv"></select>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="num_movinv">Número</label>
                        <input type="text" class="form-control text-right text-xs" name="num_movinv" id="num_movinv">
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="fecha_comp">Fecha</label>
                        <input type="date" class="form-control text-right text-xs" name="fecha_comp" id="fecha_comp">
                    </div>
                    <select class="form-control custom-select rounded-0 text-xs" name="id_moneda" id="id_moneda" style="display: none;"></select>
                    <input type="hidden" class="form-control text-right text-xs" name="tasa_cambio" id="tasa_cambio">
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="id_alm">Almacén</label>
                        <select class="form-control custom-select rounded-0 text-xs" name="id_alm" id="id_alm"></select>
                    </div>
                </div>                
                <div class="row">
                    <div class="form-group col-md-10 col-sm-10 col-xs-12">
                        <label for="descrip_movinv">Descripción</label>
                        <textarea id="descrip_movinv" name="descrip_movinv" class="form-control text-xs" rows="2"></textarea>
                    </div>
                    <div class="form-group col-md-2 col-sm-2 col-xs-12">
                        <label for="status">Estatus</label>
                        <select class="form-control custom-select rounded-0 text-xs" name="status" id="status"></select>
                    </div>
                </div>

                <hr class="my-3">

                <!-- Sección Detalle del Movimiento -->
                <div class="row mb-2">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h5 class="text-sm font-weight-bold text-muted mb-0">Detalle de Artículos</h5>
                        <button type="button" id="btnAddRow" name="btnAddRow" class="btn btn-primary btn-sm text-xs">
                            <i class="fas fa-plus mr-1"></i> Agregar nuevo registro
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-12">
                        <table class="display responsive nowrap compact table table-hover text-xs tblEncaMov" style="width:100%;">                 
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Productos/modal_Productos.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Ubicaciones/modal_Ubicaciones.php';
?>