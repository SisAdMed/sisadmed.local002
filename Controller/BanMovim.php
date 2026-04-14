<?php

/**
 * Clase creada por José Vargas
 * Fecha: 20-12-2004 Hora 11:22 a.m.
 * Metodos para los Movimientos Bancarios
 */
include $_SERVER['DOCUMENT_ROOT'] . '/Models/EmpresasModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Models/CXCMovementModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Models/CXPMovementModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Models/TipoMovCXCModel.php';
include $_SERVER['DOCUMENT_ROOT'] . '/Models/CambiosModel.php';


//Variables
class BanMovim extends Controller
{
    public $function_js = 'BanMovim.js';
    public $page_name = 'Movimiento Bancario';
    function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(159);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = BanMovimModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de ' . $this->page_name,
            'function_js' => $this->function_js,
            'r' => to_obj($objeto)
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo ' . $this->page_name,
            'function_js' => $this->function_js
        ]);
    }
    public function cargar_screen_main()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = BanMovimModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $error_level = 0;
            $id = $_POST['id'];
            $modo = "modify_user";
            $data_banmov = array();
            if (empty($id)) {
                $modo = "create_user";
            }
            try {
                //Encabezo de movimiento bancario
                $id_emp = $_POST['id_emp'];
                $id_bantmo = $_POST['id_bantmo'];
                $id_bancue = $_POST['id_bancue'];
                $num_banmov = $_POST['num_banmov'] ;
                $fecha_comp = $_POST['fecha_comp'];
                $id_moneda = $_POST['id_moneda'];
                $tasa_cambio = convert_string_to_number($_POST['tasa_cambio']);
                $des_banmov = $_POST['des_banmov'];
                $benef_banmov = limpiar($_POST['benef_banmov']);
                $status = $_POST['status'];
                //$num_banmov = $num_banmov + mt_rand(1, 10000);
                $data_banmov = [
                    'id_emp' => $id_emp,
                    'id_bantmo' => $id_bantmo,
                    'id_bancue' => $id_bancue,
                    'num_banmov' => $num_banmov,
                    'fecha_comp' => $fecha_comp,
                    'id_moneda' => $id_moneda,
                    'tasa_cambio' => $tasa_cambio,
                    'benef_banmov' => strtoupper($benef_banmov),
                    'des_banmov' => $des_banmov,
                    'status' => $status,
                    $modo => $_SESSION['id_user']
                ];
                if (isset($_POST['id_cli']) && $_POST['id_cli'] > 0) {
                    $id_ent = $_POST['id_cli'];
                    $data_banmov += ['id_ent' => $id_ent];
                }
                if (isset($_POST['id_ent']) && $_POST['id_ent'] > 0) {
                    $id_prov = $_POST['id_ent'];
                    $data_banmov += ['id_ent' => $id_prov];
                }
                if (empty($id)) {
                    $enc_ban_mov = BanMovimModel::guardar($data_banmov);                    
                } else {
                    $enc_ban_mov = BanMovimModel::actualizar($id, $data_banmov);
                    $rdet = BanMovimModel::borrar_det_mov($id);
                    $enc_ban_mov = $id;
                }
                
                if ($enc_ban_mov) {
                  
                    //Detalle de movimiento bancario
                    $tot_rows = count($_POST['id_bancon']);
                    $tot_monto_for = 0;
                    $tot_monto_dom = 0;
                    $id_banmov = $enc_ban_mov;
                    if ($_POST['id']) {
                        $id_banmov = $_POST['id'];
                    }
                    
                    for ($i = 0; $i < $tot_rows; $i++) {

                        $id_bancon = $_POST['id_bancon'][$i];
                        $monto_nac = convert_string_to_number($_POST['monto'][$i]);
                        $monto_for = convert_string_to_number($_POST['monto'][$i]);
                        if ($monto_nac != 0) {
                            $tot_monto_for += $monto_for;
                            $tot_monto_dom += $monto_nac;
                            $id_aux = $_POST['id_aux'][$i];
                            $data_banmov = [
                                'id_banmov' => $id_banmov,
                                'id_bancon' => $id_bancon,
                                'monto_nac' => $monto_nac,
                                'monto_for' => $monto_for,
                                'create_user' => $_SESSION['id_user'],
                            ];
                            if (!empty($id_aux) && $id_aux > 0) {
                                $data_banmov += ['id_aux' => $id_aux];
                            }                            
                            $det_ban_mov = BanMovimModel::guardar_det($data_banmov);
                        }
                    }
                    //Actualiar monto del Movimiento
                    $data = [
                        'monto_nac' => $tot_monto_dom,
                        'monto_for' => $tot_monto_for,
                    ];
                    $enc_ban_mov = BanMovimModel::actualizar($id, $data);
                }
                //Encabezado de Movimiento de CXC            
                if (!empty($id_ent)) {
                    $empresa = EmpresasModel::edit($id_emp);
                    if ($empresa) {
                        $moneda_cia = $empresa['id_moneda'];
                    }
                    $efecto = $_POST['efecto'];
                    $cxtmo = $_POST['cxtmo'];
                    if (empty($id)) {
                        $r = to_obj(CXCMovementModel::val_tmo($cxtmo));
                        if ($r[0]->con_tmocxc == "S") {
                            $r = to_obj(CXCMovementModel::nextNumber($id_emp, $cxtmo));
                            $movem_number = (intval($r->next_tmocxc) - 1);
                        }
                    }

                    $sql = "SELECT CONCAT(b.cod_bantmo, '-', c.cuenta_bancue, '-', a.num_banmov) descrip, CONCAT(b.cod_bantmo, c.cuenta_bancue, a.num_banmov) cont FROM f5006 a INNER JOIN f5002 b ON b.id_bantmo = a.id_bantmo INNER JOIN f5004 c ON c.id_bancue = a.id_bancue WHERE a.id_banmov = {$id_banmov}";
                    $descrip = BanMovimModel::query($sql);


                    $id_tmocxc = $cxtmo;
                    $movem_descrip = "COBRO Según {$descrip[0]['descrip']}";
                    $movem_amount = 0;
                    $arraycxc = array();
                    $arraycxc_enca = array();
                    $id_moneda_previus = '';
                    //Saber cuantas monedas tengo en el $_POST
                    $array_moneda = $_POST['id_moneda_doc'];
                    //Remover duplicados
                    $array_moneda_agrupadas = array_unique($array_moneda);
                    //Reindexar
                    $array_agrupado = array_values($array_moneda_agrupadas);
                    //Recorrerregistros segun las cantidades de monedas
                    $tot_monedas = count($array_agrupado);
                    for ($mon = 0; $mon < $tot_monedas; $mon++) {
                        $item = 0;
                        //Crear encabezado por cada moneda
                        $tcon_mov = false;
                        if (empty($id)) {
                            $r = to_obj(CXCMovementModel::val_tmo($cxtmo));
                            if ($r[0]->con_tmocxc == "S") {
                                $tcon_mov = true;
                                $r = to_obj(CXCMovementModel::nextNumber($id_emp, $cxtmo));
                                $movem_number = (intval($r->next_tmocxc) - 1);                                
                            }
                        } else {
                            $r = to_obj(CXCMovementModel::query("SELECT * FROM f6006 a INNER JOIN f0005 b ON b.id_moneda = a.id_moneda WHERE a.movem_origen = '$id' AND b.codigo_moneda = '$array_agrupado[$mon]'"));
                            $movem_number = $r[0]->movem_number;
                            $movem_id = $r[0]->id_movement;
                            $id_tmocxc = $r[0]->id_tmocxc;
                            $r = CXCMovementModel::borrarDetCXCMovement($movem_id);
                        }
                        $sql = "SELECT CONCAT(b.cod_bantmo, '-', c.cuenta_bancue, '-', a.num_banmov) descrip, CONCAT(b.cod_bantmo, c.cuenta_bancue, a.num_banmov) cont FROM f5006 a INNER JOIN f5002 b ON b.id_bantmo = a.id_bantmo INNER JOIN f5004 c ON c.id_bancue = a.id_bancue WHERE a.id_banmov = {$id_banmov}";
                        $descrip = BanMovimModel::query($sql);
                        $sql = "SELECT id_moneda FROM f0005 WHERE codigo_moneda = '$array_agrupado[$mon]'";
                        $moneda = BanMovimModel::query($sql);
                        $moneda_id = $moneda[0]['id_moneda'];
                        $data_banmov = [
                            'id_emp' => $id_emp,
                            'id_tmocxc' => $id_tmocxc,
                            'id_cli' => $id_ent,
                            'fecha_comp' => $fecha_comp,
                            'movem_descrip' => $movem_descrip,
                            'status' => $status,
                            'movem_origen' => $id_banmov,
                            'id_moneda' => $moneda_id,
                            $modo => $_SESSION['id_user']
                        ];
                        if($tcon_mov){
                            $movem_number++;
                        }                  
                        if (empty($id)) {                            
                            $data_banmov += ['movem_number' => $movem_number];
                        } else {
                            $data_banmov += ['movem_number' => $movem_number];
                        }
                        if (empty($_POST['id'])) {
                            $enc_cxc_mov = CXCMovementModel::guardar($data_banmov);
                            $movem_id = $enc_cxc_mov;
                          
                            if ($tcon_mov) {
                                $movem_number++;
                            }
                            //actualziar proximo numero    
                            $data1 = array();
                            $data1 = [
                                'next_tmocxc' => $movem_number,
                                $modo => $_SESSION['id_user'],
                            ];
                            //Actualizar el siguiente número de movimiento
                            $r1 = to_obj(CXCMovementModel::val_tmo($cxtmo));
                            if ($tcon_mov) {
                                $num = CXCMovementModel::setNextNumber($id_emp, $cxtmo, $data1);
                            }
                        } else {
                            $enc_cxc_mov = CXCMovementModel::actualizar($movem_id, $data_banmov);
                        }
                        //Detalle de Movimiento de CXC
                        if ($movem_id) {
                            $movem_amount = 0;
                            $tot_rows = count($_POST['id_moneda_doc']);

                            for ($x = 0; $x < $tot_rows; $x++) {
                                $id_moneda_doc = $_POST['id_moneda_doc'][$x];
                                if ($id_moneda_doc  == $array_agrupado[$mon]) {
                                    $item++;
                                    $id_cot = $_POST['id_cot'][$x];
                                    $tdo = BanMovimModel::query("SELECT a.num_tdo, a.id_tdo, a.id_moneda, a.tasa_cambio, b.codigo_moneda FROM f6003 a INNER JOIN f0005 b ON b.id_moneda = a.id_moneda WHERE a.id_cot = {$id_cot}");
                                    $id_moneda_id_doc = $tdo[0]['id_moneda'];
                                    $cod_moneda = $array_agrupado[$mon];
                                    $sql = "SELECT id_moneda FROM f0005 WHERE codigo_moneda = '$cod_moneda'";
                                    $moneda = BanMovimModel::query($sql);
                                    $mont_doc = convert_string_to_number($_POST['mon_can'][$x]);
                                    $mon_ret = convert_string_to_number($_POST['mon_ret'][$x]);
                                    $tasa_cambio_fac = $tdo[0]['tasa_cambio'];
                                    $id_tdo = $tdo[0]['id_tdo'];
                                    $num_tdo = $tdo[0]['num_tdo'];
                                    $moneda_doc = $tdo[0]['codigo_moneda'];
                                    if ($mont_doc != 0 || $mon_ret  != 0) {
                                        $num_ret = $_POST['num_ret'][$x];

                                        if ($id_moneda == $id_moneda_id_doc) {
                                            $mont_doc = convert_string_to_number($_POST['mon_can'][$x]);
                                            $mon_ret = convert_string_to_number($_POST['mon_ret'][$x]);
                                        } else if ($tasa_cambio_fac != 1 and $id_moneda_id_doc == $moneda_cia) {
                                            $mont_doc = convert_string_to_number($_POST['mon_can'][$x]) * $tasa_cambio_fac;
                                            $mon_ret = convert_string_to_number($_POST['mon_ret'][$x]) * $tasa_cambio_fac;
                                        } else {
                                            $mont_doc = ($mont_doc / $tasa_cambio_fac);
                                            if ($mon_ret != 0) {
                                                $mon_ret = ($mon_ret / $tasa_cambio_fac);
                                            }
                                        }
                                        $movem_amount += $mont_doc;
                                        $data_banmov = [
                                            'movem_id' => $movem_id,
                                            'id_tdo' => $id_tdo,
                                            'id_cot' => $id_cot,
                                            'num_tdo' => $num_tdo,
                                            'monto_doc' => $mont_doc,
                                            'mon_ret' => $mon_ret,
                                            'num_ret' => $num_ret,
                                            'create_user' => $_SESSION['id_user'],
                                        ];
                                        $det_cxc_mov = CXCMovementModel::guardarDetMovement($data_banmov);
                                        //Actualizar monto del encabezado                                
                                        if ($moneda[0]['id_moneda'] == $moneda_cia) {
                                            $tasa_cambio_fac = 1;
                                        } else {
                                            if ($item > 1) {
                                                $tasa_cambio_fac = convert_string_to_number(CambiosModel::rateExchange($moneda[0]['id_moneda'], $fecha_comp));
                                            } else {
                                                $tasa_cambio_fac = $tasa_cambio;
                                            }
                                        }
                                        $data_banmov = [
                                            'movem_amount' => $movem_amount,
                                            'id_moneda' => $id_moneda_id_doc,
                                            'tasa_cambio' => $tasa_cambio_fac
                                        ];
                                        $enc_cxc_mov = CXCMovementModel::actualizar($movem_id, $data_banmov);
                                    }
                                }
                            }
                        }
                    }
                }
                //Encabezado de Movimiento de CXP
             
                if (!empty($id_prov)) {

                    $efecto = $_POST['efecto'];
                    $cxtmo = $_POST['cxtmo'];
                    $empresa = EmpresasModel::edit($id_emp);
                    if ($empresa) {
                        $moneda_cia = $empresa['id_moneda'];
                    }
                    $descrip = '';
                    $sql = "SELECT CONCAT(b.cod_bantmo, '-', c.cuenta_bancue, '-', a.num_banmov) descrip, CONCAT(b.cod_bantmo, c.cuenta_bancue, a.num_banmov) cont FROM f5006 a INNER JOIN f5002 b ON b.id_bantmo = a.id_bantmo INNER JOIN f5004 c ON c.id_bancue = a.id_bancue WHERE a.id_banmov = $id_banmov";
                    $descrip = BanMovimModel::query($sql);
                    $id_bantmo = $_POST['id_bantmo'];
                    $sql = "SELECT  b.id_tmocxc FROM `f5002` a INNER JOIN f3002 b on b.id_tmocxc = a.id_cxtmo  WHERE a.id_bantmo = $id_bantmo";
                    $r1 = to_obj(DB::query($sql));
                    $cxtmo = $r1[0]->id_tmocxc;
                    

                    $id_tmocxc = $cxtmo;
                    $movem_descrip = "PAGO Según {$descrip[0]['descrip']}";
                    //
                    //Saber cuantas monedas tengo en el $_POST
                    $array_moneda = $_POST['id_moneda_doc'];
                    //Remover duplicados
                    $array_moneda_agrupadas = array_unique($array_moneda);
                    //Reindexar
                    $array_agrupado = array_values($array_moneda_agrupadas);
                    //Recorrerregistros segun las cantidades de monedas
                    $tot_monedas = count($array_agrupado);                    
                    $num_sin_con = 0;
                    for ($mon = 0; $mon < $tot_monedas; $mon++) {
                        //Asignar Proximo NUmero
                        if (empty($_POST['id'])) {
                            $r = to_obj(CXPMovementModel::val_tmo($cxtmo));
                            if ($r[0]->con_tmocxc == "S") {
                                $movem_number = intval($r[0]->next_tmocxc) - 1;
                            } else {
                                $movem_number = $num_banmov + $num_sin_con;
                            }
                        } else {
                            $r = CXPMovementModel::query("SELECT * FROM f3008 WHERE movem_origen = '$id_banmov'");
                            if ($r) {
                                $id_movement = $r[0]['id_movement'];
                                $movem_number = $r[0]['movem_number'];
                            } else {
                                $r = to_obj(CXPMovementModel::val_tmo($cxtmo));
                                if ($r[0]->con_tmocxc == "S") {
                                    $movem_number = intval($r[0]->next_tmocxc) - 1;
                                } else {
                                    $movem_number = $num_banmov + $num_sin_con;
                                }
                            }
                        }
                        //Creación de Encabezado
                        //Crear encabezado por cada moneda
                        $sql = "SELECT CONCAT(b.cod_bantmo, '-', c.cuenta_bancue, '-', a. num_banmov) descrip, CONCAT(b.cod_bantmo, c.cuenta_bancue, a.num_banmov) cont FROM f5006 a INNER JOIN f5002 b ON b.id_bantmo = a.id_bantmo INNER JOIN f5004 c ON c.id_bancue = a.id_bancue WHERE a.id_banmov = {$id_banmov}";
                        $descrip = BanMovimModel::query($sql);                        
                        $sql = "SELECT id_moneda FROM f0005 WHERE codigo_moneda = '$array_agrupado[$mon]'";
                        $moneda = BanMovimModel::query($sql);                        
                        $moneda_id = $moneda[0]['id_moneda'];
                        $data_banmov = [
                            'id_emp' => $id_emp,
                            'id_tmocxp' => $id_tmocxc,
                            'id_ent' => $id_prov,
                            'fecha_comp' => $fecha_comp,
                            'movem_descrip' => $movem_descrip,
                            'status' => $status,
                            'movem_origen' => $id_banmov,
                            'id_moneda' => $moneda_id,
                            'tasa_cambio' => $tasa_cambio,
                            $modo => $_SESSION['id_user'],
                        ];
                        if (empty($id)) {
                            $data_banmov += ['movem_number' => $movem_number++];
                        } else {
                            $data_banmov += ['movem_number' => $movem_number];
                        }
                        if ($_POST['id']) {
                            if (isset($id_movement)) {
                                $r = CXPMovementModel::actualizar($id_movement, $data_banmov);
                                $r = CXPMovementModel::borrarDetCXPMovement($id_movement);
                            } else {
                                $r = CXPMovementModel::guardar($data_banmov);
                                $id_movement = $r;
                                $movem_number = $movem_number + 1;
                                $data1 = array();
                                $data1 = [
                                    'next_tmocxc' => $movem_number,
                                    $modo => $_SESSION['id_user'],
                                ];
                                //Actualizar el siguiente número de movimiento
                                $r1 = to_obj(CXPMovementModel::val_tmo($cxtmo));
                                if ($r1[0]->con_tmocxc == "S") {
                                    $num = CXPMovementModel::setNextNumber($id_emp, $cxtmo, $data1);
                                }
                            }
                        } else {
                            $r = CXPMovementModel::guardar($data_banmov);
                            $id_movement = $r;
                            $movem_number = $movem_number + 1;
                            $data1 = array();
                            $data1 = [
                                'next_tmocxc' => $movem_number,
                                $modo => $_SESSION['id_user'],
                            ];
                            //Actualizar el siguiente número de movimiento
                            $r1 = to_obj(CXPMovementModel::val_tmo($cxtmo));
                            if ($r1[0]->con_tmocxc == "S") {
                                $num = CXPMovementModel::setNextNumber($id_emp, $cxtmo, $data1);
                            }
                        }
                        //Crear Detalle por cada moneda de Documento
                        $tot_rows = count($_POST['mon_can']);
                        $movem_amount = 0;
                        $tasa_cambio_fac = 1;                        
                        for ($x = 0; $x < $tot_rows; $x++) {
                            $id_moneda_doc = $_POST['id_moneda_doc'][$x];                            
                            if ($id_moneda_doc  == $array_agrupado[$mon]){                                
                                $mont_doc = convert_string_to_number($_POST['mon_can'][$x]);
                                if ($mont_doc != 0) {
                                    $moneda_doc = $_POST['id_moneda_doc'][$x];
                                    $id_cot = $_POST['id_cot'][$x];
                                    $tdo = BanMovimModel::query("SELECT num_tdo, id_tdo, id_moneda, tasa_cambio FROM f3004 WHERE id_cot = {$id_cot}");
                                    $tasa_cambio_fac = $tdo[0]['tasa_cambio'];
                                    $id_tdo = $tdo[0]['id_tdo'];
                                    $num_tdo = $tdo[0]['num_tdo'];
                                    $moneda_doc = $tdo[0]['id_moneda'];
                                    if ($id_moneda == $moneda_doc) {
                                        $mont_doc = convert_string_to_number($_POST['mon_can'][$x]);
                                    } else {
                                        $mont_doc = ($mont_doc / $tasa_cambio_fac);
                                    }
                                    $movem_amount += $mont_doc;
                                    $data_banmov = [
                                        'movem_id' => $id_movement,
                                        'id_tdo' => $id_tdo,
                                        'id_cot' => $id_cot,
                                        'num_tdo' => $num_tdo,
                                        'monto_doc' => $mont_doc,
                                        'create_user' => $_SESSION['id_user'],
                                    ];
                                    $r = CXPMovementModel::guardarDetMovement($data_banmov);
                                }
                            }
                            $data_banmov = [
                                'movem_amount' => $movem_amount,
                                'id_moneda' => $moneda_id,
                                'tasa_cambio' => $tasa_cambio_fac
                            ];

                            //Actualizar monto del encabezado
                            $r = CXPMovementModel::actualizar($id_movement, $data_banmov);
                        }
                    }
                }
                Alertas::new('Movimiento registrado satisfactoriamente', 'success');
            } catch (PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
            } finally {
                header('Location:' . base_url . '/BanMovim');
            }
        }
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = BanMovimModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/BanMovim');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Movimiento Bancario ",
                    'function_js' => "BanMovim.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/BanMovim');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/BanMovim');
    }
    public function show_row($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = BanMovimModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function det_doc_can()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $efecto = $_POST['efecto'];
            if (isset($_POST['id_moneda'])) {
                $id_moneda = $_POST['id_moneda'];
            }
            $r = BanMovimModel::det_doc_can($id, $efecto);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete_row()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = BanMovimModel::delete_row($id);
            if ($r) {
                $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            } else {
                $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function print_movement($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = BanMovimModel::print_mov($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/BanMovim');
                }
                $this->views->getView($this, "print_movement", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/BanMovim');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/BanMovim');
    }
    public function movcuentas()
    {
        if (Permisos::read()) {
            $this->views->getView($this, "movcuentas", [
                'page_name' => 'Movimientos por Cuentas',
                'function_js' => 'movcuentas.js',
            ]);
        }
    }
    public function banmov_cuentas()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $id_bancue = $_POST['id_bancue'];
            $id_bancon = $_POST['id_bancon'];
            $r = BanMovimModel::banmov_cuentas($id_emp, $fec_ini, $fec_fin, $id_bancue, $id_bancon);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
