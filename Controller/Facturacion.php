<?php
include_once VARTAX;
include PRODUCTOMODEL;
include EMPRESAMODEL;
include CAMBIOMODEL;
include $_SERVER['DOCUMENT_ROOT'] . '/Models/TipoDocCXCModel.php';
$page = '';
if (isset($_GET['tipo'])) {
    $ori = htmlspecialchars($_GET['tipo']);
    if (isset($_SESSION['tipo_fact'])) {
        unset($_SESSION['tipo_fact']);
        $_SESSION['tipo_fact'] = $ori;
    }
    $xori = '?ori=' . $ori;
    if ($_SESSION['tipo_fact'] == 'B') {
        $page = 'Notas de Débito';
    } else if ($_SESSION['tipo_fact'] == 'C') {
        $page = 'Notas de Crédito';
    } else if ($_SESSION['tipo_fact'] == 'D') {
        $page = 'Notas de Devolución';
    } else if ($_SESSION['tipo_fact'] == 'F') {
        $page = 'Facturación';
    } else if ($_SESSION['tipo_fact'] == 'N') {
        $page = 'Nota de Entrega';
    } else if ($_SESSION['tipo_fact'] == 'Z') {
        $page = 'Nota de Entrega No Fiscal';
    }
    $_SESSION['page_name'] = $page;
}
class Facturacion extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(72);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = FacturacionModel::all($_SESSION['tipo_fact']);
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de ' . $_SESSION['page_name'],
            'function_js' => 'Facturacion.js?v=' . SITE_VERSION,
            'function_js_mod' => 'FACFun.js?v=' . SITE_VERSION,
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva '  . $_SESSION['page_name'],
            'function_js' => 'Facturacion.js?v=' . SITE_VERSION,
            'function_js_mod' => 'FACFun.js?v=' . SITE_VERSION,
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = FacturacionModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Facturacion');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la factura Nro. " . $r['num_tdo'],
                    'function_js' => 'Facturacion.js?v=' . SITE_VERSION,
                    'function_js_mod' => 'FACFun.js?v=' . SITE_VERSION,
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Facturacion');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Facturacion');
    }
    public function consultar_factura()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_cot'];
            if ($id > 0) {
                $r = FacturacionModel::edit_deta($id);
                echo json_encode($r, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    public function consulta_adic01()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $fecha_precio = $_POST['fecha_precio'];
            $objeto = FacturacionModel::consulta_adic01($id_emp, $fecha_precio);
            echo json_encode($objeto);
        }
    }
    public function consulta_adic02()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cli = $_POST['id_cli'];
            $objeto = FacturacionModel::consulta_adic02($id_cli);
            echo json_encode($objeto);
        }
    }
    public function print_factura($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = FacturacionModel::print_factura($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Facturacion');
                }
                $this->views->getView($this, "print_factura", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Facturacion');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Facturacion');
    }
    public function print_factura_metro($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = FacturacionModel::print_factura($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Facturacion');
                }
                $this->views->getView($this, "print_factura_metro", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Facturacion');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Facturacion');
    }
    public function destroy()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $dataJson = [];
            $id = $_POST["id"];
            $r = FacturacionModel::selectEncyDetmovinv($id);
            $data = [];
            foreach ($r as $key) {
                $data[] = [
                    'id_movinv' => $key['id_movinv']
                ];
            }
            try {
                $r = FacturacionModel::borrarEncyDetmovinv($data, $id);
                $dataJson = [
                    'title' => "Registro Eliminado",
                    'icon' => 'success',
                    'msg' => 'El registro se ha eliminado satisfactoriamente'
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
    public function create_express()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_fab = $_POST['id_fab'];
            $objeto = FacturacionModel::create_express($id_fab);
            echo json_encode($objeto);
        }
    }
    public function listar_factura()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $r = FacturacionModel::listar_Factura($id_emp);
            echo json_encode($r);
        }
    }
    public function tip_doc_fac()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_emp'];
            $r = FacturacionModel::tip_doc_fac($id);
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
            $r = FacturacionModel::aprobacion($data);
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => 'Registro en espera de aprobación'
            ];
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function update_tasa()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array();
            $id_cot = $_POST['id_cot'];
            $tasa_cambio = $_POST['tasa_cambio'];
            $data = ['tasa_cambio' => $tasa_cambio];
            $r = FacturacionModel::actualizar($id_cot, $data);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function consultar_factura_nc()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_cot'];
            $r = FacturacionModel::edit_deta($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function readpdf()
    {
        $this->views->getView($this, 'readpdf', [
            'page_name' => 'Nueva '  . $_SESSION['page_name'],
            'function_js' => 'Facturacion.js',
        ]);
    }
    public function equivale()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $id_ent = $_POST['id_ent'];
            $id_prod = $_POST['id_prod'];
            $format = $_POST['format'];
            $id_ubi = $_POST['id_ubi'];
            $id_alm = $_POST['id_alm'];
            $fecha_comp = $_POST['fecha'];
            $r = FacturacionModel::equivale($id_emp, $id_ent, $id_prod, $format, $id_ubi, $id_alm, $fecha_comp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function ventas_unidades()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(176);
        if (Permisos::read()) {
            $this->views->getView($this, 'ventas_unidades', [
                'page_name' => 'Ventas por Unidades',
                'function_js' => 'Ventas_unidades.js'
            ]);
        }
    }

    /**Nuevos Cambios */
    public function cargar_screen_main()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos_tabla = [];
            $ori = $_SESSION['tipo_fact'];
            $r = FacturacionModel::all($ori);
            //Crear tokens
            foreach ($r as $p) {
                $datos_tabla[] = array_merge($p, [
                    "token_edit" => encriptar_url(json_encode(['accion' => 'edit', 'id' => $p['id']]))
                ]);
            }
            echo json_encode($datos_tabla, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $todosLosItems = [];
            $totalItems = count($_POST['id_prod']);
            if ($_POST['id_emp'] == 1) {
                $limitePorFactura = 40;
            } else {
                $limitePorFactura = 30;
            }
            if ($_POST['id_cli'] == 11) {
                $limitePorFactura = 30;
            }
            for ($i = 0; $i < $totalItems; $i++) {
                $todosLosItems[] = [
                    "id_cli" => $_POST["id_cli"],
                    "id_ubi" => $_POST["id_ubi"],
                    "id_prod" => $_POST["id_prod"][$i],
                    "cant" => $_POST["cant"][$i],
                    "uni_ven_prod" => $_POST["uni_ven_prod"][$i],
                    "ventas_prod" => $_POST["ventas_prod"][$i],
                    "ventas_prod1" => $_POST["ventas_prod1"][$i],
                    "iva_prod" => $_POST["iva_prod"][$i],
                    "total" => $_POST["total"][$i],
                ];
            }
            $facturasDivididas = array_chunk($todosLosItems, $limitePorFactura);

            try {
                foreach ($facturasDivididas as $indice => $itemsFactura) {
                    $conteoItems = count($itemsFactura); // 30, 30, 6
                    $tip_doc_fuente = '';
                    $id_cli = $_POST['id_cli'];
                    //Buscar personalizaciones
                    $id_ent = FacturacionModel::consulta_vend($_POST['id_cli']);
                    if (isset($_POST['fuente']) && $_POST['fuente'] > 0) {
                        $r = TipoDocCXCModel::name_tip_doc($_POST['fuente']);
                        $tip_doc_fuente = $r['tipo_tdoc'];
                    }
                    $modo = 'modify_user';
                    $data = array();
                    $nro_control = 0;
                    //Buscar Moneda de la Compañia
                    $config_idemp = EmpresasModel::edit($_POST['id_emp']);
                    $moneda_cia = $config_idemp['id_moneda'];
                    if (isset($_POST['nro_control'])) {
                        $nro_control = $_POST['nro_control'];
                    }
                    $r = to_obj(FacturacionModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                    $tipo_fac = $r->tipo_codigo;
                    $origen = '';
                    if (empty($_POST['id'])) {
                        $modo = 'create_user';
                        $r = to_obj(FacturacionModel::nextNumber($_POST['id_emp'], $_POST['id_tdo']));
                        if ($r->con_tdoc == 1) {
                            $tipo_fac = $r->tipo_codigo;
                            $numero_fac = intval($r->num_tdoc);
                            $data += ['num_tdo' => $numero_fac];
                        } else {
                            $data += ['num_tdo' => $_POST['num_tdo']];
                        }
                        //Asignar Numero de Control
                        $r = to_obj(FacturacionModel::getnro_control($_POST['id_emp']));
                        $nro_control = $r->next_nroControl;
                        $r = to_obj(FacturacionModel::nextnro_control($_POST['id_emp']));
                    } else {
                        //$data += ['id_cot' => limpiar($_POST['id'])];
                    }
                    $id_fab = '';
                    if (isset($_POST['id_fab'])) {
                        $id_fab = limpiar($_POST['id_fab']);
                    }
                    $id_des = 0;
                    $id_des_val = false;
                    if (isset($_POST['id_des_enca']) && $_POST['id_des_enca']) {
                        $id_des = $_POST['id_des_enca'];
                        $id_des_reqapp = FacturacionModel::show_row_des($id_des);
                        if ($id_des_reqapp['appreq'] == 1) {
                            $id_des_val = true;
                        }
                    }
                    if (isset($_POST['tasa_cambio'])) {
                        $xtasa = str_replace(',', '.', $_POST['tasa_cambio']);
                        $xtasa = number_format(floatval($xtasa), 8);
                    } else {
                        $xtasa = 1;
                    }
                    if ($xtasa == 1) {
                        $mon_doc =  str_replace('.', '', $_POST['total_frml']);
                    } else {
                        $mon_doc =  str_replace('.', '', $_POST['total_frm']);
                    }
                    $mon_doc = str_replace(",", ".", $mon_doc);
                    $mon_doc = $mon_doc;
                    $id_cont = 'FAC';
                    if (isset($_POST['origen']) && $_POST['origen']) {
                        if ($_POST['tipo_fact'] == 'F') {
                            if ($_POST['origen'] == 6) {
                                $fuente = FacturacionModel::origen($_POST['origen'], 'C');
                            } else {
                                $fuente = FacturacionModel::origen($_POST['origen'], 'NOF');
                            }
                        } else {
                            $fuente = FacturacionModel::origen($_POST['origen'], 'NC');
                        }
                        $id_cont = $id_cont . '-' . $fuente['tipo_codigo'] . '-' . $fuente['num_tdo'];
                    }
                    $id_doc_afec = $_POST['id_doc_afec'];
                    //Buscar personalizaciones

                    //Cambio 09-05-2005 Solicitado por Nelson Guerra a las 11:30:00
                    //Para buscar el Motivo de Cambio del Cliente y guardarlo en el documento para que se muestre en el EDC CXC
                    $id_cli = limpiar($_POST['id_cli']);
                    if (isset($_POST['id_motcam']) && $_POST['id_motcam'] > 0) {
                        $id_motcam = $_POST['id_motcam'];
                        $mot_cam = FacturacionModel::query("SELECT * FROM f0012a WHERE id_motcam = {$id_motcam}");
                        if ($mot_cam) {
                            $data += ['id_motcam' => $id_motcam];
                            //
                            $adic_01 = $mot_cam[0]['adic_01'];
                            $adic_02 = $mot_cam[0]['adic_02'];
                            //
                            $data += ['adic_01' => $adic_01];
                            $data += ['adic_02' => $adic_02];
                        }
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

                    if ($id_des_val) {
                        $data += ['id_des' => $id_des];
                    }
                    if ($id_doc_afec) {
                        $data += ['id_doc_afec' => $id_doc_afec];
                    }
                    $id_notify = 0;

                    if (empty($_POST['id'])) {
                        //Guardar encabezado de factura//
                        $id = FacturacionModel::guardar($data);
                        $id_notify = $id;
                        $id_det_cot = $id;
                        if ($id) {
                            $data1 = array();
                            $data1 = [
                                'num_tdoc' => $numero_fac + 1,
                                $modo => $_SESSION['id_user'],
                            ];
                            //Actualizar el siguiente numero de factura
                            $num = FacturacionModel::setNextNumber($data['id_emp'], $data['id_tdo'], $data1);
                            //Actualizar el Numero de FActura en la cotización utilizada
                            if (isset($_POST['fuente']) && isset($_POST['origen'])) {
                                if ($tip_doc_fuente == 'P') {
                                    $cont = sprintf("FAC-%s-%s", $tipo_fac, $numero_fac);
                                    $data_cont = array();
                                    $data_cont = [
                                        'id_cont' => $cont,
                                        'modify_user' => $_SESSION['id_user'],
                                    ];
                                } else {
                                    $cont = sprintf("FAC-%s-%s", $tipo_fac, $numero_fac);
                                    $data_cont = array();
                                    $data_cont = [
                                        'invoice' => $cont,
                                        'modify_user' => $_SESSION['id_user'],
                                    ];
                                    $cotizacion = FacturacionModel::set_cotiza($_POST['origen'], $data_cont, $tip_doc_fuente);
                                }
                            }
                        }
                        Alertas::new(sprintf('La factura número %s se ha creado exitosamente.', $data['num_tdo']));
                    } else {
                        $id = FacturacionModel::actualizar($_POST['id'], $data);
                        $id_notify = $_POST['id'];
                        $id_det = FacturacionModel::borrarDetfactura($_POST['id']);
                        $id = $_POST['id'];
                        $numero_fac = $_POST['num_tdo'];
                        Alertas::new(sprintf('La factura número %s se ha modificado exitosamente.', $_POST['num_tdo']));
                    }
                    //Guardar detalle
                    //Tas IVA
                    $tasa_iva = 0;
                    $dif_cambio = 0;
                    $sub_total_dif_camb = 0;
                    $xvatTax = xvatTax($_POST['fecha_comp'], 'IVA');
                    $tasa_iva = $xvatTax[0]['txr1_iva'];
                    //$itemTotal = count($_POST['id_prod']);
                    $data2 = array();
                    foreach ($itemsFactura as $item) {
                        $id_prod = $item['id_prod'];
                        $can_det = $item['cant'];
                        $uni_vta = $item['uni_ven_prod'];
                        $pre_unit =  str_replace('.', '', $item['ventas_prod']);
                        $pre_unit =  str_replace(',', '.', $pre_unit);
                        $pre_vta =  str_replace('.', '', $item['ventas_prod1']);
                        $pre_vta =  str_replace(',', '.', $pre_vta);
                        $iva_prod = $item['iva_prod'];
                        $sub_total =  str_replace('.', '', $item['total']);
                        $sub_total =  str_replace(',', '.', $sub_total);
                        $pre_unit = $pre_vta / $uni_vta;
                        //Nuevos valores a guardar para el dashboard
                        //Buscar producto para analisis de dashboard
                        $sql = "SELECT costo1 costo, a.ventas_prod - costo1 utilidad, a.ventas_prod, a.uni_ven_prod, c.adic_01, c.adic_02, ((a.ventas_prod / c.adic_01) - a.ventas_prod) adicional FROM f4005 a LEFT OUTER JOIN f0014 b ON b.id_ent = {$_POST['id_cli']} INNER JOIN f0012a c ON c.id_motcam = b.id_motcam WHERE a.id_prod = {$id_prod}";
                        $val_cod_prod = ProductosModel::query($sql);
                        //Inicializar variabels por producto
                        $dif_cambio = 0;
                        $costo = 0;
                        $utilidad = 0;
                        $adicional = 0;
                        $tasa_fact = 1;
                        //asignacion de valores desde la consulta
                        $costo = $val_cod_prod[0]['costo'];
                        $utilidad = $val_cod_prod[0]['utilidad'];
                        $xuni_venta = $val_cod_prod[0]['uni_ven_prod'];
                        $adicional = $val_cod_prod[0]['adicional'];

                        //Validar si es un clietne en consignacion y aplica conversion

                        if ($id_ent['handling_conver'] == 1) {
                            $xuni_venta = 1;
                            $costo = $costo / $val_cod_prod[0]['uni_ven_prod'];
                            $utilidad = $utilidad / $val_cod_prod[0]['uni_ven_prod'];
                            $adicional = $adicional / $val_cod_prod[0]['uni_ven_prod'];
                        }
                        //Valores por la cantidades
                        $costo = $costo * $can_det;
                        $utilidad = $utilidad * $can_det;
                        $adicional = $adicional * $can_det;
                        $dif_cambio = 0;
                        if ($_POST['id_moneda'] == $moneda_cia) {
                            $tasa_fact = str_replace(',', '.', CambiosModel::rateExchange(2, $_POST['fecha_comp']));
                        }
                        $mon_iva = 0.00;
                        if (isset($_POST['id_des'][$i]) && $id_des_val == false) {
                            $id_des = $_POST['id_des'][$i];
                            if ($id_des) {
                                $id_des_reqapp = FacturacionModel::show_row_des($id_des);
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
                            'costo' => $costo,
                            'utilidad' => $utilidad,
                            'adicional' => $adicional,
                            'dif_cambio' => $dif_cambio,
                            $modo => $_SESSION['id_user'],
                        ];
                        if ($id_des_val) {
                            $data2 += ['id_des' => $id_des];
                        }
                        $id_det = FacturacionModel::guardarDetfactura($data2);
                    }

                    //Guardar  de CXC
                    //Detalle de Ventas
                    //Concepto de Ventas
                    $id_det = FacturacionModel::borrarDetCXCDocument($id);
                    $con_ventas = FacturacionModel::tip_doc_fac($_POST['id_emp']);;

                    $det_venta = (FacturacionModel::detalle_venta($id));
                    $itemTotal = count($det_venta);
                    $data2 = array();
                    $mon_doc = 0;
                    for ($i = 0; $i < $itemTotal; $i++) {
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
                            'monto' => $xmonto,
                            'mon_iva' => $mon_iva,
                            'create_user' => $_SESSION['id_user'],
                            $modo => $_SESSION['id_user'],
                        ];
                        $mon_doc += $xmonto + $mon_iva;
                        $id_det = FacturacionModel::guardarDetfactura_CXC($data2);
                    }
                    //Actualizar saldo del documento
                    $data = array();
                    $data = [
                        'mon_doc' => $mon_doc,
                        'sal_doc' => $mon_doc,
                    ];
                    $r = FacturacionModel::actualizar($id, $data);
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
                        $r = FacturacionModel::aprobacion($data_app);
                    }

                    if ($tip_doc_fuente != 'Z' || $id_cli == 13) {
                        //Guardar Movimiento de Inventario Encabezado
                        $tconfig_fac = FacturacionModel::tip_doc_fac($_POST['id_emp']);
                        $id_ubi = $tconfig_fac['id_ubi'];
                        if ($_POST['tipo_fact'] == 'F') {
                            $tmov_fac = $tconfig_fac['tmov_fac'];
                        } else {
                            $tmov_fac = $tconfig_fac['tmov_noc'];
                        }

                        $id_alm = $tconfig_fac['id_alm'];

                        if ($id_ent['c_consig'] == 1) {
                            $id_alm = $id_ent['id_alm'];
                            if (!isset($_POST['id_ubi'])) {
                                $id_ubi = $id_ent['id_ubi'];
                            }
                        }
                        //Proximo número de Movimiento
                        $origen = '';
                        //Buscar el movimiento generado y borrar
                        if ($_POST['tipo_fact'] == 'F') {
                            $origen = 'FAC-' . $tipo_fac . '-' . $_POST['id_emp'] . '-' . $numero_fac;
                        } else {
                            $origen = 'FNC-' . $tipo_fac . '-' . $_POST['id_emp'] . '-' . $numero_fac;
                        }

                        $mov_inv = FacturacionModel::getNumberMovim($origen);
                        if (is_array($mov_inv) ? true : false) {
                            $id = FacturacionModel::borrarDetInvMov($mov_inv[0]['id_movinv']);
                        }
                        $r = FacturacionModel::getNextNumer($tmov_fac);
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
                            'id_cli' => $_POST['id_cli'],
                            'descrip_movinv' => 'Movimiento automático generado desde Facturación ' . $origen,
                            'origen' => $origen,
                            'status' => 1,
                            $modo => $_SESSION['id_user']
                        ];
                        //Guardar movimiento
                        $id_mov_inv = FacturacionModel::guardar_mov_inv($data_mov_fac);
                        //Actualizar numero siguiente
                        $id_num_movin = FacturacionModel::setNextNumber_Mov_Inv($tmov_fac);
                        //Guardar Movimiento de Inventario Detalle
                        $num_rows = count($_POST['id_prod']);
                        $data_det_fac = array();
                        foreach ($itemsFactura as $item) {
                            $id_prod = $item['id_prod'];
                            $can_det = ($item['cant']);
                            $pre_vta = str_replace(".", "", $item['ventas_prod1']);
                            $pre_vta = str_replace(",", ".", $pre_vta);
                            //Inicio Modificación José Vargas el 26-01-2026, para guardar los registos segun lo facturado, ya psado por la vlaidacion del stock
                            if ($id_ent['c_consig'] == 1) {
                                $id_ubi = $item['id_ubi'];
                            }
                            $fec_venc = '0000-00-00';
                            $data_det_fac = [
                                'id_movinv' => $id_mov_inv,
                                'id_prod' => $id_prod,
                                'id_ubi' => $id_ubi,
                                'cantidad' => $can_det,
                                'lote' => 'SL',
                                'fec_venc' => $fec_venc,
                                'costo' => $pre_vta,
                                'costo1' => $pre_vta,
                                'create_user' => $_SESSION['id_user']
                            ];
                            $id_det = FacturacionModel::guardar_Det_Movin($data_det_fac);
                            //Fin Modificación José Vargas el 26-01-2026
                            //Comentado po lo anterior expuesto
                            /*
                            $fec_venc = '0000-00-00';
                            if ($id_ent['c_consig'] == 1) {
                                $id_ubi = $item['id_ubi'];
                                $stock = FacturacionModel::consulta_prod_consig($id_prod, $item['id_cli'], $id_alm, $id_ubi);                               
                            } else {
                                $stock = FacturacionModel::consulta_prod($id_prod);
                            }

                            $total_rows_stock = is_array($stock) ? count($stock) : 0;

                            $xcan_det = $can_det;
                            if ($total_rows_stock == 0) {
                                $data_det_fac = [
                                    'id_movinv' => $id_mov_inv,
                                    'id_prod' => $id_prod,
                                    'id_ubi' => $id_ubi,
                                    'cantidad' => ($xcan_det),
                                    'lote' => 'SL',
                                    'fec_venc' => $fec_venc,
                                    'costo' => $pre_vta,
                                    'costo1' => $pre_vta,
                                    'create_user' => $_SESSION['id_user']
                                ];
                                $id_det = FacturacionModel::guardar_Det_Movin($data_det_fac);
                            }

                            for ($xi = 0; $xi < $total_rows_stock; $xi++) {
                                $dis_stock = $stock[$xi]['stock'];
                                $disp_lote = $stock[$xi]['lote'];
                                $dis_fec_ven = $stock[$xi]['fec_ven'];
                                $id_ubi = $stock[$xi]['id_ubi'];
                                if ($dis_stock >= $xcan_det) {
                                    $data_det_fac = [
                                        'id_movinv' => $id_mov_inv,
                                        'id_prod' => $id_prod,
                                        'id_ubi' => $id_ubi,
                                        'cantidad' => ($xcan_det),
                                        'lote' => $disp_lote,
                                        'fec_venc' => $dis_fec_ven,
                                        'costo' => $pre_vta,
                                        'costo1' => $pre_vta,
                                        'create_user' => $_SESSION['id_user']
                                    ];
                                    $xcan_det -= $xcan_det;
                                    $id_det = FacturacionModel::guardar_Det_Movin($data_det_fac);
                                } elseif ($dis_stock <= $xcan_det) {
                                    $data_det_fac = [
                                        'id_movinv' => $id_mov_inv,
                                        'id_prod' => $id_prod,
                                        'id_ubi' => $id_ubi,
                                        'cantidad' => ($dis_stock),
                                        'lote' => $disp_lote,
                                        'fec_venc' => $dis_fec_ven,
                                        'costo' => $pre_vta,
                                        'costo1' => $pre_vta,
                                        'create_user' => $_SESSION['id_user']
                                    ];
                                    $xcan_det = $xcan_det - $dis_stock;
                                    $id_det = FacturacionModel::guardar_Det_Movin($data_det_fac);
                                }
                                //Validar si activo el Intercompañia
                                if ($xcan_det == 0) {
                                    break;
                                }
                            }
                            */
                        }
                    }
                }
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
            } finally {
                header('Location:' . base_url . '/Facturacion');
            }
        }
    }
}
