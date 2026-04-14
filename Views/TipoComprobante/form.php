<div class="card-body">
    <input type="hidden" name="id" value="<?php echo $r->id_tipcom  ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="codigo_tipcom">Tipo Comprobante <span class="required">*</span></label>
            <input autofocus type="text" class="form-control" id="codigo_tipcom" name="codigo_tipcom" placeholder="Tipo de Comprobante" onkeyup="mayusculas(this)" <?php echo !empty($r->codigo_tipcom) ? 'readonly' : ''?> required title="Código alfanumérico de 2 digítoTipo de Comprobante" maxlength=2 value="<?php echo $r->codigo_tipcom ?? ''; ?>">
        </div>
        <div class="form-group col-md-8 col-sm-8 col-xs-12">
            <label for="nombre_tipcom">Nombre<span class="required">*</span></label>
            <input autofocus type="text" class="form-control" id="nombre_tipcom" name="nombre_tipcom" placeholder="Ingrese código"  value="<?php echo $r->nombre_tipcom  ?? '' ?>" onkeyup="mayusculas(this);" required>
            <span></span>
        </div>
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="status">Estado </label>
            <select autofocus class="form-control custom-select rounded-0 " id="status" name="status" required>
                <?php
                    status($r->status ?? '');
                ?>
            </select>
        </div>
    </div>
</div>