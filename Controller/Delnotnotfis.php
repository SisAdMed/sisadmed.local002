<?php
include_once VARTAX;
include $_SERVER['DOCUMENT_ROOT'] . '/Controller/Facturacion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Models/EquivaleModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Models/FacturacionModel.php';

//
$ori = '';
$page = '';
$id_cont = '';

if (isset($_GET['ori'])) {
    $ori = htmlspecialchars($_GET['ori']);
    if (isset($_SESSION['ori'])) {
        unset($_SESSION['or']);
        $_SESSION['ori'] = $ori;
    }
    $xori = '?ori=' . $ori;
    if ($_SESSION['ori'] == 'F') {
        $page = 'Facturación';
        $id_cont = 'FAC';
    } else if ($_SESSION['ori'] == 'N') {
        $page = 'Notas de Entrega';
        $id_cont = 'NOL';
    } else if ($_SESSION['ori'] == 'Z') {
        $id_cont = 'NOF';
        $page = 'Notas de Entrega no Fiscal';
    } else if ($_SESSION['ori'] == 'C') {
        $page = 'Notas de Crédito';
        $id_cont = 'NC';
    }
    $_SESSION['page_name'] = $page;
    $_SESSION['id_cont'] = $id_cont;
}
//
class Delnotnotfis extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(131);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = DelnotnotfisModel::all($_SESSION['ori']);
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta ' . $_SESSION['page_name'],
            'function_js' => 'Delnotnotfis.js?v=' . SITE_VERSION,
            'function_js_mod' => 'FACFun.js?v=' . SITE_VERSION,
            'objeto' => to_obj($objeto),
        ]);
    }
    public function cargar_screen_main()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos_tabla = [];
            $r = DelnotnotfisModel::all($_SESSION['ori']);
            foreach ($r as $p) {
                $datos_tabla[] = array_merge($p, [
                    "token_edit" => encriptar_url(json_encode(['accion' => 'edit', 'id' => $p['id_cot']]))
                ]);
            }
            echo json_encode($datos_tabla, JSON_UNESCAPED_UNICODE);
        }
    }
    public function nuevo()
    {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva ' . $_SESSION['page_name'],
            'function_js' => 'Delnotnotfis.js?v=' . SITE_VERSION,
            'function_js_mod' => 'FACFun.js?v=' . SITE_VERSION,
        ]);
    }
    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $consig = false;
            $data_num_tdo = array();
            $data_enca = array();
            $data_deta = [];
            $data_mov_enca = array();
            $data_mov_deta = [];
            $data_mov_encaC = array();
            $data_mov_detaC = [];
            $dataJson = array();
            //Asignar valores a variables			
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            $modu = 'modify_user';
            $modd = 'modify_date';
            //Buscar Moneda de la Compañia
            $config_idemp = EmpresasModel::edit($_POST['id_emp']);
            $moneda_cia = $config_idemp['id_moneda'];
            if (empty($id)) {
                $modu = "create_user";
                $modd = "create_date";
                //Validar si usa consecutivo            
                $r = (DelnotnotfisModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                if ($r) {
                    $con_tdoc = 0;
                    $con_tdoc = $r[0]['con_tdoc'];
                    if ($con_tdoc == 1) {
                        $tipo_fac = $r[0]['tipo_codigo'];
                        $num_tdo = intval($r[0]['num_tdoc']);
                    }
                }
            }
            $mot_cam = DelnotnotfisModel::query("SELECT b.id_motcam, b.adic_01, b.adic_02 FROM `f0014` a INNER JOIN `f0012a` b on b.id_motcam = a.id_motcam WHERE a.id_ent = {$id_cli}");
            if ($mot_cam) {
                $id_motcam = $mot_cam[0]['id_motcam'];
                $adic_01 = $mot_cam[0]['adic_01'];
                $adic_02 = $mot_cam[0]['adic_02'];
            }
            $xtasa = convert_string_to_number($tasa_cambio);
            //Encabezado
            $data_enca += [
                'id_emp' => $id_emp,
                'id_tdo' => $id_tdo,
                'num_tdo' => $num_tdo,
                'id_cli' => $id_cli,
                'id_fab' => $id_fab ?? '0',
                'fecha_comp' => $fecha_comp,
                'fecha_venci' => $fecha_venci,
                'id_moneda' => $id_moneda,
                'tasa_cambio' => $xtasa,
                'id_vend' => $id_vend,
                'descrip_cot' => limpiar($descrip_cot),
                'mon_doc' => $moneda_cia = $id_moneda ? convert_string_to_number($total_frmDom) : convert_string_to_number($total_frm),
                'sal_doc' => $moneda_cia = $id_moneda ? convert_string_to_number($total_frmDom) : convert_string_to_number($total_frm),
                'status' => '1',
                $modu => $_SESSION['id_user'],
                $modd => getAuditoria(),
                'id_cont' =>  $_SESSION['id_cont'],
                'id_des' => $id_des ?? '0',
                'oc_cliente' => trim($oc_cliente) === "" ? "NA" : $oc_cliente,
                'id_motcam' => $id_motcam,
                'adic_01' => $adic_01,
                'adic_02' => $adic_02
            ];
            //Detalle
            //Tasa IVA
            $tasa_iva = 0;
            $xvatTax = xvatTax($fecha_comp, 'IVA');
            if ($xvatTax) {
                $tasa_iva = $xvatTax[0]['txr1_iva'];
            }
            //Total items            
            $itemTotal = count($_POST['id_prod']);
            for ($i = 0; $i < $itemTotal; $i++) {
                $id_prod = $_POST['id_prod'][$i];
                $can_det = $_POST['cant'][$i];
                $uni_vta = $_POST['uni_ven_prod'][$i];
                $pre_unit = convert_string_to_number($_POST['ventas_prod'][$i]);
                $pre_vta = convert_string_to_number(($_POST['ventas_prod1'][$i]));
                $iva_prod = $_POST['iva_prod'][$i];
                $sub_total = convert_string_to_number($_POST['total'][$i]);
                //Nuevos valores a guardar para el dashboard
                //Buscar producto para analisis de dashboard                        
                $val_cod_prod = ProductosModel::query("SELECT (costo_prod + flete_prod + otros_prod + door_costo) costo, ((ventas_prod - (costo_prod + flete_prod + otros_prod + door_costo))) utilidad, ventas_prod,  uni_ven_prod FROM f4005 WHERE id_prod = {$id_prod}");
                $val_adic = ProductosModel::query("SELECT b.adic_01, b.adic_02 FROM f0014 a INNER JOIN f0012a b ON b.id_motcam = a.id_motcam WHERE a.id_ent = {$id_cli}");
                $tasa_fact = 1;
                $xuni_venta = 1;
                $utilidad = 0;
                $dif_cambio = 0;
                $costo = $val_cod_prod[0]['costo'];
                $utilidad = isset($val_cod_prod[0]['utilidad']) ? $val_cod_prod[0]['utilidad'] : 0;
                $costo    = isset($val_cod_prod[0]['costo']) ? $val_cod_prod[0]['costo'] : 0;
                $costo = $costo * $can_det;
                if ($utilidad != 0) {
                    $utilidad = $utilidad * $can_det;
                }

                if ($id_moneda == $moneda_cia) {
                    $tasa_fact = convert_string_to_number(CambiosModel::rateExchange(2, $fecha_comp));
                }

                $adicional = (floatval($sub_total) / floatval($tasa_fact)) - floatval($costo) - floatval($utilidad);
                $tot_fact = 1;
                if ($id_moneda == $moneda_cia) {
                    if (isset($val_adic[0]['adic_01']) && $val_adic[0]['adic_01'] != 0) {
                        $val_adic_01 = $val_adic[0]['adic_01'];
                        $xadi01 = ($val_cod_prod[0]['ventas_prod'] / $xuni_venta);
                        $xadi02 = $xadi01 / $val_adic_01;
                        $adicional = ($xadi02 - $xadi01) * $can_det;
                        $tot_fact = $xadi02 * $can_det;
                    }
                    //
                    $tasa_pro   = ($tot_fact != 0) ? ($sub_total / $tot_fact) : 1;
                    $validar    = $sub_total - ((($costo / $can_det) + $utilidad + $adicional) * $tasa_pro);
                    $dif_cambio = $validar / $tasa_pro;
                }
                $mon_iva = 0;
                $id_des = 0;
                if (isset($_POST['id_des'][$i]) && $id_des_val == false) {
                    $id_des = $_POST['id_des'][$i];
                    if ($id_des) {
                        $id_des_reqapp = DelnotnotfisModel::show_row_des($id_des);
                        if ($id_des_reqapp['appreq'] == 1) {
                            $id_des_val = true;
                        }
                    }
                }
                if ($iva_prod == "S") {
                    $mon_iva = floatval($sub_total) * floatval($tasa_iva / 100);
                }
                $tota_prod = floatval($sub_total) + floatval($mon_iva);
                //                
                $data_deta[] = [
                    'id_cot' => '',
                    'id_prod' => $id_prod,
                    'can_det' => $can_det,
                    'uni_vta' => $uni_vta,
                    'pre_unit' => $pre_unit,
                    'pre_vta' => $pre_vta,
                    'iva_prod' => $iva_prod,
                    'sub_total' => $sub_total,
                    'mon_iva' => $mon_iva,
                    'tota_prod' => $tota_prod,
                    $modu => $_SESSION['id_user'],
                    $modd => getAuditoria(),
                    'id_des' => $id_des,
                    'utilidad' => $utilidad,
                    'adicional' => $adicional,
                    'costo' => $costo,
                    'dif_cambio' => $dif_cambio
                ];
            }
            //Guardar Encabezado Movimiento de Inventario
            $tconfig_fac = DelnotnotfisModel::tip_doc_fac($_POST['id_emp']);
            $tmov_fac = $tconfig_fac['tmov_fac'];
            $id_alm = $tconfig_fac['id_alm'];
            $id_ubi = $tconfig_fac['id_ubi'];
            if (isset($_POST['id_alm_def'])) {
                $id_alm = $_POST['id_alm_def'];
            }
            if (isset($_POST['id_ubi_def'])) {
                $id_ubi = $_POST['id_ubi_def'];
            }
            //Verificar si ya existe el Movimiento de Salida y obtener el numero de movimiento
            $origen = $_SESSION['id_cont'] . '-' . $tipo_fac . '-' . $_POST['id_emp'] . '-' . $num_tdo;
            $id_mov_inv = DelnotnotfisModel::consult_mov_in_ppal($origen, $tmov_fac);
            $data_mov_exist = [];
            if ($id_mov_inv) {
                $total_rows = count($id_mov_inv);
                for ($z = 0; $z < $total_rows; $z++) {
                    $id_mov_inv_id = $id_mov_inv[$z]['id_movinv'];
                    $data_mov_exist[] = [
                        'id_movinv' => $id_mov_inv_id
                    ];
                }
                $id_mov_inv_id = $id_mov_inv[0]['id_movinv'];
                $data_mov_exist[] = [
                    'id_movinv' => $id_mov_inv_id
                ];
            }
            //Proximo número de Movimiento

            $r = DelnotnotfisModel::getNextNumer($tmov_fac);
            $num_movinv = intval($r['proximo_tmoinv']);

            $data_mov_enca += [
                'id_emp' => $id_emp,
                'id_tmovinv' => $tmov_fac,
                'num_movinv' => $num_movinv,
                'fecha_comp' => $fecha_comp,
                'id_moneda' => $id_moneda,
                'tasa_cambio' => $xtasa,
                'id_alm' => $id_alm,
                'descrip_movinv' => 'Movimiento automático generado desde Nota de Entrega no Fiscal ' . $origen,
                'status' => '1',
                'id_cot' => '',
                'id_cli' => $id_cli,
                'id_vend' => '',
                'id_fab' => '',
                'origen' => $origen,
                $modu => $_SESSION['id_user'],
                $modd => getAuditoria(),
            ];
            //Guardar Detalle Movimiento de Enventario                                        
            $itemTotal = count($_POST['id_prod']);
            $fec_venc = '0000-00-00';
            for ($i = 0; $i < $itemTotal; $i++) {
                $id_prod = $_POST['id_prod'][$i];
                $can_det = $_POST['cant'][$i];
                $uni_ven_prod = convert_string_to_number($_POST['ventas_prod1'][$i]);
                $pre_vta = convert_string_to_number($_POST['ventas_prod1'][$i]);
                $xcan_det = $can_det;
                $data_mov_deta[] = [
                    'id_movinv' => '',
                    'id_prod' => $id_prod,
                    'id_ubi' => $id_ubi,
                    'cantidad' => $xcan_det,
                    'costo' => $pre_vta,
                    'flete' => '',
                    'otros_cargos' => '',
                    'door_cargos' => '',
                    'costo1' => $pre_vta,
                    'lote' => '',
                    'fec_venc' => $fec_venc,
                    'create_user' => $_SESSION['id_user'],
                    'create_date' => getAuditoria(),
                ];
            }
            //Validar si es una Nota de Entrega a un consignado para crear la Entrada al Almacen del Consignado
            if ((isset($_POST['id_alm_ent']) && $_POST['id_alm_ent'] > 0) && (isset($_POST['id_ubi_ent'])) && $_POST['id_ubi_ent'] > 0) {
                $consig = true;
                //Almacenes y Ubicación de Entrada de Consignado
                $id_alm = $_POST['id_alm_ent'];
                $id_ubi = $_POST['id_ubi_ent'];
                //Guardar Encabezado Movimiento de Inventario
                $tconfig_fac = DelnotnotfisModel::tip_doc_fac($_POST['id_emp']);
                $tmov_fac = $tconfig_fac['tmov_noc'];
                //Verificar si ya existe el Movimiento de Salida y obtener el numero de movimiento
                $origen = $_SESSION['id_cont'] . '-' . $tipo_fac . '-' . $_POST['id_emp'] . '-' . $num_tdo;
                $id_mov_inv = DelnotnotfisModel::consult_mov_in_ppal($origen, $tmov_fac);
                $data_mov_existC = [];
                if ($id_mov_inv) {
                    $total_rows = count($id_mov_inv);
                    for ($z = 0; $z < $total_rows; $z++) {
                        $id_mov_inv_id = $id_mov_inv[$z]['id_movinv'];
                        $data_mov_existC[] = [
                            'id_movinv' => $id_mov_inv_id
                        ];
                    }
                    $id_mov_inv_id = $id_mov_inv[0]['id_movinv'];
                    $data_mov_existC[] = [
                        'id_movinv' => $id_mov_inv_id
                    ];
                }
                //Proximo número de Movimiento            
                $r = DelnotnotfisModel::getNextNumer($tmov_fac);
                $num_movinv = intval($r['proximo_tmoinv']);
                $data_mov_encaC += [
                    'id_emp' => $id_emp,
                    'id_tmovinv' => $tmov_fac,
                    'num_movinv' => $num_movinv,
                    'fecha_comp' => $fecha_comp,
                    'id_moneda' => $id_moneda,
                    'tasa_cambio' => $xtasa,
                    'id_alm' => $id_alm,
                    'descrip_movinv' => 'Movimiento automático generado desde Nota de Entrega no Fiscal ' . $origen,
                    'status' => '1',
                    'id_cot' => '',
                    'id_cli' => $id_cli,
                    'id_vend' => '',
                    'id_fab' => '',
                    'origen' => $origen,
                    $modu => $_SESSION['id_user'],
                    $modd => getAuditoria(),
                ];
                //Guardar Detalle Movimiento de Enventario                                        
                $itemTotal = count($_POST['id_prod']);
                $fec_venc = '0000-00-00';
                for ($i = 0; $i < $itemTotal; $i++) {
                    $id_prod = $_POST['id_prod'][$i];
                    $can_det = $_POST['cant'][$i];
                    $uni_ven_prod = convert_string_to_number($_POST['ventas_prod1'][$i]);
                    $pre_vta = convert_string_to_number($_POST['ventas_prod1'][$i]);
                    $xcan_det = $can_det;
                    $data_mov_detC[] = [
                        'id_movinv' => '',
                        'id_prod' => $id_prod,
                        'id_ubi' => $id_ubi,
                        'cantidad' => $xcan_det,
                        'costo' => $pre_vta,
                        'flete' => '',
                        'otros_cargos' => '',
                        'door_cargos' => '',
                        'costo1' => $pre_vta,
                        'lote' => '',
                        'fec_venc' => $fec_venc,
                        'create_user' => $_SESSION['id_user'],
                        'create_date' => getAuditoria(),
                    ];
                }
            }
            //Generar las cuentas por cobrar en caso de que sea Facturación y/o Nota de Credito.
            //Guardar  de CXC
            //Detalle de Ventas
            //Concepto de Ventas
            $cxc = false;
            if ($_SESSION['ori'] == 'F' || $_SESSION['ori'] == 'C') {
                $cxc = true;
            }
            //Enviar al modelo para procesar la transaccion
            try {
                $data_where = array();
                if (!empty($_POST['id'])) {
                    $data_where += ['id_cot' => $_POST['id']];
                }
                $id_doc = DelnotnotfisModel::DocumentSaved($data_enca, $data_deta, $data_mov_exist, $data_mov_enca, $data_mov_deta, $data_where, $data_mov_existC, $data_mov_encaC, $data_mov_detC, $consig, $cxc);
                $title = "Registro guardado satisfactoriamente";
                $msg = "Se ha guardado satisfactoriamente la Nota de Entrega no Fiscal Nro. $num_tdo ";
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
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = DelnotnotfisModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Delnotnotfis');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la Nota de Entrega no Fiscal Nro. " . $r['num_tdo'],
                    'function_js' => "Delnotnotfis.js",
                    'function_js_mod' => "FACFun.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Delnotnotfis');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Delnotnotfis');
    }
    public function consultar_factura()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_cot'];
            if ($id > 0) {
                $r = DelnotnotfisModel::edit_deta($id);
                echo json_encode($r, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    public function consulta_adic01()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $fecha_precio = $_POST['fecha_precio'];
            $objeto = DelnotnotfisModel::consulta_adic01($id_emp, $fecha_precio);
            echo json_encode($objeto);
        }
    }
    public function consulta_adic02()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cli = $_POST['id_cli'];
            $objeto = DelnotnotfisModel::consulta_adic02($id_cli);
            echo json_encode($objeto);
        }
    }
    public function print_Delnotfis_con(int $id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = DelnotnotfisModel::print_factura($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Delnotnotfis');
                }
                $this->views->getView($this, "print_Delnotfis_con", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Delnotnotfis');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Delnotnotfis');
    }
    public function print_Delnotfis_cant_con(int $id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = DelnotnotfisModel::print_factura($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Delnotnotfis');
                }
                $this->views->getView($this, "print_Delnotfis_cant_con", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Delnotnotfis');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Delnotnotfis');
    }
    public function print_Delnotfis_sin(int $id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = DelnotnotfisModel::print_factura($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Delnotnotfis');
                }
                $this->views->getView($this, "print_Delnotfis_sin", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Delnotnotfis');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Delnotnotfis');
    }
    public function print_Delnotfis_cant_sin(int $id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = DelnotnotfisModel::print_factura($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Delnotnotfis');
                }
                $this->views->getView($this, "print_Delnotfis_cant_sin", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Delnotnotfis');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Delnotnotfis');
    }
    public function destroy()
    {
        $dataJson = [];
        $id = intval(limpiar($_POST['id']));
        try {
            $r = DelnotnotfisModel::destroy($id);
            $title = "Registro eliminado";
            $msg = "El registro se ha eliminado satisfactoriamente";
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
    public function create_express()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_fab = $_POST['id_fab'];
            $objeto = DelnotnotfisModel::create_express($id_fab);
            echo json_encode($objeto);
        }
    }
    public function listar_factura()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $r = DelnotnotfisModel::listar_Factura($id_emp);
            echo json_encode($r);
        }
    }
    public function tip_doc_fac()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_emp'];
            $r = DelnotnotfisModel::tip_doc_fac($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function aprobacion()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'tipo_fgenmsgcol' => 1,
                'title' => $_POST['title'],
                'message' => $_POST['message'],
                'status' => 1,
                'create_user' => $_SESSION['id_user'],
            ];
            $r = DelnotnotfisModel::aprobacion($data);
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => 'Registro en espera de aprobación'
            ];
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_notas()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $id_cli = $_POST['id_cli'];
            $fuente = $_POST['fuente'];
            $r = DelnotnotfisModel::listar_notas($id_emp, $id_cli, $fuente);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function consultar_nota()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cot = $_POST['id_cot'];
            $r = DelnotnotfisModel::consultar_nota($id_cot);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function gestion($token = null)
    {
        if (!$token) {
            return;
        }
        $datos = desencriptar_url($token);
        switch ($datos['accion']) {
            case 'edit':
                $this->edit($datos['id']);
                break;
            default:
                // Acción no permitida
                break;
        }
    }
}