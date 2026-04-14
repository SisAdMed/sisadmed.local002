<?php
class ConcepCXC extends Controller{
   public function __construct(){
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(87);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $r = ConcepCXCModel::all();
      $this->views->getView($this, 'index', [
         'page_name' => 'Consulta de Conceptos',
         'function_js' => 'ConcepCXC.js',
         'function_js_mod' => 'CXCFun.js',
         'objeto' => to_obj($r)
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Concepto",
         'function_js' => "ConcepCXC.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = ConcepCXCModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/ConcepCXC');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['codigo_con'] . ' - ' . $r['nombre_con'],
               'function_js' => "ConcepCXC.js",
               'r' => $r
            ]);
         } else {
            header('Location:' . base_url . '/ConcepCXC');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/ConcepCXC');
   }
   public function show_row(){
      $id = intval($_POST['id']);
      if ($id > 0) {
         $r = ConcepCXCModel::show_row($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function datosCue(){
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $id = $_POST['id_cue'];
         $r = ConcepCXCModel::datosCue($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_conceptos(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_emp = $_POST['id_emp'];
         $r = ConcepCXCModel::listar_conceptos_CXC($id_emp);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function cargar_screen_main(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $r = ConcepCXCModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function val_con(){
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $id = $_POST["id"];
         $r = ConcepCXCModel::val_con($id);
         if (!$r) {
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
         } else if ($r) {
            echo json_encode(true, JSON_UNESCAPED_UNICODE);
         } else {
            echo json_encode(false, JSON_UNESCAPED_UNICODE);
         }
      }
   }
   public function store(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $modo = 'modify_user';
         $data = array();
         $dataJson = array();
         //Asignar valores a variables
         foreach ($_POST as $key => $value) {
            $$key = $value;
         }
         if (empty($id)) {
            $modo = 'create_user';
         }
         try {
            $data += [
               'id_emp' => $id_emp,
               'codigo_con' => limpiar($codigo_con),
               'nombre_con' => limpiar($nombre_con),
               'agrupa_con' => $agrupa_con,
               'id_ctbcue' => isset($id_ctb) && $id_ctb > 0 ? $id_ctb : 0,
               'id_ctbaux'  => isset($id_aux) && $id_aux > 0 ? $id_aux : 0,
               'status' => $status,
               $modo => $_SESSION['id_user'],
            ];
            if (empty($id)) {
               $id = ConcepCXCModel::guardar($data);
               $title = "Registro se ha agregado satisfactoriamente";
            } else {
               $id = ConcepCXCModel::actualizar($_POST['id'], $data);
               $title = "Registro se ha modificado satisfactoriamente";
            }
            $msg = "Se ha salvado satisfactoriamente el Concepto $codigo_con $nombre_con";
            $dataJson = [
               'title' => $title,
               'icon' => "success",
               'msg' => $msg
            ];
         } catch (\PDOException $e) {
            $title = "Se ha presentado un error, intente luego";
            $msg = sprintf("Error códoigo: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
            $dataJson = [
               'title' => $title,
               'icon' => "error",
               'msg' => $msg
            ];
         }
         echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
      }
   }
   public function destroy(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $dataJson = [];
         $id = $_POST['recordCode'];
         try {
            $r = ConcepCXCModel::destroy($id);
            if ($r) {
               $dataJson = [
                  'status' => true,
                  'msg' => 'Eliminado',
                  'icon' => 'success',
                  'title' => 'Registro eliminado satisfactoriamente'
               ];
            } else {
               $dataJson = [
                  'status' => false,
                  'msg' => 'Error',
                  'icon' => 'error',
                  'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'
               ];
            }
         } catch (\PDOException $e) {
            $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
            $dataJson = [
               'status' => false,
               'msg' => $msg,
               'icon' => 'error',
               'title' => 'Error'
            ];
         }
         echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
      }
   }
    public function editar_row(){
      $id = intval($_POST['id']);
      if ($id > 0) {
         $r = ConcepCXCModel::edit($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}
