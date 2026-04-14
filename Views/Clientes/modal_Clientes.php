<!-- Modal para listar y buscar clientes -->
<div class="modal fade" id="modal-clientes" tabindex="-1" aria-labelledby="miModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar y seleccionar Clientes</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12" id="listar_entidad_modal" name="listar_entidad_modal">
                    <table id="tblModalClientes" name="tblModalClientes" class="display responsive nowrap table table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Rif</th>
                                <th>Cliente</th>
                                <th>Zona</th>
                                <th>Vendedor</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>