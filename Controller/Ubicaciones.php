<?php 
class Ubicaciones extends Controller{
   public function __construct(){
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(45);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objeto = UbicacionesModel::all();
      $this->views->getView($this, "index", [
         'page_name' => "Ubicaciones",
         'function_js' => "Ubicaciones.js",
         'objeto' => to_obj($objeto),
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nueva Ubicacion",
         'function_js' => "Ubicaciones.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = UbicacionesModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/Ubicaciones');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nom_ubi'],
               'function_js' => "Ubicaciones.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/Ubicaciones');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Ubicaciones');
   }
   public function validar(){
      $codigo = to_obj($_POST);
      $totreg = 0;
      $jsonData = array();
      if(!empty($codigo->cod_ubi)){
         $rows = UbicacionesModel::validar_cod($codigo->cod_ubi);
         $totreg = $rows;
      }
      if($totreg <= 0){
         $jsonData['success'] = 0;
         $jsonData['message'] = '';
      }else{
         $jsonData['success'] = 1;
         $jsonData['message'] = 'Registro ya existe...';
      }
      echo json_encode($jsonData, JSON_UNESCAPED_UNICODE );
   }
   public function destroy(){
      $dataJson = [];
      if (empty($_POST['id'])) {
         $dataJson = [
            'status' => false,
            'type' => 'warning',
            'msg' => 'No se recibieron los datos'
         ];
      } else {
         $id = intval(limpiar($_POST['id']));
         $cod = limpiar($_POST['cod_ubi']);
         $ide = UbicacionesModel::borrar($id, $cod);
         if($ide){
            $dataJson = [
               'status' => true,
               'type' => 'success',
               'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['cod_ubi'], $_POST['name'])
            ];
         }else{
            $dataJson = [
               'status' => false,
               'type' => 'warning',
               'msg' => sprintf('No se puede elimiar el registro %s con la descripción %s, motivado a que tienes registros hijos y/o posee movimientos', $_POST['cod_ubi'], $_POST['name'])
            ];
         }
      }
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function store(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $modo = 'modify_user';
         $codigo = '';
         $data = array();
         if(empty($_POST['id'])){
            $data = ['cod_ubi' => limpiar($_POST['cod_ubi'])];
            $modo = 'create_user';
         }
         try {
            $data += [
               'id_emp' => limpiar($_POST['id_emp']),
               'nom_ubi' => limpiar($_POST['nom_ubi']),
               'agru_ubi' => limpiar($_POST['agru_ubi']),
               'refri_ubi' => limpiar($_POST['refri_ubi']),
               'uso_ubi' => limpiar($_POST['uso_ubi']),
               'status' => limpiar($_POST['status']),
               $modo => $_SESSION['id_user'],
            ];
            if(empty($_POST['id'])){
               $id = UbicacionesModel::guardar($data);
               Alertas::new(sprintf('El registros %s se ha creado exitosamente con el id %s', $data['nom_ubi'], $id));
            }else{
               $id = UbicacionesModel::actualizar($_POST['id'], $data);
               Alertas::new(sprintf('El registros %s se ha modificado exitosamente con el id %s', $data['nom_ubi'], $_POST['id']));
            }
            header('Location:' . base_url. '/Ubicaciones');
         } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/Ubicaciones/nuevo');
         }
      }
   }
   public function listar_ubicaciones(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id_emp = $_POST['id_emp'];
         $agrupa = $_POST['agrupa'];
         $r = UbicacionesModel::listar_ubicaciones($id_emp, $agrupa);
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
     }
   }
   public function con_ubi(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $r = UbicacionesModel::con_ubi($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}