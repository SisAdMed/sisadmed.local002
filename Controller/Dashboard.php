<?php
class Dashboard extends Controller
{
    var $views;
    var $data;
    public function __construct()
    {
        Auth::noAuth();
        Permisos::getPermisos(DASHBOARD);
        parent::__construct();
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url. '/Perfil' );
        }
        $data['page_name'] = 'Dashboard';
        $data['function_js'] = 'Dashboard.js';
        $this->views->getView($this, 'Dashboard', $data);
    }
}
