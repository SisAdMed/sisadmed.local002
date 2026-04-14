<?php

/**
 * Controlador de la Factura de Compras 
 * Creado por José Vargas el 20-09-2004
 */
include_once VARTAX;
include_once CXPDOCMODEL;
include_once CXPCONMODEL;
include_once DELNOTFISMODEL;
include_once TIPDOCCXPMODEL;
include_once MOVINMODEL;
include_once PRODUCTOMODEL;
include_once CAMBIOMODEL;
include_once COTIZAMODEL;
include_once ROOT . DS . 'Models' . DS . 'EmpresasModel.php';


$ori = '';
$page = '';

if (isset($_GET['ori'])) {
    $ori = htmlspecialchars($_GET['ori']);
    if (isset($_SESSION['ori'])) {
        unset($_SESSION['or']);
        $_SESSION['ori'] = $ori;
    }
    $xori = '?ori=' . $ori;
    if ($_SESSION['ori'] == 'M') {
        $page = 'Facturas de Compras';
    } else if ($_SESSION['ori'] == 'O') {
        $page = 'Ordenes de Compras';
    } else if ($_SESSION['ori'] == 'A') {
        $page = 'Notas de Crédito';
    } else if ($_SESSION['ori'] == 'T') {
        $page = 'Notas de Entrega';
    } else if ($_SESSION['ori'] == 'V') {
        $page = 'Notas de Devolución';
    }
    $_SESSION['page_name'] = $page;
}
class PurInv extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(122);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        global $ori;
        $objeto = PurInvModel::all($_SESSION['ori']);
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta ' . $_SESSION['page_name'],
            'function_js' => 'PurInv.js',
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva ' . $_SESSION['page_name'],
            'function_js' => 'PurInv.js',
        ]);
    }
    public function tip_doc_com(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_emp'];
            $r = PurInvModel::tip_doc_com($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_entidad_modal(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tipo = $_POST['tipo'];
            $r = PurInvModel::listar_entidad_modal($tipo);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $ori = $_POST['ori'];
            $modo = 'modify_user';
            $origen = 'COM';
            $id_cont = $origen;

            $xmon_iva = 0;
            $xmon_doc = 1;
            //Validar el tipo de documento
            $id_tdo = TipoDocCXPModel::edit($_POST['id_tdo']);
            $data = array();
            if (empty($_POST['id'])) {
                $modo = 'create_user';
            }
            try {

                if ($ori == 'A') {
                    $id_cont = $id_cont . '-' . $_POST['fuente'] . '-' . $_POST['origen'];
                }

                $xtasa = str_replace(',', '.', $_POST['tasa_cambio']);
                $xtasa = number_format($xtasa, 2);
                $xdec = 2;
                $data += [
                    'id_emp' => $_POST['id_emp'],
                    'id_tdo' => $_POST['id_tdo'],
                    'num_tdo' => $_POST['num_tdo'],
                    'fecha_venci' => $_POST['fecha_venci'],
                    'fecha_comp' => $_POST['fecha_comp'],
                    'fec_fact' => $_POST['fec_fact'],
                    'num_control' => $_POST['num_control'],
                    'id_moneda' => $_POST['id_moneda'],
                    'tasa_cambio' => $xtasa,
                    'id_cli' => $_POST['id_cli'],
                    'id_cont' => $id_cont,
                    'origen' => $origen,
                    'status' => 1,
                    $modo => $_SESSION['id_user'],
                ];
                $id_retiva =  $_POST['id_retiva'];

                if ($id_retiva) {
                    if (($_POST['ori'] == 'M' || $_POST['ori'] == 'A')) {
                        $data += ['id_retiva' => $_POST['id_retiva']];
                    }
                }
                $id_det_doc = '';
                if (empty($_POST['id'])) {
                    $id = PurInvModel::guardar($data);
                    $id_det_doc = $id;
                    $mensaje = sprintf('El documento %s - %s número %s se ha creado exitosamente', $id_tdo['tipo_codigo'], $id_tdo['nom_tdoc'], $_POST['num_tdo']);
                } else {
                    $id = PurInvModel::actualizar($_POST['id'], $data);
                    $id_det_doc = $_POST['id'];
                    $mensaje = sprintf('El documento %s - %s número %s se ha modificado exitosamente',  $id_tdo['tipo_codigo'], $id_tdo['nom_tdoc'], $_POST['num_tdo']);
                }
                //DEtalles de Compañia, moneda
                $datos_cia = datos_cia($_POST['id_emp'], $_POST['fecha_comp']);
                $cambio = 1;
                if ($_POST['id_moneda'] == $datos_cia['id_moneda']) {
                    $cambio = CambiosModel::rateExchange(2, $_POST['fecha_comp']);
                    $cambio = convert_string_to_number($cambio);
                }
                //Guardar detalle de comrpas
                //Valor del Iva
                $xvatTax = xvatTax($_POST['fecha_comp'], 'IVA');
                $xtasaIVA = (($xvatTax[0]['txr1_iva']));
                $data_det = array();
                PurInvModel::borrarDet($id_det_doc);
                $itemTotal = count(($_POST['id_prod']));
                $total_frm = 0;
                for ($i = 0; $i < $itemTotal; $i++) {
                    $id_prod = $_POST['id_prod'][$i];
                    if ($id_prod) {
                        $can_det = $_POST['cant'][$i];
                        $uni_vta = $_POST['uni_com_prod'][$i];
                        $sub_total = floatval(convert_string_to_number($_POST['total'][$i]) * $xmon_doc);
                        $pre_unit = $sub_total / $can_det;
                        //$pre_unit = convert_string_to_number( $_POST['costo_prod'][$i])  * $xmon_doc;
                        $pre_vta = $pre_unit;
                        $pre_vta = convert_string_to_number($_POST['costo_prod1'][$i]) * $xmon_doc;
                        $iva_prod = $_POST['iva_prod'][$i];

                        $lote = $_POST['lote'][$i];
                        $fec_venc = $_POST['fec_venc'][$i];
                        $mon_iva = 0;
                        if ($iva_prod == "S") {
                            $mon_iva =  $sub_total * ($xtasaIVA / 100);
                        }
                        $tota_prod = $sub_total + $mon_iva;

                        $data_det = [
                            'id_cot' => $id_det_doc,
                            'id_prod' => $id_prod,
                            'can_det' => $can_det,
                            'lote' => $lote,
                            'fec_venc' => $fec_venc,
                            'uni_vta' => $uni_vta,
                            'pre_unit' =>  $pre_unit,
                            'pre_vta' => $pre_vta,
                            'iva_prod' => $iva_prod,
                            'sub_total' => $sub_total,
                            'mon_iva' => $mon_iva,
                            'tota_prod' => $tota_prod,
                            'create_user' => $_SESSION['id_user'],
                        ];
                        debug($data_det);
                        $total_frm += $tota_prod;
                        PurInvModel::guardarDet($data_det);
                    }
                }
                //Buscar el Configuracion de Compras
                $config_com = PurInvModel::tip_doc_com($_POST['id_emp']);
                $id_cxpcon = $config_com['con_purcon'];
                $id_alm = $config_com['id_alm'];
                $id_ubi = $config_com['id_ubi'];
                if ($_POST['ori'] == 'A') {
                    $id_typmovinwar = $config_com['id_typmovoutwar'];
                } else {
                    $id_typmovinwar = $config_com['id_typmovinwar'];
                }

                $cxp_con_aux = ConcepCXPModel::edit($id_cxpcon);
                if ($_POST['ori'] == 'M' || $_POST['ori'] == 'T' || $_POST['ori'] == 'A') {
                    //Crear la Cuenta por Pagar
                    $origen = $id_det_doc;
                    $data = [
                        'id_emp' => $_POST['id_emp'],
                        'id_tdo' => $_POST['id_tdo'],
                        'num_tdo' => $_POST['num_tdo'],
                        'id_cli' => $_POST['id_cli'],
                        'fecha_comp' => $_POST['fecha_comp'],
                        'fecha_venci' => $_POST['fecha_venci'],
                        'id_moneda' => $_POST['id_moneda'],
                        'tasa_cambio' => $xtasa,
                        'num_control' => $_POST['num_control'],
                        'descrip_cot' => 'Factura generada desde el Modulo de Compras ',
                        'mon_doc' => $total_frm,
                        'sal_doc' => $total_frm,
                        'origen' => $origen,
                        'status' => 1,
                        'create_user' => $_SESSION['id_user']
                    ];
                    if ($id_retiva) {
                        $data += ['id_retiva' => $id_retiva];
                    }
                    //Borrar documento en caso de que exista
                    $id_doc = CXPDocumentModel::sel_doc_origen($origen) ?? '';
                    if ($id_doc) {
                        CXPDocumentModel::borrarDetCXPDocument($id_doc[0]['id_cot']);
                        CXPDocumentModel::borrar($id_doc[0]['id_cot']);
                    }
                    //Guardar encabezado de documentos
                    $id_emp = $_POST['id_emp'];
                    $id_con_emp = EmpresasModel::edit($id_emp);
                    //Configuracion del modulo
                    $config_cxp = CXPDocumentModel::config_cxp($id_emp);
                    $id = CXPDocumentModel::guardar($data);
                    $id_cxp_doc = $id;
                    //Guardar detalle
                    $itemTotal = count($_POST['id_prod']);
                    $xmon_base = 0;
                    $xmon_exe = 0;
                    $data2 = array();
                    for ($i = 0; $i < $itemTotal; $i++) {
                        $id_prod = $_POST['id_prod'][$i];
                        if ($id_prod) {
                            $xmonto = convert_string_to_number($_POST['total'][$i]) * $xmon_doc;
                            if ($_POST['iva_prod'][$i] == 'S') {
                                $xmon_base += $xmonto;
                                $xmon_exe += 0;
                            } else {
                                $xmon_base += 0;
                                $xmon_exe += $xmonto;
                            }
                        }
                    }

                    $id_aux = 0;
                    if ($cxp_con_aux['id_aux']) {
                        $id_aux = 0;
                    }

                    if ($xmon_base != 0) {
                        $xmon_iva = $xmon_base * ($xtasaIVA / 100);
                        $data2 = [
                            'id_cot' => $id_cxp_doc,
                            'id_concxp' => $id_cxpcon,
                            'iva' => 'S',
                            'id_aux' => $id_aux,
                            'monto' => $xmon_base,
                            'mon_iva' => $xmon_iva,
                            'create_user' => $_SESSION['id_user']
                        ];
                        CXPDocumentModel::guardarDetDocument($data2);
                    }
                    if ($xmon_exe != 0) {
                        $data2 = [
                            'id_cot' => $id_cxp_doc,
                            'id_concxp' => $id_cxpcon,
                            'iva' => 'N',
                            'id_aux' => $id_aux,
                            'monto' => $xmon_exe,
                            'mon_iva' => 0,
                            'create_user' => $_SESSION['id_user']
                        ];
                        CXPDocumentModel::guardarDetDocument($data2);
                    }
                }
                //Crear Movimiento de Inventario de Entrada y/o Salida en caso de NC
                if ($ori != 'O') {
                    //if ($ori == 'M') {
                        $cont = 'COM-' . $id_tdo['tipo_codigo'] . '-' . $_POST['id_emp'] . '-' . $_POST['num_tdo'];
                    //} elseif ($ori == 'A') {
                        //$cont = 'COM-' . $id_tdo['tipo_codigo'] . '-' . $_POST['id_emp'] . '-' . $_POST['num_tdo'];
                    //}

                    //Eliminar movimiento en caso de que exista
                    $id_mov_inv = DelnotnotfisModel::consult_mov_in_ppal($cont, $id_typmovinwar);

                    if (is_array($id_mov_inv)) {
                        $id_mov = $id_mov_inv[0]['id_movinv'];
                        $id = DelnotnotfisModel::borrarEncyDetmovinv($id_mov);
                    }
                    //Obtener el numero siguiente
                    $num_movinv = DelnotnotfisModel::getNextNumer($id_typmovinwar);
                    $data = [
                        'id_emp' => limpiar($_POST['id_emp']),
                        'id_tmovinv' => $id_typmovinwar,
                        'num_movinv' => $num_movinv['proximo_tmoinv'],
                        'fecha_comp' => ($_POST['fecha_comp']),
                        'id_moneda' => limpiar($_POST['id_moneda']),
                        'tasa_cambio' => $xtasa,
                        'id_alm' => $id_alm,
                        'descrip_movinv' => 'Movimiento auttomático generado desde Compras',
                        'origen' => $cont,
                        'status' => 1,
                        'create_user' => $_SESSION['id_user']
                    ];
                    $id_mov = MovinvModel::guardar($data);
                    $id_num_movin = MovinvModel::setNextNumber($id_typmovinwar);

                    //Detalle de Movimiento
                    $num_rows = count($_POST['id_prod']);
                    $lote = 'SL';
                    $fec_venc = '0000-00-00';
                    $dat_mov_det = array();
                    for ($i = 0; $i < $num_rows; $i++) {
                        $lote = 'SL';
                        $fec_venc = '0000-00-00';
                        $xmonto = convert_string_to_number($_POST['total'][$i]);
                        $xcant = $_POST['cant'][$i];
                        $id_prod = MovinvModel::cons_producto($_POST['id_prod'][$i]);
                        $id_prod_id = $_POST['id_prod'][$i];
                        $costo_ori = $id_prod['costo_prod'];
                        $usa_lote = $id_prod['lote_prod'];
                        if ($usa_lote == 1) {
                            $lote = $_POST['lote'][$i];
                            $fec_venc = $_POST['fec_venc'][$i];
                        }
                        $costo =  ($xmonto / $cambio) / $xcant;
                        $flete = $id_prod['flete_prod'];
                        $otros = $id_prod['otros_prod'];
                        $door = $id_prod['door_costo'];
                        $iva_prod = $_POST['iva_prod'][$i];
                        $recargo1 = $id_prod['recar_prod'];
                        $recargo2 = $id_prod['recar2_prod'];
                        $costo1 = number_format(($costo + $flete + $otros + $door), 4);
                        $datdet = [
                            'id_movinv' => $id_mov,
                            'id_prod' => $id_prod_id,
                            'id_ubi' => $id_ubi,
                            'cantidad' => $xcant,
                            'costo' => $costo,
                            'flete' =>  $flete,
                            'otros_cargos' => $otros,
                            'door_cargos' =>  $door,
                            'costo1' => $costo1,
                            'lote' => $lote,
                            'fec_venc' => $fec_venc,
                            'create_user' => $_SESSION['id_user'],
                        ];
                        $id_det = MovinvModel::guardarDetMovin($datdet);
                        //Actualizar Costos del producto
                        $dat_pro = array();
                        $xiva = 0;
                        if ($iva_prod == "S") {
                            $xiva = 1;
                        }
                        if ($ori == 'M') {
                            $dat_pro += ['iva_prod' => $xiva];
                        }
                        if ($costo > $costo_ori) {
                            $dat_pro += ['costo_prod' => number_format($costo, 4)];
                        }
                        if ($recargo1 > 0) {
                            $dat_pro += ['ventas_prod' => number_format($costo1 / $recargo1, 4)];
                        }
                        if ($recargo2 > 0) {
                            $dat_pro += ['venta2_prod' => number_format($costo1 / $recargo2, 4)];
                        }
                        $id_pro_ud = ProductosModel::actualizar($id_prod_id, $dat_pro);
                    }
                }
                //Validar si se aplica retencion de IVA
                //Llamar Calculo de Retención de IVA
                $id_emp = $_POST['id_emp'];
                $id_con_emp = EmpresasModel::edit($id_emp);
                $especial_contrib = $id_con_emp['especial_contrib'];
                $id_retiva = $_POST['id_retiva'];
                $tencontre = true;
                if ($especial_contrib == 'S' && $xmon_iva != 0 && $id_retiva != '') {
                    //Obtener Configuracion de CXP
                    if (empty($_POST['id'])) {
                        $config_cxp = CXPDocumentModel::config_cxp($id_emp);
                        $num_ret = $config_cxp['con_ret_iva'];
                    } else {
                        $con_retiva = CXPDocumentModel::con_retiva($id_det_doc, 'COM');
                        if ($con_retiva) {
                            $id_retiva_db = $con_retiva[0]['id'];
                            if (isset($con_retiva['num_retiva']))
                                $num_ret = $con_retiva['num_retiva'];
                            else {
                                $num_ret = $config_cxp['con_ret_iva'];
                            }
                        } else {
                            $tencontre = false;
                            $num_ret = $config_cxp['con_ret_iva'];
                        }
                    }

                    //
                    $dat_ret_iva = array();
                    $val_retiva = CXPDocumentModel::ret_iva($id_retiva);

                    $por_retiva = $val_retiva['tasa_retiva'];
                    $min_retiva = $val_retiva['min_retiva'];
                    //
                    $xmon_retiva = $xmon_iva * $por_retiva / 100;

                    //
                    if ($min_retiva != 0 && $xmon_retiva < $min_retiva) {
                        $xmon_retiva = $min_retiva;
                    }

                    //Guardar Retención de IVA
                    $dat_ret_iva = [
                        'id_emp' => $id_emp,
                        'id_cot' => $id_det_doc,
                        'id_retiva' => $id_retiva,
                        'id_ent' => $_POST['id_cli'],
                        'fecha_pago' => $_POST['fecha_comp'],
                        'num_retiva' => $num_ret,
                        'create_user' => $_SESSION['id_user'],
                        'tot_compras' => $total_frm,
                        'tot_exento' => $xmon_exe,
                        'tot_base' => $xmon_base,
                        'tasa_iva' => $xtasaIVA,
                        'tot_iva' => $xmon_iva,
                        'tot_ret' => $xmon_retiva,
                        'origen' => 'COM',
                        'por_retiva' => $por_retiva,
                        'modify_user' => $_SESSION['id_user'],
                    ];
                    //Guardar Retención
                    if (empty($_POST['id'])) {
                        $r = CXPDocumentModel::save_retiva($dat_ret_iva);
                        //Actualizar el proximo numero de la retencion de iva
                        $data_cfg_cxp = array();
                        $data_cfg_cxp = [
                            'con_ret_iva' => $num_ret + 1,
                            'modify_user' => $_SESSION['id_user'],
                        ];
                        $r = CXPDocumentModel::config_cxp_up($config_cxp['id_config'], $data_cfg_cxp);
                    } else {
                        if ($tencontre) {
                            $r = CXPDocumentModel::update_retiva($id_retiva_db, $dat_ret_iva);
                        } else {
                            $r = CXPDocumentModel::save_retiva($dat_ret_iva);
                            //Actualizar el proximo numero de la retencion de iva
                            $data_cfg_cxp = array();
                            $data_cfg_cxp = [
                                'con_ret_iva' => $num_ret + 1,
                                'modify_user' => $_SESSION['id_user'],
                            ];
                            $r = CXPDocumentModel::config_cxp_up($config_cxp['id_config'], $data_cfg_cxp);
                        }
                    }
                    //Guardar cocnepto de retención en el detalle del documento
                    $id_con_retiva = $config_cxp['id_retiva'];
                    $data2 = [
                        'id_cot' => $id_cxp_doc,
                        'id_concxp' => $id_con_retiva,
                        'iva' => 'N',
                        'monto' => $xmon_retiva * -1,
                        'mon_iva' => 0,
                        'create_user' => $_SESSION['id_user']
                    ];
                    $id_det = CXPDocumentModel::guardarDetDocument($data2);
                    //Actualizar monto del documento
                    $data = [
                        'mon_doc' => $total_frm - $xmon_retiva,
                        'sal_doc' => $total_frm - $xmon_retiva,
                    ];
                    $id = CXPDocumentModel::actualizar($id_cxp_doc, $data);
                }
                Alertas::new($mensaje);
            } catch (Exception $e) {
                Alertas::new($e->getMessage(), 'danger');
            } finally {
                header('Location: ' . base_url . '/PurInv?ori=' . $ori);
            }
        }
    }
    public function destroy(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = [];
            $id = intval(limpiar($_POST['id']));
            $origen = limpiar($_POST['origen']);
            $id_emp = intval($_POST['id_emp']);
            $ori = $_POST['ori'];
            //Buscar y borrar Movimientos de Inventarios
            try {
                $r = PurInvModel::selectEncyDetmovinv($origen, $id_emp);
                if ($r) {
                    $id_movinv = $r['id_movinv'];
                    $ide = PurInvModel::borrarEncyDetmovinv($id_movinv);
                }
                //Buscar y borrar las Cuentas por Pagar

                if ($ori == 'M' || $ori == 'A') {
                    $r = PurInvModel::selectEncyDetCXP($id, $id_emp);
                    $id_cot = $r['id_cot'];
                    $ide = PurInvModel::borrarEncyDetCXP($id_cot);
                }
                //Eliminar Retenciones de IVA
                if ($ori == 'M' || $ori == 'A') {
                    $r = PurInvModel::query("DELETE FROM f3006 WHERE id_cot = {$id_cot}");
                }
                //Borrar Compras
                $ide = PurInvModel::borrar($id);
                if ($ide) {
                    $msg = 'El Registro se ha eliminado satisfactoriamente';
                    $title = 'Eliminado correctamente';
                    $dataJson = [
                        'title' => $title,
                        'icon' => 'success',
                        'msg' => $msg
                    ];
                } else {
                    $title = 'Error al eliminar el registro';
                    $dataJson = [
                        'title' => $title,
                        'icon' => 'error',
                        'msg' => 'No se puede elimianr el registros, se encuetra asociado con otro registro, por favor inente luego',
                    ];
                }
            } catch (PDOException $e) {
                $title = "Se ha presentado un error, intente luego";
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => $title,
                    'icon' => 'error',
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = PurInvModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/PurInv');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el documento Número " . $r['num_tdo'],
                    'function_js' => "PurInv.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/PurInv');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/PurInv');
    }
    public function showrow(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cot = $_POST['id'];
            $r = PurInvModel::showrow($id_cot);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function print_Retiva($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = PurInvModel::print_retiva_enca($id, 'O');
                $d = PurInvModel::print_retiva_deta($id, 'O');
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/PurInv');
                }
                $this->views->getView($this, "print_RetIva", [
                    'r' => to_obj($r),
                    'd' => to_obj($d),
                ]);
            } else {
                header('Location:' . base_url . '/PurInv');
            }
            return;
        }
    }
    public function listar_doc_fuentes(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $id = $_POST['id'];
            $tipo  = $_POST['tipo'];
            $id_cli = $_POST['id_cli'];
            $tipo_doc_ori = $_POST['tipo_doc_ori'];
            $r = PurInvModel::listar_doc_fuentes($id_emp, $id, $tipo, $id_cli, $tipo_doc_ori);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
