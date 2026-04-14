<?php headerAdmin($data); ?>
<div class='content-wrapper'>
    <section class='content-header'>
        <div class='container-fluid'>
            <div class='row mb-2'>
                <span class='col-sm-12'>
                    <h1><?php echo $data['page_name']; ?>
                        <?php if (Permisos::create()) : ?>
                            <ol class='breadcrumb float-sm-right'>
                                <li class='breadcrumb-item'><a href='<?= base_url ?>/TipoCliente/nuevo' title='Nuevo registro'><i class='fa fa-plus-circle'></i></a></li>
                            </ol>
                        <?php endif ?>
            </div>
        </div>
    </section>
    <section class="content">
        <?php echo Alertas::mostrarAlerta() ?>
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Listado de Tipos de Clientes</h1>
            </div>
            <div class="card-body">
                <table id="tblTable_TipoCliente" class="display responsive table table-hover text-xs blue" style="width:100%">
                    <thead>
                    </thead>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </section>
</div>
<?php footerAdmin($data); ?>