<div class="row">
	<div class="col-12 col-sm-12">
		<div class="card card-primary card-tabs">
			<div class="card-header p-0 pt-1">
				<ul class="nav nav-tabs" id="customer-products" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="customer-basic-tab" data-toggle="pill" href="#customer-basic" role="tab" aria-controls="customer-basic" aria-selected="true">Principal <span class="error-star text-danger"></span></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="customer-contact-tab" data-toggle="pill" href="#customer-contact" role="tab" aria-controls="customer-contact" aria-selected="true">Contactos <span class="error-star text-danger"></span></a>
					</li>
					<?php if ($_SESSION['administrator'] == 1) : ?>
						<li class="nav-item">
							<a class="nav-link" id="customer-finance-tab" data-toggle="pill" href="#customer-finance" role="tab" aria-controls="customer-finance" aria-selected="true">Financiero <span class="error-star text-danger"></span></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="customer-additional-tab" data-toggle="pill" href="#customer-additional" role="tab" aria-controls="customer-additional" aria-selected="true">Adicional <span class="error-star text-danger"></span></a>
						</li>
					<?php endif ?>
					<li class="nav-item">
						<a class="nav-link" id="customer-internet-tab" data-toggle="pill" href="#customer-internet" role="tab" aria-controls="customer-internet" aria-selected="true">Internet <span class="error-star text-danger"></span></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="card-body">
	<div class="tab-content" id="customer-products-tabcontent">
		<div class="tab-pane fade show active" id="customer-basic" role="tabpanel" aria-labelledby="customer-basic-tab">
			<input type="hidden" name="id" id="id" value="<?= $r[0]->id_ent ?? '' ?>">
			<input type="hidden" name="tip_ent" id="tip_ent" value="C">
			<div class="row">
				<div class="form-group col-md-2 col-sm-2 col-xs-12">
					<label for="rif_ent">Rif <span class="required">*</span></label>
					<input type="text" class="form-control rif text-xs mayusculas" id="rif_ent" name="rif_ent" placeholder="Ingrese RIF">
					<span></span>
				</div>
				<div class="form-group col-md-6 col-sm-6 col-xs-12">
					<label for="nom_ent">Nombre <span class="required">*</span></label>
					<input type="text" class="form-control text-xs mayusculas" id="nom_ent" name="nom_ent" placeholder="Ingrese Nombre">
				</div>
				<div class="form-group col-md-4 col-sm-4 col-xs-12">
					<label for="cor_ent">Nombre corto</label>
					<input type="text" class="form-control text-xs mayusculas" id="cor_ent" name="cor_ent" placeholder="Ingrese Nombre">
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-3 col-sm-3 col-xs-12">
					<label for="id_vend">Vendedor</label>
					<select name="id_vend" id="id_vend" class="form-control select2 select2bs4 text-xs">
					</select>
				</div>
				<div class="form-group col-md-3 col-sm-3 col-xs-12">
					<label for="id_zona">Zona</label>
					<select name="id_zona" id="id_zona" class="form-control select2 select2bs4 text-xs">
					</select>
				</div>
				<div class="form-group col-md-2 col-sm-2 col-xs-12">
					<label for="postal_ent">Zona Postal</label>
					<input type="text" class="form-control text-xs mayusculas" id="postal_ent" name="postal_ent" placeholder="Ingrese Zona postal">
				</div>
				<div class="form-group form-check col-md-2 col-sm-2 col-xs-12 text-center">
					<label for="contr_esp">Contribuyente especial</label>
					<input type="checkbox" class="form-control float-left text-xs" id="contr_esp" name="contr_esp" style="vertical-align: bottom;">
				</div>
				<div class="form-group col-md-2 col-sm-2 col-xs-12">
					<label for="id_por_ret_iva">Porcentaje Ret. IVA</label>
					<select name="id_por_ret_iva" id="id_por_ret_iva" class="select2 select2bs4 text-xs"></select>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-3 col-sm-3 col-xs-12">
					<label for="id_pais">País</label>
					<select name="id_pais" id="id_pais" class="form-control select2 select2bs4 text-xs">
					</select>
				</div>
				<div class="form-group col-md-3 col-sm-3 col-xs-12">
					<label for="id_edo">Estado</label>
					<select name="id_edo" id="id_edo" class="form-control select2 select2bs4 text-xs">
					</select>
				</div>
				<div class="form-group col-md-3 col-sm-3 col-xs-12">
					<label for="id_ciudad">Ciudad</label>
					<select name="id_ciudad" id="id_ciudad" class="form-control select2 select2bs4 text-xs">
					</select>
				</div>
				<div class="form-group col-md-3 col-sm-3 col-xs-12">
					<label for="id_diascre">Días de Crédito</label>
					<select name="id_diascre" id="id_diascre" class="form-control select2 select2bs4 text-xs">
					</select>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-md-12 col-sm-12 col-xs-12">
					<label for="dir_ent">Dirección</label>
					<textarea class="form-control mayusculas text-xs" name="dir_ent" id="dir_ent" cols="143" rows="2"></textarea>
				</div>
			</div>
			<?php if ($_SESSION['administrator'] == 1) : ?>
				<div class="row">
					<div class="form-group col-md-3 col-md-3 col-sm-3 col-xs-12">
						<label for="id_emp">Empresa a facturar</label>
						<select class="form-control select2 select2bs4 text-xs" id="id_emp" name="id_emp">
						</select>
					</div>
					<div class="form-group col-md-3 col-md-3 col-sm-3 col-xs-12">
						<label for="id_motcam">Motivo Cambio facturación</label>
						<select class="form-control select2 select2bs4 text-xs " id="id_motcam" name="id_motcam">
						</select>
					</div>
					<div class="form-group col-md-2 col-md-2 col-sm-2 col-xs-12">
						<label for="id_moneda">Moneda a Facturar y/o Cotizar</label>
						<select class="form-control select2 select2bs4 text-xs" id="id_moneda" name="id_moneda">
						</select>
					</div>
					<div class="form-group col-md-2 col-md-2 col-sm-2 col-xs-12">
						<label for="id_tipocliente">Tipo de Cliente</label>
						<select class="form-control select2 select2bs4 text-xs" id="id_tipocliente" name="id_tipocliente">
						</select>
					</div>
					<div class="form-group col-md-2 col-md-2 col-sm-3 col-xs-12">
						<label for="status">Condición</label>
						<select class="form-control select2 select2bs4 text-xs" id="status" name="status">
						</select>
					</div>
				</div>
			<?php endif ?>
		</div>
		<div class="tab-pane fade show" id="customer-contact" role="tabpanel" aria-labelledby="customer-contact-tab">
			<div class="row">
				<div class="form-group col-md-12 col-sm-12 col-xs-12">
					<table id="det_con" name="det_con" class="display responsive nowrap table table-hover text-xs" style="width:100%">
						<thead>
							<tr>
								<th>Nombre(s)</th>
								<th>Apellidos(s)</th>
								<th>Correo</th>
								<th>Area</th>
								<th>Teléfono</th>
								<th>Departamento</th>
								<th>Acción</th>
							</tr>
						</thead>
						<tbody></tbody>
						<tfoot>
							<tr>
								<th colspan="7"><button type="button" class="btn btn-primary text-xs" id="btn_accion">Nuevo contacto</button></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		<div class="tab-pane fade show" id="customer-finance" role="tabpanel" aria-labelledby="customer-finance-tab">
			<div class="row">
				<div class="form-group col-md-12 col-sm-12 col-xs-12">
					<table id="tblCXCedo_cuenta" name="tblCXCedo_cuenta" class="display responsive nowrap table table-hover text-xs" style="width:100%">
						<thead></thead>
						<tbody></tbody>
						<tfoot></tfoot>
					</table>
				</div>
			</div>
		</div>
		<div class="tab-pane fade show" id="customer-additional" role="tabpanel" aria-labelledby="customer-additional-tab">
			<div class="row">
				<div class="form-group col-sm-4 col-md-4 col-xs-12">
					<label for="note_fac">Notas Factura</label>
					<textarea name="note_fac" id="note_fac" class="form-control text-xs" rows="2" cols="50"></textarea>
				</div>
				<div class="form-group col-sm-4 col-md-4 col-xs-12">
					<label for="id_alm">Almacén</label>
					<select name="id_alm" id="id_alm" class="form-control select2 select2bs4 text-xs"></select>
					<div class="custom-control custom-checkbox">
						<div class="custom-control custom-checkbox">
							<input class="form-control custom-control-input text-xs" type="checkbox" id="c_consig" name="c_consig">
							<label for="c_consig" class="custom-control-label">En consignación</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="form-control custom-control-input text-xs" type="checkbox" id="handling_conver" name="handling_conver">
							<label for="handling_conver" class="custom-control-label">Aplica conversión</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="form-control custom-control-input text-xs" type="checkbox" id="print_lote" name="print_lote">
							<label for="print_lote" class="custom-control-label">Imprimir lote en factura</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="form-control custom-control-input text-xs" type="checkbox" id="print_special" name="print_special">
							<label for="print_special" class="custom-control-label">Impresión especial</label>
						</div>
						<div class="custom-control custom-checkbox">
							<input class="form-control custom-control-input text-xs" type="checkbox" id="req_exc_rat" name="req_exc_rat">
							<label for="req_exc_rat" class="custom-control-label">Solicitar Fecha para Tasa de Cambio</label>
						</div>
					</div>
				</div>
				<div class="form-group col-sm-4 col-md-4 col-xs-12">
					<label for="id_ubi">Ubicación</label>
					<select name="id_ubi" id="id_ubi" class="form-control select2 select2bs4 text-xs"></select>
				</div>
			</div>
			<div class="row">
				<div class="form-group col-sm-2 col-md-2 col-xs-12">
					<label for="cant_dec">Decimales en Documentos</label>
					<input type="number" name="cant_dec" id="cant_dec" class="form-control text-right text-xs">
				</div>
			</div>
		</div>
		<div class="tab-pane fade show" id="customer-internet" role="tabpanel" aria-labelledby="customer-internet-tab">
			<div class="row">
				<div class="form-group col-sm-2 col-md-2 col-xs-12 text-center">
					<label for="internet">Usar el en Web Site</label>
					<input type="checkbox" name="internet" id="internet" class="form-control text-xs">
				</div>
				<div class="form-group col-sm-4 col-md-4 col-xs-12">
					<label for="url">Dirección web</label>
					<input type="url" name="url" id="url" class="form-control text-xs" disabled>
				</div>
			</div>
			<div class="row">
				<form action="<?= $_SERVER["PHP_SELF"]; ?>" method="$_POST" enctype="multipart/form-data" id="imgLogo">
					<div class="row">
						<div class="col-lg-4">
							<h1 class="text-primary text-xs">Subir imagen</h1>
							<form action="<?= $_SERVER['PHP_SELF'];?>" method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<label for="logo_ent">Seleccione una imagen</label>
									<input type="file" accept="image/*" name="logo_ent" id="logo_ent" onchange="previewImage(event, '#imgPreview')">
								</div>
							</form>
						</div>
						<div class="col-lg-8">
							<h1 class="text-primary text-center">Logo</h1>
							<hr>
							<div class="card-columns text-center">
								<div id="cardimg1">
									<img id="imgPreview" name="imgPreview" width="200px" height="200px">
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Views/Ubicaciones/modal_Ubicaciones.php';
?>