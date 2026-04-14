<?php
class ConfigFAC extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(96);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = ConfigFACModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Configuración',
            'function_js' => 'ConfigFAC.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Configuración",
            'function_js' => "ConfigFAC.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ConfigFACModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ConfigFAC');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la Configuración de la Empresa  " . $r['nom_empresa'] ,
                    'function_js' => "ConfigFAC.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/ConfigFAC');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ConfigFAC');
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
                    'id_con_sales' => limpiar($_POST['id_con_sales']),
                    'id_tdoc_fac' => limpiar($_POST['id_tdoc_fac']),
                    'id_tdoc_cre' => $_POST['id_tdoc_cre'],
                    'id_tdoc_pre' => $_POST['id_tdoc_pre'],
                    'id_tdoc_not' => $_POST['id_tdoc_not'],
                    'id_tdoc_not_no_fis' => $_POST['id_tdoc_not_no_fis'],
                    'id_tdoc_dev' => $_POST['id_tdoc_dev'],
                    'note_fac' => $_POST['note_fac'],
                    'note_cre' => $_POST['note_cre'],
                    'note_pre' => $_POST['note_pre'],
                    'note_not' => $_POST['note_not'],
                    'note_not_no_fis' => $_POST['note_not_no_fis'],
                    'note_dev' => $_POST['note_dev'],
                    'tmov_fac' => $_POST['tmov_fac'],
                    'tmov_noc' => $_POST['tmov_noc'],
                    'id_alm' => $_POST['id_alm'],
                    'id_ubi' => $_POST['id_ubi'],
                    'status' => $_POST['status'], 
                    $modo => $_SESSION['id_user']
                ];
             if(empty($_POST['id'])){
                    $id = ConfigFACModel::guardar($data);
                    Alertas::new('Configuración de la empresa se ha creado exitosamente');
                }else{
                    $id = ConfigFACModel::actualizar($data, $_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new('Configuración de la empresa se ha modificado exitosamente');
                }
                //Actualizacion Global, para todas las empresas
                $data = [
                    'fac_stock' => limpiar(!empty($_POST['fac_stock']) ? 1 : 0),
                    'loc_pri_cot' => limpiar(!empty($_POST['loc_pri_cot']) ? 1: 0),
                    'locked_invoice' => limpiar(!empty($_POST['locked_invoice']) ? 1: 0),
                    'cot_stock' => limpiar(!empty($_POST['cot_stock']) ? 1 : 0),
                    'loc_pri_inv' => limpiar(!empty($_POST['loc_pri_inv']) ? 1: 0),
                ];
                $act_glo = ConfigFACModel::act_glo($data);
                header('Location:' . base_url . '/ConfigFAC');
            } catch (Exception $e) {
                 Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/ConfigFAC');
            }
        }
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigFACModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_config_fac(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigFACModel::show_config_fac($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function val_fact(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = ConfigFACModel::query("SELECT locked_invoice FROM f4999 LIMIT 1");
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}