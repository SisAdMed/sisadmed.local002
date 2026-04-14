<?php headerAdmin($data);?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-12">
					<h1><?php echo $data['page_name']; ?>               
				</h1>
			</div>
		</div>
	</div>
</section>
<section class="hold-transition login-page">	
	<div class="login-box">
		<div class="card card-outline card-primary">
			<div class="card-header text-center">
				<a href="<?= base_url ?>" class="h1"><b><?= SITE_NAME ?></a>
			</div>
			<div class="card-body">
				<p class="login-box-msg">Estás a sólo un paso de tu nueva contraseña, recupera tu contraseña ahora.</p>
				<form action="<?= $_SERVER['PHP_SELF']; ?>" method="post" name="my_form" id="my_form">
					<div class="input-group mb-3">
						<input id="password" name="password" type="password" class="form-control" placeholder="Ingrese el nuevo Password" required minlength="5">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input id="repassword" name="repassword" type="password" class="form-control" placeholder="Confirmar Password" required minlength="5">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button id="btnChange" name="btnChange" type="button" class="btn btn-primary btn-block">Cambiar password</button>
						</div>
						<!-- /.col -->
					</div>
				</form>
			</div>
			<!-- /.login-card-body -->
		</div>
	</div>
	<!-- /.login-box -->	
</section>
<?php footerAdmin($data);?>