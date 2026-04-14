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
                                <li class="breadcrumb-item"><a href="<?= base_url ?>/Calendar/new" title="Nuevo registro"><i class="fa fa-plus-circle"></i></a></li>
                                &nbsp;&nbsp;<button class="refresh-button btn-xs btn-primary" title="Refrescar página"><i class="fa-solid fa-arrow-rotate-right"></i></button>
                            </ol>
                        <?php endif ?>
                    </h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="dt-table">
            <table id="tblIndexMain" class="display responsive nowrap table table-hover text-xs" style="width:100%">
            </table>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.CONTENIDO DINAMICO -->
<?php footerAdmin($data); ?>