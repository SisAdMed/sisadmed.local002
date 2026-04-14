<div class="card-body text-xs">
    <input type="hidden" id="id" name="id" value="<?= $r->id_cot ?? '' ?>">
    <div class="row text-xs">
        <div class="form-group col-md-6 col-sm-6 col-xs-12 text-xs">
            <label for="id_emp" class="text-xs">Empresa <span class="required">*</span></label>
            <select name="id_emp" id="id_emp" class="custom-select rounded-0 text-xs" required>
            </select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="fuente" class="text-xs">Fuente</label>
            <select name="fuente" id="fuente" class="form-control text-xs"></select>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12 text-xs">
            <label for="origen" class="text-xs">Origen</label>
            <select name="origen" id="origen" class="form-control select2 select2bs4 text-xs"></select>
        </div>
    </div>
    <div class="row text-xs">
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="id_tdo" class="text-xs">Tipo <span class="required">*</span></label>
            <select name=" id_tdo" id="id_tdo" class="custom-select rounded-0 text-xs" readonly></select>
        </div>

        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="num_tdo" class="text-xs">Número <span class="required">*</span></label>
            <input type="number" class="form-control text-right text-xs" id="num_tdo" name="num_tdo" readonly >
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="fecha_comp" class="text-xs">Fecha <span class="required">*</span></label>
            <input type="date" id="fecha_venci" name="fecha_venci" hidden>
            <input type="date" class="form-control text-xs" id="fecha_comp" name="fecha_comp">
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
        <!-- <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="id_des_enca" class="text-xs">Descuento</label>
            <select name="id_des_enca" id="id_des_enca" class="form-control select2 select2bs4 text-xs" style="width:100%">
            </select>
        </div> -->
    </div>
    <div class="row text-xs">
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="id_moneda" class="text-xs">Moneda</label>
            <select name="id_moneda" id="id_moneda" class="custom-select rounded-0 text-xs" required></select>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="tasa_cambio" class="text-xs">Tasa</label>
            <input type="text" class="form-control text-right text-xs" name="tasa_cambio" id="tasa_cambio" readonly>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-xs">
            <label for="id_vend" class="text-xs">Vendedor</label>
            <select name="id_vend" id="id_vend" class="custom-select rounded-0 text-xs" required></select>
        </div>
        <!--Moneda Foaranea-->
        <div class="form-group col-md-1 col-sm-1 col-xs-12 text-center foranea text-xs text-xs">
            <label for="sub_total" class="text-xs">Sub-Total</label>
            <input type="text" name="sub_total" id="sub_total" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right foranea text-xs">
            <label for="iva" class="text-xs">IVA</label>
            <input type="text" name="iva" id="iva" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right foranea text-xs">
            <label for="total_frm" class="text-xs">Total</label>
            <input type="text" name="total_frm" id="total_frm" class="form-control text-right text-xs" readonly>
        </div>
        <!--Moneda Foaranea mostrando Moneda local-->
        <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right foranea text-xs">
            <label for="sub_totalBs" class=" text-xs">Sub-Total Bs.</label>
            <input type="text" name="sub_totalBs" id="sub_totalBs" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right foranea text-xs">
            <label for="ivaBs" class=" text-xs">IVA Bs.</label>
            <input type="text" name="ivaBs" id="ivaBs" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-1 col-sm-1 col-xs-12 text-right foranea text-xs">
            <label for="total_frmBs" class=" text-xs">Total Bs.</label>
            <input type="text" name="total_frmBs" id="total_frmBs" class="form-control text-right text-xs" readonly>
        </div>
        <!--Moneda Local-->
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right local text-xs">
            <label for="sub_totall" class=" text-xs">Sub-Total</label>
            <input type="text" name="sub_totall" id="sub_totall" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right local text-xs">
            <label for="ival" class=" text-xs">IVA</label>
            <input type="text" name="ival" id="ival" class="form-control text-right text-xs" readonly>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12 text-right local text-xs">
            <label for="total_frml" class=" text-xs">Total</label>
            <input type="text" name="total_frml" id="total_frml" class="form-control text-right text-xs" readonly>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4 col-sm-4 col-xs-12 text-xs">
            <label for="oc_cliente">OC del Cliente</label>
            <input type="text" name="oc_cliente" id="oc_cliente" class="form-control text-xs">
        </div>
         <div class="form-group col-md-4 col-sm-4 col-xs-12 text-xs">
            <label for="descrip_cot">Notas</label>
            <textarea class="form-control" name="descrip_cot" id="descrip_cot"></textarea>
        </div>
        <div class="form-group col-md-4 col-sm-4 col-xs-12 text-xs">
            <label for="nro_control">Nro. Control</label>
            <input type="text" name="nro_control" id="nro_control" class="form-control text-xs text-right" readonly>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12 text-xs" >
            <table id="tblDetalle" name="tblDetalle" class="display responsive nowrap table table-hover text-xs" style="width:100%">
                <thead>
                    <th class="text-right">Item</th>
                    <th>Descripción</th>
                    <th class="text-right">Cantidad</th>
                    <?php if(!isset($r->id_cot)) : ?>
                        <th class="text-right">Stock</th>
                    <?php endif ?>
                    <th class="text-right">Uni.Vta</th>
                    <th class="text-right">PVP Uni.</th>
                    <th class="text-right">Precio Vta</th>
                    <th class="text-right" hidden>Dcto.</th>
                    <th class="text-center">IVA</th>
                    <th class="text-right">Sub-Total</th>
                    <th class="text-center">Acción</th>
                </thead>
                <tbody id="cuerpoTablaDetalle" name="cuerpoTablaDetalle" class="text-xs">
                </tbody>
                <tfooter>
                  <th class="text-right">Item</th>
                    <th>Descripción</th>
                    <th class="text-right">Cantidad</th>
                    <?php if(!isset($r->id_cot)) : ?>
                        <th class="text-right">Stock</th>
                    <?php endif ?>
                    <th class="text-right">Uni.Vta</th>
                    <th class="text-right">PVP Uni.</th>
                    <th class="text-right">Precio Vta</th>
                    <th class="text-right" hidden>Dcto.</th>
                    <th class="text-center">IVA</th>
                    <th class="text-right">Sub-Total</th>
                    <th class="text-center">Acción</th>
                </tfooter>
            </table>
        </div>
    </div>
     <div class="center text-xs">
        <button type="button" class="btn btn-primary btn-sm text-xs" onclick="agregarDetalleFactura();">+ Agregar Detalle</button>
    </div>
</div>
<!-- Ventana modal para ver las fotos de los productos -->
<div class="modal fade" id="modal-showpicture" aria-hidden="true" aria-labelledby="myExtraLargeModalLabel1" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-product"></h5>
                <button type="button" class="close" data-dismiss="modal" arial-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="imgPreview">
                </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para listar y buscar clientes -->
<div class="modal fade" id="modal-clientes">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar y seleccionar Clientes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12" id="listar_entidad_modal" name="listar_entidad_modal">
                    <table id="tblModal" name="tblModal" class="display responsive nowrap table table-hover" style="width:100%">
                         <thead>
                            <tr>
                                <th>Id</th>
                                <th>Rif</th>
                                <th>Cliente</th>
                                <th>Zona</th>
                                <th>Vendedor</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal para listar y buscar productos -->
<div class="modal fade" id="modal-productos">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar y seleccionar Productos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12" id="listar_product_modal" name="listar_product_modal">
                    <table id="tblModalProd" name="tblModalProd" class="display responsive nowrap table table-hover" style="width:100%">
                         <thead>
                            <tr>
                                <th>Id</th>
                                <th>Código</th>
                                <th>Código 2</th>
                                <th>Descripción</th>
                                <th>Referencia</th>
                                <th>Marca</th>
                                <th class="text-right">Stock</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-descuentos">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar y seleccionar Descuentos</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12" id="listar_discount_modal" name="listar_discount_modal">
                    <table id="tblModalDcto" name="tblModalDcto" class="display responsive nowrap table table-hover" style="width:100%">
                         <thead>
                            <tr>
                                <th>Id</th>
                                <th>Descripción</th>
                                <th class="text-right">Dcto</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>