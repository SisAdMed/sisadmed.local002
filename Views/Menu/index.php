<?php headerAdmin($data);?>
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
                                <li class="breadcrumb-item"><a href="<?= base_url ?>/Menu/nuevo" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
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
                <th>Descripción</th>
                <th>Menú padre</th>
                <th>Página</th>
                <th>Icono</th>
                <th>Orden</th>
                <th>Status</th>
                <th>Acciones</th>
            </thead>
            <tbody>
                <?php foreach ($menu as $r) : ?>
                    <tr>
                        <td><?php echo $r->id_menu; ?></td>
                        <td><?php echo $r->nombre_menu; ?></td>
                        <td><?php echo $r->desc_menu; ?></td>
                        <td><?php echo $r->padre; ?></td>
                        <td><?php echo $r->page_menu; ?></td>
                        <td><?php echo $r->icono_menu; ?></td>
                        <td><?php echo $r->orden_menu; ?></td>
                        <!--Status-->
                        <?php if ($r->status_menu) : ?>
                            <td><span class="badge badge-success">Activo</span></td>
                        <?php else : ?>
                            <td><span class="badge badge-danger">Inactivo</span></td>
                        <?php endif ?>
                        <!--Acciones-->
                        <td>
                            <?php if (Permisos::updater()) : ?>
                                <a type="button" class="btn btn-warning btn-xs" href="<?php echo base_url . '/Menu/edit/' .  $r->id_menu; ?>"><i class="fa fa-edit"></i></a>
                            <?php endif ?>
                            <?php if (Permisos::deleter()) : ?>
                                <button id="Data" data-id="<?php echo $r->id_menu ?>" data-name="<?php echo $r->nombre_menu ?>" type="button" class="btn btn-danger btn-xs" onclick="eliminarBtn(this)"><i class="fa fa-trash"></i></button>
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
<?php footerAdmin($data); ?>