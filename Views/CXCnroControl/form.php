<div class="card-body">
    <input type="hidden" name="id" id="id" value="<?php echo  $r[0]->id_nrocontrol ?? '' ?>">
    <div class="row">
        <div class="form-group col-md-12 col-sm-12 col-xs-12">
            <label for="id_emp">Empresa <span class="required">*</span></label>
            <select autofocus class="form-control custom-select rounded-0" name="id_emp" id="id_emp">
            </select>

        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="ini_nroControl">Número inicial<span class="required">*</span></label>
            <input autofocus type="number" class="form-control text-right" id="ini_nroControl" name="ini_nroControl" placeholder="Ingrese Número de Control Inicial" title="Ingrese Número de Control Inicial">
            <span></span>
        </div>
      <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fin_nroControl">Número final<span class="required">*</span></label>
            <input autofocus type="number" class="form-control text-right" id="fin_nroControl" name="fin_nroControl" placeholder="Ingrese Número de Control Final" title="Ingrese Número de Control Final">
            <span></span>
        </div>
         <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="next_nroControl">Próximo número control<span class="required">*</span></label>
            <input autofocus type="number" class="form-control text-right" id="next_nroControl" name="next_nroControl" placeholder="Ingrese próximo número de control" title="Ingrese próximo número de control">
            <span></span>
        </div>
         <div class="form-group col-md-2 col-sm-2 col-xs-12">
            <label for="fec_asig">Fecha asignación<span class="required">*</span></label>
            <input autofocus type="date" class="form-control text-right" id="fec_asig" name="fec_asig" placeholder="Ingrese la fecha de asignación" title="Ingrese la fecha de asignación">
            <span></span>
        </div>
         <div class="form-group col-md-4 col-md-4 col-sm-4 col-xs-12">
            <label for="status">Estado </label>
            <select autofocus class="form-control custom-select rounded-0 " id="status" name="status" title="Status">
            </select>
        </div>
    </div>
</div>