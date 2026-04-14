<?php
//Para las lecturas de excel
require_once(SPREADEXCEL . "/vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;

class InvLoad extends Controller{
   public function __construct(){
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(97);
   }
   public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = InvLoadModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Listado de Movimientos Carga de Inventario",
            'function_js' => "InvLoad.js",
            'objeto' => to_obj($objeto),
        ]);
   }
   public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Movimiento de Carga de Inventario",
            'function_js' => "InvLoad.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = InvLoadModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/InvLoad');
                }
                if($r['modo'] == 'P'){
                    $modo = ' - <b>en modo Preliminar</b>';
                }else{
                    $modo = ' - <b>en modo Definitivo</b>';
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Movimiento de Carga de Inventario " . $r['cod_tmoinv'] . ' - ' . $r['nom__tmoinv'] . ' Número ' . $r['num_InvLoad'] . $modo,
                    'function_js' => "InvLoad.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/InvLoad');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/InvLoad');
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $estado = "M";
            $modo_InvLoad = $_POST['modo'];
            $modo = 'modify_user';
            $num_InvLoad = $_POST['num_InvLoad'];
            $data = array();
            if(empty($_POST['id'])){
                $modo = 'create_user';
                $estado = "N";
                if(empty($num_InvLoad)){
                   $r = InvLoadModel::getNextNumer($_POST['id_tInvLoad']);
                   $num_InvLoad = intval($r['proximo_tmoinv']);
                   $modo_InvLoad = 'P';
                }
            }
            //Encabezado de Movimiento
            //Tasa de cambio
            $tasa = str_replace(',', '.', $_POST['tasa_cambio']);
            $xasa = number_format($tasa, 8);
            try{
                $data = [
                    'id_emp' => limpiar($_POST['id_emp']),
                    'id_tInvLoad' => limpiar($_POST['id_tInvLoad']),
                    'num_InvLoad' => $num_InvLoad,
                    'fecha_comp' => ($_POST['fecha_comp']),
                    'id_moneda' => limpiar($_POST['id_moneda']),
                    'tasa_cambio' => $tasa,
                    'modo' => $modo_InvLoad,
                    'id_alm' => limpiar($_POST['id_alm']),
                    'descrip_InvLoad' => limpiar($_POST['descrip_InvLoad']),
                    'status' => limpiar($_POST['status']),
                    $modo => $_SESSION['id_user']
                ];
                //Guardar y/o actualizar encabezado
                if(empty($_POST['id'])){
                    $id = InvLoadModel::guardar($data);
                    Alertas::new(sprintf('El movimiento con el número %s se ha creado exitosamente con el id %s', $data['num_InvLoad'], $id));
                     if(empty($_POST['num_InvLoad'])){
                        $id_num_movin = InvLoadModel::setNextNumber($_POST['id_tInvLoad']);
                    }
                }else{
                    $id = InvLoadModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El movimiento con el número %s se ha actualziado exitosamente con el id %s', $data['num_InvLoad'], $_POST['id']));
                    //Borrar detalles en caso de que existan
                    $id = InvLoadModel::borrarDetInvMov($_POST['id']);
                    $id = $_POST['id'];
                }
                //Detalle de Movimiento
                $num_rows = count($_POST['id_prod']);
                $datdet = array();
                for($i=0; $i < $num_rows ; $i++){
                    $id_prod_con = InvLoadModel::cons_producto($_POST['id_prod'][$i]);
                    if($id_prod_con){
                        $id_prod = $_POST['id_prod'][$i];
                        $id_ubi = $_POST['id_ubi'][$i];
                        $lote = $_POST['lote'][$i];
                        $fec_ven = date($_POST['fec_ven'][$i]);
                        $cantidad = $_POST['cant'][$i];
                        $costo = $id_prod_con['costo_prod'];
                        $flete = $id_prod_con['flete_prod'];
                        $otros_cargos = $id_prod_con['otros_prod'];
                        $door_cargos = $id_prod_con['door_costo'];
                        $costo1 = $id_prod_con['costo_prod'] + $id_prod_con['flete_prod'] + $id_prod_con['otros_prod'] + $id_prod_con['door_costo'];
                        $create_user = $_SESSION['id_user'];
                        $datdet = [
                            'id_InvLoad' => $id,
                            'id_prod' => $id_prod,
                            'id_ubi' => $id_ubi,
                            'cantidad' => $cantidad,
                            'costo' => $costo,
                            'flete' => $flete,
                            'otros_cargos' => $otros_cargos,
                            'door_cargos' => $door_cargos,
                            'costo1' => $costo1,
                            'lote' => $lote,
                            'fec_venc' => $fec_ven,
                            'create_user' => $create_user
                        ];
                        $id_det = InvLoadModel::guardarDetMovin($datdet);
                    }
                }
                header('Location:' . base_url . '/InvLoad');
            } catch (Exception $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/InvLoad');
            }
        }
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = InvLoadModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function approve(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $dataJson = [];
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $number = $_POST['number'];
            $mov_ent = $_POST['mov_ent'];
            try {
                //Buscar proximo numero
                $r = InvLoadModel::getNextNumer($mov_ent);
                $num_movinv = intval($r['proximo_tmoinv']);
                //Buscar registro a aprobar
                $reg = InvLoadModel::showrow($id);
                //crear array de encabezado
                $enca = array();
                $modo = 'create_user';
                $descrip_movinv = 'Movimiento automatico generado desde Carga de Inventario';
                $enca = [
                    'id_emp' => limpiar($reg[0]['id_emp']),
                    'id_tmovinv' => $mov_ent,
                    'num_movinv' => $num_movinv,
                    'fecha_comp' => ($reg[0]['fecha_comp']),
                    'id_moneda' => limpiar($reg[0]['id_moneda']),
                    'tasa_cambio' => $reg[0]['tasa_cambio'],
                    'id_alm' => $reg[0]['id_alm'],
                    'descrip_movinv' => limpiar($descrip_movinv),
                    'id_cot' => 0,
                    'id_vend' => 0,
                    'id_fab' => 0,
                    'id_cli' => 0,
                    'origen' => 'INV-CO'.$reg[0]['id_emp'].'-'.$reg[0]['cod_tmoinv'].'-'.$reg[0]['num_InvLoad'],
                    'status' => limpiar($reg[0]['status']),
                    $modo => $_SESSION['id_user']
                ];
                //Guardar encabezado del movimiento
                $id_mov = InvLoadModel::guardar_mov($enca);
                //Generar detalles del movimiento
                //
                $num_rows = count($reg);
                $datdet = array();
                for($i=0; $i < $num_rows ; $i++){
                    $id_prod = InvLoadModel::cons_producto($reg[$i]['id_prod']);
                    $datdet = [
                        'id_movinv' => $id_mov,
                        'id_prod' => $reg[$i]['id_prod'],
                        'id_ubi' => $reg[$i]['id_ubi'],
                        'cantidad' => $reg[$i]['cantidad'],
                        'lote' => $reg[$i]['lote'],
                        'fec_venc' => $reg[$i]['fec_venc'],
                        'costo' => $id_prod['costo_prod'],
                        'flete' => $id_prod['flete_prod'],
                        'otros_cargos' => $id_prod['otros_prod'],
                        'door_cargos' => $id_prod['door_costo'],
                        'costo1' => $id_prod['costo_prod'] + $id_prod['flete_prod'] + $id_prod['otros_prod'] + $id_prod['door_costo'],
                        'create_user' => $_SESSION['id_user']
                    ];
                    //Guardar detalles del movimiento
                    $id_det = InvLoadModel::guardarDetMovin_mov($datdet);

                }
                //Asignar numero siguiente
                $r = InvLoadModel::setNextNumber($mov_ent);
                //Cambiar Estado de la Carga de Inventario
                $r = InvLoadModel::approve($id);
                if($r){
                    $dataJson = [
                        'status' => true,
                        'icon' => 'success',
                        'title' => 'Movimiento de Carga de Inventario Aprobado',
                        'msg' => sprintf('El Movimiento de Carga de Inventario %s  %s Número %d, se ha aprobado correctamente', $code, $name, $number)
                    ];
                }else{
                    $dataJson = [
                        'status' => false,
                        'icon' => 'warning',
                        'title' => 'Error',
                        'msg' => sprintf('El Movimiento de Carga de Invetnario %s %s Número %d, no se pudo aprobar', $code, $name, $number)
                    ];
                }
                echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                //En caso de error eliminar encabezado generado
                $r = InvLoadModel::borrar_mov($id_mov);
                Alertas::new($e->getMessage(), 'danger');
            }
        }
    }
    public function destroy(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $dataJson = [];
            $id = $_POST['id'];
            $name = $_POST['name'];
            $code = $_POST['code'];
            $number = intval($_POST['number']);
            $e = InvLoadModel::borrarDetCarInvMov($id);
            $e = InvLoadModel::borrarEncCarInvMov($id);
            if($e){
                $dataJson = [
                    'status' => true,
                    'icon' => 'success',
                    'title' => 'Registro eliminado',
                    'msg' => sprintf('El Movimiento %s  %s Número %d, se ha eliminado correctamente', $code, $name, $number)
                ];
            }else{
                $dataJson = [
                    'status' => false,
                    'icon' => 'warning',
                    'title' => 'Error',
                    'msg' => sprintf('El Movimiento %s %s Número %d, no se pudo eliminar', $code, $name, $number)
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
}