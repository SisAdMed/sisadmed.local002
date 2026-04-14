<?php
/**
 * Clase para los metodos del Manejo de la Configuración de Inventarios
 * Creado por José Vargas
 * El 12-05-2024
 */
class ConfigINV extends Controller {
    function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(152);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = ConfigINVModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Listado Configuración de Inventarios',
            'function_js' => 'ConfigINV.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva Configuración de Inventarios',
            'function_js' => 'ConfigINV.js'
        ]);
    }
    public function edit($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if($id > 0){
                $r = ConfigINVModel::edit($id);
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Editando la Configuración de Inventrios de la Empresa ' . $r['nom_empresa'],
                    'function_js' => 'ConfigINV.js',
                    'r' => to_obj($r)
                ]);
            }
        }
    }
    public function showrow(){
        if($_SESSION['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ConfigINVModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_config_inv(){
        $id = $_POST['id'];
        $r = ConfigINVModel::show_config_inv($id);
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
}