<!-- Modal para listar y buscar productos -->
<div class="modal fade" id="modal-productos">
    <div class="modal-dialog modal-xl">
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
                                <th>Lote</th>
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