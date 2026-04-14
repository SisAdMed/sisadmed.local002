<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-users" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="users-basic-tab" data-toggle="pill" href="#users-basic" role="tab" aria-controls="users-basic" aria-selected="true">Usuario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="users-appnot-tab" data-toggle="pill" href="#users-appnot" role="tab" aria-controls="users-appnot" aria-selected="true">Aprobaciones y o Notificaciones</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="card-body">
    <div class="tab-content" id="users-logo-tabcontent">
        <div class="tab-pane fade show active" id="users-basic" role="tabpanel" aria-labelledby="users-basic-tab">
            <input type="hidden" name="id" id="id" value="<?php echo $usuario->id_user ?? '' ?>">
            <div class="row">
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="name_user">Nombre(s) <span>*</span></label>
                    <input type="text" class="form-control text-xs mayusculas" id="name_user" name="name_user" placeholder="Ingrese nombre(s)">
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="last_user">Apellido(s) <span>*</span></label>
                    <input type="text" class="form-control text-xs mayusculas" id="last_user" name="last_user" placeholder="Ingrese Apellido(s)">
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="code_user">Código <span>*</span></label>
                    <input type="text" class="form-control text-xs" id="code_user" name="code_user" placeholder="Ingrese código" readonly>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="email_user">Correo </label>
                    <input type="email" class="form-control text-xs minusculas" id="email_user" name="email_user" placeholder="Ingrese correo">
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="id_rol">Rol <span>*</span></label>
                    <select class="custom-select rounded-0 text-xs" id="id_rol" name="id_rol"></select>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <label for="status_user">Estado </label>
                    <select class="custom-select rounded-0 text-xs" id="status_user" name="status_user"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="last_login">Último ingreso</label>
                    <input type="text" class="form-control text-xs" id="last_login" name="last_login" placeholder="Último ingreso" disabled>
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="password_user">Contraseña <span class="required">*</span></label>
                    <input type="password" class="form-control text-xs" id="password_user" name="password_user" placeholder="Ingrese contraseña" autocomplete="off">
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <label for="repassword_user">Repetir contraseña <span class="required">*</span></label>
                    <input type="password" class="form-control text-xs" id="repassword_user" name="repassword_user" placeholder="Repita contraseña" autocomplete="off">
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
                    <label for="administrator">Usuario Administrador <span class="required">*</span></label>
                    <input type="checkbox" class="form-control text-xs" id="administrator" name="administrator" placeholder="Repita contraseña" autocomplete="off">
                </div>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="$_POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-4">
                        <h1 class="text-primary text-xs">Subir imagen</h1>
                        <div class="form-group">
                            <label for="url_photo">Seleccione una imagen</label>
                            <input type="file" accept="image/*" name="url_photo" id="url_photo" onchange="previewImage(event, '#imgPreview')">
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <h1 class="text-primary text-center">Foto de usuario</h1>
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
        <div class="tab-pane fade" id="users-appnot" role="tabpanel" aria-labelledby="users-appnot-tab">
            <div class="row">
                <div class="form-group col-lg-4 col-md-4 col-xs-12 text-center">
                    <label for="appdis">Aprobación Descuentos</label>
                    <input type="checkbox" class="form-control text-xs" name="appdis" id="appdis">
                </div>
            </div>
        </div>
    </div>
</div>