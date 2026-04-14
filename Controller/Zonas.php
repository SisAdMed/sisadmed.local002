<?php
class Zonas extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(58);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = ZonasModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Consulta de Zonas",
            'function_js' => "Zonas.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Zona",
            'function_js' => "Zonas.js"
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            //Asignar valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if(empty($id)){
                $modo = 'create_user';
            }
            try {
                $data = [
                    'cod_zona' => $cod_zona,
                    'nombre_zona' => $nombre_zona,
                    'status' => $status,
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($id)){
                    $id = ZonasModel::guardar($data);
                    $title = "Registro se ha agregado satisfactoriamente";
                }else{
                    $id = ZonasModel::actualizar($id, $data);
                    $title = "Registro se ha modificado satisfactoriamente";
                }
                $msg = "Se ha salvado satisfactoriamente la Zona $cod_zona $nombre_zona";
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
            $r = ZonasModel::edit($id);            
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/Zonas');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['cod_zona'] . ' - ' . $r['nombre_zona'],
               'function_js' => "Zonas.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/Zonas');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Zonas');
   }
   public function listar_zonas(){
        $r = ZonasModel::listar_zonas();
        echo json_encode($r); 
    }
    public function destroy(){
        $dataJson = [];     
        $id = intval(limpiar($_POST['id']));
        try {
            $r = ZonasModel::destroy($id);
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
   public function cargar_screen_main(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $r = ZonasModel::all();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
   }
   public function next_zone(){
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $r = ZonasModel::next_zone();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
   }
   public function show_row(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id = $_POST["id"];
        $r = ZonasModel::edit($id);
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
   }
}