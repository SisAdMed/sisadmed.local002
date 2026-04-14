<?php
class Cambios extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(24);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = CambiosModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Tasa de cambio",
            'function_js' => "Cambios.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva tasa de cambio",
            'function_js' => "Cambios.js"
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CambiosModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Cambios');
                }
                //mostrar registro
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . formatFecha($r['fecha_cambio']),
                    'function_js' => "Cambios.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Cambios');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Cambios');
    }
    public function store()
    {
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $modo = 'modify_user';
            $data = array();
            if(empty($_POST['id'])){
                $modo = 'create_user';
            }
            try{
                $data = [
                    'fecha_cambio' => limpiar($_POST['fecha_cambio']),
                    'id_moneda' => limpiar($_POST['id_moneda']),
                    'cambio_compra' => limpiar($_POST['cambio_compra']),
                    'cambio_venta' => limpiar($_POST['cambio_venta']),
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($_POST['id'])){
                    $id = CambiosModel::guardar($data);
                    Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', formatFecha($data['fecha_cambio']), $id));
                    header('Location:' . base_url . '/Cambios');
                }else{
                    $id = CambiosModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', formatFecha($_POST['fecha_cambio']), $_POST['id']));
                    header('Location:' . base_url . '/Cambios');
                }
            }catch(\PDOException $e){
                 Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/Cambios');
            }
        }
    }
    public function destroy()
    {
        $dataJson = [];
        if (empty($_POST['id'])) {
            $dataJson = [
                'status' => false,
                'msg' => 'No se recibieron los datos'
            ];
        } else {
            $id = intval(limpiar($_POST['id']));
            $ide = CambiosModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente', $_POST['fecha_cambio'])
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function rateExchange(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $moneda = $_POST['moneda'];
            $fecha = $_POST['fecha'];
            $cambio = 1;
            if(!empty($moneda) && !empty($fecha)){
                $cambio = CambiosModel::rateExchange($moneda, $fecha);
            }
            echo json_encode($cambio, JSON_UNESCAPED_UNICODE);
        }
    }
    public function guardar($data){
        $cambio = CambiosModel::guardar($data);
    }
}