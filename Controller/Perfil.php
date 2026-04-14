<?php
class Perfil extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(PERFIL);
    }
    public function index(){
        $this->views->getView($this, "index", [
            'page_name' => "Perfil del usuario",
            'function_js' => "Perfil.js",
        ]);
    }
    public function index2(){
        $this->views->getView($this, "index2", [
            'page_name' => "Dashboard de Productos",
            'function_js' => "Perfil.js",
        ]);
    }
    public function index3(){
        $this->views->getView($this, "index3", [
            'page_name' => "Dashboard de Productos Tablas",
            'function_js' => "Perfil.js",
        ]);
    }
    public function grafica_001(){
        $r = PerfilModel::grafica_001();
        echo json_encode($r);
    }
    public function grafica_002(){
        $r = PerfilModel::grafica_002();
        echo json_encode($r);
    }
    public function grafica_003(){
        $r = PerfilModel::grafica_003();
        echo json_encode($r);
    }
    public function grafica_004(){
        $r = PerfilModel::grafica_004();
        echo json_encode($r);
    }
    public function DatosTabla_001(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $r = PerfilModel::DatosTabla_001($fec_ini, $fec_fin, $id_emp); 
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function DatosTabla_001_det(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $r = PerfilModel::DatosTabla_001_det($fec_ini, $fec_fin, $id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function DatosTabla_001_det_cli()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $r = PerfilModel::DatosTabla_001_det_cli($fec_ini, $fec_fin, $id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function info_tasa(){
        $this->views->getView($this, 'info_tasa', [
            'page_name' => 'Información',
            'function_js' => 'Perfil.js'
        ]);
    }
    public function consumo(){
        $this->views->getView($this, 'info_consumo', [
            'page_name' => 'Reporte de Consumos para visualizar fallas',
            'function_js' => 'Perfil.js'
        ]);
    }
    public function ReportexConsumo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_fab = '';
            $id_tipocliente = '';      
            $id_emp = $_POST["id_emp"];
	        $fec_ini = $_POST["fec_ini"];
	        $fec_fin = $_POST["fec_fin"];
            if (isset($_POST['id_fab'])) {
                $id_fab = implode(',', $_POST['id_fab']);
            }
	        $id_cli = $_POST["id_cli"];
            $id_gru = $_POST['id_gru'];
            $id_vend = $_POST['id_vend'];
            if(isset($_POST['id_tipocliente'])){
                $id_tipocliente = $_POST['id_tipocliente'];
            }
            $r = PerfilModel::ReportexConsumo($id_emp, $fec_ini, $fec_fin, $id_fab, $id_cli, $id_gru, $id_vend, $id_tipocliente);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}