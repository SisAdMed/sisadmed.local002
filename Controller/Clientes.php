<?php
class Clientes extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(53);
    }
    public function index($tipo){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = ClientesModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Consulta de Clientes",
            'function_js' => "Clientes.js",
            'function_js_mod' => "CXCFun.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Cliente",
            'function_js' => "Clientes.js"
        ]);
    }
    public function listar_paises(){
        $objeto = ClientesModel::listar_paises();
        echo json_encode($objeto);
    }
    public function listar_estados(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_pais = $_POST['id_pais'];
            $objeto = ClientesModel::listar_estados($id_pais);
            echo json_encode($objeto, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_ciudades(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_edo = $_POST['id_edo'];
            $objeto = ClientesModel::listar_ciudades($id_edo);
            echo json_encode($objeto);
        }
    }
    public function listar_vendedores(){
        $objeto = ClientesModel::listar_vendedores();
        echo json_encode($objeto);
    }
    public function listar_clientes(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $tip_ent = $_POST['tip_ent'];
            $id_emp = $_POST['id_emp'];
            $objeto = ClientesModel::listar_clientes($tip_ent, $id_emp);
            echo json_encode($objeto);
        }
    }
    public function edit($id) {
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ClientesModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Clientes');
                }
            $this->views->getView($this, "edit", [
                'page_name' => "Editando el cliente " . $r[0]['rif_ent'] . ' - ' . html_entity_decode($r[0]['nom_ent']),
                'function_js' => "Clientes.js",
                'r' => to_obj($r),
            ]);
        } else {
            header('Location:' . base_url . '/Clientes');
        }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Clientes');
    }
    public function consulta_vend(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id_ent = $_POST['id_ent'];
            $objeto = ClientesModel::consulta_vend($id_ent);
            echo json_encode($objeto);
        }
    }
    public function listar_dpto_ent(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $objeto = ClientesModel::listar_dpto_ent();
            echo json_encode($objeto);
        }
    }
    public function listar_codigos_area(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $objeto = ClientesModel::listar_codigos_area();
            echo json_encode($objeto);
        }
    }
    public function consulta_motivo(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ClientesModel::consulta_motivo($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    //Total clientes
    public function tot_cli(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = ClientesModel::tot_cli();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    //Mostrar datos de clientes
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ClientesModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    //Llenar combp de Status de Entidad (Clientes)
    public function statusEntidad(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ClientesModel::statusEntidad($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    //Llenar combo Días de Crédito
    public function listar_dias_credito(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = ClientesModel::listar_dias_credito();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function val_ent_rif() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $rows = to_obj(ClientesModel::total_rows($id));
            $jsonData = array();
            if ($rows[0]->total !=0) {
                $jsonData['success'] = 1;
                $jsonData['message'] = 'Registro ya existe...';
            } else {
                $jsonData['success'] = 0;
                $jsonData['message'] = '';
            }
            echo json_encode($jsonData, JSON_UNESCAPED_UNICODE);
        }
    }
    //Llenar combo Tipos de Clientes agregado el 04-07-2025 a las 10:43:00 por José vargas
    public function listar_tipos_clientes(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = ClientesModel::listar_tipos_clientes();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = ClientesModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = [];
            $id = $_POST['id'];
            try {
                $r = ClientesModel::destroy($id);
                if ($r) {
                    $dataJson = [
                        'status' => true,
                        'msg' => 'Eliminado',
                        'icon' => 'success',
                        'title' => 'Registro eliminado satisfactoriamente'
                    ];
                } else {
                    $dataJson = [
                        'status' => false,
                        'msg' => 'Error',
                        'icon' => 'error',
                        'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'
                    ];
                }
            } catch (\PDOException $e) {
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'status' => false,
                    'msg' => $msg,
                    'icon' => 'error',
                    'title' => 'Error'
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modo = 'modify_user';
            $data = array();
            $dataJson = array();
            //Asignar valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if (empty($id)) {                
                $modo = 'create_user';
            }
            try {
                $data += [
                    'tip_ent' => limpiar($tip_ent),
                    'rif_ent' => limpiar($rif_ent),
                    'nom_ent' => limpiar($nom_ent),
                    'cor_ent' => limpiar($cor_ent),
                    'id_pais' => $id_pais,
                    'id_edo' => $id_edo,
                    'id_ciudad' => $id_ciudad,
                    'dir_ent' => limpiar($dir_ent),
                    'id_vend' => $id_vend,
                    'id_zona' => $id_zona,
                    'postal_ent' => limpiar($postal_ent),
                    'id_diascre' => $id_diascre,
                    $modo => $_SESSION['id_user'],
                    'note_fac' => limpiar($note_fac ?? ""),
                    'id_alm' => (isset($id_alm) && $id_alm > 0) ? $id_alm : null,
                    'id_ubi' => (isset($id_ubi) && $id_ubi > 0) ? $id_ubi : null,
                    'c_consig' => (isset($c_consig) && !empty($c_consig)) ? 1 : 0,
                    'handling_conver' => (isset($handling_conver) && !empty($handling_conver)) ? 1 : 0,
                    'print_lote' => (isset($print_lote) && !empty($print_lote)) ? 1 : 0,
                    'print_special' => (isset($print_special) && !empty($print_special)) ? 1 : 0,
                    'req_exc_rat' => (isset($req_exc_rat) && !empty($req_exc_rat)) ? 1 : 0,
                    'contr_esp' => (isset($contr_esp) && !empty($contr_esp)) ? 1 : 0,
                    'id_por_ret_iva' => (isset($id_por_ret_iva) && $id_por_ret_iva > 0) ? $id_por_ret_iva : null,
                ];
                //Si el usuarios es administrador
                if ($_SESSION['administrator'] == 1) {
                    $data += [
                        'id_motcam' => $id_motcam,
                        'id_emp' => $id_emp,
                        'id_moneda' => $id_moneda,
                        'id_tipocliente' => $id_tipocliente,
                        'status' => $status,
                    ];
                }                                
                if (empty($_POST['id'])) {
                    $id = ClientesModel::guardar($data);
                    $id_ent_con = $id;
                    $title = "Registro se ha agregado satisfactoriamente";
                    Alertas::new(sprintf('El cliente %s se ha creado exitosamente con el id %s', $data['nom_ent'], $id));
                } else {
                    $id = ClientesModel::actualizar($id, $data);
                    $id_ent_con = $id;
                    $title = "Registro se ha actualizado satisfactoriamente";
                    Alertas::new(sprintf('El cliente %s se ha modificado exitosamente con el id %s', $data['nom_ent'], $id_ent_con));
                }
                //Guardar detalles de contactos
                $data_det_cont = array();
                $iddc = ClientesModel::borrar_contactos($id_ent_con);
                if (isset($_POST['nom_con']) && $_POST['nom_con'] != null) {
                    for ($i = 0; $i < count($_POST['nom_con']); $i++) {
                        $data_det_cont = [
                            'id_ent' => $id_ent_con,
                            'nom_con' => strtoupper(limpiar($_POST['nom_con'][$i])),
                            'ape_con' => strtoupper(limpiar($_POST['ape_con'][$i])),
                            'email_con' => limpiar($_POST['email_con'][$i]),
                            'id_pre' => limpiar(intval($_POST['id_pre'][$i])),
                            'num_tel_con' => limpiar($_POST['num_tel_con'][$i]),
                            'id_dep' => limpiar(intval($_POST['id_dep'][$i])),
                            'create_user' => $_SESSION['id_user'],
                        ];
                        $iddc = ClientesModel::guardar_contactos($id, $data_det_cont);
                    }
                }
                $msg = "Se ha salvado satisfactoriamente el Cliente $rif_ent $nom_ent";
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg,                    
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
}