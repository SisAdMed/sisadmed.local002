<?php
class MovDocCxc extends Controller{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(180);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        
        $this->views->getView($this, "index", [
            'page_name' => "Consulta Cuentas por Cobrar",
            'function_js' => "MovDocCxc.js"
        ]);
    }
    public function show_rows(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = '';
            $id_tdoc = '';
            $num_tdo = '';
            $id_cli = '';
            $fec_ini = '';
            $fec_fin = '';
            $origen = '';
            if(isset($_POST['id_emp'])){
                $id_emp = $_POST['id_emp'];
            }
            if(isset($_POST['id_tdoc'])){
                $id_tdoc = $_POST['id_tdoc'];
            }
            if(isset($_POST['num_tdo']) && $_POST['num_tdo'] > 0){
                $num_tdo = $_POST['num_tdo'];
            }
            if (isset($_POST['id_cli']) && $_POST['id_cli'] >0 ) {
                $id_cli = $_POST['id_cli'];
            }
            if (isset($_POST['fec_ini'])) {
                $fec_ini = $_POST['fec_ini'];
            }
            if (isset($_POST['fec_ini'])) {
                $fec_ini = $_POST['fec_ini'];
            }
            if (isset($_POST['fec_fin'])) {
                $fec_fin = $_POST['fec_fin'];
            }
            if(isset($_POST['origen'])){
                $origen = $_POST['origen'];
            }   
            $r = MovDocCxcModel::show_rows($id_emp, $id_tdoc, $num_tdo, $id_cli, $fec_ini, $fec_fin, $origen);
            
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function index_cxp(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $this->views->getView($this, 'index-cxp', [
            'page_name' => 'Movimientos por Documentos de Cuentas por Pagar',
            'function_js' => 'MovDocCxc.js'
        ]);
    }
}