<?php
class Almacen extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent:: __construct();
        Permisos::getPermisos(51);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = AlmacenModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Almacenes",
            'function_js' => "Almacenes.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Almacen",
            'function_js' => "Almacenes.js"
        ]);
    }
    public function next_codigo(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $codigo1 = AlmacenModel::next_codigo($_POST['id_emp']);
            echo json_encode($codigo1, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            //Asignar variables
            //Asignar Valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            $codigo = '';
            if(empty($_POST['id'])){
                $modo = 'create_user';
            }
            try {
                $data += [
                    'id_emp' => $id_emp,
                    'cod_alm' => $cod_alm,
                    'nom_alm' => $nom_alm,
                    'con_alm' => $con_alm,
                    'email_alm' => $email_alm,
                    'tel_alm' => $tel_alm,
                    'dir_alm' => $dir_alm,
                    'id_cli' => $id_cli,
                    'status' => $status,
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($id)){
                    $id = AlmacenModel::guardar($data);
                    $title = sprintf('El Almacén %s se ha creado exitosamente con el id %s', $data['nom_alm'], $id);
                    $msg = "Registro agregado";
                }else{
                    $id_upd = AlmacenModel::actualizar($id, $data);
                    $title = sprintf('El Almacén %s se ha modificado exitosamente con el id %s', $data['nom_alm'], $id);
                    $msg = "Registro modificado";
                }
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg
                ];
            } catch (\PDOException $e) {
                $title = "Se ha presentado un error, intente luego";
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => $title,
                    'icon' => 'error',
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = AlmacenModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Almacen');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r['nom_alm'],
                    'function_js' => "Almacenes.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Almacen');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Almacen');
    }
    public function destroy(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $dataJson = [];
            $id = $_POST['id'];
            $code = $_POST['code'];
            $name = $_POST['name'];
            try {
                $ide = AlmacenModel::borrar($id);
                if ($ide) {
                    $dataJson = [
                        'title' => 'Registro eliminado',
                        'icon' => 'success',
                        'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $code, $name)
                    ];
                } 
            } catch (\PDOException $e) {
                $dataJson = [
                    'title' => 'Error en eliminar el registro',
                    'icon' => 'error',
                    'msg' => 'Error ' . $e->getMessage()
                ];
            }
           
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
       
    }
    public function listar_almacenes(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = intval($_POST['id_emp']);
            $r = AlmacenModel::listar_almacenes($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_almacenes_ppal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = intval($_POST['id_emp']);
            $r = AlmacenModel::listar_almacenes_ppal($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_entidad_modal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = intval(($_POST['id_emp']));
            $r = AlmacenModel::listar_entidad_modal($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = AlmacenModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = AlmacenModel::edit($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}