<?php
class MotivoCambio extends Controller{
   public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(55);
    }
    public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objeto = MotivoCambioModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Tabla de Precio por Cliente",
         'function_js' => "MotivoCambio.js",
         'objeto' => to_obj($objeto)
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nueva Tabla de Precio por Cliente",
         'function_js' => "MotivoCambio.js"
      ]);
   } public function store(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $modo = 'modify_user';
         $codigo = '';
         $data = array();
         if(empty($_POST['id'])){
            $modo = 'create_user';
         }
         try {
            $data += [
               'nom_motcam' => limpiar($_POST['nom_motcam']),
               'adic_01' => str_replace(',', '', ($_POST['adic_01'])),
               'adic_02' => str_replace(',', '', ($_POST['adic_02'])),
               'status' => limpiar($_POST['status']),
               $modo => $_SESSION['id_user'],
            ];
            if(empty($_POST['id'])){
               $id = MotivoCambioModel::guardar($data);
               Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nom_motcam'], $id));
               $id_det = $id;
            }else{
               $id = MotivoCambioModel::actualizar($_POST['id'], $data);
               Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nom_motcam'], $_POST['id']));
               $id_det = $_POST['id'];
            }
            //Guardar detalles de fabricantes con adicional
            $id_del = MotivoCambioModel::destroy_del_detmotcam($id_det);
            if(isset($_POST['id_fab'])){
               $itemTotal = count($_POST['id_fab']);
               $data2 = array();
               for($i=0;$i<$itemTotal;$i++){
                  $data2 = [
                     'id_motcam' => $id_det,
                     'id_fab' => $_POST['id_fab'][$i],
                     'adicional' => str_replace(',', '', $_POST['adicional'][$i]),
                     'vigencia' => $_POST['vigencia'][$i],
                     'create_user' => $_SESSION['id_user'],
                  ];
                  $id = MotivoCambioModel::guardar_del_detmotcam($data2);
               }
            }
            header('Location:' . base_url . '/MotivoCambio');
         } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/MotivoCambio');
         }
      }
   }
   public function edit($id){
      if(Permisos::read()){
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = MotivoCambioModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/MotivoCambio');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nom_motcam'],
               'function_js' => "MotivoCambio.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/MotivoCambio');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/MotivoCambio');
   }
   public function listar_motivo_cambio($id){
      $r = MotivoCambioModel::listar_motivo_cambio($id);
      echo json_encode($r);
   }
   public function destroy(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $r = MotivoCambioModel::destroy($id);
         if($r){
            echo true;
         }else{
            echo false;
         }
      }
   }
   public function show_detalle(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = $_POST['id'];
         $r = MotivoCambioModel::show_detalle($id);
         echo json_encode($r);
      }
   }
}