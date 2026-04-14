<?php
class ConfigCOM extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(127);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = ConfigCOMModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Configuración de Compras',
            'function_js' => 'ConfigCOM.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Configuración de Compras",
            'function_js' => "ConfigCOM.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ConfigCOMModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ConfigCOM');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la Configuración de Compras de   " . $r['nom_empresa'] ,
                    'function_js' => "ConfigCOM.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/ConfigCOM');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ConfigCOM');
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'create_user';
            $data = array();
            if(empty($_POST['id'])){
                $data = ['id_emp' => limpiar($_POST['id_emp'])];
                $modo = 'create_user';
            }
            try {
                $data += [
                    'con_purcon' => limpiar($_POST['con_purcon']),
                    'tdoc_pur' => limpiar($_POST['tdoc_pur']),
                    'tdoc_purcrenot' => $_POST['tdoc_purcrenot'],
                    'tdoc_purord' => $_POST['tdoc_purord'],
                    'tdoc_purdelnot' => $_POST['tdoc_purdelnot'],
                    'tdoc_purretnot' => $_POST['tdoc_purretnot'],
                    'id_alm' => $_POST['id_alm'],
                    'id_typmovinwar' => $_POST['tmov_pur'],
                    'id_typmovoutwar' => $_POST['tmov_pur_sal'],
                    'id_ubi' => $_POST['id_ubi'],
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user']
                ];
             if(empty($_POST['id'])){
                    $id = ConfigCOMModel::guardar($data);
                    Alertas::new('Configuración de la empresa se ha creado exitosamente');
                }
                else{
                    $id = ConfigCOMModel::actualizar($data, $_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new('Configuración de la empresa se ha modificado exitosamente');
                }
                header('Location:' . base_url . '/ConfigCOM');
            } catch (Exception $e) {
                 Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/ConfigCOM');
            }
        }
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigCOMModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_config_fac(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigCOMModel::show_config_fac($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}