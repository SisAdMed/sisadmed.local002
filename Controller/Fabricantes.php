<?php
class Fabricantes extends Controller
{
   public function __construct()
   {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(47);
   }
   public function index()
   {
      if(empty($_SESSION['permisosMod']['r'])){
         header('Location:' . base_url . '/Perfil');
      }

      $objeto = FabricantesModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Fabricante/Marca/Laboratorio",
         'function_js' => 'Fabricantes.js',
         'objeto' => to_obj($objeto),
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Fabricante/Marca/Laboratorio",
         'function_js' => "Fabricantes.js"
      ]);
   }
   public function validar()
   {
      $codigo = $_POST;
      $totreg = 0;
      $jsonData = array();
      if(!empty($codigo['nom_fab'])){
         $rows = FabricantesModel::validar($codigo['nom_fab']);
         $totreg = $rows;
      }
      if($totreg <= 0){
         $jsonData['success'] = 0;
         $jsonData['message'] = '';
      }else{
         $jsonData['success'] = 1;
         $jsonData['message'] = 'Registro ya existe...';
      }
      echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
   }
   public function store(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $modo = 'modify_user';
         $codigo = '';
         $data = array();
         if(empty($_POST['id'])){
            $modo = 'create_user';
         }
         $adicional01 = 0;
         if(isset($_POST['adicional01'])){
               $adicional01 = 1;
         }
         try {
            $data += [
               'nom_fab' => limpiar($_POST['nom_fab']),
               'status' => limpiar($_POST['status']),
               'observa' => limpiar($_POST['observa']),
               'adicional01' => $adicional01,
               $modo => $_SESSION['id_user'],
            ];
            if(empty($_POST['id'])){
               $id = FabricantesModel::guardar($data);
               Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nom_fab'], $id));
            }else{
               $id = FabricantesModel::actualizar($_POST['id'], $data);
               Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nom_fab'], $_POST['id']));
            }
            header('Location:' . base_url . '/Fabricantes');
         } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/Fabricantes');
         }
      }
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = FabricantesModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/Fabricantes');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nom_fab'],
               'function_js' => "Fabricantes.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/Fabricantes');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Fabricantes');
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
         $name = limpiar($_POST['name']);
         $ide = FabricantesModel::borrar($id);
         if($ide){
            $dataJson = [
               'status' => true,
               'title' => 'Borrado!',
               'icon' => 'success',
               'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['id'], $_POST['name'])
            ];
         }else{
            $dataJson = [
               'status' => false,
               'title' => 'Oops!',
               'icon' => 'error',
               'msg' => sprintf('No se puede elimiar el registro %s con la descripción %s, motivado a que tienes registros hijos y/o posee movimientos', $_POST['id'], $_POST['name'])
            ];
         }
      }
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function listar_marcas(){
      $objeto = FabricantesModel::listar_marcas();
      echo json_encode($objeto);
   }
   public function listar_grupos(){
      $objeto = FabricantesModel::listar_grupos();
      echo json_encode($objeto);
   }
   public function showrowfab(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $r = FabricantesModel::showrowfab($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function getMarcas(){
    if($_SERVER["REQUEST_METHOD"] == 'POST'){
        $r = FabricantesModel::getMarcas();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
   }
}