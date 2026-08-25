<?= headerAdmin($data); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?= $data['page_name'] ?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url ?>">Home</a></li>
                        <li class="breadcrumb-item active"><?= $data['page_name'] ?></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-pdf text-danger mr-1"></i> Vista Previa del Documento
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Visor del PDF -->
                    <embed
                        id="visor_pdf"
                        src=""
                        type="application/pdf"
                        width="100%"
                        height="500px" />
                </div>
                <div class="card-footer text-center">
                    <button type="button" id="btnAprobar" class="btn btn-xs btn-primary">Aprobar</button>
                    <button type="button" id="btnRechazar" class="btn btn-xs btn-danger">Rechazar</button>
                </div>
            </div>
        </div>
    </section>
</div>
<?= footerAdmin($data); ?>