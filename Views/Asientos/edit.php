<?php headerAdmin($data); ?>
<!-- CONTENIDO DINAMICO -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url ?>/Asientos" title="Lista de menú"><i class="fa fa-reply"></i></a></li>
                        </ol>
                    </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form id="my_form" name="my_form" method="POST">
            <div lass="row d-flex justify-content-ceter">
                <?php include_once __DIR__ . "/form.php" ?>
                <div class="card-footer">
                    <input type="submit" class="btn btn-success guardar btn-xs" value="Actualizar" />
                </div>
            </div>
        </form>
    </section>
<!-- /.content -->
</div>
<!-- /.CONTENIDO DINAMICO -->
<?php footerAdmin($data); ?>