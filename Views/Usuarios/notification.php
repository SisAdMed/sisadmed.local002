<?= headerAdmin($data) ?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1><?php echo $data['page_name']; ?>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url ?>/CXCDocument" title="Lista de menú"><i class="fa fa-reply"></i></a></li>
                        </ol>
                    </h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <table id="tblAprobaciones" class="display responsive nowrap table table-hover text-xs" style="width:100%">
        </table>
    </section>
</div>
<?= footerAdmin($data) ?>