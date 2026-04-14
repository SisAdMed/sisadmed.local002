<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-no-expand sidebar-dark-primary elevation-4" >
    <!-- Brand Logo -->
    <a href="<?= base_url; ?>" class="brand-link">
        <img src="<?= IMG  ?>/<?= SITE_LOGO ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?= SITE_NAME ?></span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar" >
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $_SESSION['photo_user'] ?? ''; ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $_SESSION['full_name'] ?? '';?></a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2 sidebar-collapse" data-widget="tree">
            <ul class="nav nav-pills nav-sidebar flex-column sidebar-collapse" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
                <?= Permisos::crear_menu(0) ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>