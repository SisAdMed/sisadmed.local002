<?php
class ConfigPrecio extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent:: __construct();
        Permisos::getPermisos(59);
    }
    public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objeto = ConfigPrecioModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Configuración de precios",
         'function_js' => "ConfigPrecio.js",
         'objeto' => to_obj($objeto)
      ]);
   }
   public function nuevo(){
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nueva Configuración de precios",
         'function_js' => "ConfigPrecio.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = ConfigPrecioModel::edit($id);            
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/ConfigPrecio');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['id_precio'],
               'function_js' => "ConfigPrecio.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/ConfigPrecio');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/ConfigPrecio');
   }
    public function store(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $modo = 'modify_user';
         $codigo = '';
         $data = array();
         if(empty($_POST['id'])){                    
            $data += ['id_emp' => limpiar($_POST['id_emp'])];
            $modo = 'create_user';
         }
         try {           
            $data += [                  
               'tasa' => limpiar($_POST['tasa']),                             
               'status' => limpiar($_POST['status']),
               'fecha_precio' => limpiar($_POST['fecha_precio']),
               $modo => $_SESSION['id_user'],
            ];
            if(empty($_POST['id'])){
               $id = ConfigPrecioModel::guardar($data);
               Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['tasa'], $id));
            }else{
               $id = ConfigPrecioModel::actualizar($_POST['id'], $data);
               Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['tasa'], $_POST['id']));
            }        
            header('Location:' . base_url . '/ConfigPrecio');      
         } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/ConfigPrecio'); 
         }
      }
   }
}