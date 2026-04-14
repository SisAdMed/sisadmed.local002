<?php
class CuentasCtb extends Controller {
   public function __construct(){
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(12);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objeto = CuentasCtbModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Cuentas contables",
         'function_js' => "CuentasCtb.js",
         'function_js_mod' => "CTBFun.js",
         'objeto' => to_obj($objeto)
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nueva Cuenta contable",
         'function_js_mod' => "CTBFun.js",
         'function_js' => "CuentasCtb.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = CuentasCtbModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/CuentasCtb');
            }
            //mostrar registro
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nombre_cta'],
               'function_js_mod' => "CTBFun.js",
               'function_js' => "CuentasCtb.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/CuentasCtb');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/CuentasCtb');
   }
   public function validar_tipo_cta(){
      $codigo = $_POST;
      $jsonData = array();
      if(!empty($codigo['cod_cta'])){
         $tipo = to_obj(CuentasCtbModel::validar_tipo($codigo['cod_cta']));
         if(isset($tipo)){
            $jsonData['success'] = 1;
            $jsonData['message'] = $tipo[0]->tip_cta;
         }else{
            $jsonData['success'] = 0;
            $jsonData['message'] = 'Hay un error';
         }
         echo json_encode($jsonData, JSON_UNESCAPED_UNICODE );
      }
   }
   public function validaSelecCue_AuxSN(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id_cta'];
         if (!empty($id)){
            $usaAux = CuentasCtbModel::validaSelecCue_AuxSN($id);
         }
         echo json_encode($usaAux, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_ctas_ctbles(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $idCuentas = CuentasCtbModel::listar_ctas_ctbles();
         echo json_encode($idCuentas, JSON_UNESCAPED_UNICODE);
      }
   }
   public function modal_CuentasCtb(){
      $r = CuentasCtbModel::modal_CuentasCtb();
      echo json_encode($r, JSON_UNESCAPED_UNICODE);
   }
   public function nom_ctb($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id_ctb = $_POST['id_ctb'];
         $r = CuentasCtbModel::nom_ctb($id_ctb);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_nivel_detalle(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $r = CuentasCtbModel::listar_nivel_detalle();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function cargar_screen_main() {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $r = CuentasCtbModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function val_con() {
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
         $id = $_POST["id"];
         $r = CuentasCtbModel::val_con($id);
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
         foreach($_POST as $key => $value){
            $$key = $value;
         }
         if (empty($id)) {            
            $modo = 'create_user';
         }
         try {
            $data += [
               'cod_cta' => limpiar($cod_cta),
               'nombre_cta' => limpiar($nombre_cta),
               'agrupa_cta' => $agrupa_cta,
               'aux_cta' => $aux_cta,
               'tip_cta' => $tip_cta,
               'status' => $status,
               $modo => $_SESSION['id_user']
            ];
            if (empty($id)) {
               $id = CuentasCtbModel::guardar($data);
               $title = "Registro se ha agregado satisfactoriamente";
            } else {
               $id_upd = CuentasCtbModel::actualizar($id, $data);
               $title = "Registro se ha modificado satisfactoriamente";
            }
            $msg = "Se ha salvado satisfactoriamente la Cuenta Contable $cod_cta con el nombre $nombre_cta";
            $dataJson = [
               "title" => $title,
               "icon" => "success",
               "msg" => $msg
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
      if($_SERVER["REQUEST_METHOD"] == "POST"){
         $dataJson = [];
         $id = $_POST["recordCode"];
         try {
            $r = CuentasCtbModel::destroy($id);
            if($r){
               $dataJson = [                 
                  'msg' => 'Eliminado',
                  'icon' => 'success',
                  'title' => 'Registro eliminado satisfactoriamente'
               ];
            }else{
               $dataJson = [
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
   public function show_row(){
      if($_SERVER["REQUEST_METHOD"] == "POST"){
         $id = $_POST["id"];
         $r = CuentasCtbModel::edit($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}