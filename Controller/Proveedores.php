<?php
class Proveedores extends Controller{
   public function __construct(){
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(108);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $this->views->getView($this, "index", [
         'page_name' => "Consulta de Proveedores",
         'function_js' => "Proveedores.js",
         'function_js_mod' => "CXPFun.js",
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Proveedor",
         'function_js' => "Proveedores.js"
      ]);
   }
   public function listar_paises(){
      $objeto = ProveedoresModel::listar_paises();
      echo json_encode($objeto);
   }
   public function listar_estados(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id_pais = $_POST['id_pais'];
         $objeto = ProveedoresModel::listar_estados($id_pais);
         echo json_encode($objeto, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_ciudades(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id_edo = $_POST['id_edo'];
         $objeto = ProveedoresModel::listar_ciudades($id_edo);
         echo json_encode($objeto);
      }
   }
   public function listar_vendedores(){
      $objeto = ProveedoresModel::listar_vendedores();
      echo json_encode($objeto);
   }
   public function listar_Proveedores(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $tip_ent = $_POST['tip_ent'];
         $id_emp = $_POST['id_emp'];
         $objeto = ProveedoresModel::listar_Proveedores($tip_ent, $id_emp);
         echo json_encode($objeto);
      }
   }
   public function store(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $modo = 'modify_user';
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
            $data = [
               'rif_ent' => $rif_ent,
               'tip_ent' => $tip_ent,
               'nom_ent' => $nom_ent,
               'cor_ent' => $cor_ent,
               'id_pais' => $id_pais,
               'id_edo' => $id_edo,
               'id_ciudad' => $id_ciudad,
               'id_diascre' => $id_diascre,
               'dir_ent' => $dir_ent,
               'contr_esp' => isset($contr_esp) ? 1 : 0,
               'postal_ent' => $postal_ent,
               'id_por_ret_iva' => isset($id_por_ret_iva) ? $id_por_ret_iva : 0,
               'status' => limpiar($_POST['status']),
               $modo => $_SESSION['id_user'],
            ];
            if(empty($id)){
               $id_ent_con = ProveedoresModel::guardar($data);
               $title = "Registro se ha agregado satisfactoriamente";
               $msg = sprintf('El Proveedor %s se ha creado exitosamente con el id %s', $nom_ent, $id_ent_con);
            }else{
               $id_ent_con = ProveedoresModel::actualizar($_POST['id'], $data);
               $title = "Registro se ha modificado satisfactoriamente";
               $msg = sprintf('El Proveedor %s se ha modificado exitosamente con el id %s', $nom_ent, $id);
            }
            //Tabla de Contactos
            //Borrar contactos existentes
            $iddc = ProveedoresModel::borrar_contactos($id_ent_con);
            $data = array();
            if (isset($_POST['nom_con']) && $_POST['nom_con'] != null) {
               for ($i = 0; $i < count($_POST['nom_con']); $i++) {
                  $nom_con = strtoupper(limpiar($_POST['nom_con'][$i]));
                  if($nom_con != ""){
                     $ape_con = strtoupper(limpiar($_POST['ape_con'][$i]));
                     $email_con = strtolower(limpiar($_POST['email_con'][$i]));
                     $id_pre = (intval($_POST['id_pre'][$i]));
                     $num_tel_con = ($_POST['num_tel_con'][$i]);
                     $data = [
                        'id_ent' => $id_ent_con,
                        'nom_con' => $nom_con,
                        'ape_con' => $ape_con,
                        'email_con' => $email_con,
                        'id_pre' => $id_pre,
                        'num_tel_con' => $num_tel_con,
                        'id_dep' => (intval($_POST['id_dep'][$i])),
                        'create_user' => $_SESSION['id_user'],
                     ];
                     $iddc = ProveedoresModel::guardar_contactos($id, $data);
                  }
               }
            }

            $dataJson = [
               'title' => $title,
               'icon' => "success",
               'msg' => $msg
            ];
         } catch (\PDOException $e) {
            $title = "Se ha presentado un error, intente luego";
            $msg = sprintf("Error código: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
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
      if(Permisos::read()){
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = ProveedoresModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/Proveedores');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el Proveedor " . $r[0]['rif_ent'] . ' - ' . $r[0]['nom_ent'],
               'function_js' => "Proveedores.js",
               'r' => to_obj($r),
            ]);
         } else {
            header('Location:' . base_url . '/Proveedores');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Proveedores');
   }
   public function consulta_dias_cre_provee(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id_ent = $_POST['id_ent'];
         $objeto = ProveedoresModel::consulta_dias_cre_provee($id_ent);
         echo json_encode($objeto, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_dpto_ent(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $objeto = ProveedoresModel::listar_dpto_ent();
         echo json_encode($objeto);
      }
   }
   public function listar_codigos_area(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $objeto = ProveedoresModel::listar_codigos_area();
         echo json_encode($objeto);
      }
   }
   public function consulta_motivo(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $r = ProveedoresModel::consulta_motivo($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   //Total Proveedores
   public function tot_cli(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $r = ProveedoresModel::tot_cli();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   //Mostrar datos de Proveedores
   public function show_row(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $r = ProveedoresModel::show_row($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   //Llenar combp de Status de Entidad (Proveedores)
   public function statusEntidad(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $r = ProveedoresModel::statusEntidad($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   //Llenar combo Días de Crédito
   public function listar_dias_credito(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $r = ProveedoresModel::listar_dias_credito();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_entidad_modal(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $tipo = $_POST['tipo'];
         $id = $_POST['id'];
         $r = ProveedoresModel::listar_entidad_modal($id, $tipo);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function cargar_screen_main(){
      if($_SERVER["REQUEST_METHOD"] == "POST"){
         $r = ProveedoresModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function destroy(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $dataJson = [];
         $id = intval($_POST['id']);
         try {
            $r = ProveedoresModel::destroy($id);
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
}