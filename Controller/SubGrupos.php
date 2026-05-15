<?php
class SubGrupos extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(177);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = SubGruposModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Sub Grupos',
            'function_js' => 'SubGrupos.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, 'new', [
            'page_name' => 'Nuevo registro de Sub Grupo',
            'function_js' => 'SubGrupos.js'
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = SubGruposModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/SubGrupos');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro ',
                    'function_js' => 'SubGrupos.js',
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/SubGrupos');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/SubGrupos');
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modo = 'modify_user';
            $data = array();
            //Variables
            $id = $_POST['id'];
            $id_grupo = $_POST['id_grupo'];
            $nombre_grupo = $_POST['nombre_grupo'];
            $sub_grupo_nombre = $_POST['sub_grupo_nombre'];
            $status = $_POST['status'];
            $user = $_SESSION['id_user'];
            //Array
            $data = [
                'id_grupo' => $id_grupo,
                'sub_grupo_nombre' => $sub_grupo_nombre,
                'status' => $status
            ];
            try {
                if (empty($id)) {
                    $modo = 'create_user';
                    $data += [
                        $modo => $user,
                    ];
                    $r = SubGruposModel::guardar($data);
                    $msg = sprintf('Registro Grupo %s SubGrupo %s. creado satisfactoriamente', $nombre_grupo, $sub_grupo_nombre);
                    $title = 'Registro agregado';
                } else {
                    $data += [
                        $modo => $user,
                    ];
                    $r = SubGruposModel::actualizar($id, $data);
                    $msg = sprintf('Registro Grupo %s SubGrupo %s, actualizado satisfactoriamente', $nombre_grupo, $sub_grupo_nombre);
                    $title = 'Registro modificado';
                }
                if ($r) {
                    $dataJson = [
                        'title' => $title,
                        'icon' => 'success',
                        'msg' => $msg
                    ];
                } else {
                    $dataJson = [
                        'title' => $title,
                        'icon' => 'error',
                        'msg' => 'Error al momento de crear y/o actualizar el registro, por favor inente luego',
                    ];
                }
            } catch (\PDOException $e) {
                $title = "Se ha presentado un error, intente luego";
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage() );
                $dataJson = [
                    'title' => $title,
                    'icon' => 'error',
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy()
    {
        $dataJson = [];
        if (empty($_POST['id'])) {
            $dataJson = [
                'status' => false,
                'msg' => 'No se recibieron los datos'
            ];
        } else {
            $id = intval(limpiar($_POST['id']));
            $ide = SubGruposModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente',)
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = SubGruposModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete_row() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = SubGruposModel::borrar($id);
            if ($r) {
                $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            } else {
                $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function getSubgrupos(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = SubGruposModel::getSubgrupos($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
