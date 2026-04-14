<div class='card-body'>
    <input type='hidden' id='item' name='item' min="1">
    <input type='hidden' id='id_empr' name='id_empr' value = "<?php echo $r[0]->id_emp ?? '' ?>">
    <input type='hidden' id='id_entr' name='id_entr' value = "<?php echo $r[0]->id_ent ?? '' ?>">
    <input type='hidden' id='fechar' name='fechar' value = "<?php echo $r[0]->fecha ?? '' ?>">
    <div class='row'>
        <div class='form-group col-md-4 col-sm-4 col-xs-12'>
            <label for='id_emp'>Empresa <span>*</span></label>
            <select name='id_emp' id='id_emp' class='form-control text-xs'>
            </select>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12 text-xs">
            <label for="" class="text-xs">Nombre de Cliente <span class="required">*</span></label>
            <input type="hidden" id="id_cli" name="id_cli">
            <div class="input-group">
                <input type="text" class="form-control text-xs" id="nom_cli" name="nom_cli" readonly>
                <div class="input-group-append text-xs">
                    <span class="input-group-text  text-xs"><a href="#" data-toggle="modal" data-target="#modal-clientes" title="Buscar y seleccionar cliente"><i class="fas fa-search text-xs"></i></a></span>
                </div>
            </div>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="fecha" class="text-xs">Fecha <span class="required">*</span></label>
            <input type="date" class="form-control text-xs" id="fecha" name="fecha">
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12 text-xs">
            <label for="format" class="text-xs">Formato <span class="required">*</span></label>
            <input type="number" class="form-control text-xs text-right" id="format" name="format">
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12 text-xs">
            <label for="status" class="text-xs">Status <span class="required">*</span></label>
            <select id="status" name="status" class="form-control text-xs"></select>
        </div>
    </div>
    <div class="row">
    <div class="form-group col-md-12 col-sm-12 col-xs-12 text-xs" >
            <table id="tblDetalle" name="tblDetalle" class="display responsive nowrap table table-hover text-xs" style="width:100%">
                <thead>
                    <th class="text-right">Item</th>
                    <th>Código Producto Cliente</th>
                    <th>Código Producto</th>
                    <th class="text-center">Acción</th>
                </thead>
                <tbody id="cuerpoTablaDetalle" name="cuerpoTablaDetalle" class="text-xs">
                </tbody>
                <tfooter>
                    <th class="text-right">Item</th>
                    <th>Código Producto Cliente</th>
                    <th>Código Producto</th>
                    <th class="text-center">Acción</th>
                </tfooter>
            </table>
            <div class="center text-xs">
                <button type="button" class="btn btn-primary btn-sm text-xs" onclick="agregarProductos();">+ Agregar</button>
            </div>
        </div>
    </div>
</div>
<div class='loader'
    <img src='<?= IMG . '/ajax-loading.gif'  ?>' />
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/Views/Clientes/modal_Clientes.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/Views/Productos/modal_Productos.php';