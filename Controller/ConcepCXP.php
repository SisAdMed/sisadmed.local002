<?php
class ConcepCXP extends Controller{
   public function __construct(){
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(107);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $r = ConcepCXPModel::all();
      $this->views->getView($this, 'index', [
         'page_name' => 'Consulta de Conceptos',
         'function_js' => 'ConcepCXP.js',
         'function_js_mod' => 'CXPFun.js',
         'objeto' => to_obj($r)
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Concepto",
         'function_js' => "ConcepCXP.js"
      ]);
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
            $modo = "create_user";
         }
         try {
            $data = [
               'codigo_con' => $codigo_con,
               'nombre_con' => $nombre_con,
               'agrupa_con' => $agrupa_con,
               'id_ctb' => isset($id_ctb) && $id_ctb > 0 ? $id_ctb : 0,
               'id_aux'  => isset($id_aux) && $id_aux > 0 ? $id_aux : 0,
               'id_retislr' => isset($id_retislr) && $id_retislr > 0 ? $id_retislr : 0,
               'status' => $status,
               $modo => $_SESSION['id_user']
            ];
            if (empty($id)) {
               $r = ConcepCXPModel::guardar($data);
               $title = "Registro se ha agregado satisfactoriamente";
            } else {
               $r = ConcepCXPModel::actualizar($id, $data);
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
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = ConcepCXPModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/ConcepCXP');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['codigo_con'] . ' - ' . $r['nombre_con'],
               'function_js' => "ConcepCXP.js",
               'r' => $r
            ]);
         } else {
            header('Location:' . base_url . '/ConcepCXP');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/ConcepCXP');
   }
   public function editar_row(){
      $id = intval($_POST['id']);
      if ($id > 0) {
         $r = ConcepCXPModel::editar_row($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function destroy(){
      $dataJson = [];
      $recordCode = limpiar($_POST['recordCode']);
      try {
         $r = ConcepCXPModel::destroy($recordCode);
         if ($r) {
            $dataJson = [
               'status' => true,
               'title' => 'Eliminado',
               'icon' => 'success',
               'msg' => 'Registro eliminado satisfactoriamente'
            ];
         } else {
            $dataJson = [
               'status' => false,
               'title' => 'Error',
               'icon' => 'error',
               'msg' => 'No se puede eliminar un concepto Agrupador, si posee conceptos dependientes. Favor revisar e intentar nuevamente'
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
   public function datosCue(){
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $id = $_POST['id_cue'];
         $r = ConcepCXPModel::datosCue($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_conceptos(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         //$id_emp = $_POST['id_emp'];
         //$r = ConcepCXPModel::listar_conceptos_CXP($id_emp);
         $r = ConcepCXPModel::listar_conceptos_CXP();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_conceptos_exc(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         //$id_emp = $_POST['id_emp'];
         //$r = ConcepCXPModel::listar_conceptos_CXP($id_emp);
         $r = ConcepCXPModel::listar_conceptos_CXP_EXC();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function cargar_screen_main(){
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $r = ConcepCXPModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function val_con(){
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $id = $_POST["id"];
         $r = ConcepCXPModel::val_con($id);
         if (!$r) {
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
         } else if ($r) {
            echo json_encode(true, JSON_UNESCAPED_UNICODE);
         } else {
            echo json_encode(false, JSON_UNESCAPED_UNICODE);
         }
      }
   }
   public function show_row(){
      if($_SERVER["REQUEST_METHOD"] == "POST"){
         $id = $_POST["id"];
         $r = ConcepCXPModel::editar_row($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}
