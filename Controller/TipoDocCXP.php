<?php
class TipoDocCXP extends Controller{
   public function __construct(){
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(104);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objetos = TipoDocCXPModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Consulta Tipos de Documentos",
         'function_js' => "TipoDocCXP.js",
         'function_js_mod' => "CXPFun.js",
         'objeto' => to_obj($objetos)
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Tipo de Documento",
         'function_js' => "TipoDocCXP.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = TipoDocCXPModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/TipoDocCXP');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['tipo_codigo'] . ' - ' . $r['nom_tdoc'],
               'function_js' => "TipoDocCXP.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/TipoDocCXP');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/TipoDocCXP');
   }
   public function listar_tipos_documentos(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_emp = $_POST['id_emp'];
         $tipo_tdoc = $_POST['tipo_tdoc'];
         $r = TipoDocCXPModel::listar_tipos_documentos($id_emp, $tipo_tdoc);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_tipos_documentos_fuente(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_emp = $_POST['id_emp'];
         $r = TipoDocCXPModel::listar_tipos_documentos_fuente($id_emp);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   // Cargar registros Nueva Forma
   public function cargar_screen_main(){
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
         $r = TipoDocCXPModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function destroy(){
      $dataJson = [];
      $id = intval(limpiar($_POST['id']));
      try {
            $r = TipoDocCXPModel::destroy($id);
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
   public function selTipDocCxP(){
      $dataJson = [
         ["id" => "", "name" => "Seleccione"],
         ["id" => "M", "name" => "Factura"],
         ["id" => "O", "name" => "Orden de Compra"],
         ["id" => "T", "name" => "Nota de Entrega"],
         ["id" => "X", "name" => "Recepción S.T."],
         ["id" => "A", "name" => "Nota de Crédito"],
         ["id" => "B", "name" => "Nota de Débito"],
         ["id" => "V", "name" => "Nota de Devolución"],
         ["id" => "G", "name" => "Entrega S.T."],
      ];
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function store(){
      if($_SERVER['REQUEST_METHOD'] == "POST"){
         $modo = "modify_user";
         $data = array();
         $dataJson = array();
         //Asignar valores a variables
         foreach($_POST as $key => $value){
            $$key = $value;
         }
         if(empty($id)){
            $modo = "create_user";
         }
         try {
            $data += [
               "id_emp" => $id_emp,
               "tipo_codigo" => $tipo_codigo,
               "nom_tdoc" => $nom_tdoc,
               "tipo_tdoc" => $tipo_tdoc,
               "con_tdoc" => isset($con_tdoc) ? 1 : 0,
               "num_tdoc" => $num_tdoc,
               "id_ctb" => $id_ctb,
               "id_aux" => (isset($id_aux) && $id_aux > 0) ? $id_aux : 0,
               "id_tmoinv" => (isset($id_tmoinv) && $id_tmoinv > 0) ? $id_tmoinv : 0,
               "status" => $status,
               "sol_aprob" => isset($sol_aprob) ? : 0,
               $modo => $_SESSION["id_user"]
            ];
            if(empty($id)){
               $r = TipoDocCXPModel::guardar($data);
               $title = "Registro se ha agregado satisfactoriamente";               
            }else{
               $r = TipoDocCXPModel::actualizar($id, $data);
               $title = "Registro se ha modificado satisfactoriamente";
            }
            $msg = "Se ha salvado satisfactoriamente el Tipo de Documento $tipo_codigo $nom_tdoc";
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
      }
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function show_row(){
      if($_SERVER['REQUEST_METHOD'] == "POST"){
         $id = $_POST["id"];
         $r = TipoDocCXPModel::show_row($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}