<?php
class Presentaciones extends Controller
{
   public function __construct()
   {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(46);
   }
   public function index()
   {
      if(empty($_SESSION['permisosMod']['r'])){
         header('Location:' . base_url . '/Perfil');
      }

      $objeto = PresentacionesModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Presentaciones",
         'function_js' => 'Presentaciones.js',
         'objeto' => to_obj($objeto),
      ]);
   }
   public function nuevo(){ 
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nueva Presentación",
         'function_js' => "Presentaciones.js"
      ]);
   }
   public function validar()
   {
      $codigo = $_POST;
      $totreg = 0;
      $jsonData = array();
      if(!empty($codigo['cod_pre'])){
         $rows = PresentacionesModel::validar($codigo['cod_pre']);
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
            $data = ['cod_pre' => limpiar($_POST['cod_pre'])];            
            $modo = 'create_user'; 
         }
         try {   
            
            $data += [                  
               'nom_pre' => limpiar($_POST['nom_pre']),               
               'status' => limpiar($_POST['status']),
               $modo => $_SESSION['id_user'],
            ];                 
            if(empty($_POST['id'])){               
               $id = PresentacionesModel::guardar($data);
               Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nom_pre'], $id));
            }else{       
               $id = PresentacionesModel::actualizar($_POST['id'], $data);
               Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nom_pre'], $_POST['id']));
            }      
            header('Location:' . base_url . '/Presentaciones');      
         } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/Presentaciones');
         }
      }
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = PresentacionesModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/Presentaciones');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nom_pre'],
               'function_js' => "Presentaciones.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/Presentaciones');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Presentaciones');
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
         $cod = limpiar($_POST['cod']);            
         $ide = PresentacionesModel::borrar($id, $cod);       
         if($ide){
            $dataJson = [
               'status' => true,
               'type' => 'success',
               'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['cod'], $_POST['name'])
            ];
         }else{
            $dataJson = [
               'status' => false,
               'type' => 'warning',
               'msg' => sprintf('No se puede elimiar el registro %s con la descripción %s, motivado a que tienes registros hijos y/o posee movimientos', $_POST['cod'], $_POST['name'])
            ];
         }            
      }        
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
}