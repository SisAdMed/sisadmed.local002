<?php
    /**
     * Clase de los metodos para las Consultas de Movimientos Contables
     * Creado por José Vargas
     * Fecha 09-12-2024 12:59 p.m.
    */
    class CTBConsult extends Controller{
        public function __construct(){
            Auth::noAuth();
            parent::__construct();
            Permisos::getPermisos(153);
        }
        public function index(){
            if(empty($_SESSION['permisosMod']['r'])){
                header('Locarion: ' . base_url . '/Perfil');
            }
            $this->views->getView($this, 'index', [
                'page_name' => 'Consulta de Movimientos Contables',
                'function_js' => 'CTBConsult.js'
            ]);
        }
        public function saldosCuentas_mov(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $id_emp = $_POST['id_emp'];
                $fec_ini = $_POST['fec_ini'];
                $fec_fin = $_POST['fec_fin'];
                $cod_cta = $_POST['cod_cta'];
                $cod_aux = $_POST['cod_aux'];
                $r = CTBConsultModel::saldosCuentas_mov($id_emp, $fec_ini, $fec_fin, $cod_cta, $cod_aux);
                echo json_encode($r, JSON_UNESCAPED_UNICODE);
            }
        }
    }

?>