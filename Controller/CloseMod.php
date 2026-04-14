<?php
include_once ROOT . DS . 'Models' . DS . 'AsientosModel.php';
$page = 'Cierre Contable de Módulos';
if(isset($_GET['ori']) && $_GET['ori'] == 'A'){
    $page = 'Apertura Contable de Módulos';
}
$_SESSION['page_name'] = $page;
class CloseMod extends Controller
{

    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(186);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $this->views->getView($this, 'index', [
            'page_name' => $_SESSION['page_name'],
            'function_js' => 'CloseMod.js',
        ]);
    }
    public function close_modules(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //Recibir variables
            $id_emp = $_POST['id_emp'];
            $fec_ini = $_POST['fec_ini'];            
            $fec_ini_mes = date('Y-m-d', strtotime('+1 day', strtotime($fec_ini)));                     
            $fec_fin = $_POST['fec_fin'];
            //Sumar in dia a la fecha inicial para evitar problemas con registros del mismo día del cierre
            $fec_ini = date("Y-m-d", strtotime($fec_ini . " +1 day"));        
            //Moneda de Cia
            $id_moneda = CloseModModel::query("SELECT id_moneda FROM f0011 WHERE id_emp = {$id_emp} LIMIT 1");
            $id_moneda = $id_moneda[0]['id_moneda'];
            $tasa_cambio = 1;
            //1.- Obtener la lista de módulos a procesar
            $modulos = CloseModModel::obtenerModulos();
            $totalModulos = count($modulos);
            $modulosProcesados = 0;
            //2.- Inicializar el progreso
            $_SESSION['cierre_status'] = "INICIADO";
            $_SESSION['cierre_progreso'] = 0;
            $_SESSION['cierre_mensaje'] = "Preparando el cierre de Módulos";
            foreach ($modulos as $Nombre) {
                //Actualizar el estado antes de procesar el módulo
                $modulosProcesados++;
                $module = $Nombre['module'];
                $nombre = $Nombre['nombre'];
                $_SESSION['cierre_mensaje'] = "Procesando módulo {$nombre} ({$modulosProcesados} de {$totalModulos})";
                //Module
                //Calcular el progreso (esto es una aproximación)
                $_SESSION['cierre_progreso'] = floor(($modulosProcesados / $totalModulos) * 100);
                //3.- Logica Principal: Leer registros y crear comprobante  
                $registrosAProcesar = CloseModModel::leerRegistrosModulo($module, $id_emp, $fec_ini, $fec_fin) ?? 0;
               
                //                
                if ($registrosAProcesar && count($registrosAProcesar) > 0) {
                    // Crear Encabezado de Asiento Contable
                    $data = array();
                    $id_tipcom = '';
                    $num_comp = '';
                    $desc_comp = "MOVIMIENTOS NETOS";
                    //Verificar si usa consecutivo
                    $r = AsientosModel::var_consec($id_emp);
                    if ($r['consecu_config'] == 'S') {                        
                        $r = AsientosModel::nextNumber($id_emp, $r['numdia_config'], $fec_fin);
                        $num_comp = intval($r['num_comp']) + 1;
                    }
                    $id_tipcom_mod = CloseModModel::query("SELECT id_tipcom, nombre_tipcom FROM f0019 WHERE modulo = '$module'");
                    if ($id_tipcom_mod) {
                        $id_tipcom = $id_tipcom_mod[0]['id_tipcom'];
                        $desc_comp =  str_replace("COMPROBANTES", $desc_comp, $id_tipcom_mod[0]['nombre_tipcom']);
                        $desc_comp .= " DEL " . (date("d-m-Y", strtotime($fec_ini_mes))) . " AL " . date("d-m-Y", strtotime($fec_fin));
                    }
                    $fecha_comp = $fec_fin;
               
                    $fec_str = str_replace('-', '', $fec_fin);
                    $ori_comp = $module . $fec_str;
                    $data_enca = [
                        'id_emp' => $id_emp,
                        'id_tipcom' => $id_tipcom,
                        'num_comp' => $num_comp,
                        'fecha_comp' => $fecha_comp,
                        'id_moneda' => $id_moneda,
                        'tasa_cambio' => $tasa_cambio,
                        'desc_comp' => $desc_comp,
                        'ori_comp' => $ori_comp,
                        'create_user' => $_SESSION['id_user'],
                        'status' => 1,
                    ];
                    $r = AsientosModel::guardar($data_enca);                    
                    if($r){
                        foreach($registrosAProcesar as $registro){
                            $id_cue = $registro['id_cta'];
                            $id_aux = $registro['id_aux'];
                            $det_observa = '';
                            $det_tipo = $registro['tipo'];
                            if($det_tipo == 'H'){
                                $det_monto = $registro['mon_habe'];
                                if($det_monto <0){
                                    $det_tipo = 'D';
                                    $det_monto = abs($det_monto);
                                }
                            }else{
                                $det_monto = $registro['mon_debe'];
                                if ($det_monto < 0) {
                                    $det_tipo = 'H';
                                    $det_monto = abs($det_monto);
                                }
                            }
                            $id_aux = ($id_aux > 0) ? $id_aux : 0;
                            
                            $existe = CloseModModel::query("SELECT * FROM f00122 WHERE id_comp = {$r} AND id_cue = {$id_cue} AND id_aux = {$id_aux}");
                            if(!$existe){
                                  $data = [
                                'id_comp' => $r,
                                'id_cue' => $id_cue,
                                'id_aux' => $id_aux,
                                'det_observa' => $det_observa,
                                'det_tipo' => $det_tipo,
                                'det_monto' => $det_monto,
                                'create_user' => $_SESSION['id_user']
                                ];
                                $r_det = AsientosModel::guardardet($data);
                            }else{                             
                                $det_tipo_db = $existe[0]['det_tipo'];
                                $det_monto_db = $existe[0]['det_monto'];
                                $id_det = $existe[0]['id_det'];

                                if($det_tipo == $det_tipo_db){
                                    $det_monto+= $det_monto_db;
                                }else{
                                    $det_monto_db = ($det_monto_db - $det_monto) ;
                                    $det_monto = $det_monto_db;
                                }
                                if($det_monto < 0){
                                    $det_monto = abs($det_monto);
                                    if($det_tipo == 'D'){
                                        $det_tipo = 'H';
                                    }
                                }else{
                                    $det_tipo = $det_tipo_db;
                                }
                                $data = [                                                                   
                                    'det_tipo' => $det_tipo,
                                    'det_monto' => $det_monto,
                                ];
                                $r_det = AsientosModel::updatedet($id_det, $data);
                                
                            }
                        }
                    }
                    //Simulacion de un retraso de otarea pesada
                    sleep(1);                   
                }
            }
            //4.- DFinalizar el proceso
            $_SESSION['cierre_progreso'] = 100;
            $_SESSION['cierre_status'] = "Completado";
            $_SESSION['cierre_mensaje'] =  'Cierre de Módulos finalizado. Asientos Contables generados';
            // Nota: En un entorno real, es mejor devolver la respuesta de inmediato 
            // y dejar que el script se siga ejecutando o usar colas de trabajo (jobs).
            echo json_encode(['status' => 'INICIADO']);
        }
    }
    public function open_modules(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $fec_ini = $_POST['fec_ini'];
            $fec_fin = $_POST['fec_fin'];
            $r = CloseModModel::delete_journal_entries($id_emp, $fec_ini);
            IF($r){

            $data = [
                "fec_ctb" => $fec_fin,
                "fec_ban" => $fec_fin,
                "fec_cxc" => $fec_fin,
                "fec_cxp" => $fec_fin,
                "fec_nom" => $fec_fin
            ];
            $r = CloseModModel::upd_fec_cie($id_emp, $data);
            }
            echo json_encode(['status' => 'COMPLETADO']);
        }
    }
    public function consultar_progreso(){
        //Devolver el estado actual
        echo json_encode([
            'progreso' => $_SESSION['cierre_progreso'] ?? 0,
            'status' => $_SESSION['cierre_status'] ?? 'Inactivo',
            'mensaje' => $_SESSION['cierre_mensaje'] ?? 'Esperando inicio ...'
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = AsientosModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/CloseMod');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de ',
                    'function_js' => 'CloseMod.js',
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/CloseMod');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/CloseMod');
    }
    public function store(){
        $modo = 'modify_user';
        $data = array();
        if (empty($_POST['id'])) {
        } else {
        }
        try {
            header('Location:' . base_url . '/CloseMod');
        } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/CloseMod');
        }
    }
    public function destroy(){
        $dataJson = [];
        if (empty($_POST['id'])) {
            $dataJson = [
                'status' => false,
                'msg' => 'No se recibieron los datos'
            ];
        } else {
            $id = intval(limpiar($_POST['id']));
            //$ide = CloseModModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente',)
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function upd_fec_cie(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id_emp = $_POST["id_emp"];
            $fec_fin = $_POST["fec_fin"];
            $data = [
                "fec_ctb" => $fec_fin,
                "fec_ban" => $fec_fin,
                "fec_cxc" => $fec_fin,
                "fec_cxp" => $fec_fin,
                "fec_nom" => $fec_fin
            ];
            $r = CloseModModel::upd_fec_cie($id_emp, $data);            
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
