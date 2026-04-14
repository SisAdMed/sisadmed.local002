<!-- Modal para listar y buscar Descuentos -->
<div class="modal fade" id="modal-descuentos">
    <div class="modal-dialog modal-md" >
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