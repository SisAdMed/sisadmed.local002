<?php
class ConfigCXC extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(94);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = ConfigCXCModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Configuración',
            'function_js' => 'ConfigCXC.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Configuración",
            'function_js' => "ConfigCXC.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ConfigCXCModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ConfigCXC');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la Configuración de la Empresa  " . $r['nom_empresa'] ,
                    'function_js' => "ConfigCXC.js",
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
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            if(empty($_POST['id'])){
                $data = ['id_emp' => limpiar($_POST['id_emp'])];
                $modo = 'create_user';
            }
            try {
                $data += [
                    'show_doc' => limpiar($_POST['show_doc']),
                    'over_charges' => limpiar($_POST['over_charges']),
                    'fir_due_date' => $_POST['fir_due_date'] ?? 0,
                    'sec_due_date' => $_POST['sec_due_date'] ?? 0,
                    'thi_due_date' => $_POST['thi_due_date'] ?? 0,
                    'fou_due_date' => $_POST['fou_due_date'] ?? 0,
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user']
                ];
             if(empty($_POST['id'])){
                    $id = ConfigCXCModel::guardar($data);
                    Alertas::new('Configuración de la empresa se ha creado exitosamente');
                }
                else{
                    $id = ConfigCXCModel::actualizar($data, $_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new('Configuración de la empresa se ha modificado exitosamente');
                }
                header('Location:' . base_url . '/ConfigCXC');
            } catch (Exception $e) {
                 Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/ConfigCXC');
            }
        }
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigCXCModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_config_cxc(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $r = ConfigCXCModel::show_config_cxc($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}