<?php
class CXPMovement extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(110);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = CXPMovementModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Listado de Movimientos',
            'function_js' => 'CXPMovement.js',
            'function_js_mod' => 'CXPFun.js', 
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "new", [
            'page_name' => "Nuevo Movimiento",
            'function_js' => "CXPMovement.js",
            'function_js_mod' => 'CXPFun.js',
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            $TConsecutivo = false;
            //Asignar valroes a variables
            foreach( $_POST as $key => $value){
                $$key = $value;
            }
            if(empty($id)){
                $modo = 'create_user';
                $r = to_obj(CXPMovementModel::val_tmo($id_tmocxp));
                if($r[0]->con_tmocxc == 'S'){
                    $TConsecutivo = true;
                    $r = to_obj((CXPMovementModel::nextNumber($id_emp, $id_tmocxp)));
                    $movem_number = intval($r->next_tmocxc);
                }
            }
            $movem_amount = 0;
            $tasa_cambio = convert_string_to_number($tasa_cambio);
            try {
                $data = [
                    'id_emp' => $id_emp,
                    'id_tmocxp' => $id_tmocxp,
                    'movem_number' => $movem_number,
                    'id_ent' => $id_ent,
                    'fecha_comp' => $fecha_comp,
                    'movem_descrip' => $movem_descrip,
                    'movem_amount' => $movem_amount,
                    'status' => $status,
                    'movem_origen' => $movem_origen,
                    'id_moneda' => $id_moneda,
                    'tasa_cambio' => $tasa_cambio,
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($id)){
                    //Guardar Encabezado
                    $id = CXPMovementModel::guardar($data);
                    $title = 'Registro agregado';
                    if($id){
                        //Actualizar el siguiente numero
                        if($TConsecutivo){
                            $data = 
                                [
                                    'next_tmocxc' => $movem_number + 1,
                                    'modify_user' => $_SESSION['id_user']
                            ];
                            $r = CXPMovementModel::setNextNumber($id_emp, $id_tmocxp, $data);
                        } 
                    }
                }else{
                    $upd = CXPMovementModel::actualizar($id, $data);
                    $del_det = CXPMovementModel::borrarDetCXPMovement($id);
                }
                //Guardar los detalles del moviento
                $tot_rows = count($_POST['id_cot']);
                for($i=0; $i < $tot_rows; $i++){
                    $xmonto = convert_string_to_number($_POST['mon_can'][$i]);
                    $id_doc = $_POST['id_cot'][$i];
                    $tip = CXPMovementModel::query("SELECT a.id_tdo, a.num_tdo FROM f3004 a INNER JOIN f3001 b ON b.id_tdoc = a.id_tdo WHERE a.id_cot = {$id_doc} ");
                    $id_tdo = $tip[0]['id_tdo'];
                    $num_tdo = $tip[0]['num_tdo'];
                    $data = [
                        'movem_id' => $id,
                        'id_tdo' => $id_tdo,
                        'num_tdo' => $num_tdo,
                        'id_cot' => $id_doc,
                        'monto_doc' => $xmonto,
                        'create_user' => $_SESSION['id_user']
                    ];
                    $id_det = CXPMovementModel::guardarDetMovement($data);
                    $movem_amount += $xmonto;
                }
                $r = CXPMovementModel::query("UPDATE f3008 SET movem_amount = $movem_amount WHERE id_movement = {$id}");
                if(empty($id)){
                    $msg = sprintf('Movimiento número %s creado satisfactoriamente', $movem_number);
                    $title = 'Registro agregado';
                }else{
                    $msg = sprintf('Movimiento número %s actualizado satisfactoriamente', $movem_number);
                    $title = 'Registro modificado';
                }
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg
                ];
            } catch (\PDOException $e) {
                $r = CXPMovementModel::delete_row($id);
                $title = "Se ha presentado un error, intente luego";
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => $title,
                    'icon' => 'error',
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_tipos_mov(){
        if($_SERVER['REQUEST_METHOD'] = 'POST'){
            $efecto = '';
            if(isset($_POST['efecto'])){
                $efecto = $_POST['efecto'];
            }
            $r = CXPMovementModel::listar_tipos_mov($efecto);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function val_tmo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXPMovementModel::val_tmo($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CXPMovementModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CXPMovement');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Movimiento " . $r[0]['cod_tmocxc'] . ' número ' . $r[0]['movem_number'],
                    'function_js' => "CXPMovement.js",
                    'function_js_mod' => 'CXPFun.js',
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CXPMovement');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CXPMovement');
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXPMovementModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXPMovementModel::delete_row($id);
            if($r){
                $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            }else{
                $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function print_movement($id){
        if(Permisos::deleter()){
           $id = intval(limpiar($id));
           if ($id > 0) {
              $r = CXPMovementModel::print_movement($id);
              if (empty($r)) {
                  Alertas::new('El registro no existe', 'warning');
                  header('Location:' . base_url . '/CXPMovement');
              }
              $this->views->getView($this, "print_movement", [
                 'r' => to_obj($r)
              ]);
           } else {
              header('Location:' . base_url . '/CXPMovement');
           }
           return;
        }
    }
    public function cargar_screen_main(){
        if($_SERVER['REQUEST_METHOD']){
            $r = CXPMovementModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row_det(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = CXPMovementModel::show_row_det($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}