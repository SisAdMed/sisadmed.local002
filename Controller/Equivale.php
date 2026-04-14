<?php
class Equivale extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(169);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = EquivaleModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Productos Equivalentes',
            'function_js' => 'Equivale.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo registro de Productos Equivalentes',
            'function_js' => 'Equivale.js'
        ]);
    }
    public function edit($id) {
        if (Permisos::read()) {
            $id = explode('|', $id);
            $id_emp = $id[0];
            $id_cli = $id[1];
            $fecha = $id[2];
            if ($id > 0) {
                $r = EquivaleModel::edit($id_emp, $id_cli, $fecha);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Equivale');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de Productos Equivalentes',
                    'function_js' => 'Equivale.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/Equivale');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Equivale');
    }
    static  function store() {
        $modo = 'modify_user';
        $data = array();
        $tot_item = 0;
        if(isset($_POST['id_prod'])){
            $tot_item = count($_POST['id_prod']);
        }
        $fecha = $_POST['fecha'];
        $id_emp = $_POST['id_emp'];
        $id_ent = $_POST['id_cli'];
        $format = $_POST['format'];
        $status = $_POST['status'];
        try{
            $mensaje = 'El registro se ha creado exitosamente';
            //Borrar registro existente y actualziar por el actual
            if(EquivaleModel::delete('f4013', ['id_emp' => $id_emp, 'id_ent' => $id_ent, 'format' => $format])){
                $mensaje = 'El registro se ha modificado exitosamente';
            }
            for($i=0; $i<$tot_item; $i++){
                $cod_prod_ent = $_POST['cod_prod_ent'][$i];
                $id_prod = $_POST['id_prod'][$i];
                //
                if($id_prod > 0 && $cod_prod_ent != ''){
                    $data = [
                        'fecha' => $fecha,
                        'id_emp' => $id_emp,
                        'id_ent' => $id_ent,
                        'format' => $format,
                        'cod_prod_ent' => $cod_prod_ent,
                        'id_prod' => $id_prod,
                        'status' => $status,
                        'create_user' => $_SESSION['id_user']
                    ];
                    $r = EquivaleModel::guardar($data);
                }
              
            }
            Alertas::new($mensaje);
        } catch (\PDOException $e){
            Alertas::new($e->getMessage(), 'danger');
        }finally{
            header('Location:'. base_url . '/Equivale');
        }
    }
    public function delete_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $id_ent = $_POST['id_ent'];
            $fecha = $_POST['fecha'];
            $r = EquivaleModel::delete_row($id_emp, $id_ent, $fecha);
            if($r){
                $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            }else{
                $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $id_ent = $_POST['id_ent'];
            $fecha = $_POST['fecha'];
            $r = EquivaleModel::show_row($id_emp, $id_ent, $fecha);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}