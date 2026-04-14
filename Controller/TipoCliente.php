<?php
class TipoCliente extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(175);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = TipoClienteModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Menú Tipo de Clientes',
            'function_js' => 'TipoCliente.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'new', [
            'page_name' => 'Nuevo registro de Tipo de Clientes',
            'function_js' => 'TipoCliente.js'
        ]);
    }
    public function edit($id) {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = TipoClienteModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/TipoCliente');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de Tipo de Cliente: <b>' . $r['description']. '</b>',
                    'function_js' => 'TipoCliente.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/TipoCliente');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/TipoCliente');
    }
    public function store() {
        $modo = 'modify_user';
        $data = array();
        $opcion = '';
        debug($_POST);
        //Asignar valores a variables recibidas
        $id = $_POST['id'];
        $description = strtoupper($_POST['description']);
        $status = $_POST['status'];
        try{
            //Preparar guardaddo
            if (!$id) {
                $modo = 'create_user';
                $data = [
                    "description" => $description,
                    "status" => $status,
                    $modo => $_SESSION["id_user"]
                ];
                $id = TipoClienteModel::guardar($data);
                if($id){
                    $opcion = 'creado';
                }
            } else {
                $data = [
                    "description" => $description,
                    "status" => $status,
                    $modo => $_SESSION["id_user"]
                ];
                $id = TipoClienteModel::actualizar($id, $data);
                if($id){
                    $opcion = 'modificado';
                    
                }
            }
            Alertas::new(sprintf('El registro %s se ha %s exitosamente con el id %s', $description, $opcion,  $id));
            header('Location:' . base_url . '/TipoCliente');
        } catch (\PDOException $e){
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/TipoCliente');
            
        }
    }
    public function destroy(){
        $dataJson = [];
        $id = intval(limpiar($_POST['id']));
        $description = limpiar($_POST['description']);
        $ide = TipoClienteModel::borrar($id);
        if($ide){
            $dataJson = [
                'icon' => 'success',
                'title' => 'Exito',
                'status' => true,
                'msg' => sprintf('El Tipo de Cliente %s se ha eliminado correctamente', $description)
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function description() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $description = $_POST['description'];
            $rows = to_obj(TipoClienteModel::description($description));
            $jsonData = array();
            if ($rows[0]->total != 0) {
                $jsonData['success'] = 1;
                $jsonData['message'] = 'Registro ya existe...';
            } else {
                $jsonData['success'] = 0;
                $jsonData['message'] = '';
            }
            echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            $r = TipoClienteModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = TipoClienteModel::edit($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}