<?php
class  NotiEnvio extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(63);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = NotiEnvioModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Envios',
            'function_js' => 'NotiEnvio.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo envío',
            'function_js' => 'NotiEnvio.js'
        ]);
    }
    public function edit(){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if($id > 0){
                $r = NotiEnvioModel::edit($id);
                if(empty($r)){
                    Alertas::new("El registro seleccionado no existe", 'warning');
                    header('Location:' . base_url . '/NotiEnvio');
                }
                $this->views-getView($this, 'edit', [
                    'page_name' => 'Editar envió',
                    'function_js' => 'NotiEnvio.js'
                ]);
            }else{
                header('Location:' . base_url . '/NotiEnvio');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/NotiEnvio');
    }
}