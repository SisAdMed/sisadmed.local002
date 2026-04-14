<?php headerAdmin($data); ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url ?>/TipoMovCXC" title="Lista de menú"><i class="fa fa-reply"></i></a></li>
                        </ol>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <form method="POST" class="form-horizontal form-label-left" name="my_form" id="my_form">
            <div lass="row d-flex justify-content-ceter">
                <?php include_once __DIR__ . "/form.php" ?>
                <div class="card-footer">
                    <input type="submit" id="btnok" name="btnok" class="btn btn-success btn-xs" value="Guardar" />
                </div>
            </div>
        </form>
    </section>
</div>
<?php footerAdmin($data); ?>