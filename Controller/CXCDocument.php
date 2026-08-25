<?php
include_once VARTAX;
include_once $_SERVER['DOCUMENT_ROOT'] . '/Models/UsuariosModel.php';
include_once ROOT . DS . 'Models' . DS . 'EmpresasModel.php';
include_once ROOT . DS . 'Controller' . DS . 'Usuarios.php';
class CXCDocument extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(90);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $r = CXCDocumentModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Listado de Documentos',
            'function_js' => 'CXCDocument.js?v=' . SITE_VERSION,
            'function_js_mod' => 'CXCFun.js?v=' . SITE_VERSION,
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Documento",
            'function_js' => "CXCDocument.js?v=" . SITE_VERSION,
            'function_js_mod' => 'CXCFun.js?v=' . SITE_VERSION,
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CXCDocumentModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CXCDocument');
                }
                $_SESSION['status'] = $r['status'];
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Documento " . $r['nom_tdoc'] . ' número ' . $r['num_tdo'],
                    'function_js' => "CXCDocument.js?=" . SITE_VERSION,
                    'function_js_mod' => 'CXCFun.js?v=' . SITE_VERSION,
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CXCDocument');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CXCDocument');
    }
    public function listar_conceptos()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $r = CXCDocumentModel::listar_conceptos($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function showrow_c()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = CXCDocumentModel::showrow_c($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function showrow_i()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = CXCDocumentModel::showrow_i($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function print_CXCDocument($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));

            if ($id > 0) {
                $r = CXCDocumentModel::print_CXCDocument($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CXCDocument');
                }
                $this->views->getView($this, "print_CXCDocument", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CXCDocument');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CXCDocument');
    }
    public function val_tdo($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            if ($id) {
                $r = CXCDocumentModel::val_tdo($id);
                $_SESSION["status"] = $id;
                echo json_encode($r, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    public function val_aux()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = CXCDocumentModel::val_aux($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function doc_ped_cli()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $id_cli = $_POST['id_cli'];
            $fecha = $_POST['fecha_comp'];
            $id_moneda = '';
            if (isset($_POST['id_moneda']) && $_POST['id_moneda'] != 0) {
                $id_moneda = $_POST['id_moneda'];
            }
            $r = CXCDocumentModel::doc_ped_cli($id_emp, $id_cli, $fecha, $id_moneda);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function doc_ped_cli_one()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $id_cli = $_POST['id_cli'];
            $fecha = $_POST['fecha_comp'];
            $tipo_doc = $_POST['tipo_doc'];
            $num_doc = $_POST['num_doc'];
            $r = CXCDocumentModel::doc_ped_cli_one($id_emp, $id_cli, $fecha, $tipo_doc, $num_doc);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function get_doc_cxc()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_cot = $_POST['id_cot'];
            $id_moneda = $_POST['id_moneda'];
            $movem_origen = false;
            if (isset($_POST['movem_origen'])) {
                $movem_origen = $_POST['movem_origen'];
            }
            $r = CXCDocumentModel::get_doc_cxc($id_cot, $id_moneda, $movem_origen);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function index2()
    {
        $this->views->getView($this, "index2", [
            'page_name' => "Actualizar Número de Control",
            'function_js' => "CXCDocument2.js",
        ]);
    }
    public function DocCtrolCXC()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $id_tdo_ctrl = $_POST['id_tdo_ctrl'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $r = CXCDocumentModel::DocCtrolCXC($id_emp, $id_tdo_ctrl, $fec_ini, $fec_fin);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function nro_control()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array();
            $id_cot = $_POST['id_cot'];
            $nro_control = $_POST['nro_control'];
            $data = ['nro_control' => $nro_control];
            $r = CXCDocumentModel::nro_control($id_cot, $data);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_facturas_clientes()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $id_ent = $_POST['id_ent'];
            $r = CXCDocumentModel::listar_facturas_clientes($id_emp, $id_ent);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function edo_cuenta()
    {
        $this->views->getView($this, "edo_cuenta", [
            'page_name' => "Estados de Cuentas",
            'function_js' => "CXCDocument3.js?v=" . SITE_VERSION,
        ]);
    }
    public function edo_cuenta_data()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $id_cli = false;
            $ori = 0;
            if (isset($_POST['id_cli']) && $_POST['id_cli'] > 0) {
                $id_cli = $_POST['id_cli'];
            }
            if (isset($_POST['ori']) && $_POST['ori'] > 0) {
                $ori = $_POST['ori'];
            }
            $r = CXCDocumentModel::edo_cuenta($id_emp, $id_cli, $ori);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function saldos_vencidos()
    {
        $this->views->getView($this, "saldos_vencidos", [
            'page_name' => "Saldos Vencidos",
            'function_js' => "saldos_vencidos.js?v=" . SITE_VERSION,
        ]);
    }
    public function saldos_vencidos_data()
    {
        $id_emp = 0;
        $id_ent = 0;
        if (isset($_POST['id_emp']) and $_POST['id_emp'] > 0) {
            $id_emp = $_POST['id_emp'];
        }
        if (isset($_POST['id_cli']) && $_POST['id_cli'] > 0) {
            $id_ent = $_POST['id_cli'];
        }
        $r = CXCDocumentModel::saldos_vencidos($id_emp, $id_ent);
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
    public function rep_fac_pag_data()
    {
        if ($_SERVER['REQUEST_METHOD']) {
            $id_emp = $_POST['id_emp'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $id_cli = $_POST['id_cli'];
            $r = CXCDocumentModel::rep_fac_pag($id_emp, $fec_ini, $fec_fin, $id_cli);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $r = CXCDocumentModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            //Asignar valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            //Validar si usa Consecutivo
            $tip_doc_cons = false;
            $sol_aprob = false;
            if (empty($id)) {
                $modo = "create_user";
                //Buscar Next Number
                $r = to_obj(CXCDocumentModel::val_tdo($id_tdo));
                if ($r->con_tdoc == 1) {
                    $num_tdo = intval($r->num_tdoc);
                    $tip_doc_cons = true;
                }
                //Verificar si requiere Aprobación
                if ($r && $r->sol_aprob == 1) {
                    $sol_aprob = true;
                    $tipo_codigo = $r->tipo_codigo;
                    $nom_tdoc = $r->nom_tdoc;
                }
                //Asignar Numero de Control
                $r = to_obj(CXCDocumentModel::getnro_control($id_emp));
                $nro_control = $r->next_nroControl;
                //Actualizar el siguiente numero de control
                $r = to_obj(CXCDocumentModel::nextnro_control($id_emp));
            }
            try {
                //Configuracion de mpresa
                $config_emp = EmpresasModel::edit($id_emp);
                //Confgiuracion Modulo
                $config_cxc = CXCDocumentModel::config_cxc($id_emp);
                $tasa = convert_string_to_number($tasa_cambio);
                $data = [
                    "id_emp" => $id_emp,
                    "id_tdo" => $id_tdo,
                    "num_tdo" => $num_tdo,
                    "id_cli" => $id_cli,
                    "fecha_comp" => $fecha_comp,
                    "fecha_venci" => $fecha_venci,
                    "id_moneda" => $id_moneda,
                    "tasa_cambio" => $tasa,
                    "descrip_cot" => $descrip_cot,
                    "status" => $status,
                    "nro_control" => $nro_control,
                    "id_doc_afec" => $id_afectado,
                    "doc_afectado" => $doc_afectado,
                    "motivo" => $motivo,
                    $modo => $_SESSION["id_user"],
                ];
                if (empty($id)) {
                    //Guardar encabezado de documentos
                    $id = CXCDocumentModel::guardar($data);
                    $id_cot_doc = $id;
                    //Actualizar el siguiente número de documento
                    if ($id && $tip_doc_cons) {
                        $data = array();
                        $data = [
                            "num_tdoc" => intval($num_tdo + 1),
                            $modo => $_SESSION["id_user"],
                        ];
                        $num = CXCDocumentModel::setNextNumber($id_emp, $id_tdo, $data);
                    }
                    $title = "Registro agregado satisfactoriamente";
                } else {
                    $r = to_obj(CXCDocumentModel::nextNumber($id_emp, $id_tdo));
                    //Verificar si requiere Aprobación
                    if ($r && $r->sol_aprob == 1) {
                        $sol_aprob = true;
                        $tipo_codigo = $r->tipo_codigo;
                        $nom_tdoc = $r->nom_tdoc;
                    }
                    $id_doc = CXCDocumentModel::actualizar($id, $data);
                    $id_cot_doc = $id;
                    $id_det = CXCDocumentModel::borrarDetCXCDocument($id);
                    $title = "Registro modificado satisfactoriamente";
                }
                //Guardar detalle
                $xvatTax = xvatTax($_POST['fecha_comp'], 'IVA');
                $tasa_iva = $xvatTax[0]['txr1_iva'];
                $itemTotal = count($_POST['id_con']);
                $data = array();
                $xmondoc = 0;
                for ($i = 0; $i < $itemTotal; $i++) {
                    $monto = ROUND(convert_string_to_number($_POST['mon_con'][$i]), 2);
                    $xmondoc += $monto;
                    $mon_iva = ROUND(convert_string_to_number($_POST['mon_iva'][$i]), 2);
                    $id_concxc = $_POST['id_con'][$i];
                    $iva =  $_POST['iva'][$i];
                    $id_aux = 0;
                    if (!empty($_POST["id_aux"][$i])) {
                        $id_aux = $_POST["id_aux"][$i];
                    }
                    if ($iva == 'S') {
                        $mon_iva = $monto * ($tasa_iva / 100);
                        $xmondoc += floatval($mon_iva);
                    }
                    $data = [
                        'id_cot' => $id,
                        'id_concxc' => $id_concxc,
                        'iva' => $iva,
                        'id_aux' => $id_aux,
                        'monto' => ROUND($monto, 2),
                        'mon_iva' => round($mon_iva, 2),
                        'create_user' => $_SESSION['id_user'],
                    ];
                    $id_det = CXCDocumentModel::guardarDetDocument($data);
                }
                //Actiualizar encabezado de Documento con los montos                
                $data = [
                    'mon_doc' => $xmondoc,
                    'sal_doc' => $xmondoc
                ];
                $id_det = CXCDocumentModel::actualizar($id, $data);
                //Generar Mensaje de Notificacion para Aprobobación
                if ($sol_aprob) {
                    $fgenmsg = array();
                    //Datos de aprobación
                    $id_emisor = $_SESSION['id_user'];
                    $name_user = $_SESSION['name_user'] . ' ' . $_SESSION['last_user'];
                    $mensaje = "Solicitud de Aprobación de $tipo_codigo - $nom_tdoc Número $num_tdo. elaborado por $name_user";
                    $tipo = "Aprobación pendiente $nom_tdoc $num_tdo de CXC";
                    $leido = 0;
                    $url_destino = 'CXCDocument/gestion/';
                    $token_revision = encriptar_url(json_encode(['id' => $id, 'accion' => 'revisar']));
                    //$token_revision = (json_encode(['id' => $id, 'accion' => 'revisar']));
                    $val_apro_exi = DB::query("SELECT * FROM f0025 WHERE modulo = 'C' AND id_origen = {$id}");
                    if ($val_apro_exi) {
                        $id_apro = $val_apro_exi[0]['id'];
                        $fgenmsg = [
                            'modify_user' => $_SESSION['id_user'],
                            'modify_date' => getAuditoria(),
                        ];
                        $not = UsuariosModel::upd_not($id_apro, $fgenmsg);
                    } else {
                        $approved = 0;
                        //Usuarios aprovadores
                        $users_aprovved = UsuariosModel::users_approve();
                        foreach ($users_aprovved as $user_app) {
                            $id_receptor = $user_app['id_user'];
                            //Guardar aprobación;                            
                            $fgenmsg = [
                                'id_emisor' => $id_emisor,
                                'id_receptor' => $id_receptor,
                                'mensaje' => $mensaje,
                                'tipo' => $tipo,
                                'url_destino' => $url_destino . $token_revision,
                                'leido' => $leido,
                                'modulo' => 'C',
                                'id_origen' => $id,
                                'motivo' => $motivo,
                                'approved' => $approved,
                                'create_user' => $_SESSION['id_user'],
                                'create_date' => getAuditoria(),
                            ];
                            $not = UsuariosModel::save_not($fgenmsg);
                            //Notificar a Aprobadores     
                            $url_end = $url_destino . $token_revision;                          
                            //$notpush = Usuarios::enviarPushAUsuario($id_receptor, $mensaje, $url_end);                            
                        }
                    }                    
                }
                $msg = "Se ha salvado satisfactoriamente el Documento $num_tdo ";
                $dataJson = [
                    'title' => $title,
                    'icon' => "success",
                    'msg' => $msg
                ];
            } catch (\PDOException $e) {
                $title = "Se ha presentado un error, intente luego";
                $msg = sprintf("Error códoigo: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => $title,
                    'icon' => "error",
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST["id"];
            $r = CXCDocumentModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    //Mostrar Documentos para elaboración de nOta de Credito y/o Debito
    /**
     * Obtener documentos de CXC para un cliente específico
     * @return void
     */
    public function getDocCXC()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {            
            $id = $_POST["id_cli"];
            $id_moneda = $_POST["id_moneda"];
            $r = CXCDocumentModel::get_doc_cxc_cli($id, $id_moneda);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function gestion($token = null)
    {
        if (!$token) {
            return;
        }
        $datos = desencriptar_url($token);
        $accion = $datos['accion'];
        $id = $datos['id'];
        switch ($accion) {
            case 'revisar':
                $this->view_document($id);
                break;
            case 'printer':
                $url_print = base_url . '/CXCDocument/print_CXCDocument/' . $id;
                $url_destino = base_url . '/CXCDocument';
                // Imprimir el script JS para abrir en nueva pestaña                                
                echo "<script type='text/javascript'>
                    // 1. Abre el reporte es una ventana nueva
                    window.open('$url_print', '_blank');
                    // 2. Redirigr a pestaña actual a la nueva
                    window.location.href = '$url_destino';
                </script>";

                CXCDocumentModel::check_read($id);
                break;
            default:
                // Acción no permitida
                break;
        }
    }
    public function view_document(int $id)
    {
        setcookie("id_ado_apro", $id, time() + 3600, "/");
        $this->views->getView($this, 'document', [
            'page_name' => 'Revisión de Documento',
            'function_js' => 'CXCDocument.js?v=' . SITE_VERSION,
            'function_js_mod' => 'CXCFun.js?v=' . SITE_VERSION,
        ]);
    }
    public function approved()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datajson = [];
            $id = $_POST['id'];
            try {
                $r = CXCDocumentModel::approved($id);
                $title = "Documento aprobado";
                $msg = "El documento se ha aprobado satisfactoriamente";
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg,
                ];
            } catch (\PDOException $e) {
                $title = "Se ha presentado un error, por favor intente luego";
                $msg = sprintf("Error código: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg,
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy()
    {
        $dataJson = [];        
        $id_cot = $_POST['id_cot'];
        try {
            $r = CXCDocumentModel::destroy($id_cot);
            
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
}
