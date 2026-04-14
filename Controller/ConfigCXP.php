    <?php
class ConfigCXP extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(152);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = ConfigCXPModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Configuración de Cuentas por Pagar',
            'function_js' => 'ConfigCXP.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Configuración de Cuentas por Pagar",
            'function_js' => "ConfigCXP.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ConfigCXPModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ConfigCXP');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la Configuración de la Empresa  " . $r['nom_empresa'] ,
                    'function_js' => "ConfigCXP.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/ConfigCXP');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ConfigCXP');
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
                    'id_tdo' => $_POST['id_tdo'],
                    'id_retiva' => $_POST['id_retiva'],
                    'id_retislr' => $_POST['id_retislr'],
                    'con_ret_iva' => limpiar($_POST['con_ret_iva']),
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user']
                ];
             if(empty($_POST['id'])){
                    $id = ConfigCXPModel::guardar($data);
                    Alertas::new('Configuración de la empresa se ha creado exitosamente');
                }
                else{
                    $id = ConfigCXPModel::actualizar($data, $_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new('Configuración de la empresa se ha modificado exitosamente');
                }
                header('Location:' . base_url . '/ConfigCXP');
            } catch (Exception $e) {
                 Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/ConfigCXP');
            }
        }
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigCXPModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_config_cxp(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $r = ConfigCXPModel::show_config_cxp($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = ConfigCXPModel::all();
            echo  json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}