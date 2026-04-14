<?php
class CreditDays extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(91);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . 'Perfil');
        }
        $objeto = CreditDaysModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Días de Créditos',
            'function_js' => 'CreditDays.js',
            'function_js_mod' => 'CXCFun.js',
            'r' => to_obj($objeto)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevp Día de Crédito',
            'function_js' => 'CreditDays.js'
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo_user = 'modify_user';
            $modo_date = 'modify_date';
            $data = array();
            $dataJson = array();
            //Asignar valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if (empty($id)) {
                $modo_user = 'create_user';
                $modo_date = 'create_date';
            }
            try {
                $data = [
                    'cod_diascre' => $cod_diascre,
                    'des_diascre' => $des_diascre,
                    'status' => $status,
                    $modo_user => $_SESSION['id_user'],
                    $modo_date => getAuditoria()
                ];
                if(empty($id)){
                    $id = CreditDaysModel::guardar($data);
                    $title = "Registro se ha agregado satisfactoriamente";
                }else{
                    $id = CreditDaysModel::actualizar($_POST['id'], $data);
                    $title = "Registro se ha modificado satisfactoriamente";
                }
                $msg = "Se ha salvado satisfactoriamente los días de Crédito $cod_diascre $des_diascre";
                $dataJson = [
                    "title" => $title,
                    "icon" => "success",
                    "msg" => $msg
                ];
            } catch (PDOException $e) {
                $title = "Se ha presentado un error, fvor intentar luego";
                $msg = sprintf("Error códoigo: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => $title,
                    'icon' => "error",
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CreditDaysModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CreditDays');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r['des_diascre'],
                    'function_js' => "CreditDays.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CreditDays');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CreditDays');
    }
    public function destroy(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = [];
            $id = $_POST['id'];
            try {
                $r = CreditDaysModel::destroy($id);
                if ($r) {
                    $dataJson = [
                        'status' => true,
                        'msg' => 'Eliminado',
                        'icon' => 'success',
                        'title' => 'Registro eliminado satisfactoriamente'
                    ];
                } else {
                    $dataJson = [
                        'status' => false,
                        'msg' => 'Error',
                        'icon' => 'error',
                        'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'
                    ];
                }
            } catch (\PDOException $e) {
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'status' => false,
                    'msg' => $msg,
                    'icon' => 'error',
                    'title' => 'Error'
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CreditDaysModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $r = CreditDaysModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}