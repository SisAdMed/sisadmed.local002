<?php
class TipoDocCXC extends Controller{
   public function __construct()
   {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(49);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objetos = TipoDocCXCModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Consulta Tipos de Documentos",
         'function_js' => "TipoDocCXC.js",
         'function_js_mod' => "CXCFun.js",
         'objeto' => to_obj($objetos)
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Tipo de Documento",
         'function_js' => "TipoDocCXC.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = TipoDocCXCModel::edit($id);
            if (empty($r)) {
               header('Location:' . base_url . '/TipoDocCXC');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['tipo_codigo'] . ' - ' . $r['nom_tdoc'],
               'function_js' => "TipoDocCXC.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/TipoDocCXC');
         }
         return;
      }
      header('Location:' . base_url . '/TipoDocCXC');
   }
   public function store(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $modo = 'modify_user';
         $data = array();
         //
         $id = $_POST['id'];
         $id_emp = $_POST['id_emp'];
         $tipo_codigo = $_POST['tipo_codigo'];
         $nom_tdoc = $_POST['nom_tdoc'];
         $tipo_tdoc = $_POST['tipo_tdoc'];
         $con_tdoc = !empty($_POST['con_tdoc']) ? 1 : 0;
         $num_tdoc = $_POST['num_tdoc'];
         $status = $_POST['status'];
         $sol_aprob = !empty($_POST['sol_aprob']) ? 1 : 0;
         $id_user = $_SESSION['id_user'];
         //
         $data = [
            'id_emp' => $id_emp,
            'tipo_codigo' => $tipo_codigo,
            'nom_tdoc' => $nom_tdoc,
            'tipo_tdoc' => $tipo_tdoc,
            'con_tdoc' => $con_tdoc,
            'num_tdoc' => $num_tdoc,
            'status' => $status,
            'sol_aprob' => $sol_aprob,
         ];
         if (isset($_POST['id_ctb'])) {
            $data += ['id_cta' => limpiar($_POST['id_ctb']),];
         }
         if (isset($_POST['id_aux'])) {
            $data += ['id_aux' => limpiar($_POST['id_aux']),];
         }
         if (isset($_POST['id_tmoinv'])) {
            $data += ['id_tmoinv' => intval($_POST['id_tmoinv']),];
         }
         try {
            if (empty($id)) {
               $modo = 'create_user';
               $data += [
                  $modo => $id_user,
               ];
               $r = TipoDocCXCModel::guardar($data);
               $msg = sprintf('Registro Tipo de Documento %s con la Descripción %s creado satisfactoriamente', $tipo_codigo, $nom_tdoc);
               $title = 'Registro agregado';
            } else {
               $data += [
                  $modo => $_SESSION['id_user'],
               ];
               $r = TipoDocCXCModel::actualizar($id, $data);
               $msg = sprintf('Registro Tipo de Documento %s con la Descripción %s modificado satisfactoriamente', $tipo_codigo, $nom_tdoc);
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
                  'msg' => 'Error al momento de crar y/o actualizar el registro, por favor inente luego',
               ];
            }
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
   public function listar_tipos_documentos(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_emp = $_POST['id_emp'];
         //$tipo_tdoc = explode(',', $_POST['tipo_tdoc']);
         $tipo_tdoc = $_POST['tipo_tdoc'];
         $r = TipoDocCXCModel::listar_tipos_documentos($id_emp, $tipo_tdoc);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_tipos_documentos_fuente(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_emp = $_POST['id_emp'];
         $r = TipoDocCXCModel::listar_tipos_documentos_fuente($id_emp);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function tipo_doc_name(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_tip_doc = $_POST['id_tip_doc'];
         $r = TipoDocCXCModel::name_tip_doc($id_tip_doc);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function cargar_screen_main(){
      if (($_SERVER['REQUEST_METHOD']) == 'POST') {
         $r = TipoDocCXCModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function show_row(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id = $_POST['id'];
         $r = TipoDocCXCModel::edit($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function delete_row(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id = $_POST['id'];
         $r = TipoDocCXCModel::delete_row($id);
         try {
            if ($r) {
               $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            } else {
               $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            } 
         } catch (\PDOException $pd) {
            $msg = $pd->getMessage();
            $dataJson = ['status' => false, $msg => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
         }

         echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
      }
   }
   public function get_id_tipo_doc_fuente(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $tipo_codigo = limpiar($_POST['tipo_codigo']);
         $r = TipoDocCXCModel::get_id_tipo_doc($tipo_codigo);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }  
}
