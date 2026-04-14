<?php
/**
 * Clase para los metodos de la Confgiuración de Bancos
 * Creado por José Vargas el 16-01-2025 a las 10:39:00
 */
class ConfigBAN extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(153);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = ConfigBANModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Configuración de Bancos',
            'function_js' => 'ConfigBAN.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva Configuración de Bancos',
            'function_js' => 'ConfigBAN.js'
        ]);
    }
    public function edit($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if($id > 0){
                $r = ConfigBANModel::edit($id);
                if(empty($r)){
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ConfigBAN');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Editando la Configuración de la Empresa ' . $r['nom_empresa'],
                    'function_js' => 'ConfigBAN.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/ConfigBAN');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ConfigBAN');
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            $id = $_POST['id'];
            if(empty($id)){
                $data = ['id_emp' => $_POST['id_emp']];
                $modo = 'create_user';
            }
            try{
                $data += [
                    'id_bancon_CXC' => $_POST['id_bancon_CXC'],
                    'id_bancon_CXP' => $_POST['id_bancon_CXP'],
                    'id_bancon_RETIVA' => $_POST['id_bancon_RETIVA'],
                    'id_bantipmov_IGTF' => $_POST['id_bantipmov_IGTF'],
                    'id_bancon_IGTH' => $_POST['id_bancon_IGTH'],
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user']
                ];
                if(empty($id)){
                    $id = ConfigBANModel::guardar($data);
                    Alertas::new('Configuración de la Empresa se ha creado existosmente');
                }else{
                    $id = ConfigBANModel::actualizar($data, $_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new('Configuración de la Empresa se ha modificado exitosamente');
                }
                header('Location: ' . base_url . '/ConfigBAN');                
            }catch (\PDOException $e){
                Alertas::new($e->getMessage(), 'danger');
                header('Location: ' . base_url . '/ConfigBAN');
            }
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigBANModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_config(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigBANModel::show_config($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}