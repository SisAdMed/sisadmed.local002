<?php
class RetIva extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(161);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = RetIvaModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Retención de IVA',
            'function_js' => 'RetIva.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo registro de Retención de IVA',
            'function_js' => 'RetIva.js'
        ]);
    }
    public function edit($id) {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = RetIvaModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/RetIva');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de Retención de IVA',
                    'function_js' => 'RetIva.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/RetIva');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/RetIva');
    }
    public function store() {
        $modo = $_POST['id'] == '' ? 'create_user' : 'modify_user';
        $data = array();
        $min_retiva = $_POST['min_retiva'];
        if(empty($min_retiva)){
            $min_retiva = 0;
        }
        $data = [
            'fecha_vigenc' => $_POST['fecha_vigenc'],
            'desc_retiva' => $_POST['desc_retiva'],
            'tasa_retiva' => $_POST['tasa_retiva'],
            'min_retiva' => $min_retiva,
            'id_ctb' => $_POST['id_ctb'],
            'status' => $_POST['status'],
            $modo => $_SESSION['id_user'],
        ];
        if(isset($_POST['id_aux']) && $_POST['id_aux'] >0){
            $data += ['id_aux' => $_POST['id_aux']];
        }
        try{
            if(empty($_POST['id'])){
                $id = RetIvaModel::guardar($data);
                Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nombre_provint'], $id));
            }else{
                $id = $_POST['id'];
                $r = RetIvaModel::actualizar($id, $data);
                Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nombre_provint'], $id));
            }
            header('Location:'. base_url . '/RetIva');
        } catch (\PDOException $e){
            Alertas::new($e->getMessage(), 'danger');
            header('Location:'. base_url . '/RetIva');
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
            $ide = RetIvaModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente', '')
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function load_data(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = RetIvaModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = RetIvaModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cal_retiva(){
        $this->views->getView($this, 'cal_retiva', [
            'page_name' => 'Cálculo Retención de IVA',
            'function_js' => 'RetIva.js'
        ]);
    }
    public function listar_retiva(){
        if($_SERVER['REQUEST_METHOD'] = 'POST'){
            $r = RetIvaModel::listar_retiva();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function index2() {
        $this->views->getView($this, 'index2', [
            'page_name' => 'Reporte Retención de IVA',
            'function_js' => 'RetIva.js',
        ]);
    }
    public function report_retiva(){
        //if(Permisos::read()){
            $id_emp = $_GET['id_emp'];
            $fec_ini = $_GET['fec_ini'];
            $fec_fin = $_GET['fec_fin'];
            $r = RetIvaModel::report_retiva($id_emp, $fec_ini, $fec_fin);
            if(empty($r)){
                Alertas::new('El registro no existe', 'warning');
                header('Location:' . base_url . '/RetIva');
            }
            $this->views->getView($this, "report_retiva", [
                'r' => to_obj($r)
            ]);
            return;
            Alertas::new('No tiene permiso para realizar esta acción', 'warning');
            header('Location:' . base_url . '/RetIva');
        //}
    }
    public function report_retiva_text(){
        //if(Permisos::read()){
            $id_emp = $_GET['id_emp'];
            $fec_ini = $_GET['fec_ini'];
            $fec_fin = $_GET['fec_fin'];
            $r = RetIvaModel::file_text($id_emp, $fec_ini, $fec_fin);
            if(empty($r)){
                Alertas::new('El registro no existe', 'warning');
                header('Location:' . base_url . '/RetIva');
            }
            $this->views->getView($this, "report_retiva_text", [
                'r' => $r
            ]);
            return;
            Alertas::new('No tiene permiso para realizar esta acción', 'warning');
            header('Location:' . base_url . '/RetIva');
        //}
    }
}