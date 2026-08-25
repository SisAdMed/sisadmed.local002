<?php
class ConfigCXC extends Controller
{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(94);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $r = ConfigCXCModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Configuración',
            'function_js' => 'ConfigCXC.js?v=' . SITE_VERSION,
            'function_js_mod' => 'CXCFun.js?v=' . SITE_VERSION,
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Configuración",
            'function_js' => 'ConfigCXC.js?v=' . SITE_VERSION,
            'function_js_mod' => 'CXCFun.js?v=' . SITE_VERSION,
        ]);
    }
    public function edit(int $id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ConfigCXCModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ConfigCXC');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la Configuración de la Empresa  " . $r['nom_empresa'],
                    'function_js' => 'ConfigCXC.js?v=' . SITE_VERSION,
                    'function_js_mod' => 'CXCFun.js?v=' . SITE_VERSION,
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/ConfigCXC');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ConfigCXC');
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {            
            $data = array();
            $dataJson = array();
            //Asignar valores a variables
            foreach($_POST as $key => $value){
                $$key = $value;
            }
            $modu = 'modify_user';
            $modd = 'modify_date';
            if(empty($id)){
                $modu = "create_user";
                $modd = "create_date";
            }         
            try {
                $data += [
                    'id_emp' => $id_emp,
                    'show_doc' => $show_doc,
                    'over_charges' => $over_charges,
                    'fir_due_date' => $fir_due_date ?? 0,
                    'sec_due_date' => $sec_due_date ?? 0,
                    'thi_due_date' => $thi_due_date ?? 0,
                    'fou_due_date' => $fou_due_date ?? 0,
                    'cant_dec' => $cant_dec,
                    'status' => $_POST['status'],
                    $modu => $_SESSION['id_user'],
                    $modd => getAuditoria(),
                ];                
                 if (empty($id)) {
                     $id = ConfigCXCModel::guardar($data);
                     $title = "Registro agregado";
                }else{
                    $id = ConfigCXCModel::actualizar($data, $_POST['id']);
                    $title = "Registro modificado";
                    $id = $_POST['id'];
                }
                $msg = sprintf("La Confgiuración se ha salvado satisfactoriamente, con el ID %s", $id);
				$dataJson = [
					'title' => $title,
					'icon' => "success",
					'msg' => $msg
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
    public function show_row(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = ConfigCXCModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_config_cxc(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $r = ConfigCXCModel::show_config_cxc($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = ConfigCXCModel::all();
            $datos_tabla = [];
            foreach ($r as $p) {
	            $datos_tabla[] = array_merge($p, ["token_edit" => encriptar_url(json_encode(['accion' => 'edit', 'id' => $p['id_config']]))
                ]);
            }   
            echo json_encode($datos_tabla, JSON_UNESCAPED_UNICODE);
        }
    }
    public function gestion($token = null){
		if (!$token) {
			return;
		}        
		$datos = desencriptar_url($token);        
		switch ($datos['accion']) {
			case 'edit':
				$this->edit($datos['id']);
				break;
			default:
				// Acción no permitida
				break;
		}
	}
}
