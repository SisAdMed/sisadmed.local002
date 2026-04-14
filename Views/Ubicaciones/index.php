<?php headerAdmin($data);?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-12">
					<h1><?php echo $data['page_name']; ?>
					<?php if (Permisos::create()) : ?>
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="<?= base_url ?>/Ubicaciones/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
						</ol>
					<?php endif ?>
				</h1>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<!-- Tabla -->
	<?php echo Alertas::mostrarAlerta() ?>
	<table id="tblTable" class="display responsive nowrap table table-hover" style="width:100%">
		<thead>
			<th>Id</th>
			<th>Empresa</th>
			<th>Código</th>
			<th>Nombre</th>
			<th>Agrupador</th>
			<th>Refrigerado</th>
			<th>Uso interno</th>
			<th>Status</th>
			<th>Acciones</th>
		</thead> 
		<tbody>
			<?php if(is_iterable($objeto)) : ?>
				<?php foreach ($objeto as $r) : ?>
					<tr>
						<td><?php echo $r->id_ubi; ?></td>
						<td><?php echo $r->nombre_emp; ?></td>
						<td><?php echo $r->cod_ubi; ?></td>
						<td><?php echo $r->nom_ubi; ?></td>
						<td><?php echo $r->agru_ubi; ?></td>
						<td><?php echo $r->refri_ubi; ?></td>
						<td><?php echo $r->uso_ubi; ?></td>
						<!--Status-->
						<?php if ($r->status) : ?>
							<td><span class="badge badge-success">Activo</span></td>
						<?php else : ?>
							<td><span class="badge badge-danger">Inactivo</span></td>
						<?php endif ?>
						<!--Acciones-->
						<td>
							<?php if (Permisos::updater()) : ?>
								<a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Ubicaciones/edit/' .  $r->id_ubi; ?>"><i class="fa fa-edit"></i></a>
							<?php endif ?>
							<?php if (Permisos::deleter()) : ?>
								<button id="Data" data-id="<?php echo $r->id_ubi ?>" data-name="<?php echo $r->nom_ubi ?>" data-code = "<?php echo $r->cod_ubi; ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
							<?php endif ?>
						</td>
					</tr>
				<?php endforeach ?>
			<?php endif ?>
		</tbody>
	</table>
</section>
</div>
<?php footerAdmin($data); ?>