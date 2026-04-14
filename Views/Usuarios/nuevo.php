<?php headerAdmin($data); ?>
<!-- CONTENIDO DINAMICO -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url ?>/Usuarios" title="Lista de usuarios"><i class="fa fa-reply"></i></a></li>
                        </ol>
                    </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form action="#" method="POST" class="form-horizontal" enctype="multipart/form-data" name="my_form" id="my_form">
            <div lass="row d-flex justify-content-ceter">
                <?php include_once __DIR__ . "/form.php" ?>
                <div class="card-footer">
                    <input type="submit" class="btn btn-success text-xs" value="Guardar" id="btnok" />
                </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
</div>
<!-- /.CONTENIDO DINAMICO -->
<?php footerAdmin($data); ?>