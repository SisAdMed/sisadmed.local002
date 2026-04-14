<?php
class ParametrosInv extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(64);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = ParametrosInvModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Configuración de Inventarios",
            'function_js' => 'ParametrosInv.js',
            'r' => to_obj($objeto)
        ]);
    }
     public function nuevo() {
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nueva Configuración de Inventarios",
         'function_js' => "ParametrosInv.js"
      ]);
   }
   public function edit($id){
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = ParametrosInvModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/ParametrosInv');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nombre_emp'],
               'function_js' => "ParametrosInv.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/ParametrosInv');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/ParametrosInv');
   }
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            try {
                $modo = 'modify_user';
                $datos = array();
                if(empty($_POST['id_config'])){
                    $datos = ['id_emp' => limpiar($_POST['id_emp']) ?? ''];
                    $modo = 'create_user';
                }
                $datos += [
                    'val_min' => limpiar(!empty($_POST['val_min']) ? 1 : 0),
                    'costo_pie3' => str_replace(',', '', $_POST['costo_pie3']),
                    $modo => $_SESSION['id_user']
                ];
                if(empty($_POST['id'])){
                    $id = ParametrosInvModel::guardar($datos);
                    Alertas::new('La Configuración se ha creado de manera satisfactoria');
                }else{
                    $id = ParametrosInvModel::actualizar($_POST['id'], $datos );
                    Alertas::new('La confgiuración se ha actualizado de manera satisfactoria');
                }
                header('Location: ' . base_url . '/ParametrosInv/');
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location: ' . base_url . '/ParametrosInv/');
            }
        }
    }
    public function getParam(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            try {
                $objeto = ParametrosInvModel::getParam();
                echo json_encode($objeto);
            } catch (Exception $e) {
                 Alertas::new($e->getMessage(), 'danger');
            }

        }
    }
}