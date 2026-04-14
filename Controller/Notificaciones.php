<?php
    /**
     *Clases para el manejo de las notificaciones
     */
    class Notificaciones extends Controller{
        public function __construct(){
            Auth::noAuth();
            parent::__construct();
            Permisos::getPermisos(129);
        }
        public function index(){
            if(empty($_SESSION['permisosMod']['r'])){
                header('Location:' . base_url . '/Perfil');
            }
            $r = NotificacionesModel::all();
            $this->views->getView($this, 'index', [
                'page_name' => 'Notificaciones',
                'function_js' => 'Notificaciones.js',
                'objeto' => to_obj($r)
            ]);
        }
        public function all_index(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                $r = NotificacionesModel::all_index();
                echo json_encode($r, JSON_UNESCAPED_UNICODE);
            }
        }
    }
?>