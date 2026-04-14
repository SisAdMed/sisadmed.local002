<?php
class ConExcludeDash extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(178);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = ConExcludeDashModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Conceptos a Excluir',
            'function_js' => 'ConExcludeDash.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function new() {
        $this->views->getView($this, 'new', [
            'page_name' => 'Nuevo registro de Conceptos a Excluir',
            'function_js' => 'ConExcludeDash.js'
        ]);
    }
    public function edit($id) {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ConExcludeDashModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ConExcludeDash');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de Conceptos a Excluir',
                    'function_js' => 'ConExcludeDash.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/ConExcludeDash');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ConExcludeDash');
    }
    public function store() {
        $data = array();
        //Valores del formulario a guardar
        $id = $_POST['id'];
        $module = $_POST['mod'];
        $nom_mod = $_POST['nom_mod'];
        $id_concept = $_POST['id_concept'];
        $nom_con = $_POST['nom_con'];
        $status = $_POST['status'];
        $user = $_SESSION['id_user'];
        //Armar Array
        $data = [
            'module' => $module,
            'id_concept' => $id_concept,
            'status' => $status
        ];
        $msg = 'Error al momento de crar y/o actualizar el registro, por favor inente luego';
        $icon = 'error';
        try {
            if(empty($id)){
                $modo = 'create_user';
                $data += [$modo => $user];
                $r = ConExcludeDashModel::guardar($data);
                $msg = sprintf('Registro Módulo %s con el Concetp %s creado satisfactoriamente', $nom_mod, $nom_con);
                $title = 'Registro agregado';
                $icon = 'success';
            }else{
                $modo = 'modify_user';
                $data += [$modo => $user];
                $r = ConExcludeDashModel::actualizar($id, $data);
                $msg = sprintf('Registro Módulo %s con el Concetp %s creado satisfactoriamente', $nom_mod, $nom_con);
                $title = 'Registro modificado';
                $icon = 'success';
            }
            $dataJson = [
                'title' => $title,
                'icon' => $icon,
                'msg' => $msg
            ];
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        } catch (\PDOException $e){
            $title = 'No se ha podido realizar la transacción';
            $msg = sprintf('Se ha generado un error: %s ',  $e->getMessage());
            $dataJson = [
                'title' => $title,
                'icon' => 'error',
                'msg' => $msg
            ];
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
        $dataJson = [];
        if(empty($_POST['id'])) {
            $dataJson = [
                'status' => false,
                'msg' => 'No se recibieron los datos'
            ];
        }else{
            $id = intval(limpiar($_POST['id']));
            $ide = ConExcludeDashModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente', )
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function load_scream_main(){
        $r = ConExcludeDashModel::all();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConExcludeDashModel::edit($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete_row(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = ConExcludeDashModel::borrar($id);
            if ($r) {
                $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            } else {
                $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
}