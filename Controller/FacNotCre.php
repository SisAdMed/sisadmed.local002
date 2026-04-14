<?php
  include_once VARTAX;
class FacNotCre extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(72);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = FacNotCreModel::all('C');
        $this->views->getView($this, 'index', [
            'page_name' => 'Listado Notas de Crédito',
            'function_js' => 'FacNotCre.js',
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva Nota de Crédito',
            'function_js' => 'FacNotCre.js',
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $modo = 'modify_user';
            $data = array();
            $nro_control = 0;
            $con_tdoc = 0;
            $numero_fac = $_POST['num_tdo'];
            $usa_con_tdo = 0;
            if(isset($_POST['nro_control'])){
                $nro_control = $_POST['nro_control'];
            }
            if(empty($_POST['id'])){
                $modo = 'create_user';
                $r = to_obj(FacNotCreModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                $con_tdoc = $r->con_tdoc;
                if($con_tdoc==1){
                    $usa_con_tdo = 1;
                    $tipo_fac = $r->tipo_codigo;
                    $numero_fac = intval($r->num_tdoc);
                }
                $data += ['num_tdo' => $numero_fac];
                //Asignar Numero de Control
                $r = to_obj(FacNotCreModel::getnro_control($_POST['id_emp']));
                $nro_control = $r->next_nroControl;
            }else{
                $data += ['id_cot' => limpiar($_POST['id'])];
            }
            $id_fab ='';
            if(isset($_POST['id_fab'])){
                $id_fab = limpiar($_POST['id_fab']);
            }
            $id_des = 0;
            $id_des_val = false;
            if(isset($_POST['id_des_enca']) && $_POST['id_des_enca']){
                $id_des = $_POST['id_des_enca'];
                $id_des_reqapp = FacNotCreModel::show_row_des($id_des);
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
                if($xtasa==1){
                    $mon_doc =  str_replace('.', '',$_POST['total_frml']);
                }else{
                    $mon_doc =  str_replace('.', '',$_POST['total_frm']);
                }
                $mon_doc = str_replace(",", ".", $mon_doc);
                $mon_doc = $mon_doc;
                $id_cont = 'FAC';
                if(isset($_POST['origen']) && $_POST['origen']){
                    $fuente = FacNotCreModel::origen($_POST['origen']);
                    $id_cont = $id_cont . ' - ' . $fuente['tipo_codigo'].'-'.$fuente['num_tdo'];
                }
                $data += [
                    'id_emp' => limpiar($_POST['id_emp']),
                    'id_tdo' => limpiar($_POST['id_tdo']),
                    'id_cli' => limpiar($_POST['id_cli']),
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
                    'mon_doc' => $mon_doc *-1,
                    'sal_doc' => $mon_doc *-1,
                    'id_cont' =>  $id_cont,
                    'id_des' => $id_des,
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($_POST['id'])){
                    //Guardar encabezado de factura//
                    $id = FacNotCreModel::guardar($data);
                    $id_det_cot = $id;
                    if($id){
                        $data1 = array();
                        $data1 = [
                            'num_tdoc' => $numero_fac + 1,
                            $modo => $_SESSION['id_user'],
                        ];
                        //Actualizar el siguiente numero de factura
                        $num = FacNotCreModel::setNextNumber($data['id_emp'], $data['id_tdo'], $data1);
                        //Actualizar el Numero de FActura en la cotización utilizada
                        if(isset($_POST['fuente']) && isset($_POST['origen'])){
                            $cont = sprintf("FAC-%s-%s", $tipo_fac, $numero_fac);
                            $data_cont = array();
                            $data_cont =[
                                'id_cont' => $cont,
                                'modify_user' => $_SESSION['id_user'],
                            ];
                            $cotizacion = FacNotCreModel::set_cotiza($_POST['origen'], $data_cont);
                        }
                    }
                    Alertas::new(sprintf('La factura número %s se ha creado exitosamente.', $data['num_tdo']));
                }else{
                     $r = to_obj(FacNotCreModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                    $tipo_fac = $r->tipo_codigo;
                    $numero_fac = $_POST['num_tdoc'];
                    $id = FacNotCreModel::actualizar($_POST['id'], $data);
                    $id_det = FacNotCreModel::borrarDetfactura($_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new(sprintf('La factura número %s se ha modificado exitosamente.', $_POST['id']));
                }
                //Guardar detalle
                //Tas IVA
                $tasa_iva = 0;
                $xvatTax = xvatTax($_POST['fecha_comp'], 'IVA');
                $tasa_iva = $xvatTax[0]['txr1_iva'];
                $itemTotal = count($_POST['id_prod']) ;
                echo '<br>$itemTotal: ' . $itemTotal;
                $data2 = array();
                for($i=0;$i<$itemTotal;$i++){
                    echo '<br>$i: ' . $i;
                    $id_prod = $_POST['id_prod'][$i];
                    $can_det = $_POST['cant'][$i];
                    $uni_vta = $_POST['uni_ven_prod'][$i];
                    $pre_unit =  str_replace('.', '',$_POST['ventas_prod'][$i]);
                    $pre_unit =  str_replace(',', '.',$pre_unit);
                    $pre_vta =  str_replace('.', '',$_POST['ventas_prod1'][$i]);
                    $pre_vta =  str_replace(',', '.',$pre_vta);
                    $iva_prod = $_POST['iva_prod'][$i];
                    $sub_total =  str_replace('.', '',$_POST['total'][$i]);
                    $sub_total =  str_replace(',', '.',$sub_total);
                    $mon_iva = 0.00;
                    if(isset($_POST['id_des'][$i]) && $id_des_val == false){
                        $id_des = $_POST['id_des'][$i];
                        if($id_des){
                            $id_des_reqapp = FacNotCreModel::show_row_des($id_des);
                            if($id_des_reqapp['appreq'] == 1){
                                $id_des_val = true;
                            }
                        }
                    }
                    if($iva_prod == "S"){
                        $mon_iva = floatval($sub_total) * floatval($tasa_iva/100);
                    }
                    $tota_prod = floatval($sub_total) + floatval($mon_iva);
                    $data2 = [
                        'id_cot' => $id,
                        'id_prod' => $id_prod,
                        'can_det' => $can_det,
                        'uni_vta' => $uni_vta,
                        'pre_unit' => $pre_unit *-1,
                        'pre_vta' => $pre_vta *-1,
                        'iva_prod' => $iva_prod,
                        'sub_total' => $sub_total *-1,
                        'id_des' => $id_des,
                        'mon_iva' => $mon_iva *-1,
                        'tota_prod' => $tota_prod *-1,
                        $modo => $_SESSION['id_user'],
                    ];
                    $id_det = FacNotCreModel::guardarDetfactura($data2);
                }
                //Guardar  de CXC
                //Detalle de Ventas
                //Concepto de Ventas
                $id_det = FacNotCreModel::borrarDetCXCDocument($id);
                $con_ventas = FacNotCreModel::tip_doc_fac($_POST['id_emp']);;

                $det_venta = (FacNotCreModel::detalle_venta($id));
                $itemTotal = count($det_venta);
                $data2 = array();
                for ($i=0; $i<$itemTotal; $i++) {
                    $xmonto = $det_venta[$i]['monto'];
                    $mon_iva = $det_venta[$i]['mon_iva'];
                    $id_concxc = $con_ventas['id_con_sales'];
                    $id_aux = $con_ventas['id_ctbaux'];
                    $iva = $det_venta[$i]['iva_prod'];
                    $data2 = [
                        'id_cot' => $id,
                        'id_concxc' => $id_concxc,
                        'iva' => $iva,
                        'id_aux' => $id_aux ?? '',
                        'monto' => floatval($xmonto),
                        'mon_iva' => floatval($mon_iva),
                        $modo => $_SESSION['id_user']
                    ];
                    $id_det = FacNotCreModel::guardarDetfactura_CXC($data2);
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
                    $r = FacNotCreModel::aprobacion($data_app);
                }
                //Guardar Movimiento de Inventario Encabezado
                $tconfig_fac = FacNotCreModel::tip_doc_fac($_POST['id_emp']);
                $tmov_noc = $tconfig_fac['tmov_noc'];
                $id_alm = $tconfig_fac['id_alm'];
                //Proximo número de Movimiento
                $r = FacNotCreModel::getNextNumer($tmov_noc);
                $num_movinv = intval($r['proximo_tmoinv']);
                $data_mov_fac = array();
                $data_mov_fac = [
                    'id_emp' => limpiar($_POST['id_emp']),
                    'id_tmovinv' => $tmov_noc,
                    'num_movinv' => $num_movinv,
                    'fecha_comp' => ($_POST['fecha_comp']),
                    'id_moneda' => limpiar($_POST['id_moneda']),
                    'tasa_cambio' => $xtasa,
                    'id_alm' => $id_alm,
                    'descrip_movinv' => 'Movimiento automático generado desde Facturación',
                    'origen' => 'FAC-'.$tipo_fac.'-'.$_POST['id_emp'].'-'.$numero_fac,
                    'status' => 1,
                    $modo => $_SESSION['id_user']
                ];
                $id_mov_inv = FacNotCreModel::guardar_mov_inv($data_mov_fac);
                //Actualizar numero siguiente
                $id_num_movin = FacNotCreModel::setNextNumber_Mov_Inv($tmov_fac);
                //Guardar Movimiento de Inventario Detalle
                $id = FacNotCreModel::borrarDetInvMov($id_num_movin);
                $num_rows = count($_POST['id_prod']);
                $data_det_fac = array();
                for($i=0; $i < $num_rows ; $i++){
                    $id_prod = $_POST['id_prod'][$i];
                    $can_det = $_POST['cant'][$i];
                    $pre_vta = str_replace(".","",$_POST['ventas_prod1'][$i]);
                    $pre_vta = str_replace(",",".",$pre_vta);

                    $stock = FacNotCreModel::consulta_prod($id_prod);

                    $total_rows_stock = is_array($stock) ? count($stock) : 0;

                    $xcan_det = $can_det;

                    if($total_rows_stock == 0){
                         $data_det_fac = [
                            'id_movinv' => $id_mov_inv,
                            'id_prod' => $id_prod,
                            'id_ubi' => $id_ubi,
                            'cantidad' => $xcan_det,
                            'lote' => '',
                            'fec_venc' => '',
                            'costo' => $pre_vta,
                            'costo1' => $pre_vta,
                            'create_user' => $_SESSION['id_user']
                        ];
                        $id_det = FacNotCreModel::guardar_Det_Movin($data_det_fac);
                    }

                    for($xi=0; $xi < $total_rows_stock;$xi++){
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
                            $id_det = FacNotCreModel::guardar_Det_Movin($data_det_fac);
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
                            $id_det = FacNotCreModel::guardar_Det_Movin($data_det_fac);
                        }
                        if($xcan_det == 0){
                            break;
                        }
                    }
                }
                //Actualizar el siguiente numero de control
                $r = to_obj(FacNotCreModel::nextnro_control($_POST['id_emp']));
                header('Location:'. base_url . '/FacNotCre');
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:'. base_url . '/FacNotCre');
            }
        }
    }
    public function edit($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = FacNotCreModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/FacNotCre');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la factura Nro. " . $r['num_tdo'],
                    'function_js' => "FacNotCre.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/FacNotCre');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/FacNotCre');
    }
    public function consultar_factura(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id_cot'];
            if($id >0){
                $r = FacNotCreModel::edit_deta($id);
                echo json_encode($r, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    public function consulta_adic01(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $fecha_precio = $_POST['fecha_precio'];
            $objeto = FacNotCreModel::consulta_adic01($id_emp, $fecha_precio);
            echo json_encode($objeto);
        }
    }
    public function consulta_adic02(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_cli = $_POST['id_cli'];
            $objeto = FacNotCreModel::consulta_adic02($id_cli);
            echo json_encode($objeto);
        }
    }
    public function print_factura($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = FacNotCreModel::print_factura($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/FacNotCre');
                }
                $this->views->getView($this, "print_factura", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/FacNotCre');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/FacNotCre');
    }
    public function destroy(){
        $dataJson = [];
        $id = intval(limpiar($_POST['id']));
        $ide = FacNotCreModel::borrar($id);
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
            $objeto = FacNotCreModel::create_express($id_fab);
            echo json_encode($objeto);
        }
    }
    public function listar_fac_facturas(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $r = FacNotCreModel::listar_fac_facturas($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function tip_doc_fac(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id_emp'];
            $r = FacNotCreModel::tip_doc_fac($id);
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
            $r = FacNotCreModel::aprobacion($data);
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => 'Registro en espera de aprobación'
            ];
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
}