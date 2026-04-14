<?php
class TipoMovCXC extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(85);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = TipoMovCXCModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => "Consulta Tipos de Movimientos",
            'function_js' => 'TipoMovCXC.js',
            'function_js_mod' => 'CXCFun.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => "Nuevo Tipo de Movimiento",
            'function_js' => "TipoMovCXC.js"
        ]);
    }
    public function datosCue(){
      if($_SERVER["REQUEST_METHOD"] == "POST"){
         $id = $_POST['id_cue'];
         $r = TipoMovCXCModel::datosCue($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = TipoMovCXCModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/TipoMovCXC');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Tipo de Movimiento " . $r['cod_tmocxc'] . ' ' . $r['des_tmocxc'],
                    'function_js' => "TipoMovCXC.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/TipoMovCXC');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/TipoMovCXC');
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = TipoMovCXCModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = [];
            $id = intval(limpiar($_POST['id']));
            try {
                $r = TipoMovCXCModel::destroy($id);
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
    public function cargar_screen_main(){
      if(($_SERVER['REQUEST_METHOD']) == 'POST'){
         $r = TipoMovCXCModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      } 
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            //Asignar valores a variables
            foreach($_POST as $key => $value){
                $$key = $value;
            }
            if (empty($id)) {                
                $modo = 'create_user';
            }
            try {                
                $data += [
                    'id_emp' => $id_emp,
                    'acc_tmocxc' => $acc_tmocxc,
                    'rec_tmocxc' => $rec_tmocxc,
                    'cod_tmocxc' => $cod_tmocxc,
                    'des_tmocxc' => $des_tmocxc,
                    'con_tmocxc' => $con_tmocxc,
                    'next_tmocxc' => $next_tmocxc,
                    'id_ctbcue' => $id_ctb,
                    'id_aux' => $id_aux ?? 0,
                    'status' => $status,
                    $modo => $_SESSION['id_user']
                ];
                if (empty($_POST['id'])) {
                    $id = TipoMovCXCModel::guardar($data);
                    $title = "Registro se ha agregado satisfactoriamente";
                    $msg = sprintf('El Tipo de Movimiento %s - %s se ha creado exitosamente con el id %s', $cod_tmocxc, $des_tmocxc, $id);                    
                } else {
                    $id_upd = TipoMovCXCModel::actualizar($id, $data);
                    $title = "Registro se ha modificado satisfactoriamente";
                    $msg = sprintf('El Tipo de Movimiento %s - %s se ha modificado exitosamente con el id %s', $cod_tmocxc, $des_tmocxc, $id);
                }
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg,
                ];
            } catch (\PDOException $e) {
                $title = "Se ha presentado un error, intente luego";
                $msg = sprintf("Error código: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => $title,
                    'icon' => "error",
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
}