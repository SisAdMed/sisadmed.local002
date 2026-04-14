<?php
/**
* Clase CXC Control
* Para las transacciones de Numero de Control
*/
class CXCnroControl extends Controller {
    function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(132);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = CXCnroControlModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Consulta Número de Control",
            'function_js' => "CXCnroControl.js",
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Serie Número de Control",
            'function_js' => "CXCnroControl.js"
        ]);
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            if(empty($_POST['id'])){
                $modo = 'create_user';
                $data = [
                    'id_emp' => $_POST['id_emp']
                ];
            }
            try {
                $data += [
                    'ini_nroControl' => $_POST['ini_nroControl'],
                    'fin_nroControl' => $_POST['fin_nroControl'],
                    'next_nroControl' => $_POST['next_nroControl'],
                    'fec_asig' => $_POST['fec_asig'],
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($_POST['id'])){
                    $id = CXCnroControlModel::guardar($data);
                    Alertas::new(sprintf('Los números de control del %s al %s se ha creado exitosamente', $_POST['ini_nroControl'], $_POST['fin_nroControl']));
                }else{
                    $id = CXCnroControlModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('Los números de control del %s al %s se ha modificado exitosamente', $_POST['ini_nroControl'], $_POST['fin_nroControl']));
                }
                header('Location:' . base_url . '/CXCnroControl');
            } catch (Exception $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/CXCnroControl');
            }
        }
    }
    public function showrowupdate_nroContrl(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXCnroControlModel::showrowupdate_nroContrl($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
     public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CXCnroControlModel::showrowupdate_nroContrl($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CXCnroControl');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando números de control del  " . $r[0]['ini_nroControl'] . ' al número ' . $r[0]['fin_nroControl'],
                    'function_js' => "CXCnroControl.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CXCnroControl');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CXCnroControl');
    }
}