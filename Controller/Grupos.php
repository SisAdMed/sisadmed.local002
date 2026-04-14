<?php
class Grupos extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(176);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = GruposModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Grupo',
            'function_js' => 'Grupos.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'new', [
            'page_name' => 'Nuevo registro de Grupo',
            'function_js' => 'Grupos.js'
        ]);
    }
    public function edit($id) {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = GruposModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Grupos');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de Grupo',
                    'function_js' => 'Grupos.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/Grupos');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Grupos');
    }
    public function store() {
        $modo = 'modify_user';
        $data = array();
        $id = $_POST['id'];
        $grupo_codigo = $_POST['grupo_codigo'];
        $grupo_nombre = strtoupper($_POST['grupo_nombre']);
        $status = $_POST['status'];
        $user = $_SESSION['id_user'];
        $data = [
            'grupo_codigo' => $grupo_codigo,
            'grupo_nombre' => $grupo_nombre,
            'status' => $status,
        ];
        if(empty($id)){
            $modo = 'create_user';
            $data += [
                $modo => $user,
            ];
            $r = GruposModel::guardar($data);
            $msg = sprintf('Registro Código %s con la Descripción %s creado satisfactoriamente', $grupo_codigo, $grupo_nombre);
            $title = 'Registro agregado';
        }else{
            $data += [
                $modo => $user,
            ];
            $r = GruposModel::actualizar($id, $data);
            $msg = sprintf('Registro Código %s con la Descripción %s actualizado satisfactoriamente', $grupo_codigo, $grupo_nombre);
            $title = 'Registro modificado';
        }
        if($r){
            $dataJson = [
                'title' => $title,
                'icon' => 'success',
                'msg' => $msg
            ];
        }else{
            $dataJson = [
                'title' => $title,
                'icon' => 'error',
                'msg' => 'Error al momento de crar y/o actualizar el registro, por favor inente luego',
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        /*
        try{
            header('Location:'. base_url . '/Grupos');
        } catch (\PDOException $e){
            Alertas::new($e->getMessage(), 'danger');
            header('Location:'. base_url . '/Grupos');
        }
        */
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
            $ide = GruposModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente', )
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function next_codigo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = GruposModel::next_codigo();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = GruposModel::edit($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = GruposModel::borrar($id);
            if ($r) {
                $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            } else {
                $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
}
?>