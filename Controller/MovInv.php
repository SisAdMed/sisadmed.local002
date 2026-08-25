<?php
class MovInv extends Controller{
    #[Override]
    public function __construct(){
        Auth::noAuth();        
        parent::__construct();
        Permisos::getPermisos(97);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil' );
        }
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta Movimientos de Inventarios',
            'function_js' => 'MovInv.js?v=' . SITE_VERSION,
            'function_js_mod' => 'INVFun.js?v=' . SITE_VERSION
        ]);
    }
    public function cargar_screen_main(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $datos_tabla = [];
            $r = MovInvModel::cargar_screen_main();
            foreach($r as $p){
                $datos_tabla[] = array_merge($p, [
                    "token_edit" => encriptar_url(json_encode(['accion' => 'edit', 'id' => $p['id_movinv']]))
                ]);
            }            
            echo json_encode($datos_tabla, JSON_UNESCAPED_UNICODE);
        }
    }
    public function new(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo Movimiento de Inventario',
            'function_js' => 'MovInv.js?v=' . SITE_VERSION,
            'function_js_mod' => 'INVFun.js?v=' . SITE_VERSION
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data_enca = array();
            $date_deta = array();
			$dataJson = array();
            foreach ($_POST as $key => $value) {
				$$key = $value;
			}
			$modu = 'modify_user';
            $modd = 'modify_date';
			if (empty($id)) {
				$modu = "create_user";
                $modd = "create_date";
			}
            $tasa = str_replace(',', '.', $_POST['tasa_cambio']);
            $xtasa = number_format($tasa, 8);
            $data_enca = [
                'id_emp' => $id_emp,
                'id_tmovinv' => $id_tmovinv,
                'num_movinv' => $num_movinv,
                'fecha_comp' => $fecha_comp,
                'id_moneda' => $id_moneda,
                'tasa_cambio' => $xtasa,
                'id_alm' => $id_alm,
                'descrip_movinv' => limpiar($descrip_movinv),
                'status' => $status,
                'id_cot' => $id_cot ?? null,
                'id_cli' => $id_cli ?? 0,
                'id_vend' => $id_vend ?? null,
                'id_fab' => $id_fab ?? null,
                'origen' => $origen  ?? null,
                $modu => $_SESSION['id_user'],
                $modd => getAuditoria()
            ];            
            $detalles = json_decode($detalle, true);
            foreach($detalles as $row){                
                $id_prod = MovinvModel::cons_producto($row['id_prod']);                
                $date_deta[] = [
                    'id_movinv' => $id ?? '',
                    'id_prod' => $row['id_prod'],
                    'id_ubi' => $row['id_ubi'],
                    'cantidad' => floatval($row['cantidad']),
                    'costo' => $id_prod['costo_prod'],
                    'flete' => $id_prod['flete_prod'],
                    'otros_cargos' => $id_prod['otros_prod'],
                    'door_cargos' => $id_prod['door_costo'],
                    'costo1' => $id_prod['costo1'],
                    'lote' => trim(mb_strtoupper($row['lote'] ?? ' ', 'UTF-8')),
                    'fec_venc' => !empty($row['fec_venc']) ? $row['fec_venc'] : '0000-00-00',
                    'create_user' => $_SESSION['id_user'],
                    'create_date' => getAuditoria(),
                ];                
            }                
            $r = MovinvModel::guardar($data_enca, $date_deta, $id);                        
            echo json_encode($r, JSON_UNESCAPED_UNICODE);            
        }
    }
    public function gestion($token = null){
        if(!$token) return;
        $datos = desencriptar_url($token);
        switch($datos['accion']){
            case 'edit':
                $this->edit($datos['id']);
                break;
            default:
                break;
        }
    }
    public function edit(int $id){
        if(Permisos::read()){    
            $r = MovInvModel::edit($id);
            $this->views->getView($this, 'edit', [
                'page_name' => sprintf('Modificando el Movimiento de Inventario %s número %s.', $r['nom__tmoinv'], $r['num_movinv']),
                'function_js' => 'MovInv.js?v=' . SITE_VERSION,
                'function_js_mod' => 'INVFun.js?v=' . SITE_VERSION,
                'r' => $r
            ]);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = MovInvModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $dataJson = [];
            $id = $_POST['id'];
            $code = $_POST['recordCode'];
            $recordName = $_POST['recordName'];
            $recordNumbre =$_POST['recordNumbre'];
            $r = MovInvModel::destroy($id);            
            if($r['status']){
                $dataJson = [
                    'icon' => 'success',
                    'title' => 'Eliminación existosa',
                    'msg' => 'Movimiento de Inventario ' . $code . ' - ' .  $recordName . ' - ' . 'número ' . $recordNumbre . 'eliminado satisfactoriamente'
                ];
            }else{
                $dataJson = [
                    'icon' => $r['error'],
                    'title' => $r['title'],
                    'msg' => $r['msg'],
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function index2(){
        $this->views->getView($this, "index2", [
            'page_name' => "Consulta Movimientos de Inventario",
            'function_js' => "MovInvConInv.js",
        ]);
    }
    public function index3(){
        $this->views->getView($this, "index3", [
            'page_name' => "Reporte Movimientos de Inventario",
            'function_js' => "MovInvConInv.js",
        ]);
    }
    public function conmovinv(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_alm = "";
            if($_POST['id_alm']){
                $id_alm = $_POST['id_alm'];
            }
            $id_emp = $_POST['id_emp'];
            //$id_alm = $_POST['id_alm'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $id_fab = $_POST['id_fab'];
            $id_ubi = $_POST["id_ubi"];
            $id_prod = $_POST['id_prod'];
            $r = MovInvModel::conmovinv($id_emp, $fec_ini, $fec_fin, $id_alm, $id_fab, $id_prod, $id_ubi);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function InvCons(){
        $this->views->getView($this, "InvCons", [
            'page_name' => "Inventarios de Consignados",
            'function_js' => "InvCons.js",
        ]);
    }
    public function InvPpal(){
        $this->views->getView($this, "InvPpal", [
            'page_name' => "Inventario Almacén Principal",
            'function_js' => "InvPpal.js",
        ]);
    }
    public function update_lotes(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $origen = $_POST['origen'];
            $r = MovInvModel::update_lotes($origen);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function printer_movinv($id = 0) {
    $id = intval($id);  
        if ($id > 0) {
            $r = MovInvModel::print_movement($id);
            if (empty($r)) {
                Alertas::new('El registro no existe', 'warning');
                header('Location: ' . base_url . '/MovInv');
                exit;
            }        
            $this->views->getView($this, "printer_movinv", [
                'r' => to_obj($r)
            ]);
            exit;
        }
        header('Location: ' . base_url . '/MovInv');
        exit;
    }
}