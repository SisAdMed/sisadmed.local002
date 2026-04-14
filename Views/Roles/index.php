<?php
headerAdmin($data);
?>
<!-- CONTENIDO DINAMICO -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <?php if (Permisos::create()) : ?>
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url ?>/Roles/nuevo" title="Nuevo rol"><i class="fa fa-plus-circle"></i></a></li>
                            </ol>
                        <?php endif ?>
                    </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Tabla -->
        <?php echo Alertas::mostrarAlerta() ?>
        <table id="tblTable" class="display responsive nowrap table table-hover" style="width:100%">
            <thead>
                <th>Id</th>
                <th>Nombre</th>
                <th>Status</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php foreach ($roles as $r) : ?>
                    <tr>
                        <td></td>
                        <td><?php echo $r->nombre_rol; ?></td>
                        <!--Status-->
                        <?php if ($r->status_rol) : ?>
                            <td><span class="badge badge-success">Activo</span></td>
                        <?php else : ?>
                            <td><span class="badge badge-danger">Inactivo</span></td>
                        <?php endif ?>
                        <!--Acciones-->
                        <td>
                            <a type="button" class="btn btn-info btn-xs" href="<?php echo base_url . '/Permiso/index/' .  $r->id_rol; ?>"><i class="fa fa-key"></i></a>
                            <?php if (Permisos::updater()) : ?>
                                <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Roles/edit/' .  $r->id_rol; ?>"><i class="fa fa-edit"></i></a>
                            <?php endif ?>
                            <?php if (Permisos::deleter()) : ?>
                                <button id="rolData" data-idrol="<?php echo $r->id_rol ?>" data-namerol="<?php echo $r->nombre_rol ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarFnt(this)"><i class="fa fa-trash"></i></button>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </section>
    <!-- /.content -->
</div>
<!-- /.CONTENIDO DINAMICO -->
<?php
footerAdmin($data);
?>