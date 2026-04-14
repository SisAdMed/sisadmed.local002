<?php headerAdmin($data); ?>
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
                                <li class="breadcrumb-item"><a href="<?= base_url ?>/Roles" title="Lista de roles"><i class="fa fa-reply"></i></a></li>
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
        <form class="form-horizontal form-label-left" action="<?php echo base_url ?>/Permiso/store" method="POST" novalidate>
            <input type="hidden" name="idRol" id="idRol" value="<?php echo $accesobyRole['id_rol']?>">
            <table id="tblPermiso" class="display responsive nowrap table table-hover" style="width:100%">
                <thead>
                    <th>#</th>
                    <th>Página</th>
                    <th>Crear - C</th>
                    <th>Consultar - R</th>
                    <th>Actualizar - U</th>
                    <th>Borrar - D</th>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    $paginas =  $accesobyRole['paginas'];
                    for ($i = 0; $i < count($paginas); $i++) :
                        $accesos = $paginas[$i]['accesos']; //defailt estan en 0
                        $cCkeck = $accesos['c'] == 1 ? "checked" : "";
                        $rCkeck = $accesos['r'] == 1 ? "checked" : "";
                        $uCkeck = $accesos['u'] == 1 ? "checked" : "";
                        $dCkeck = $accesos['d'] == 1 ? "checked" : "";
                        $idPage = $paginas[$i]['id_menu'];
                    ?>
                        <tr>
                            <td><?= $n; ?>
                                <input type="text" name="paginas[<?php echo $i ?>][id_menu]" value="<?php echo $idPage ?>">
                            </td>
                            <td><?php echo $paginas[$i]['nombre_menu'] . ' - ' . $paginas[$i]['desc_menu']?> </td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" name="paginas[<?php echo $i ?>][c]" <?php echo $cCkeck ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" name="paginas[<?php echo $i ?>][r]" <?php echo $rCkeck ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" name="paginas[<?php echo $i ?>][u]" <?php echo $uCkeck ?>>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" name="paginas[<?php echo $i ?>][d]" <?php echo $dCkeck ?>>
                                </div>
                            </td>
                        </tr>
                    <?php 
                    $n++; //
                        endfor 
                    ?>
                </tbody>
            </table>
        <div class="mt-3 mt-md-1">
            <input type="submit" class="btn btn-info btn-lg btn-block" value="Guardar Permisos">
        </div>
        </form>
    </section>
    <!-- /.content -->
</div>
<!-- /.CONTENIDO DINAMICO -->
<?php footerAdmin($data); ?>