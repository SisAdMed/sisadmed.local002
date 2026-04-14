<!-- Modal para listar y buscar Conceptos de Cuentas por Cobrar-->
<div class="modal fade" id="modal-ConcepCXC">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar y seleccionar Conceptos de Cuentas por Cobrar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12 col-sm-12 col-xs-12" id="listar_ConceptoCXC_modal" name="listar_ConceptoCXC_modal">
                    <table id="tblModalConcepCXC" name="tblModalConcepCXC" class="display responsive nowrap table table-hover" style="width:100%">
                         <thead>
                            <tr>
                                <th>Id</th>
                                <th>Código</th>
                                <th>Descripción</th>
                                <!-- <th>Cuenta Contable</th>
                                <th>Auxiliar Contable</th> -->
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