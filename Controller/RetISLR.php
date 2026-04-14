<?php
class RetISLR extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(165);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = RetISLRModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Conceptos de Retención de ISLR',
            'function_js' => 'RetISLR.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo registro de Concepto de Retención de ISLR',
            'function_js' => 'RetISLR.js'
        ]);
    }
    public function edit($id) {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = RetISLRModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/RetISLR');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de Concepto de Retención de ISLR',
                    'function_js' => 'RetISLR.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/RetISLR');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/RetISLR');
    }
    public function store() {
        debug($_POST);
        $modo = 'modify_user';
        $data = array();
        if(empty($_POST['id'])){
            $modo = 'create_user';
        }
        $data += [
            'fecha_vigencia' => $_POST['fecha_vigencia'],
            'descrip' => $_POST['descrip'],
            'minimo' => $_POST['minimo_for'],
            'maximo' => $_POST['maximo_for'],
            'por_reten' => $_POST['por_reten_for'],
            'por_imp_suj_ret' => $_POST['por_imp_suj_ret_for'],
            'fac_reten' => $_POST['fac_reten_for'],
            'code_seniat' => $_POST['code_seniat'],
            'status' => $_POST['status'],
            $modo => $_SESSION['id_user'],
        ];
        try{
            if(empty($_POST['id'])){
                RetISLRModel::guardar($data);
                Alertas::new(sprintf('El registro %s con retención del %s por ciento fue cargado satisfactoriamente', $_POST['descrip'], $_POST['por_reten']));
            }else{
                RetISLRModel::actualizar(($_POST['id']), $data);
                Alertas::new(sprintf('El registro %s con retención del %s por ciento fue modificado satisfactoriamente', $_POST['descrip'], $_POST['por_reten']));
            }
            header('Location:'. base_url . '/RetISLR');
        } catch (\PDOException $e){
            Alertas::new($e->getMessage(), 'danger');
            header('Location:'. base_url . '/RetISLR');
        }
    }
    public function borrar(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $dataJson = [];
            $id = $_POST['regID'];
            $r = RetISLRModel::borrar($id);
            if($r) {
                $dataJson += ['status' => true];
            }else{
                $dataJson += ['status' => false];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function load_screan_main(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = RetISLRModel::all();
            echo  json_encode($r, JSON_UNESCAPED_UNICODE);
        } 
   }
   public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = RetISLRModel::edit($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
   }
   public function listar_retislr(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $r= RetISLRModel::listar_retislr();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
   }
   public function index2() {
   
    //if(empty($_SESSION['permisosMod']['r'])) {
    //    header('Location:' . base_url . '/Perfil');
    //}
    $this->views->getView($this, 'index2', [
        'page_name' => 'Reporte Retención de I.S.L.R.',
        'function_js' => 'RetISLR.js',
    ]);
}
public function report_retislr(){
    if(Permisos::read()){
        $id_emp = $_GET['id_emp'];
        $fec_ini = $_GET['fec_ini'];
        $fec_fin = $_GET['fec_fin'];
        $r = RetISLRModel::report_retislr($id_emp, $fec_ini, $fec_fin);
        if(empty($r)){
            Alertas::new('El registro no existe', 'warning');
            header('Location:' . base_url . '/RetISLR/index2');
        }
        $this->views->getView($this, "report_retislr", [
            'r' => to_obj($r)
        ]);
        return;
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/RetISLR');
    }
}
public function report_retislr_xml(){
    if(Permisos::read()){
        $id_emp = $_GET['id_emp'];
        $fec_ini = $_GET['fec_ini'];
        $fec_fin = $_GET['fec_fin'];
        $r = RetISLRModel::report_retislr($id_emp, $fec_ini, $fec_fin);
        if(empty($r)){
            Alertas::new('El registro no existe', 'warning');
            header('Location:' . base_url . '/RetISLR/index2');
        }
        $this->views->getView($this, "report_retislr_xml", [
            'r' => to_obj($r)
        ]);
        return;
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/RetISLR');
    }
}
}