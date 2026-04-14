<?php

include_once ROOT .DS . 'Models' . DS . 'EmpresasModel.php';
include_once ROOT .DS . 'Models' . DS . 'PurInvModel.php';
include_once ROOT .DS . 'Models' . DS . 'ConcepCXPModel.php';
include_once VARTAX;
class CXPDocument extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(109);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = CXPDocumentModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Listado de Documentos',
            'function_js' => 'CXPDocument.js',
            'function_js_mod' => 'CXPFun.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Documento",
            'function_js' => "CXPDocument.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CXPDocumentModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CXPDocument');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Documento " . $r['nom_tdoc'] . ' número ' . $r['num_tdo'],
                    'function_js' => "CXPDocument.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CXPDocument');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CXPDocument');
    }
    public function listar_conceptos(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $r = CXPDocumentModel::listar_conceptos($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXPDocumentModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function print_CXPDocument($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CXPDocumentModel::print_CXPDocument($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CXPDocument');
                }
                $this->views->getView($this, "print_CXPDocument", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CXPDocument');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CXPDocument');
    }
    public function val_tdo($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXPDocumentModel::val_tdo($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function val_aux(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = CXPDocumentModel::val_aux($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function doc_ped_cli(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $id_cli = $_POST['id_cli'];
            $fecha = $_POST['fecha_comp'];
            $id_moneda = $_POST['id_moneda'];
            $r = CXPDocumentModel::doc_ped_cli($id_emp, $id_cli, $fecha, $id_moneda); 
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function doc_ped_cli_one(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $id_cli = $_POST['id_cli'];
            $fecha = $_POST['fecha_comp'];
            $tipo_doc = $_POST['tipo_doc'];
            $num_doc = $_POST['num_doc'];
            $r = CXPDocumentModel::doc_ped_cli_one($id_emp, $id_cli, $fecha, $tipo_doc, $num_doc);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function print_RetIva($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
               $r = PurInvModel::print_retiva_enca($id, 'P');
               $d = PurInvModel::print_retiva_deta($id, 'P');
               if (empty($r)) {
                   Alertas::new('El registro no existe', 'warning');
                   header('Location:' . base_url . '/CXPDocument');
               }
               $this->views->getView($this, "print_RetIva", [
                'r' => to_obj($r),
                'd' => to_obj($d),
             ]);
            } else {
               header('Location:' . base_url . '/CXPDocument'); 
            }
            return;
         }
    }
    public function print_RetISLR($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
            $r = PurInvModel::print_retislr($id);
               if (empty($r)) {
                   Alertas::new('El registro no existe', 'warning');
                   header('Location:' . base_url . '/CXPDocument');
               }
               $this->views->getView($this, "print_RetISLR", [
                'r' => to_obj($r),
             ]);
            } else {
               header('Location:' . base_url . '/CXPDocument');
            }
            return;
         }
    }
    public function get_doc_cxc(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cot = $_POST['id_cot'];
            $id_moneda = $_POST['id_moneda'];
            $r = CXPDocumentModel::get_doc_cxc($id_cot, $id_moneda);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    //Llamada al Estado de Cuentsas por Pagar
    public function edo_cuenta(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $this->views->getView($this, 'edo_cuenta', [
            'page_name' => 'Estado de Cuentas por Pagar',
            'function_js' => 'CXPDocument3.js'
        ]);
    }
    public function edo_cuenta_data(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = "";
            $id_cli = false;
            if(isset($_POST['id_emp']) && $_POST['id_emp'] > 0){
                $id_emp = $_POST['id_emp'];
            }
            if (isset($_POST['id_cli']) && $_POST['id_cli'] > 0) {
                $id_cli = $_POST['id_cli'];
            }
            $r = CXPDocumentModel::edo_cuenta($id_emp, $id_cli);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $r = CXPDocumentModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
        $dataJson = [];
        $id = intval(limpiar($_POST['id']));
        try {
            $r = CXPDocumentModel::borrarDetCXPDocument($id, 0, "CXP");
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
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            //Asignar valores a variables
            foreach($_POST as $key => $value){
                $$key = $value;
            }
            if(empty($id)){
                $modo = "create_user";
            }
            //Validar si usa Consecutivo
            $tip_doc_cons = false;
            try {
                $r = to_obj(CXPDocumentModel::val_tdo($_POST['id_tdo']));
                if ($r->con_tdoc == 1) {
                    $r = to_obj(CXPDocumentModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                    $num_tdo = intval($r->num_tdoc);
                    //Validar si usa Consecutivo
                    $tip_doc_cons = true;
                }
                //Congifuracion de Empresa
                $id_emp = $_POST['id_emp'];
                $id_con_emp = EmpresasModel::edit($id_emp);
                //Configuracion del modulo
                $config_cxp = CXPDocumentModel::config_cxp($id_emp);
                $xtasa = convert_string_to_number($tasa_cambio);
                $data += [
                    "id_emp" => $id_emp,
                    "id_tdo" => $id_tdo,
                    'id_cli' => $id_cli,
                    'descrip_cot' => $descrip_cot,
                    'num_tdo' => $num_tdo,
                    'fecha_comp' => $fecha_comp,
                    'fecha_venci' => $fecha_venci,
                    'id_moneda' => $id_moneda,
                    'tasa_cambio' => $xtasa,
                    'descrip_cot' => $descrip_cot,
                    'num_control' => $num_control,
                    'status' => $status,
                    'id_retiva' => $id_retiva ?? 0,
                    $modo => $_SESSION['id_user']
                ];
                if(empty($id)){
                    //Guardar Encabezado de Documento
                    $id_det_doc = CXPDocumentModel::guardar($data);
                    if($id_det_doc && $tip_doc_cons){
                        //Actualizar el siguiente numero
                        $data = array();
                        $data = [
                            'num_tdoc' => intval($num_tdo + 1),
                            $modo => $_SESSION['id_user'],
                        ];
                        $num = CXPDocumentModel::setNextNumber($id_emp, $id_tdo, $data);
                    }
                    $title = "Registro agregado satisfactoriamente";
                }else{
                    //Actualizar Encabezao de Documento
                    $id_det_doc = CXPDocumentModel::actualizar($id, $data);
                    $id_det_doc = $id;                    
                    //Borrar detalles del Documento
                    $del_det_doc = CXPDocumentModel::borrarDetCXPDocument($id, 1);
                    $title = "Registro modificado satisfactoriamente";
                }
                //Guardar detalles de Documentos
                $itemTotal = count($_POST["id_con"]);
                $data = array();
                $mon_iva = 0;
                $sub_mon_iva = 0;
                $mon_exe = 0;
                $mon_bas = 0;
                $mon_doc = 0;
                $item = array();
                $xarrayISLR = array();
                //Valor del Iva
                $xvatTax = xvatTax($fecha_comp, "IVA");
                $xtasaIVA = floatval($xvatTax[0]["txr1_iva"]);
                //Recorrer detalles, 
                for($i = 0; $i < $itemTotal; $i++){
                    $id_concxc = $_POST["id_con"][$i];
                    if($id_concxc != $config_cxp["id_retiva"] && $id_concxc != $config_cxp["id_retislr"]){
                        $monto = floatval(convert_string_to_number($_POST["mon_con"][$i]));
                        $mon_iva = floatval(convert_string_to_number($_POST["mon_iva"][$i]));
                        $iva = $_POST["iva"][$i];
                        if($iva == "S"){
                            $mon_bas += $monto;
                        }else{
                            $mon_exe += $monto;
                        }
                        $id_aux = $_POST["id_aux"][$i] ?? "";
                        $mon_doc += ($monto + $mon_iva);
                        //Verificar si llega Ret de ISLR el concepto
                        
                        $val_concepto = ConcepCXPModel::ret_islr($id_concxc);
                        if($val_concepto){
                            $xarrayISLR = [
                                "monto" => $monto,
                                "id_ret" => $val_concepto["id_retislr"],
                                "por_rete" => $val_concepto["por_reten"],
                                "minimo" => $val_concepto["minimo"],
                                "maximo" => $val_concepto["maximo"],
                                "id_concxp" => $id_concxc,
                                "total_monto" => $mon_doc,
                                "total_base" => $monto,
                                "base_imp" => $val_concepto["fac_reten"],
                                "deducible" => 0,
                                "total_retenido" => $monto * ($val_concepto["por_reten"] / 100)
                            ];
                            array_push($item, $xarrayISLR);
                        }
                        $data = [
                            "id_cot" => $id_det_doc,
                            "id_concxp" => $id_concxc,
                            "iva" => $iva,
                            "monto" => $monto,
                            "mon_iva" => $mon_iva,
                            "id_aux" => ((isset($id_aux) && $id_aux != '' && $id_aux != 'undefined')) ? $id_aux : "0",
                            "create_user" => $_SESSION["id_user"],
                        ];                        
                        $sub_mon_iva += $mon_iva;
                        //Guardar Detalle de Documentos
                        $id_det = CXPDocumentModel::guardarDetDocument($data);
                    }
                }
                //Validar si se aplica Retencion de IVA
                $id_con_emp = EmpresasModel::edit($id_emp);
                $especial_contrib = $id_con_emp["especial_contrib"];
                $tencontre = true;
                if($especial_contrib == "S" && $sub_mon_iva != 0 && $id_retiva != ""){
                    $config_cxp = CXPDocumentModel::config_cxp($id_emp);
                    if(empty($id)){
                        $num_ret = $config_cxp["con_ret_iva"];
                    }else{
                        $con_retiva = CXPDocumentModel::con_retiva($id_det_doc, "CXP");
                        if($con_retiva){
                            $id_retiva_db = $con_retiva[0]["id"];
                            $num_ret = $con_retiva[0]["num_retiva"];
                        }else{
                            $tencontre = false;
                            $num_ret = $config_cxp["con_ret_va"];
                        }
                    }
                    $val_retiva = CXPDocumentModel::ret_iva($id_retiva);
                    $por_retiva = $val_retiva["tasa_retiva"];
                    $min_retiva = $val_retiva["min_retiva"];
                    //
                    $xmon_retiva = $sub_mon_iva * ($por_retiva / 100);
                    //
                    if($min_retiva !=0 && $xmon_retiva < $min_retiva){
                        $xmon_retiva = $min_retiva;
                    }
                    //Guardar Retencion de IVA
                    $data = [
                        "id_emp" => $id_emp,
                        "id_cot" => $id_det_doc,
                        "id_retiva" => $id_retiva,
                        "id_ent" => $id_cli,
                        "fecha_pago" => $fecha_comp,
                        "num_retiva" => $num_ret,
                        "tot_compras" => $mon_doc,
                        "tot_exento" => $mon_exe,
                        "tot_base" => $mon_bas,
                        "tasa_iva" => $xtasaIVA,
                        "tot_iva" => $sub_mon_iva,
                        'tot_ret' => $xmon_retiva,
                        "origen" => "CXP",
                        "por_retiva" => $por_retiva,
                        $modo => $_SESSION['id_user']
                    ];
                    //Guardar Retencion
                    if(empty($id)){
                        $r = CXPDocumentModel::save_retiva($data);
                        //Creado por
                        $data += [
                            "create_user" => $_SESSION["id_user"],
                        ];
                        //Actualizar el proximo numeroi de Retencion de IVA
                        $data_cfg_cxp = array();
                        $data_cfg_cxp = [
                            "con_ret_iva" => $num_ret + 1,
                            "modify_user" => $_SESSION["id_user"]
                        ];
                        $r = CXPDocumentModel::config_cxp_up($config_cxp["id_config"], $data_cfg_cxp);
                    }else{
                        //Modificado por
                        $data += [
                            "modify_user" => $_SESSION["id_user"],
                        ];
                        if($tencontre){
                            $r = CXPDocumentModel::update_retiva($id_retiva_db, $data);                            
                        }else{
                            $r = CXPDocumentModel::save_retiva($data);
                            $data_cfg_cxp = array();
                            $data_cfg_cxp = [
                                "con_ret_iva" => $num_ret + 1,
                                "modify_user" => $_SESSION["id_user"]
                            ];
                            $r = CXPDocumentModel::config_cxp_up($config_cxp["id_config"], $data_cfg_cxp);
                        }
                    }
                    //Guardar concepto de retencion de IVA en el detalle del documento
                    $id_con_retiva = $config_cxp["id_retiva"];
                    $data = [
                        "id_cot" => $id_det_doc,
                        "id_concxp" => $id_con_retiva,
                        "iva" => "N",
                        "monto" => $xmon_retiva *-1,
                        "mon_iva" => 0,
                        "create_user" => $_SESSION["id_user"],
                    ];
                    $id_det = CXPDocumentModel::guardarDetDocument($data);
                }
                //Guardar Ret de ISLR
                if($item){
                    //Borrar registor en caso de que exista
                    CXPDocumentModel::destroy_retislr($id_emp, $id_det_doc);
                    $total_retenido = 0;
                    foreach($item as $row){
                        $data = [
                            "id_emp" => $id_emp,
                            'id_cot' => $id_det_doc,
                            'id_concxp' => $row['id_concxp'],
                            'id_retislr' => $row['id_ret'],
                            'por_reten' => $row['por_rete'],
                            'total_monto' => $row['total_monto'],
                            'total_base' => $row['total_base'],
                            'base_imp' => $row['base_imp'],
                            'deducible' => $row['deducible'],
                            'total_retenido' => $row['total_retenido'],
                            'create_user' => $_SESSION['id_user'],
                        ];
                        $total_retenido += $row['total_retenido'];                        
                    }
                    //Guardar concepto de retencion de ISLR en el detalle del documento
                    $id_con_retislr = $config_cxp["id_retislr"];
                    $data = [
                        "id_cot" => $id_det_doc,
                        "id_concxp" => $id_con_retislr,
                        "iva" => "N",
                        "monto" => $total_retenido * -1,
                        "mon_iva" => 0,
                        "create_user" => $_SESSION["id_user"],
                    ];
                    $id_det = CXPDocumentModel::guardarDetDocument($data);
                }
                //Actualizar monto y saldo del documento
                $r = CXPDocumentModel::upd_mon_sal_doc($id_det_doc);
                if($r){
                    $monto = $r["mon_doc"];
                    $data = [
                        "mon_doc" => $monto,
                        "sal_doc" => $monto
                    ];
                    $r = CXPDocumentModel::actualizar($id_det_doc, $data);
                }
                $msg = "Se ha salvado satisfactoriamente el Documento $num_tdo";
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
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
}