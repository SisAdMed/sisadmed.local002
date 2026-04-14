<?php headerAdmin($data)?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <class class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                    <?php if(Permisos::create()) : ?>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="<?= base_url ?>/Notificaciones/nuevo" title="Nuevo registro">
                                    <i class="fa fa-plus-circle"></i>
                                </a>
                            </li>
                        </ol>
                    <?php endif; ?>
                </h1>
            </div>
        </class>
    </div>
</section>
<section class="content">
    <?php Alertas::mostrarAlerta() ?>
    <table id="tblTable" class="display responsive nowrap table table-hover" style="width: 100%;">
        <thead>
            <tr>
                <th>Id</th>
                <th>Tipo</th>
                <th>Fecha</th>
                <th>Titulo</th>
                <th>Mensaje</th>
                <th>Status</th>
                <th>Acciones</th>
            </tr>
            <tbody id="tblTableDeta">
            </tbody>
            <tfoot>
                <tr>
                    <th>Id</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Titulo</th>
                    <th>Mensaje</th>
                    <th>Status</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </thead>
    </table>
</section>
</div>
<?php footerAdmin($data)?>