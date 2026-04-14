<?php
class ParametrosCtb extends Controller {
	public function __construct() {
		Auth::noAuth();
		parent::__construct();
		Permisos::getPermisos(39);
	}
	public function index() {
		if(empty($_SESSION['permisosMod']['r'])){
			header('Location:' . base_url . '/Perfil');
		}
		$objeto = ParametrosCtbModel::all();
		$this->views->getView($this, "index", [
			'page_name' => "Configuración de Contabilidad",
			'function_js' => 'ParametrosCtb.js',
			'r' => to_obj($objeto)
		]);
	}
	 public function nuevo() {
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nueva Configuración de Contabilidad",
         'function_js' => "ParametrosCtb.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = ParametrosCtbModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/ParametrosCtb');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nombre_emp'],
               'function_js' => "ParametrosCtb.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/ParametrosCtb');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/ParametrosCtb');
   }
	public function store() {
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			try {
				$modo = 'modify_user';
				$datos = array();
				if(empty($_POST['id'])){
					$datos = ['id_emp' => limpiar($_POST['id_emp']) ?? ''];
					$modo = 'create_user';
				}
				$datos += [
					'id_tipcom' => ($_POST['id_tipcom']) ?? '',
					'id_tipcomp' => ($_POST['id_tipcomp']) ?? '',
					'id_cuegyp' => limpiar($_POST['id_cuegyp']) ?? '',
					'consecu_config' => $_POST['consecu_config'] ?? '',
					'numdia_config' => $_POST['numdia_config'] ?? '',
					'id_cuenom' => limpiar($_POST['id_cuenom']) ?? '',
					'id_cueval' => limpiar($_POST['id_cueval']) ?? '',
					'id_cuecos' => limpiar($_POST['id_cuecos']) ?? '',
					'id_cueinv' => limpiar($_POST['id_cueinv']) ?? '',
					$modo => $_SESSION['id_user']
				];
				if(empty($_POST['id'])){
					$id = ParametrosCtbModel::guardar($datos);
					Alertas::new('La Configuración se ha creado de manera satisfactoria');
				}else{
					$id = ParametrosCtbModel::actualizar($_POST['id'], $datos );
					Alertas::new('La confgiuración se ha actualizado de manera satisfactoria');
				}
				header('Location: ' . base_url . '/ParametrosCtb/');
			} catch (\PDOException $e) {
				Alertas::new($e->getMessage(), 'danger');
				header('Location: ' . base_url . '/ParametrosCtb/');
			}
		}
	}
	//Llenar Selects Tipos de Comprobantes de Contabilidad
	public function listar_tipos_comprobantes(){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$r = ParametrosCtbModel::listar_tipos_comprobantes();
			echo json_encode($r);
		}
	}
    public function showrow(){
    	if($_SERVER['REQUEST_METHOD'] == 'POST'){
    		$id= $_POST['id'];
    		$r = ParametrosCtbModel::showrow($id);
    		echo json_encode($r, JSON_UNESCAPED_UNICODE);
    	}
    }
}