<?php
class MovInv extends Controller
{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(97);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = MovinvModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Movimientos",
            'function_js' => "MovInv.js",
            'objeto' => to_obj($objeto),
        ]);
    }
    public function index2(){
        $this->views->getView($this, "index2", [
            'page_name' => "Consulta Movimientos de Inventario",
            'function_js' => "MovInvConInv.js",
        ]);
    }
    public function index3(){
        $this->views->getView($this, "index3", [
            'page_name' => "Reporte Movimientos de Inventario",
            'function_js' => "MovInvConInv.js",
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Movimiento",
            'function_js' => "MovInv.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = MovinvModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/MovInv');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Movimiento " . $r['cod_tmoinv'] . ' - ' . $r['nom__tmoinv'] . ' Número ' . $r['num_movinv'],
                    'function_js' => "MovInv.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/MovInv');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/MovInv');
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modo = 'modify_user';
            $num_movinv = $_POST['num_movinv'];
            $data = array();
            if (empty($_POST['id'])) {
                $modo = 'create_user';
                if (empty($num_movinv)) {
                    $r = MovinvModel::getNextNumer($_POST['id_tmovinv']);
                    $num_movinv = intval($r['proximo_tmoinv']);
                }
            }
            //Encabezado de Movimiento
            //Tasa de cambio
            $tasa = str_replace(',', '.', $_POST['tasa_cambio']);
            $xasa = number_format($tasa, 8);
            try {
                $data = [
                    'id_emp' => limpiar($_POST['id_emp']),
                    'id_tmovinv' => limpiar($_POST['id_tmovinv']),
                    'num_movinv' => $num_movinv,
                    'fecha_comp' => ($_POST['fecha_comp']),
                    'id_moneda' => limpiar($_POST['id_moneda']),
                    'tasa_cambio' => $tasa,
                    'id_alm' => limpiar($_POST['id_alm']),
                    'descrip_movinv' => limpiar($_POST['descrip_movinv']),
                    'status' => limpiar($_POST['status']),
                    $modo => $_SESSION['id_user']
                ];
                //Guardar y/o actualizar encabezado
                if (empty($_POST['id'])) {
                    $id = MovinvModel::guardar($data);
                    Alertas::new(sprintf('El movimiento con el número %s se ha creado exitosamente con el id %s', $data['num_movinv'], $id));
                    if (empty($_POST['num_movinv'])) {
                        $id_num_movin = MovinvModel::setNextNumber($_POST['id_tmovinv']);
                    }
                } else {
                    $id = MovinvModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El movimiento con el número %s se ha actualziado exitosamente con el id %s', $data['num_movinv'], $_POST['id']));
                    //Borrar detalles en caso de que existan
                    $id = MovinvModel::borrarDetInvMov($_POST['id']);
                    $id = $_POST['id'];
                }
                //Detalle de Movimiento
                $num_rows = count($_POST['id_prod']);
                $datdet = array();
                for ($i = 0; $i < $num_rows; $i++) {
                    $id_prod = MovinvModel::cons_producto($_POST['id_prod'][$i]);
                    $fec_venc = $_POST['fec_ven'][$i];
                    if (empty($fec_venc)) {
                        $fec_venc =  '0000-00-00';
                    }
                    $datdet = [
                        'id_movinv' => $id,
                        'id_prod' => $_POST['id_prod'][$i],
                        'id_ubi' => $_POST['id_ubi'][$i],
                        'cantidad' => $_POST['cant'][$i],
                        'fec_venc' => $fec_venc,
                        'lote' => limpiar(strtoupper($_POST['lote'][$i])),
                        'costo' => $id_prod['costo_prod'],
                        'flete' => $id_prod['flete_prod'],
                        'otros_cargos' => $id_prod['otros_prod'],
                        'door_cargos' => $id_prod['door_costo'],
                        'costo1' => $id_prod['costo_prod'] + $id_prod['flete_prod'] + $id_prod['otros_prod'] + $id_prod['door_costo'],
                        'create_user' => $_SESSION['id_user']
                    ];

                    $id_det = MovinvModel::guardarDetMovin($datdet);
                }

                header('Location:' . base_url . '/MovInv');
            } catch (Exception $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/MovInv');
            }
        }
    }
    public function showrow(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = MovinvModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = [];
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $number = $_POST['number'];
            $d = MovinvModel::borrarDetInvMov($id);
            $e = MovinvModel::borrarEncInvMov($id);
            if ($e) {
                $dataJson = [
                    'status' => true,
                    'icon' => 'success',
                    'msg' => sprintf('El Movimiento %s ' - ' %s, se ha eliminado correctamente', $code, $name)
                ];
            } else {
                $dataJson = [
                    'status' => false,
                    'icon' => 'warning',
                    'msg' => sprintf('El Movimiento %s ' - ' %s, no se pudo eliminar', $code, $name)
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function conmovinv(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_alm = "";
            if($_POST['id_alm']){
                $id_alm = $_POST['id_alm'];
            }
            $id_emp = $_POST['id_emp'];
            //$id_alm = $_POST['id_alm'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $id_fab = $_POST['id_fab'];
            $id_ubi = $_POST["id_ubi"];
            $id_prod = $_POST['id_prod'];
            $r = MovInvModel::conmovinv($id_emp, $fec_ini, $fec_fin, $id_alm, $id_fab, $id_prod, $id_ubi);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function InvCons(){
        $this->views->getView($this, "InvCons", [
            'page_name' => "Inventarios de Consignados",
            'function_js' => "InvCons.js",
        ]);
    }
    public function InvPpal(){
        $this->views->getView($this, "InvPpal", [
            'page_name' => "Inventario Almacén Principal",
            'function_js' => "InvPpal.js",
        ]);
    }
    public function printer_movinv($id){
        if ($id > 0) {
            $r = MovInvModel::print_movement($id);
            if (empty($r)) {
                Alertas::new('El registro no existe', 'warning');
                header('Location:' . base_url . '/MovInv');
            }
            $this->views->getView($this, "printer_movinv", [
                'r' => to_obj($r)
            ]);
        } else {
            header('Location:' . base_url . '/MovInv');
        }
        return;
    }
    public function update_lotes(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $origen = $_POST['origen'];
            $r = MovInvModel::update_lotes($origen);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
