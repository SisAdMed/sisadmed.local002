<?php
class TipoComprobante extends Controller{
   public function __construct() {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(62);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objetos = TipoComprobanteModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Consulta Tipo de Comprobante",
         'function_js' => "TipoComprobante.js",
         'objeto' => to_obj($objetos)
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Tipo de Comprobante",
         'function_js' => "TipoComprobante.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = TipoComprobanteModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/TipoComprobante');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['codigo_tipcom'] . ' - ' . $r['nombre_tipcom'],
               'function_js' => "TipoComprobante.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/TipoComprobante');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/TipoComprobante');
   }
   public function store(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $modo = 'modify_user';
         $codigo = '';
         $data = array();
         if(empty($_POST['id'])){
            $data = ['codigo_tipcom' => limpiar($_POST['codigo_tipcom']),
            ];
            $modo = 'create_user';
         }
         try {
            $data += [
               'nombre_tipcom' => limpiar($_POST['nombre_tipcom']),
               'status' => limpiar($_POST['status']),
               $modo => $_SESSION['id_user'],
            ];
            if(empty($_POST['id'])){
               $id = TipoComprobanteModel::guardar($data);
               Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['codigo_tipcom'], $id));
            }else{
               $id = TipoComprobanteModel::actualizar($_POST['id'], $data);
               Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['codigo_tipcom'], $_POST['id']));
            }
            header('Location:' . base_url . '/TipoComprobante');
         } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/TipoComprobante');
         }
      }
   }
   public function destroy(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id= $_POST['id'];
         $r = TipoComprobanteModel::destroy($id);
         echo json_encode($r);
      }
   }
   public function listar_tipos_documentos(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id_emp = $_POST['id_emp'];
         $tipo_tdoc = $_POST['tipo_tdoc'];
         $r = TipoComprobanteModel::listar_tipos_documentos($id_emp, $tipo_tdoc);
         echo json_encode($r);
      }
   }
}