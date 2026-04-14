<?php
    class InvLoaRep extends Controller{
        public function __construct(){
            Auth::noAuth();
            parent::__construct();
            Permisos::getPermisos(111);
        }
        public function index(){
            if(empty($_SESSION['permisosMod']['r'])){
                header('Location:' . base_url . '/Perfil');
            }
            $this->views->getView($this, "index", [
                'page_name' => "Reporte para Carga de Inventarios",
                'function_js' => "InvLoaRep.js"
            ]);
        }
        public function store(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                if(Permisos::read()){
                    $id_emp = $_POST['id_emp'];
                    $id_fab = $_POST['id_fab'];
                    $id_grupo = $_POST['id_grupo'];
                    $r = InvLoaRepModel::reportExcel($id_fab, $id_grupo);
                    $u = InvLoaRepModel::listar_ubicaciones($id_emp);
                    if (empty($r)) {
                        Alertas::new('El registro no existe', 'warning');
                        header('Location:' . base_url . '/InvLoaRep');
                    }
                    $this->views->getView($this, "reportExcel", [
                        'r' => to_obj($r),
                        'u' => to_obj($u),
                    ]);
                    return;
                }
                Alertas::new('No tiene permiso para realizar esta acción', 'warning');
                header('Location:' . base_url . '/InvLoaRep');
            }
        }
    }
?>