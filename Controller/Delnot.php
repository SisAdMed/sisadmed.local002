<?php
include_once VARTAX;
 include $_SERVER['DOCUMENT_ROOT'].'/Controller/Facturacion.php';
class Delnot extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(130);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = DelnotModel::all("N");        
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Notas de Entrega',
            'function_js' => 'Delnot.js',
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva Nota de Entrega',
            'function_js' => 'Delnot.js',
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            $nro_control = 0;
            //Buscar Moneda de la Compañia
            $config_idemp = EmpresasModel::edit($_POST['id_emp']);
            $moneda_cia = $config_idemp['id_moneda'];
             if(isset($_POST['nro_control'])){
                $nro_control = $_POST['nro_control'];
            }
            $id_alm = '';
            if(isset($_POST['id_alm']) && $_POST['id_alm'] > 0){
                $id_alm = $_POST['id_alm'];
            }
            $id_ubi = '';
            if(isset($_POST['id_ubi']) && $_POST['id_ubi'] > 0){
                $id_ubi = $_POST['id_ubi'];
            }
            $con_tdoc = 0;
            if(empty($_POST['id'])){
                $modo = 'create_user';
                $r = (DelnotModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                $con_tdoc = $r[0]['con_tdoc'];
                if($con_tdoc ==1){
                    $tipo_fac = $r[0]['tipo_codigo'];
                    $numero_fac = intval($r[0]['num_tdoc']);
                    $data += ['num_tdo' => $numero_fac];
                }else{
                    $data += ['num_tdo' => $_POST['num_tdo']];
                }
                //Asignar Numero de Control
                $r1 = to_obj(DelnotModel::getnro_control($_POST['id_emp']));
                $nro_control = $r1->next_nroControl;
                //Actualizar el siguiente numero de control
                $r2 = to_obj(DelnotModel::nextnro_control($_POST['id_emp']));
            }else{
                //$data += ['id_cot' => limpiar($_POST['id'])];
            }
            $id_fab ='';
            if(isset($_POST['id_fab'])){
                $id_fab = limpiar($_POST['id_fab']);
            }
            $id_des = 0;
            $id_des_val = false;
            if(isset($_POST['id_des_enca']) && $_POST['id_des_enca'] > 0){
                $id_des = $_POST['id_des_enca'];
                $id_des_reqapp = DelnotModel::show_row_des($id_des);
                if($id_des_reqapp['appreq'] == 1){
                    $id_des_val = true;
                }
            }
            try {
                if(isset($_POST['tasa_cambio'])){
                    $xtasa = str_replace(',', '.', $_POST['tasa_cambio']);
                    $xtasa = number_format(floatval($xtasa), 8);
                }else{
                    $xtasa = 1;
                }
                $mon_doc = str_replace(".", "",$_POST['total_frm']);
                $mon_doc = str_replace(".", ",", $mon_doc);
                $mon_doc = number_format(floatval($mon_doc), 2);
                $id_cont = 'NOL';
                if(isset($_POST['origen']) && $_POST['origen']){
                    $fuente = DelnotModel::origen($_POST['origen']);
                    $id_cont = 'NOL - ' . $fuente['tipo_codigo'].'-'.$fuente['num_tdo'];
                }
                //Cambio 09-05-2005 Solicitado por Nelson Guerra a las 11:30:00
                //Para buscar el Motivo de Cambio del Cliente y guardarlo en el documento para que se muestre en el EDC CXC
                $id_cli = limpiar($_POST['id_cli']);
                $mot_cam = DelnotModel::query("SELECT b.id_motcam, b.adic_01, b.adic_02 FROM `f0014` a INNER JOIN `f0012a` b on b.id_motcam = a.id_motcam WHERE a.id_ent = {$id_cli}");
                if($mot_cam){

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
                    'id_emp' => limpiar($_POST['id_emp']),
                    'id_tdo' => limpiar($_POST['id_tdo']),
                    'id_cli' => $id_cli,
                    'id_fab' => $id_fab,
                    'id_des' => $id_des,
                    'fecha_comp' => limpiar($_POST['fecha_comp']),
                    'fecha_venci' => limpiar($_POST['fecha_venci']),
                    'id_moneda' => limpiar($_POST['id_moneda']),
                    'tasa_cambio' => $xtasa,
                    'id_vend' => limpiar($_POST['id_vend']),
                    'oc_cliente' => limpiar($_POST['oc_cliente']),
                    'descrip_cot' => limpiar($_POST['descrip_cot']),
                    'nro_control' => $nro_control,
                    'status' => '1',
                    'mon_doc' => $mon_doc,
                    'sal_doc' => $mon_doc,
                    'id_cont' =>  $id_cont,
                    
                    $modo => $_SESSION['id_user'],
                ];

                if(empty($_POST['id'])){
                    //Guardar encabezado de factura//
                    $id = DelnotModel::guardar($data);
                    //Actualizar el siguiente numero de factura
                    $id_det_cot = $id;
                    if($con_tdoc ==1){
                        $data1 = array();
                        $data1 = [
                            'num_tdoc' => $numero_fac + 1,
                            $modo => $_SESSION['id_user'],
                        ];
                        //Actualizar el siguiente numero de factura
                        $num = DelnotModel::setNextNumber($data['id_emp'], $data['id_tdo'], $data1);
                    }
                    //Actualizar el Numero de FActura en la cotización utilizada
                    if(isset($_POST['fuente']) && isset($_POST['origen'])){
                        $cont = sprintf("NOL-%s-%s", $tipo_fac, $numero_fac);
                        $data_cont = array();
                        $data_cont =[
                            'id_cont' => $cont,
                            'modify_user' => $_SESSION['id_user'],
                        ];
                        $cotizacion = DelnotModel::set_cotiza($_POST['origen'], $data_cont);
                    }
                    Alertas::new(sprintf('La factura número %s se ha creado exitosamente.', $data['num_tdo']));
                }else{
                    $r = to_obj(DelnotModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                    $tipo_fac = $r[0]->tipo_codigo;
                    $numero_fac = $_POST['num_tdo'];
                    $id = DelnotModel::actualizar($_POST['id'], $data);
                    $id_det = DelnotModel::borrarDetfactura($_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new(sprintf('La factura número %s se ha modificado exitosamente.', $_POST['id']));
                }
                //Guardar detalle
                 //Tasa IVA
                $tasa_iva = 0;
                $xvatTax = xvatTax($_POST['fecha_comp'], 'IVA');
                $tasa_iva = $xvatTax[0]['txr1_iva'];
                //
                $itemTotal = count($_POST['id_prod']) ; 

                for($i=0;$i<$itemTotal;$i++){
                    $id_prod = $_POST['id_prod'][$i];
                    $can_det = $_POST['cant'][$i];
                    $uni_vta = $_POST['uni_ven_prod'][$i];
                    $pre_unit = str_replace(".","",$_POST['ventas_prod'][$i]);
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
                    $costo = $val_cod_prod[0]['costo'];
                    $val_cod_prod[0]['utilidad'];
                    if($_POST['handling_conver'] == 1){
                        $costo = $val_cod_prod[0]['costo'] / $val_cod_prod[0]['uni_ven_prod'];
                        $utilidad = $val_cod_prod[0]['utilidad'] / $val_cod_prod[0]['uni_ven_prod'];
                        $xuni_venta = $val_cod_prod[0]['uni_ven_prod'];
                    }
                    $costo = $costo * $can_det;
                    $utilidad = $utilidad * $can_det;
                    $dif_cambio = 0;
                    if($_POST['id_moneda'] == $moneda_cia){
                        $tasa_fact = str_replace(',', '.', CambiosModel::rateExchange(2 ,$_POST['fecha_comp']));
                    }
                    $adicional = (floatval($sub_total) / floatval($tasa_fact)) - floatval($costo) - floatval($utilidad);
                    if($_POST['id_moneda'] == $moneda_cia){
                        if($val_adic[0]['adic_01'] != 0){
                            $val_adic_01 = $val_adic[0]['adic_01'];
                            $xadi01 =($val_cod_prod[0]['ventas_prod'] / $xuni_venta);    
                            $xadi02 = $xadi01 / $val_adic_01;
                            $adicional = ($xadi02 - $xadi01) * $can_det;
                            $tot_fact = $xadi02 * $can_det;
                        }
                        $tasa_pro = $sub_total / $tot_fact;
                        //
                        $validar = $sub_total - ((($costo/$can_det) + $utilidad + $adicional) * $tasa_pro) ;
                        $dif_cambio = $validar / $tasa_pro ;
                    }
                    $mon_iva = 0.00;
                    $id_des = 0;
                    $data2 = array();
                    if(isset($_POST['id_des'][$i]) && $id_des_val == false && $_POST['id_des'][$i] != 0){
                        $id_des = $_POST['id_des'][$i];
                        if($id_des){
                            $id_des_reqapp = DelnotModel::show_row_des($id_des);
                            if($id_des_reqapp['appreq'] == 1){
                                $id_des_val = true;
                            }
                            $data2 += [
                                'id_des' => $id_des,
                            ];
                        }
                    }
                    if($iva_prod == "S"){
                        $mon_iva = floatval($sub_total) * floatval($tasa_iva/100);
                    }
                     $tota_prod = floatval($sub_total) + floatval($mon_iva);
                    $data2 += [
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
                    $id_det = DelnotModel::guardarDetfactura($data2);
                }
                //Crear la aprobación en caso de que haya descuento
                if($id_des_val){
                    $data_app = array();
                    $data_app = [
                        'id_cot' => $id,
                        'tipo_fgenmsgcol' => 1,
                        'title' => 'Aprobación aplicación de descuentos',
                        'message' => 'Se solicita aprobación de descuentos',
                        'status' => 1,
                        'create_user' => $_SESSION['id_user'],
                    ];
                    $r = DelnotModel::aprobacion($data_app);
                }
                //Guardar Movimiento de Inventario Encabezado
                $tconfig_fac = DelnotModel::tip_doc_fac($_POST['id_emp']);
                $tmov_fac = $tconfig_fac['tmov_fac'];
                $id_alm = $tconfig_fac['id_alm'];
                $id_ubi = $tconfig_fac['id_ubi'];
                if(isset($_POST['id_alm_sal']) && $_POST['id_alm_sal'] != ''){
                    $id_alm = $_POST['id_alm_sal'];
                }
                if(isset($_POST['id_ubi_sal']) && $_POST['id_ubi_sal'] != ''){
                    $id_ubi = $_POST['id_ubi_sal'];
                }

                //Proximo número de Movimiento
                $origen = '';
                 if(empty($_POST['id'])){
                    $r = DelnotModel::getNextNumer($tmov_fac);
                    $num_movinv = intval($r['proximo_tmoinv']);
                    $origen = 'NOL-'.$tipo_fac.'-'.$_POST['id_emp'].'-'.$numero_fac;
                }else{
                    $origen = 'NOL-'.$tipo_fac.'-'.$_POST['id_emp'].'-'.$_POST['num_tdo'];
                    $mov_inv = DelnotModel::getNumberMovim($origen, $tmov_fac);
                    $id_mov_inv = $mov_inv['id_movinv'];
                    $num_movinv = $mov_inv['num_movinv'];
                }
                $data_mov_fac = array();
                $data_mov_fac = [
                    'id_emp' => limpiar($_POST['id_emp']),
                    'id_tmovinv' => $tmov_fac,
                    'num_movinv' => $num_movinv,
                    'fecha_comp' => ($_POST['fecha_comp']),
                    'id_moneda' => limpiar($_POST['id_moneda']),
                    'id_cli' => $_POST['id_cli'],
                    'tasa_cambio' => $xtasa,
                    'descrip_movinv' => 'Movimiento automático generado desde Nota de Entrega ' . $origen,
                    'origen' => 'NOL-'.$tipo_fac.'-'.$_POST['id_emp'].'-'.$numero_fac,
                    'status' => 1,
                    $modo => $_SESSION['id_user']
                ];
                if(empty($_POST['id'])){
                    $data_mov_fac += ['id_alm' => $id_alm];
                     $id_mov_inv = DelnotModel::guardar_mov_inv($data_mov_fac);
                    //Actualizar numero siguiente
                    $id_num_movin = DelnotModel::setNextNumber_Mov_Inv($tmov_fac);
                }else{
                    $id_num_movin = DelnotModel::actualizar_mov_inv($id_mov_inv, $data_mov_fac);
                    $id = DelnotModel::borrarDetInvMov($id_mov_inv);
                }
                //Guardar Movimiento de Inventario Detalle
                $num_rows = count($_POST['id_prod']);
                $data_det_fac = array();
                $new_data_det_fac = array();
                for($i=0; $i < $num_rows ; $i++){
                    $id_prod = $_POST['id_prod'][$i];
                    $can_det = $_POST['cant'][$i];
                    $pre_vta = str_replace(".", "", $_POST['ventas_prod1'][$i]);
                    $pre_vta = str_replace(",", ".", $pre_vta);
                    $xid_ubi = $id_ubi;
                    $fec_venc = '0000-00-00';
                    if($id_alm){
                        $stock = DelnotModel::consulta_prod_ent_alm($id_prod, $id_alm, $_POST['id_cli']);
                    }else{
                        $stock = DelnotModel::consulta_prod($id_prod);
                    }
                    //$total_rows_stock = count($stock);
                     $total_rows_stock = is_array($stock) ? count($stock) : 0;

                    $xcan_det = $can_det;

                    if($total_rows_stock == 0){
                        $data_det_fac = [
                            'id_movinv' => $id_mov_inv,
                            'id_prod' => $id_prod,
                            'id_ubi' => $xid_ubi,
                            'cantidad' => $xcan_det,
                            'lote' => 'SL',
                            'fec_venc' => $fec_venc,
                            'costo' => $pre_vta,
                            'costo1' => $pre_vta,
                            'create_user' => $_SESSION['id_user']
                        ];
                        $id_det = DelnotModel::guardar_Det_Movin($data_det_fac);
                        $new_data_det_fac[] = $data_det_fac;
                    }

                    for($xi=0; $xi < $total_rows_stock; $xi++){
                        $dis_stock = $stock[$xi]['stock'];
                        $disp_lote = $stock[$xi]['lote'];
                        $dis_fec_ven = $stock[$xi]['fec_ven'];
                        $xid_ubi = $stock[$xi]['id_ubi'];
                        if($dis_stock >= $xcan_det){
                            $data_det_fac = [
                                'id_movinv' => $id_mov_inv,
                                'id_prod' => $id_prod,
                                'id_ubi' => $xid_ubi,
                                'cantidad' => $xcan_det,
                                'lote' => $disp_lote,
                                'fec_venc' => $dis_fec_ven,
                                'costo' => $pre_vta,
                                'costo1' => $pre_vta,
                                'create_user' => $_SESSION['id_user']
                            ];
                            $xcan_det -= $xcan_det;
                            $id_det = DelnotModel::guardar_Det_Movin($data_det_fac);
                            $new_data_det_fac[] = $data_det_fac;
                        }elseif($dis_stock <= $xcan_det) {
                            $data_det_fac = [
                                'id_movinv' => $id_mov_inv,
                                'id_prod' => $id_prod,
                                'id_ubi' => $xid_ubi,
                                'cantidad' => $dis_stock,
                                'lote' => $disp_lote,
                                'fec_venc' => $dis_fec_ven,
                                'costo' => $pre_vta,
                                'costo1' => $pre_vta,
                                'create_user' => $_SESSION['id_user']
                            ];
                            $xcan_det = ($xcan_det - $dis_stock);
                            $id_det = DelnotModel::guardar_Det_Movin($data_det_fac);
                            $new_data_det_fac[] = $data_det_fac;
                        }
                        if($xcan_det == 0){
                            break;
                        }
                    }
                }
               
                //Validar si es un cliente en consignacion                
                $id_ent = DelnotModel::consulta_vend($_POST['id_cli']);
                $tmov_fac = $tconfig_fac['tmov_noc'];
                $id_alm = $id_ent['id_alm'];
                $id_ubi = $id_ent['id_ubi'];
                if(isset($_POST['id_alm_ent']) && $_POST['id_alm_ent'] != ''){
                    $id_alm = $_POST['id_alm_ent'];
                }
                if(isset($_POST['id_ubi_ent']) && $_POST['id_ubi_ent'] != ''){
                    $id_ubi = $_POST['id_ubi_ent'];
                }
                 if(isset($id_ent) && $id_ent['c_consig'] == 1 && $id_ent['id_alm']){
                    if(empty($_POST['id'])){
                        $r = DelnotModel::getNextNumer($tmov_fac);
                        $num_movinv = intval($r['proximo_tmoinv']);
                        $origen = 'NOL-'.$tipo_fac.'-'.$_POST['id_emp'].'-'.$numero_fac;
                    }else{
                        $origen = 'NOL-'.$tipo_fac.'-'.$_POST['id_emp'].'-'.$_POST['num_tdo'];
                        $mov_inv = DelnotModel::getNumberMovim($origen, $tmov_fac);
                        $id_mov_inv = $mov_inv['id_movinv'];
                        $num_movinv = $mov_inv['num_movinv'];
                    }
                    $data_mov_fac = array();
                    $data_mov_fac = [
                        'id_emp' => limpiar($_POST['id_emp']),
                        'id_tmovinv' => $tmov_fac,
                        'num_movinv' => $num_movinv,
                        'fecha_comp' => ($_POST['fecha_comp']),
                        'id_moneda' => limpiar($_POST['id_moneda']),
                        'id_cli' => $_POST['id_cli'],
                        'tasa_cambio' => $xtasa,
                        'id_cli' => $_POST['id_cli'],
                        'descrip_movinv' => 'Movimiento automático generado desde Nota de Entrega ' . $origen,
                        'origen' => 'NOL-'.$tipo_fac.'-'.$_POST['id_emp'].'-'.$numero_fac,
                        'status' => 1,
                        $modo => $_SESSION['id_user']
                    ];
                     if(empty($_POST['id'])){
                        $data_mov_fac += ['id_alm' => $id_alm];
                        $id_mov_inv = DelnotModel::guardar_mov_inv($data_mov_fac);
                        //Actualizar numero siguiente
                        $id_num_movin = DelnotModel::setNextNumber_Mov_Inv($tmov_fac);
                    }else{
                        $id_num_movin = DelnotModel::actualizar_mov_inv($id_mov_inv, $data_mov_fac);
                        $id = DelnotModel::borrarDetInvMov($id_mov_inv);
                    }
                    //Guardar Movimiento de Inventario Detalle
                    $num_rows = count($new_data_det_fac);
                    $data_det_fac = array();
                    for($i=0; $i < $num_rows ; $i++){
                        $id_prod = $new_data_det_fac[$i]['id_prod'];
                        $cod_prod = ProductosModel::query("SELECT * FROM f4005 WHERE id_prod = {$id_prod}");
                        $id_ubi = $id_ubi;
                        //$cantidad = $new_data_det_fac[$i]['cantidad'] * $cod_prod[0]['uni_ven_prod'];
                        $cantidad = $new_data_det_fac[$i]['cantidad'];
                        $lote = $new_data_det_fac[$i]['lote'];
                        $fec_venc = $new_data_det_fac[$i]['fec_venc'];
                        $costo = $new_data_det_fac[$i]['costo'];
                        $costo1 = $new_data_det_fac[$i]['costo1'];
                        $fec_venc = '0000-00-00';
                        $data_det_fac = [
                            'id_movinv' => $id_mov_inv,
                            'id_prod' => $id_prod,
                            'id_ubi' => $id_ubi,
                            'cantidad' => $cantidad,
                            'lote' => $lote,
                            'fec_venc' => $fec_venc,
                            'costo' => $costo,
                            'costo1' => $costo1,
                            'create_user' => $_SESSION['id_user']
                        ];
                        $id_det = DelnotModel::guardar_Det_Movin($data_det_fac);
                    }
                }   
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                
            }finally{
                header('Location:'. base_url . '/Delnot');
            }
        }
    }
    public function edit($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = DelnotModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Delnot');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la Nota de Entrega Nro. " . $r['num_tdo'],
                    'function_js' => "Delnot.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Delnot');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Delnot');
    }
    public function consultar_factura(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){            
            $id = $_POST['id_cot'];
            $tipo = $_POST['tipo'];
            $r = DelnotModel::edit_deta($id, $tipo);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
            
        }
    }
    public function consulta_adic01(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $fecha_precio = $_POST['fecha_precio'];
            $objeto = DelnotModel::consulta_adic01($id_emp, $fecha_precio);
            echo json_encode($objeto);
        }
    }
    public function consulta_adic02(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_cli = $_POST['id_cli'];
            $objeto = DelnotModel::consulta_adic02($id_cli);
            echo json_encode($objeto);
        }
    }
    public function print_factura($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
                //$r = Facturacion::print_factura($id);
                $r = DelnotModel::print_factura($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Delnot');
                }
                $this->views->getView($this, "print_factura", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Delnot');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Delnot');
    }
    public function destroy(){
        $dataJson = [];
        $id = intval(limpiar($_POST['id']));
        $r = DelnotModel::selectEncyDetmovinv($id);
        foreach ($r as $key ) {
            $id_movinv = $key['id_movinv'];
            $r1 = DelnotModel::borrarEncyDetmovinv($id_movinv);
        }
        $ide = DelnotModel::borrar($id);
        if($ide) {
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['id'], $_POST['id'])
            ];
        }else{
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['cod_alm'], $_POST['noidm_alm'])
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function create_express(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_fab = $_POST['id_fab'];
            $objeto = DelnotModel::create_express($id_fab);
            echo json_encode($objeto);
        }
    }
    public function listar_factura(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $r = DelnotModel::listar_Factura($id_emp);
            echo json_encode($r);
        }
    }
    public function tip_doc_fac(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id_emp'];
            $r = DelnotModel::tip_doc_fac($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function aprobacion(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = [
                'tipo_fgenmsgcol' => 1,
                'title' => $_POST['title'],
                'message' => $_POST['message'],
                'status' => 1,
                'create_user' => $_SESSION['id_user'],
            ];
            $r = DelnotModel::aprobacion($data);
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => 'Registro en espera de aprobación'
            ];
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
}