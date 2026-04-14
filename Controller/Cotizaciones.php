<?php
include_once VARTAX;
class Cotizaciones extends Controller {
    public function __construct() {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(56);
    }
    public function index() {
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = CotizacionesModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Cotizaciones',
            'function_js' => 'Cotizaciones.js',
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo() {
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva Cotización',
            'function_js' => 'Cotizaciones.js',
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            //Asignar Valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if(empty($id)){
                $modo = 'create_user';
                $r = to_obj(CotizacionesModel::nextNumber(($id_emp), $id_tdo));
                if($r->con_tdoc == 1 || $r->con_tdoc = 'S'){
                    $data += [
                        'num_tdo' => $r->num_tdoc,
                        'id_tdo' => $id_tdo,
                    ];
                    $num_tdo = $r->num_tdoc;
                }else{
                    $data += ['id_cod' => $id];
                }
            }
            if(isset($id_fab)){
                $data += ['id_fab' => $id_fab];
            }
            
            $xtasa = convert_string_to_number($tasa_cambio);
            //$xtasa = number_format($xtasa, 8);
            try {
                $data += [
                    'id_emp' => $id_emp,
                    'id_cli' => $id_cli,
                    'fecha_comp' => $fecha_comp,
                    'id_moneda' => $id_moneda,
                    'tasa_cambio' => $xtasa,
                    'id_vend' => $id_vend,
                    'observa' => $observa,
                    'status'=> 1,
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($id)){
                    //Guardar encabezado de Cotización
                    $id = CotizacionesModel::guardar($data);
                    if($id){
                        $id_cot = $id;
                        $data = array();
                        $data = [
                            'num_tdoc' => $num_tdo + 1,
                            $modo => $_SESSION['id_user']
                        ];
                        //Actualizar next Number de Cotización
                        $upd_next = CotizacionesModel::setNextNumber($id_emp, $id_tdo, $data);
                        //Rgistro guardado
                        $msg = sprintf('Cotización número %s creado satisfactoriamente', $num_tdo);
                        $title = 'Registro agregado';
                    }
                }else{
                    $id_det = CotizacionesModel::actualizar($id, $data);
                    $id_det = CotizacionesModel::borrarDetCotizacion($id);
                    $msg = sprintf('Cotización número %s modificada satisfactoriamente', $num_tdo);
                    $title = 'Registro agregado';
                }
                //Verificar Tasa de IVA
                $xvatTax = xvatTax($fecha_comp, 'IVA');
                $tasa_iva = $xvatTax[0]['txr1_iva'];
                //Guardar Detalles de Cotización
                $tot_row = count($_POST['id_prod']);
                for($i = 0; $i < $tot_row; $i++){
                    $pre_unit = convert_string_to_number($_POST['ventas_prod'][$i]);
                    $pre_vta = convert_string_to_number($_POST['ventas_prod1'][$i]);
                    $sub_total = convert_string_to_number($_POST['total'][$i]);
                    $id_prod = $_POST['id_prod'][$i];
                    $can_det = $_POST['cant'][$i];
                    $uni_vta = $_POST['uni_ven_prod'][$i];
                    $iva_prod = $_POST['iva_prod'][$i];
                    $pre_unit = $pre_vta / $uni_vta;
                    $mon_iva = 0;
                    if($iva_prod == 'S'){
                        $mon_iva = $pre_vta * floatval($tasa_iva);
                    }
                    $tota_prod = $sub_total + $mon_iva;
                    $data = [
                        'id_cot' => $id,
                        'id_prod' => $id_prod,
                        'can_det' => $can_det,
                        'uni_vta' => $uni_vta,
                        'pre_unit' => round($pre_unit, 2),
                        'pre_vta' => round($pre_vta, 2),
                        'iva_prod' => $iva_prod,
                        'sub_total' => round($sub_total, 2),
                        'mon_iva' => round($mon_iva, 2),
                        'tota_prod' => round($tota_prod, 2),
                        $modo => $_SESSION['id_user'],
                    ];
                    $id_det = CotizacionesModel::guardarDetCotizacion($data);
                }
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg
                ];
            } catch (\PDOException $e) {
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
      if(Permisos::read()){
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = CotizacionesModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/Cotizaciones');
            }
            $this->views->getView($this, "edit", [
               'page_name' => "Editando la cotización Nro. " . $r['num_tdo'],
               'function_js' => "Cotizaciones.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/Cotizaciones');
         }
          return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Cotizaciones');
   }
   public function consultar_cotizacion(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id_cot'];
            $r = CotizacionesModel::edit_deta($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
   }
   public function consulta_adic01(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $fecha_precio = $_POST['fecha_precio'];
            $objeto = CotizacionesModel::consulta_adic01($id_emp, $fecha_precio);
            echo json_encode($objeto);
        }
   }
   public function consulta_adic02(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_cli = $_POST['id_cli'];
            $objeto = CotizacionesModel::consulta_adic02($id_cli);
            echo json_encode($objeto);
        }
   }
   public function print_cotiza($id){
      if(Permisos::read()){
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = CotizacionesModel::printer_cotiza($id);
            if (empty($r)) {
                Alertas::new('El registro no existe', 'warning');
                header('Location:' . base_url . '/Cotizaciones');
            }
            $this->views->getView($this, "print_cotiza_new", [
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/Cotizaciones');
         }
         return;
      }
      //Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      //header('Location:' . base_url . '/Cotizaciones');
   }
   public function print_cotiza_foranea($id_ori){
      if(Permisos::read()){
        $idori = explode('|', $id_ori);
        $ori = $idori[1];
         $id = intval(limpiar($idori[0]));
         if ($id > 0) {
            $r = CotizacionesModel::printer_cotiza($id);
            if (empty($r)) {
                Alertas::new('El registro no existe', 'warning');
                header('Location:' . base_url . '/Cotizaciones');
            }
            $report = "";
            if($ori==1){ 
                $report = 'print_cotiza_foranea';
            }else{
                $report = 'print_cotiza_foranea_ex';
         }
            $this->views->getView($this, $report, [
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/Cotizaciones');
         }
         //return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Cotizaciones');
   }
    //Exportar a Excel Cotización
    public function print_cotiza_excel($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CotizacionesModel::printer_cotiza($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Cotizaciones');
                }
                $this->views->getView($this, "print_cotiza_excel", [
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Cotizaciones');
            }
            return;
        }
        //Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        //header('Location:' . base_url . '/Cotizaciones');
    }
   public function destroy(){
        $dataJson = [];
        $id = intval(limpiar($_POST['id']));
        $num_tdo = $_POST['num_tdo'];
        $ide = CotizacionesModel::borrar($id);
        if($ide) {
            $dataJson = [
                    'status' => true,
                    'type' => 'success',
                    'msg' => sprintf('La Cotización número %s, con el Id %s se ha eliminado correctamente', $num_tdo, $id)
                ];
        }else{
            $dataJson = [
                    'status' => false,
                    'type' => 'warning',
                    'msg' => sprintf('La Cotización número %s con el Id %s no se pudo eliminar ya que posee un Documento asociado', $num_tdo, $id)
                ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function create_express(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_fab = implode(',', $_POST['id_fab']);
            $id_ent = $_POST['id_ent'];
            $objeto = CotizacionesModel::create_express($id_fab, $id_ent);
            echo json_encode($objeto, JSON_UNESCAPED_UNICODE);
        }
   }
   public function listar_cotizacones(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_emp = $_POST['id_emp'];
            $r = CotizacionesModel::listar_cotizacones($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
   }
   public function listar_entidad_modal(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $tipo = $_POST['tipo'];
            $id = $_POST['id'];
            $r = CotizacionesModel::listar_entidad_modal($id, $tipo);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = CotizacionesModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}