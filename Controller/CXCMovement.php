<?php
class CXCMovement extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(95);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = CXCMovementModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Listado de Movimientos',
            'function_js' => 'CXCMovement.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Movimiento",
            'function_js' => "CXCMovement.js"
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            $movem_number =  $_POST['movem_number'] ?? 0;
            $movem_descrip = $_POST['movem_descrip'] ?? '';
            if(empty($_POST['id'])){
                $modo = 'create_user';
                $data = [
                    'id_emp' => $_POST['id_emp'],
                    'id_tmocxc' => $_POST['id_tmocxc'],
                ];
                $r = to_obj(CXCMovementModel::val_tmo($_POST['id_tmocxc']));
                if($r[0]->con_tmocxc == "S"){
                    $r = to_obj(CXCMovementModel::nextNumber($_POST['id_emp'], $_POST['id_tmocxc']));
                    $movem_number = intval($r->next_tmocxc);
                }
            }
            try {
                $xtasa = convert_string_to_number($_POST['tasa_cambio']);
                $xtasa = number_format($xtasa, 8);
                $data += [
                    'movem_number' => $movem_number,
                    'id_cli' => $_POST['id_cli'],
                    'fecha_comp' => $_POST['fecha_comp'],
                    'movem_descrip' => $_POST['movem_descrip'],
                    'status' => $_POST['status'],
                    'movem_origen' => 'CXC',
                    'id_moneda' => $_POST['id_moneda'],
                    'tasa_cambio' => $xtasa,
                    $modo => $_SESSION['id_user']
                ];
                if(empty($_POST['id'])){
                    //Guardar encabezado de movimientos
                    $id = CXCMovementModel::guardar($data);
                    $id_det_mov = $id;
                    if($id){
                        $movem_number = $movem_number + 1;
                        $data1 = array();
                        $data1 = [
                            'next_tmocxc' => $movem_number,
                            $modo => $_SESSION['id_user'],
                        ];
                        //Actualizar el siguiente número de movimiento
                         $r = to_obj(CXCMovementModel::val_tmo($_POST['id_tmocxc']));
                        if($r[0]->con_tmocxc == "S"){
                            $num = CXCMovementModel::setNextNumber($data['id_emp'], $data['id_tmocxc'], $data1);
                        }
                    }
                    $r = (CXCMovementModel::nextNumber($_POST['id_emp'], $_POST['id_tmocxc']));
                    Alertas::new(sprintf('El movimiento %s - %s número %s se ha creado exitosamente', $r['cod_tmocxc'], $r['des_tmocxc'], $movem_number-1));
                }else{
                    $id = CXCMovementModel::actualizar($_POST['id'], $data);
                    $id_det = CXCMovementModel::borrarDetCXCMovement($_POST['id']);
                    $id = $_POST['id'];
                    $id_det_mov = $id;
                    $r = (CXCMovementModel::nextNumber($_POST['id_emp'], $_POST['id_tmocxc']));
                    Alertas::new(sprintf('El documento %s - %s número %s se ha modificado exitosamente', $r['tipo_codigo'], $r['nom_tdoc'], $movem_number));
                }
                //Guardar detalle de movimiento
                $itemTotal = count($_POST['id_cot']);
                $data2 = array();
                $xmon_movi = 0;
                $id_moneda = $_POST['id_moneda'];
                for ($i=0; $i < $itemTotal; $i++) {
                    $xmonto = convert_string_to_number($_POST['mon_can'][$i]);
                    $xmoret = convert_string_to_number($_POST['mon_ret'][$i]);
                    $num_ret = $_POST['num_ret'][$i];
                    if($xmonto != 0){
                        $id_cot = $_POST['id_cot'][$i];
                        $tdo = CXCMovementModel::query("SELECT num_tdo, id_tdo, id_moneda, tasa_cambio FROM f6003 WHERE id_cot = {$id_cot}");
                        $id_tdo = $tdo[0]['id_tdo'];
                        $num_tdo = $tdo[0]['num_tdo'];
                        $moneda_doc = $tdo[0]['id_moneda'];
                        $tasa_cambio_fac = $tdo[0]['tasa_cambio'];
                        $data2 = [
                            'movem_id' => $id_det_mov,
                            'id_tdo' => $id_tdo,
                            'id_cot' => $id_cot,
                            'num_tdo' => $num_tdo,
                            'monto_doc' => ROUND($xmonto, 2),
                            'mon_ret' => ROUND($xmoret,2),
                            'num_ret' => $num_ret,
                            'create_user' => $_SESSION['id_user'],
                        ];
                        if($_POST['id_moneda'] != $moneda_doc){
                            $monto_doc_for = ($xmonto / $tasa_cambio_fac);
                            $monto_ret_for = ($xmoret / $tasa_cambio_fac);
                            $data2 += [
                                'monto_doc_for' => ROUND($monto_doc_for,2 ),
                                'monto_ret_for' => ROUND($monto_ret_for, 2),
                            ];
                        }
                        $xmon_movi += $xmonto;
                        $id_det = CXCMovementModel::guardarDetMovement($data2);
                    }
                }
                $data2 = array();
                $data2 = [
                    'movem_amount' => $xmon_movi
                ];
                $id = CXCMovementModel::actualizar($id_det_mov, $data2);
            } catch (Exception $e) {
                Alertas::new($e->getMessage(), 'danger');                
            }finally{
                header('Location:' . base_url . '/CXCMovement');
            }
        }
    }
    public function listar_tipos_mov(){
        if($_SERVER['REQUEST_METHOD'] = 'POST'){
            $efecto = '';
            if(isset($_POST['efecto'])){
                $efecto = $_POST['efecto'];
            }
            $r = CXCMovementModel::listar_tipos_mov($efecto);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function val_tmo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXCMovementModel::val_tmo($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CXCMovementModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CXCMovement');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Movimiento " . $r[0]['cod_tmocxc'] . ' número ' . $r[0]['movem_number'],
                    'function_js' => "CXCMovement.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CXCMovement');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CXCMovement');
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXCMovementModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row_det(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = CXCMovementModel::show_row_det($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXCMovementModel::delete_row($id);
            if($r){
                $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            }else{
                $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function print_movement($id){
       if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CXCMovementModel::print_mov($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CXCMovement');
                }
                $this->views->getView($this, "print_movement", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CXCMovement');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CXCMovement');
    }
    public function cargar_screen_main(){
        $r = CXCMovementModel::all();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
}