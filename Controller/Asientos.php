<?php
class Asientos extends Controller {
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(23);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = AsientosModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Asientos contables",
            'function_js' => "Asientos.js",
            'function_js_mod' => "CTBFun.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Asientos contables",
            'function_js' => "Asientos.js",
            'function_js_mod' => "CTBFun.js",
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = AsientosModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    //header('Location:' . base_url . '/Asientos');
                }
                //mostrar registro
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r['num_comp'],
                    'function_js' => "Asientos.js",
                    'function_js_mod' => "CTBFun.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Asientos');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Asientos');
    }
    public function destroy(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = [];
            $id = $_POST['id'];
            try {
                $r =  AsientosModel::borrar($id);
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = AsientosModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function codigo_moneda(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = AsientosModel::codigo_moneda($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function print($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = AsientosModel::print($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Asientos');
                }
                $this->views->getView($this, "print", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Asientos');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Asientos');
    }
    public function comp_x_defecto(){
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $id = $_POST['id'];
            $origen = $_POST['origen'];
            if ($origen == "O") {
                $id_tipcom = AsientosModel::comp_x_defecto_CTB($id);
            }
            echo json_encode($id_tipcom, JSON_UNESCAPED_UNICODE);
        }
    }
    public function datosCue(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id_cue'];
            $r = AsientosModel::datosCue($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $r = AsientosModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            //Asignar valroes a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            $usaConsecutivo = false;
            if (empty($id)) {
                $modo = 'create_user';
                //Verificar si usa consecutivo
                $r = AsientosModel::var_consec($id_emp);
                if ($r['consecu_config'] == 'S') {
                    $usaConsecutivo = true;
                    $r = AsientosModel::nextNumber($id_emp, $r['numdia_config'], $fecha_comp);
                    $num_comp = intval($r['num_comp']) + 1;
                }
            }
            $xtasa = convert_string_to_number($tasa_cambio);
            try {
                $data = [
                    'id_emp' => $id_emp,
                    'id_tipcom' => $id_tipcom,
                    'num_comp' => $num_comp,
                    'fecha_comp' => $fecha_comp,
                    'id_moneda' => $id_moneda,
                    'tasa_cambio' => $xtasa,
                    'desc_comp' => limpiar($desc_comp),
                    'ori_comp' => 'CTB',
                    'status' => $status,
                    $modo => $_SESSION['id_user']
                ];
                //Guardar y/o Actualziar encabezado de Comprobante
                if (empty($id)) {
                    $id = AsientosModel::guardar($data);
                    $title = "Registro se ha agregado satisfactoriamente";
                } else {
                    $del_det = AsientosModel::borrardet($id);
                    $title = "Registro se ha modificado satisfactoriamente";
                }
                //Guardar detalle de Comprobante
                $tot_det = count($_POST['id_ctb']);
                for ($i = 0; $i < $tot_det; $i++) {
                    $id_cue = $_POST['id_ctb'][$i];
                    $id_aux = (isset($_POST['id_aux'][$i]) && $_POST['id_aux'][$i] > 0) ? $_POST['id_aux'][$i] : 0;
                    $tipo = $_POST['tipo'][$i];
                    $det_observa = $_POST['descrip_deta'][$i];
                    if ($tipo == "D") {
                        $det_monto = convert_string_to_number($_POST['mon_debe'][$i]);
                    } else {
                        $det_monto = convert_string_to_number($_POST['mon_habe'][$i]);
                    }
                    //Crear arrary para guardar
                    $data = [
                        'id_comp' => $id,
                        'id_cue' => $id_cue,
                        'id_aux' => $id_aux,
                        'det_observa' => $det_observa,
                        'det_tipo' => $tipo,
                        'det_monto' => $det_monto,
                        $modo => $_SESSION['id_user']
                    ];
                    AsientosModel::guardardet($data);
                }
                $msg = "Se ha salvado satisfactoriamente el Asiento Contable Nro. $num_comp ";
                $dataJson = [
                    'title' => $title,
                    'icon' => "success",
                    'msg' => $msg
                ];
            } catch (\PDOException $e) {
                $title = "Se ha presentado un error, intente luego";
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
}
