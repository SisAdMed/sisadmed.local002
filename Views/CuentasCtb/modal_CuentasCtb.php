<!-- Modal para listar y buscar Cuentas Contables -->
<div class="modal fade" id="modal-CuentasCtb">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar y seleccionar Cuentas Contables</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12" id="listar_CuentasCtb_modal" name="listar_CuentasCtb_modal">
                    <table id="tblModal_cuentasCTB" name="tblModal_cuentasCTB" class="display responsive nowrap table table-hover" style="width:100%">
                         <thead>
                            <tr>
                                <th>Id</th>
                                <th>Cuenta</th>
                                <th>Descripción</th>
                                <th>Agrupador</th>
                                <th>Auxiliar</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-success" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>