<?php
headerAdmin($data);
?>
<!-- CONTENIDO DINAMICO -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1><?php echo $data['page_name']; ?></h1>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="error-page">
			<h2 class="headline text-warning"> 404</h2>

			<div class="error-content">
				<h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Página no encontrada.</h3>
				<p>
					No pudimos encontrar la página que estabas buscando.
                    Mientras tanto, puedes <a href="<?= base_url ?>">volver al tablero</a> o intente usar el formulario de búsqueda.
				</p>
			</div>
			<!-- /.error-content -->
		</div>
		<!-- /.error-page -->
	</section>
	<!-- /.content -->
</div>
<!-- /.CONTENIDO DINAMICO -->
<?php
footerAdmin($data);
?>