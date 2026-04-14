<?php
	$readonly="";
	if(!empty($r)){
		$readonly = 'readonly';
	}
?>
<div class="card-body">
	<input type="hidden" name="id" value="<?php echo $r->id_pro_his  ?? '' ; ?>">
	<input type="hidden" name="statusr" id="statusr" value="<?php echo $r->status ?? '' ; ?>">
	<div class="row">
		<div class="form-group col-md-3 col-sm-3 col-xs-12">
			<label for="fecha_creacion">Fecha creación <span class="required">*</span></label>
			<input type="datetime-local" class="form-control" id="fecha_creacion" name="fecha_creacion" required value="<?php echo $r->fecha_creacion ?? date('Y-m-d H:i:s') ;?>" title="Fecha de creación" readonly>
		</div>
		<div class="form-group col-md-3 col-sm-3 col-xs-12">
			<label for="status">Status</label>
			<select name="status" id="status" class="form-control">
			</select>
		</div>
		<div class="form-group col-md-3 col-sm-3 col-xs-12">
				<label for="fecha_vigencia">Fecha vigencia</label>
				<input type="datetime-local" class="form-control" id="fecha_vigencia" name="fecha_vigencia" value="<?= $r->fecha_vigencia ?? '' ;?>" title="Fecha de vigencia" readonly>
		</div>
		<div class="form-group col-md-3 col-sm-3 col-xs-12">
			<label for="fecha_aprobado">Fecha aprobado</label>
			<input type="datetime-local" class="form-control" id="fecha_aprobado" name="fecha_aprobado" value="<?= $r->fecha_aprobado ?? '' ;?>" title;="Fecha de aprobación" readonly>
		</div>

	</div>
	<div>
		<div class="form-group col-md-4 col-sm-4 col-xs-12">
			<label for="archivo_histo">Seleccionar archivo</label>
			<input type="file" class="form-control" name="archivo_histo" id="archivo_histo" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/vnd.ms-excel.sheet.binary.macroEnabled.12">
			</select>
		</div>
		<div class="loader">
			<img src="<?= IMG . '/ajax-loading.gif'  ?>" />
		</div>
	</div>
	<div class="row">
		<div class="form-group col-md-12 col-sm-12 col-xs-12">
			<label for="observa">Observaciones</label>
			<textarea class="form-control" name="observa" id="observa" cols="30" rows="1"><?php echo $r->observa ?? '' ;?></textarea>
		</div>
	</div>
	<div class="row">
		<table id="tab_pre_pro" name="tab_pre_pro" class="table" style="width:100%">
			<thead>
				<tr>
					<th>Item</th>
					<th>Id</th>
					<th>Descripción</th>
					<th>Costo</th>
					<th>Flete</th>
					<th>Otros</th>
					<th>Cargos Door</th>
					<th>Costo1</th>
					<th>Utilidad</th>
					<th>Venta</th>
					<th>Utlidad 2</th>
					<th>Venta 2</th>
				</tr>
			</thead>
			<tbody id="tab_pre_pro_det"></tbody>
		</table>
		<div class="loader">
          <img src="<?= IMG . '/ajax-loading.gif'  ?>" />
         </div>
	</div>
</div>