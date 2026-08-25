<div class="modal fade" id="modal-AlmUbi">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Buscar y seleccionar Almacén y Ubicación de Salida y Entrada</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-6 col-sm-6 col-xs-12" id="listar_AlmUbi_modal" name="listar_AlmUbi_modal">
                        <label for="id_alm_sal">Almacén de Salida</label>
                        <select name="id_alm_sal" id="id_alm_sal" class="form-control select2 select-language text-xs"></select>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12" id="listar_AlmUbi_modal" name="listar_AlmUbi_modal">
                        <label for="id_ubi_sal">Ubicación de Salida</label>
                        <select name="id_ubi_sal" id="id_ubi_sal" class="form-control select2 select-language text-xs"></select>
                    </div>
                </div>
                <div class="row hide_entrada">
                    <div class="form-group col-md-6 col-sm-6 col-xs-12" id="listar_AlmUbi_modal" name="listar_AlmUbi_modal">
                        <label for="id_alm_ent">Almacén de Entrada</label>
                        <select name="id_alm_ent" id="id_alm_ent" class="form-control select2 select-language text-xs"></select>
                    </div>
                    <div class="form-group col-md-6 col-sm-6 col-xs-12" id="listar_AlmUbi_modal" select-language" name="listar_AlmUbi_modal">
                        <label for="id_ubi_ent">Ubicación de Entrada</label>
                        <select name="id_ubi_ent" id="id_ubi_ent" class="form-control select2 select-language text-xs"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-success btn-xs" data-dismiss="modal">Cerrar</button>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="hide-in">
                        <label class="form-check-label text-bold">Venta directa</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>