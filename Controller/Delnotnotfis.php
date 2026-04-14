<?php
include_once VARTAX;
include $_SERVER['DOCUMENT_ROOT'] . '/Controller/Facturacion.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Models/EquivaleModel.php';
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
        $objeto = DelnotnotfisModel::all("N");
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Notas de Entrega no Fiscal',
            'function_js' => 'Delnotnotfis.js',
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva Nota de Entrega no Fiscal',
            'function_js' => 'Delnotnotfis.js',
        ]);
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modo = 'modify_user';
            $data = array();
            //Buscar Moneda de la Compañia
            $config_idemp = EmpresasModel::edit($_POST['id_emp']);
            $moneda_cia = $config_idemp['id_moneda'];
            $con_tdoc = 0;
            if (empty($_POST['id'])) {
                $modo = 'create_user';
                $r = (DelnotnotfisModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                $data += ['id_emp' => limpiar($_POST['id_emp'])];
                $con_tdoc = $r[0]['con_tdoc'];
                if ($con_tdoc == 1) {
                    $tipo_fac = $r[0]['tipo_codigo'];
                    $numero_fac = intval($r[0]['num_tdoc']);
                    //$data += ['num_tdo' => $numero_fac];
                } else {
                    $numero_fac = $_POST['num_tdo'];
                    //$data += ['num_tdo' => $_POST['num_tdo']];
                }
            } else {
                $numero_fac = $_POST['num_tdo'];                
            }
            $id_fab = '0';
            if (isset($_POST['id_fab'])) {
                $id_fab = limpiar($_POST['id_fab']);
            }
            $id_des = 0;
            $id_des_val = false;
            if (isset($_POST['id_des_enca']) && $_POST['id_des_enca']) {
                $id_des = $_POST['id_des_enca'];
                $id_des_reqapp = DelnotnotfisModel::show_row_des($id_des);
                if ($id_des_reqapp['appreq'] == 1) {
                    $id_des_val = true;
                }
            }
            try {
                if (isset($_POST['tasa_cambio'])) {
                    $xtasa = str_replace(',', '.', $_POST['tasa_cambio']);
                    $xtasa = number_format(floatval($xtasa), 8);
                } else {
                    $xtasa = 1;
                }
                $id_cont = 'NOF';
                $mon_doc = str_replace(',', '.', $_POST['total_frm']);
                $mon_doc = number_format(floatval($mon_doc), 2);
                if (isset($_POST['origen']) && $_POST['origen']) {
                    $fuente = DelnotnotfisModel::origen($_POST['origen']);
                    $id_cont = $fuente['tipo_codigo'] . '-' . $fuente['num_tdo'];
                }
                //Cambio 09-05-2005 Solicitado por Nelson Guerra a las 11:30:00
                //Para buscar el Motivo de Cambio del Cliente y guardarlo en el documento para que se muestre en el EDC CXC
                $id_cli = limpiar($_POST['id_cli']);
                $mot_cam = DelnotnotfisModel::query("SELECT b.id_motcam, b.adic_01, b.adic_02 FROM `f0014` a INNER JOIN `f0012a` b on b.id_motcam = a.id_motcam WHERE a.id_ent = {$id_cli}");
                if ($mot_cam) {

                    //
                    $id_motcam = $mot_cam[0]['id_motcam'];
                    $adic_01 = $mot_cam[0]['adic_01'];
                    $adic_02 = $mot_cam[0]['adic_02'];
                    //
                    $data += ['id_motcam' => $id_motcam];
                    $data += ['adic_01' => $adic_01];
                    $data += ['adic_02' => $adic_02];
                }
                $data += [
                    'id_tdo' => limpiar($_POST['id_tdo']),
                    'id_emp' => $_POST['id_emp'],
                    'id_cli' => $id_cli,
                    'num_tdo' => $numero_fac,
                    'id_fab' => $id_fab ?? '0',
                    'id_des' => $id_des,
                    'fecha_comp' => limpiar($_POST['fecha_comp']),
                    'fecha_venci' => limpiar($_POST['fecha_venci']),
                    'id_moneda' => limpiar($_POST['id_moneda']),
                    'tasa_cambio' => $xtasa,
                    'id_vend' => limpiar($_POST['id_vend']),
                    'oc_cliente' => limpiar($_POST['oc_cliente']),
                    'descrip_cot' => limpiar($_POST['descrip_cot']),
                    'status' => '1',
                    'mon_doc' => $mon_doc,
                    'sal_doc' => $mon_doc,
                    'id_cont' =>  $id_cont,
                    $modo => $_SESSION['id_user'],
                ];
                if (empty($_POST['id'])) {
                    //Guardar encabezado de factura//
                    $id = DelnotnotfisModel::guardar($data);
                    //Actualizar el siguiente numero de Nota de Entrega no Fiscal
                    if ($con_tdoc == 1) {
                        $data1 = array();
                        $data1 = [
                            'num_tdoc' => $numero_fac + 1,
                            $modo => $_SESSION['id_user'],
                        ];
                        $num = DelnotnotfisModel::setNextNumber($data['id_emp'], $data['id_tdo'], $data1);
                    }
                    //Actualizar el Numero de FActura en la cotización utilizada
                    if (isset($_POST['fuente']) && isset($_POST['origen'])) {
                        $cont = sprintf("NOF-%s-%s", $tipo_fac, $numero_fac);
                        $data_cont = array();
                        $data_cont = [
                            'id_cont' => $cont,
                            'modify_user' => $_SESSION['id_user'],
                        ];
                        $cotizacion = DelnotnotfisModel::set_cotiza($_POST['origen'], $data_cont);
                    }
                    Alertas::new(sprintf('La Nota de Entrega no Fiscal número %s se ha creado exitosamente.', $data['num_tdo']));
                } else {
                    $r = to_obj(DelnotnotfisModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                    $tipo_fac = $r[0]->tipo_codigo;
                    $numero_fac = $_POST['num_tdo'];
                    $id = DelnotnotfisModel::actualizar($_POST['id'], $data);                    
                    $id_det = DelnotnotfisModel::borrarDetfactura($_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new(sprintf('La Nota de Entrega no Fiscal número %s se ha modificado exitosamente.', $_POST['num_tdo']));
                }
                //Guardar detalle
                //Tasa IVA
                $tasa_iva = 0;
                $xvatTax = xvatTax($_POST['fecha_comp'], 'IVA');
                $tasa_iva = $xvatTax[0]['txr1_iva'];
                //
                $itemTotal = count($_POST['id_prod']);
                $data2 = array();
                $mon_doc = 0;
                for ($i = 0; $i < $itemTotal; $i++) {
                    $id_prod = $_POST['id_prod'][$i];
                    $can_det = $_POST['cant'][$i];
                    $uni_vta = $_POST['uni_ven_prod'][$i];
                    $pre_unit = str_replace(".", "", $_POST['ventas_prod'][$i]);
                    $pre_unit = str_replace(",", ".", $pre_unit);
                    $pre_vta = str_replace(".", "", $_POST['ventas_prod1'][$i]);
                    $pre_vta = str_replace(",", ".", $pre_vta);
                    $iva_prod = $_POST['iva_prod'][$i];
                    $sub_total = str_replace(".", "", $_POST['total'][$i]);
                    $sub_total = str_replace(",", ".", $sub_total);
                    $pre_unit = $pre_vta / $uni_vta;
                    //Nuevos valores a guardar para el dashboard
                    //Buscar producto para analisis de dashboard
                    $val_cod_prod = ProductosModel::query("SELECT (costo_prod + flete_prod + otros_prod + door_costo) costo, ((ventas_prod - (costo_prod + flete_prod + otros_prod + door_costo))) utilidad, ventas_prod,  uni_ven_prod FROM f4005 WHERE id_prod = {$id_prod}");
                    $val_adic = ProductosModel::query("SELECT b.adic_01, b.adic_02 FROM f0014 a INNER JOIN f0012a b ON b.id_motcam = a.id_motcam WHERE a.id_ent = {$_POST['id_cli']}");
                    $tasa_fact = 1;
                    $xuni_venta = 1;
                    $utilidad = 0;
                    $costo = $val_cod_prod[0]['costo'];
                    $val_cod_prod[0]['utilidad'];
                    //Se comenta por cambiar productos en caja por unidades de otro codigo
                    /*
                    if(isset($_POST['handling_conver'])){
                        if($_POST['handling_conver'] == 1){
                            $costo = $val_cod_prod[0]['costo'] / $val_cod_prod[0]['uni_ven_prod'];
                            $utilidad = $val_cod_prod[0]['utilidad'] / $val_cod_prod[0]['uni_ven_prod'];
                            $xuni_venta = $val_cod_prod[0]['uni_ven_prod'];
                        }
                    }
                    */
                    $costo = $costo * $can_det;
                    if ($utilidad != 0) {
                        $utilidad = $utilidad * $can_det;
                    }
                    $dif_cambio = 0;
                    if ($_POST['id_moneda'] == $moneda_cia) {
                        $tasa_fact = str_replace(',', '.', CambiosModel::rateExchange(2, $_POST['fecha_comp']));
                    }
                    $adicional = (floatval($sub_total) / floatval($tasa_fact)) - floatval($costo) - floatval($utilidad);
                    if ($_POST['id_moneda'] == $moneda_cia) {
                        if ($val_adic[0]['adic_01'] != 0) {
                            $val_adic_01 = $val_adic[0]['adic_01'];
                            $xadi01 = ($val_cod_prod[0]['ventas_prod'] / $xuni_venta);
                            $xadi02 = $xadi01 / $val_adic_01;
                            $adicional = ($xadi02 - $xadi01) * $can_det;
                            $tot_fact = $xadi02 * $can_det;
                        }
                        $tasa_pro = $sub_total / $tot_fact;
                        //
                        $validar = $sub_total - ((($costo / $can_det) + $utilidad + $adicional) * $tasa_pro);
                        $dif_cambio = $validar / $tasa_pro;
                    }
                    $mon_iva = 0.00;
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
                    $data2 = [
                        'id_cot' => $id,
                        'id_prod' => $id_prod,
                        'can_det' => $can_det,
                        'uni_vta' => $uni_vta,
                        'pre_unit' => $pre_unit,
                        'pre_vta' => $pre_vta,
                        'iva_prod' => $iva_prod,
                        'sub_total' => $sub_total,
                        'mon_iva' => $mon_iva,
                        'tota_prod' => $tota_prod,
                        'costo' => ($costo),
                        'utilidad' => ($utilidad),
                        'adicional' => ($adicional),
                        'dif_cambio' => ($dif_cambio),
                        $modo => $_SESSION['id_user'],
                    ];
                    
                    $mon_doc += $tota_prod;

                    //Actualizar MOnto y Saldo del Documento

                    if ($id_des > 0) {
                        $data += [
                            'id_des' => $id_des,
                        ];
                    }
                    $id_det = DelnotnotfisModel::guardarDetfactura($data2);
                }
                //Actulizar $mond_doc y $mon_sal y estaba vacio
                if($mon_doc != 0){
                    $data = [
                        'mon_doc' => $mon_doc,
                        'sal_doc' => $mon_doc,
                    ];
                    DelnotnotfisModel::actualizar( $id, $data);
                }
                //Crear la aprobación en caso de que haya descuento
                if ($id_des_val) {
                    $data_app = array();
                    $data_app = [
                        'id_cot' => $id,
                        'tipo_fgenmsgcol' => 1,
                        'title' => 'Aprobación aplicación de descuentos',
                        'message' => 'Se solicita aprobación de descuentos',
                        'status' => 1,
                        'create_user' => $_SESSION['id_user'],
                    ];
                    $r = DelnotnotfisModel::aprobacion($data_app);
                }
                //Guardar Movimiento de Inventario Encabezado
                $tconfig_fac = DelnotnotfisModel::tip_doc_fac($_POST['id_emp']);
                $tmov_fac = $tconfig_fac['tmov_fac'];
                $id_alm = $tconfig_fac['id_alm'];
                $id_ubi = $tconfig_fac['id_ubi'];
                if(isset($_POST['id_alm_def'])){
                    $id_alm = $_POST['id_alm_def'];
                }
                if (isset($_POST['id_ubi_def'])) {
                    $id_ubi = $_POST['id_ubi_def'];
                }
                //Eliminar registro si ya existe y se vuelve a crear
                //Saber número de Movimiento de Inventario, en caso de que ya exista
                $origen = 'NOF-' . $tipo_fac . '-' . $_POST['id_emp'] . '-' . $numero_fac;
                $id_mov_inv = DelnotnotfisModel::consult_mov_in_ppal($origen, $tmov_fac);
                if ($id_mov_inv) {
                    $total_rows = count($id_mov_inv);
                    for ($z = 0; $z < $total_rows; $z++) {
                        $id_mov_inv_id = $id_mov_inv[$z]['id_movinv'];
                        $id = DelnotnotfisModel::borrarDetInvMov($id_mov_inv_id);
                    }
                    $id_mov_inv_id = $id_mov_inv[0]['id_movinv'];
                    $id = DelnotnotfisModel::borrarDetInvMov($id_mov_inv_id);
                }
                //Proximo número de Movimiento
                $r = DelnotnotfisModel::getNextNumer($tmov_fac);
                $num_movinv = intval($r['proximo_tmoinv']);
                $data_mov_fac = array();
                $data_mov_fac = [
                    'id_emp' => limpiar($_POST['id_emp']),
                    'id_tmovinv' => $tmov_fac,
                    'num_movinv' => $num_movinv,
                    'fecha_comp' => ($_POST['fecha_comp']),
                    'id_moneda' => limpiar($_POST['id_moneda']),
                    'tasa_cambio' => $xtasa,
                    'id_alm' => $id_alm,
                    'descrip_movinv' => 'Movimiento automático generado desde Nota de Entrega no Fiscal ' . $origen,
                    'origen' => $origen,
                    'status' => 1,
                    $modo => $_SESSION['id_user']
                ];
                $id_mov_inv = DelnotnotfisModel::guardar_mov_inv($data_mov_fac);
                //Actualizar numero siguiente
                $id_mov_inv_id = DelnotnotfisModel::setNextNumber_Mov_Inv($tmov_fac);

                //Guardar Movimiento de Inventario Detalle
                $num_rows = count($_POST['id_prod']);
                $data_det_fac = array();
                $fec_venc = '0000-00-00';
                for ($i = 0; $i < $num_rows; $i++) {
                    $id_prod = $_POST['id_prod'][$i];
                    $can_det = $_POST['cant'][$i];
                    $uni_ven_prod = $_POST['uni_ven_prod'][$i];
                    $pre_vta = str_replace(".", "", $_POST['ventas_prod1'][$i]);
                    $pre_vta = str_replace(",", ".", $pre_vta);

                    //$stock = DelnotnotfisModel::consulta_prod($id_prod);

                    //$total_rows_stock = is_array($stock) ? count($stock) : 0;

                    $xcan_det = $can_det;

                    //if($total_rows_stock == 0){
                    $data_det_fac = [
                        'id_movinv' => $id_mov_inv,
                        'id_prod' => $id_prod,
                        'id_ubi' => $id_ubi,
                        'cantidad' => $xcan_det,
                        'lote' => 'SL',
                        'fec_venc' => $fec_venc,
                        'costo' => $pre_vta,
                        'costo1' => $pre_vta,
                        'create_user' => $_SESSION['id_user']
                    ];
                    $id_det = DelnotnotfisModel::guardar_Det_Movin($data_det_fac);
                    //}

                    /*for($xi=0; $xi < $total_rows_stock;$xi++){
                        $dis_stock = $stock[$xi]['stock'];
                        $disp_lote = $stock[$xi]['lote'];
                        $dis_fec_ven = $stock[$xi]['fec_ven'];
                        $id_ubi = $stock[$xi]['id_ubi'];
                        if($dis_stock >= $xcan_det){
                            $data_det_fac = [
                                'id_movinv' => $id_mov_inv,
                                'id_prod' => $id_prod,
                                'id_ubi' => $id_ubi,
                                'cantidad' => $xcan_det,
                                'lote' => $disp_lote,
                                'fec_venc' => $dis_fec_ven,
                                'costo' => $pre_vta,
                                'costo1' => $pre_vta,
                                'create_user' => $_SESSION['id_user']
                            ];
                            $xcan_det -= $xcan_det;
                            $id_det = DelnotnotfisModel::guardar_Det_Movin($data_det_fac);
                        }elseif($dis_stock <= $xcan_det) {
                            $data_det_fac = [
                                'id_movinv' => $id_mov_inv,
                                'id_prod' => $id_prod,
                                'id_ubi' => $id_ubi,
                                'cantidad' => $dis_stock,
                                'lote' => $disp_lote,
                                'fec_venc' => $dis_fec_ven,
                                'costo' => $pre_vta,
                                'costo1' => $pre_vta,
                                'create_user' => $_SESSION['id_user']
                            ];
                            $xcan_det = $xcan_det - $dis_stock;
                            $id_det = DelnotnotfisModel::guardar_Det_Movin($data_det_fac);
                        }
                        if($xcan_det == 0){
                            break;
                        }
                    }*/
                }
                //Validar si es un cliente en consignacion / Fracción Caso Equivalencia 100
                $id_ent = DelnotnotfisModel::consulta_vend($_POST['id_cli']);
                //Validar si se hace el calculo para fracciones ne la conversion
                $id_ubi = '';
                $c_consig = $id_ent['c_consig'];
                $tmov_fac = $tconfig_fac['tmov_noc'];
                $id_alm = $id_ent['id_alm'];
                $id_ubi = $id_ent['id_ubi'];


                if ($id_ubi == null) {
                    $id_ubi = $_POST['id_ubi_cli'];
                }
                if (isset($id_ent) && $id_ent['c_consig'] == 1 && $id_ent['id_alm']) {
                    $origen = 'NOF-' . $tipo_fac . '-' . $_POST['id_emp'] . '-' . $_POST['num_tdo'];
                    $mov_inv = DelnotnotfisModel::consult_mov_in_ppal($origen, $tmov_fac);
                    if ($mov_inv) {
                        $total_rows = count($mov_inv);
                        for ($z = 0; $z < $total_rows; $z++) {
                            $id_mov_inv_id = $mov_inv[$z]['id_movinv'];
                            $id = DelnotnotfisModel::borrarDetInvMov($id_mov_inv_id);
                        }
                        $id_mov_inv = $mov_inv[0]['id_movinv'];
                        $id = DelnotnotfisModel::borrarDetInvMov($id_mov_inv);
                    }
                    $r = DelnotnotfisModel::getNextNumer($tmov_fac);
                    $num_movinv = intval($r['proximo_tmoinv']);

                    $data_mov_fac = array();
                    $data_mov_fac = [
                        'id_emp' => $_POST['id_emp'],
                        'id_tmovinv' => $tmov_fac,
                        'num_movinv' => $num_movinv,
                        'fecha_comp' => ($_POST['fecha_comp']),
                        'id_moneda' => limpiar($_POST['id_moneda']),
                        'id_cli' => $_POST['id_cli'],
                        'tasa_cambio' => $xtasa,
                        'id_alm' => $id_alm,
                        'id_cli' => $_POST['id_cli'],
                        'descrip_movinv' => 'Movimiento automático generado desde Nota de Entrega no Fiscal' . $origen,
                        'origen' => 'NOF-' . $tipo_fac . '-' . $_POST['id_emp'] . '-' . $numero_fac,
                        'status' => 1,
                        $modo => $_SESSION['id_user']
                    ];
                    $id_mov_inv = DelnotnotfisModel::guardar_mov_inv($data_mov_fac);
                    $id_num_movin = DelnotnotfisModel::setNextNumber_Mov_Inv($tmov_fac);

                    //Guardar Movimiento de Inventario Detalle
                    $num_rows = count($_POST['id_prod']);

                    $data_det_fac = array();

                    for ($i = 0; $i < $num_rows; $i++) {
                        //Modificado el 20-06-2025 a las 10.58.00 por Jose Vargas a solicutd de Alejandra, para optimizar el proceso  de consignacin a clincas
                        //Buscar equivalencia del producto de salida para asignar el nuevo producto de entrada
                        $id_prod = $_POST['id_prod'][$i];
                        $can_det = $_POST['cant'][$i];
                        $uni_ven_prod = $_POST['uni_ven_prod'][$i];
                        $pre_vta = str_replace(".", "", $_POST['ventas_prod1'][$i]);
                        $pre_vta = str_replace(",", ".", $pre_vta);
                        $id_prod_equivale = EquivaleModel::con_equivale($_POST['id_emp'], $id_cli, '100', $id_prod);                        
                        if ($id_prod_equivale) {
                            $id_prod = $id_prod_equivale[0]['id_prod'];
                            $can_det = $can_det * $uni_ven_prod;
                            $pre_vta = str_replace(".", "", $_POST['ventas_prod1'][$i]);
                            $pre_vta = str_replace(",", ".", $pre_vta);
                            $pre_vta = $pre_vta / $uni_vta;
                        }
                        $disp_lote = "SL";
                        $dis_fec_ven = '0000-00-00';
                        //$stock = DelnotnotfisModel::consulta_prod_consig($id_prod, $_POST['id_cli'], $id_alm, $id_ubi);
                        //Modificado el 20-06-2025 a las 10.58.00 por Jose Vargas a solicutd de Alejandra, para optimizar el proceso  de consignacin a clincas
                        //Buscar equivalencia del producto de salida para asignar el nuevo producto de entrada
                        //$conver_consignado = DelnotnotfisModel::consulta01($id_prod);
                        $conver_consignado_valor = 1;
                        //if($conver_consignado['conv_prod_cons'] != 1 && $conver_consignado['conv_prod_cons'] != '' && $handling_conver == 1){
                        //    $conver_consignado_valor = $conver_consignado['conv_prod_cons'];
                        //}

                        //$total_rows_stock = count($stock);
                        //$total_rows_stock = is_array($stock) ? count($stock) : 0;

                        $xcan_det = $can_det;
                        //if($total_rows_stock == 0){
                        $data_det_fac = [
                            'id_movinv' => $id_mov_inv,
                            'id_prod' => $id_prod,
                            'id_ubi' => $id_ubi,
                            'cantidad' => $xcan_det * $conver_consignado_valor,
                            'lote' => $disp_lote,
                            'fec_venc' => $dis_fec_ven,
                            'costo' => $pre_vta,
                            'costo1' => $pre_vta,
                            'create_user' => $_SESSION['id_user']
                        ];
                        $id_det = DelnotnotfisModel::guardar_Det_Movin($data_det_fac);
                        //}

                        /*for($xi=0; $xi < $total_rows_stock;$xi++){
                                $dis_stock = $stock[$xi]['stock'];
                                $disp_lote = $stock[$xi]['lote'];
                                $dis_fec_ven = $stock[$xi]['fec_ven'];
                                $id_ubi = $stock[$xi]['id_ubi'];
                                if($dis_stock >= $xcan_det){
                                    $data_det_fac = [
                                        'id_movinv' => $id_mov_inv,
                                        'id_prod' => $id_prod,
                                        'id_ubi' => $id_ubi,
                                        'cantidad' => $xcan_det * $uni_ven_prod * $conver_consignado_valor,
                                        'lote' => $disp_lote,
                                        'fec_venc' => $dis_fec_ven,
                                        'costo' => $pre_vta,
                                        'costo1' => $pre_vta,
                                        'create_user' => $_SESSION['id_user']
                                    ];
                                    $xcan_det -= $xcan_det;
                                    $id_det = DelnotnotfisModel::guardar_Det_Movin($data_det_fac);
                                }elseif($dis_stock <= $xcan_det) {
                                    $data_det_fac = [
                                        'id_movinv' => $id_mov_inv,
                                        'id_prod' => $id_prod,
                                        'id_ubi' => $id_ubi,
                                        'cantidad' => $dis_stock * $uni_ven_prod * $conver_consignado_valor,
                                        'lote' => $disp_lote,
                                        'fec_venc' => $dis_fec_ven,
                                        'costo' => $pre_vta,
                                        'costo1' => $pre_vta,
                                        'create_user' => $_SESSION['id_user']
                                    ];
                                    $xcan_det = $xcan_det - $dis_stock;
                                    $id_det = DelnotnotfisModel::guardar_Det_Movin($data_det_fac);
                                }
                                if($xcan_det == 0){
                                    break;
                                }
                            }*/
                    }
                }
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
            } finally {
                header('Location:' . base_url . '/Delnotnotfis');
            }
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
    public function print_Delnotfis_con($id)
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
    public function print_Delnotfis_cant_con($id)
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
    public function print_Delnotfis_sin($id)
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
    public function print_Delnotfis_cant_sin($id)
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
        $r = DelnotnotfisModel::selectEncyDetmovinv($id);
        foreach ($r as $key) {
            $id_movinv = $key['id_movinv'];
            DelnotnotfisModel::borrarEncyDetmovinv($id_movinv);
        }
        $ide = DelnotnotfisModel::borrar($id);
        if ($ide) {
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['id'], $_POST['id'])
            ];
        } else {
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['cod_alm'], $_POST['noidm_alm'])
            ];
        }
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
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
}
