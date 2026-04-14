<?php
class InvTipoMov extends Controller{
   public function __construct() {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(50);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objeto = InvTipoMovModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Tipos de Movimientos",
         'function_js' => "InvTipoMov.js",
         'objeto' => to_obj($objeto),
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Tipo de Movimiento",
         'function_js' => "InvTipoMov.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = InvTipoMovModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/InvTipoMov');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nom__tmoinv'],
               'function_js' => "InvTipoMov.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/InvTipoMov');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/InvTipoMov');
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
         $cod_tmoinv = limpiar($_POST['cod_tmoinv']);
         $ide = InvTipoMovModel::borrar($id, $cod_tmoinv);
         if($ide){
            $dataJson = [
               'status' => true,
               'type' => 'success',
               'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['cod_tmoinv'], $_POST['nom__tmoinv'])
            ];
         }else{
            $dataJson = [
               'status' => false,
               'type' => 'warning',
               'msg' => sprintf('No se puede elimiar el registro %s con la descripción %s, motivado a que tienes registros hijos y/o posee movimientos', $_POST['cod_tmoinv'], $_POST['nom__tmoinv'])
            ];
         }
      }
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function validaraux(){
      $codigo = $_POST;
      $totreg = 0;
      $jsonData = array();
      if(!empty($codigo['cod_tmoinv'])){
         $rows = InvTipoMovModel::validar_cod_aux($codigo['cod_tmoinv']);
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
   public function store(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $modo = 'modify_user';
         $codigo = '';
         $data = array();
         if(empty($_POST['id'])){
            $data = ['cod_tmoinv' => limpiar($_POST['cod_tmoinv'])];
            $modo = 'create_user';
         }
         try {
            $data += [
               'id_emp' => limpiar($_POST['id_emp']),
               'nom__tmoinv' => limpiar($_POST['nom__tmoinv']),
               'tipo_tmoinv' => limpiar($_POST['tipo_tmoinv']),
               'tmosal_tmoinv' => limpiar($_POST['tmosal_tmoinv'] ?? 0),
               'id_alm' => limpiar($_POST['id_alm'] ?? 0),
               'consecutiv__tmoinv' => limpiar(!empty($_POST['consecutiv__tmoinv']) ? 1: 0),
               'proximo_tmoinv' => limpiar($_POST['proximo_tmoinv']),
               'id_cta' => limpiar($_POST['id_ctb'] ),
               'status' => limpiar($_POST['status']),
               $modo => $_SESSION['id_user'],
            ];
            if(isset($_POST['id_aux']) && $_POST['id_aux'] >0){
               $data += ['id_aux' => $_POST['id_aux']];
            }
            if(isset($_POST['id_alm'])){
               $data +=  [
                  'id_alm' => $_POST['id_alm'],
                  'tmosal_tmoinv' => $_POST['tmosal_tmoinv'],
               ];
            }
            if(empty($_POST['id'])){
               $id = InvTipoMovModel::guardar($data);
               Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nom__tmoinv'], $id));
            }else{
               $id = InvTipoMovModel::actualizar($_POST['id'], $data);
               Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nom__tmoinv'], $_POST['id']));
            }
            header('Location:' . base_url . '/InvTipoMov');
         } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/InvTipoMov');
         }
      }
   }
   public function listar_InvTipoMov(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $tipo = '';
         $id = $_POST['id_emp'];
         if(isset($_POST['tipo'])){
            $tipo = $_POST['tipo'];
         }
         $ide = InvTipoMovModel::listar_InvTipoMov($id, $tipo);
         echo json_encode($ide, JSON_UNESCAPED_UNICODE);
      }
   }
   public function val_InvTipoMov(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $ide = InvTipoMovModel::edit($id);
         if($ide){
            echo json_encode($ide, JSON_UNESCAPED_UNICODE);
         }
      }
   }
   public function show_row(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $r = InvTipoMovModel::show_row($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}