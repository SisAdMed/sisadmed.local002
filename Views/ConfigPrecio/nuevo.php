<?php headerAdmin($data); ?>
<div class="content-wrapper">    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url ?>/ConfigPrecio" title="Lista de menú"><i class="fa fa-reply"></i></a></li>
                        </ol>
                    </h1>
                </div>
            </div>
        </div>
    </section>    
    <section class="content">
        <?php echo Alertas::mostrarAlerta() ?>
        <form action="<?php echo base_url ?>/ConfigPrecio/store" method="POST" class="form-horizontal form-label-left" >
            <div lass="row d-flex justify-content-ceter">
                <?php include_once __DIR__ . "/form.php" ?>
                <div class="card-footer">
                    <input type="submit" id="btnok" name="btnok" class="btn btn-success" value="Guardar" />
                </div>
            </div>
        </form>
    </section>
</div>
<?php footerAdmin($data); ?>