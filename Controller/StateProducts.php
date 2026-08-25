<?php
class StateProducts extends Controller
{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(207);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Estado de Productos',
            'function_js' => 'StateProducts.js?v=' . SITE_VERSION,
            'function_js_mod' => 'INVFun.js?v=' . SITE_VERSION
        ]);
    }
    public function new(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $this->views->getView($this, 'new', [
            'page_name' => 'Nuevo de Estado de Productos',
            'function_js' => 'StateProducts.js?v=' . SITE_VERSION,
            'function_js_mod' => 'InvFun.js?v=' . SITE_VERSION
        ]);
    }
    public function edit(int $id){
        if(Permisos::read()){
            $r = StateProductsModel::edit($id);            
            $this->views->getView($this, 'edit', [
                'page_name' => 'Editando Estado de Productos ' . $r['estado'], 
                'function_js' => 'StateProducts.js?v=' . SITE_VERSION,
                'function_js_mod' => 'InvFun.js?v=' . SITE_VERSION,
                'r' => to_obj($r)
            ]);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = StateProductsModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
        $dataJson = [];
        $id = intval(limpiar($_POST['id']));        
        $r = StateProductsModel::destroy($id);
        if($r == 'ok'){
            $title = "Registro eliminado";
            $msg = "El registro se ha eliminado satisfactoriamente";
            $dataJson = [
                'title' => $title,
                'icon' => "success",
                'msg' => $msg
            ];
        }elseif($r == '23000'){
            $title = "Error eliminado el registro";
            $msg = "No se pudo elimianr el registro ya que se encuentra relacionado en un Producto";
            $dataJson = [
                'title' => $title,
                'icon' => "error",
                'msg' => $msg
            ];
        }else{
                $title = "Error eliminado el registro";
                $msg = 'Ocurrió un error inesperado al intentar eliminar el registro.';
            $dataJson = [
                'title' => $title,
                'icon' => "error",
                'msg' => $msg
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        exit;        
    }
    public function cargar_screen_main(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos_tabla = [];
            $r = StateProductsModel::cargar_screen_main();
            foreach ($r as $p) {
                $datos_tabla[] = array_merge($p, [
                    'token_edit' => encriptar_url(json_encode(['accion' => 'edit', 'id' => $p['id']]))
                ]);
            }
            echo json_encode($datos_tabla, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = [];
            $data = array();
            $title = "";
            $msg = "";
            $icon = "success";
            //Asignar valores a variables  
            //Asignar valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }          
            try {
                $data = [
                    'estado' => $estado,
                    'status' => $status ? $status : 0,                
                ];   
                if (empty($_POST['id'])) {
                    $data += [                        
                        'create_user' => $_SESSION['id_user'],
                        'create_date' => getAuditoria(),
                    ];                    
                    $r = StateProductsModel::new_row($data);
                    $title = "Registro guardado";                    
                    $msg = sprintf("Se ha guardado satisfactoriamente el Estado de Producto %s", $_POST['estado']);
                } else {
                    $data += [                        
                        'modify_user' => $_SESSION['id_user'],
                        'modify_date' => getAuditoria(),
                    ];                    
                    $title = "Registro modificado";                    
                    $r = StateProductsModel::upd_row();
                    $msg = sprintf("Se ha modificado satisfactoriamente el Estado de Producto %s", $_POST['estado']);
                }
                if($r){                                        
                    $dataJson = [
                        'status' => true,
                        'title' => $title,
                        'icon' => $icon,
                        'msg' => $msg
                    ];
                }else{                    
                    $dataJson = [
                        'status' => true,
                        'title' => "Se ha presentado un error, intente luego",
                        'icon' => "error",
                        'msg' => sprintf("Se ha presentado un error al guardar el Estado de Producto %s ", $_POST['estado'])
                    ];
                }
            } catch (\PDOException $e) {
                $title = "Se ha presentado un error, intente luego";
                $msg = sprintf("Error código: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'status' => false,
                    'title' => $title,
                    'icon' => "error",
                    'msg' => $msg
                ];
            }            
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function gestion(string $token){
        if(!$token) return;
        $datos = desencriptar_url(($token));
        switch ($datos['accion']) {
            case 'edit':
                $this->edit($datos['id']);
                break;
            default:
                break;
        }
    }
    public function getStateProducts(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = StateProductsModel::getStateProducts();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
