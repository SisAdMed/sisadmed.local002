<?php
class ProveeInt extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent:: __construct();
        Permisos::getPermisos(77);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = ProveeIntModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Proveedores Internacionales",
            'function_js' => "ProveeInt.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Proveedor Internacional",
            'function_js' => "ProveeInt.js"
        ]);
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            if(empty($_POST['id'])){
                $modo = 'create_user';
            }
            try {
                $data = [
                    'nombre_provint' => limpiar($_POST['nombre_provint']),
                    'contacto_provint' => limpiar($_POST['contacto_provint']),
                    'email_provint' => limpiar($_POST['email_provint']),
                    'telf_provint' => limpiar($_POST['telf_provint']),
                    'dir_provint' => limpiar($_POST['dir_provint']),
                    'status' => limpiar($_POST['status']),
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($_POST['id'])){
                    $id = ProveeIntModel::guardar($data);
                    Alertas::new(sprintf('El proveedor %s se ha creado exitosamente con el id %s', $data['nombre_provint'], $id));
                }else{
                    $id = ProveeIntModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El proveedor %s se ha modificado exitosamente con el id %s', $data['nombre_provint'], $_POST['id']));
                }
                header('Location:' . base_url . '/ProveeInt');
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/ProveeInt');
            }
        }
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ProveeIntModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ProveeInt');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el proveedor " . $r['nombre_provint'],
                    'function_js' => "ProveeInt.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/ProveeInt');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ProveeInt');
    }
    public function cargar_data(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ProveeIntModel::cargar_data($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
        $dataJson = [];
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = intval(limpiar($_POST['id']));
            $ide = ProveeIntModel::borrar($id);
            if($ide){
                $dataJson = [
                    'status' => true,
                    'type' => 'success',
                    'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $id, $_POST['nombre_provint'])
                ];
            }else{
                $dataJson = [
                    'status' => false,
                    'type' => 'warning',
                    'msg' => sprintf('No se puede elimiar el registro %s con la descripción %s, motivado a que está relacionado en otros registros', $_id, $_POST['nombre_provint'])
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    /*public function listar_almacenes(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = intval($_POST['id_emp']);
            $r = AlmacenModel::listar_almacenes($id_emp);
            echo json_encode($r);
        }
    }*/

}