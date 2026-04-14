<?php
class CalRetIva extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(163);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = CalRetIvaModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Cálculo Retención de IVA',
            'function_js' => 'CalRetIva.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo registro de Cálculo Retención de IVA',
            'function_js' => 'CalRetIva.js'
        ]);
    }
    public function edit($id) {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = AsientosModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CalRetIva');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de Cálculo Retención de IVA',
                    'function_js' => 'CalRetIva.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/CalRetIva');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CalRetIva');
    }
    public function store() {
        $modo = 'modify_user';
        $data = array();
        if(empty($_POST['id'])){
        }else{
        }
        try{
            header('Location:'. base_url . '/CalRetIva');
        } catch (\PDOException $e){
            Alertas::new($e->getMessage(), 'danger');
            header('Location:'. base_url . '/CalRetIva');
        }
    }
    public function destroy(){
        $dataJson = [];
        if(empty($_POST['id'])) {
            $dataJson = [
                'status' => false,
                'msg' => 'No se recibieron los datos'
            ];
        }else{
            $id = intval(limpiar($_POST['id']));
            $ide = CalRetIvaModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente', '')
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
}