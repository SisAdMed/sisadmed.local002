<?php
class Empresas extends Controller
{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(22);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = EmpresasModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Empresas",
            'function_js' => "Empresas.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva empresa",
            'function_js' => "Empresas.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = EmpresasModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Empresas');
                }
                //mostrar registro
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r['nombre_emp'],
                    'function_js' => "Empresas.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Empresas');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Empresas');
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
            $ide = EmpresasModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El rol %s se ha eliminado correctamente', $_POST['name'])
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function listar_empresas(){
        $r = EmpresasModel::listar_empresas();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
    public function print_test(){
        $this->views->getView($this, "test_email");
        header('Location:' . base_url . '/Empresas');
    }
    public function show_row(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = EmpresasModel::edit($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_zona_fiscal(){
        $r = EmpresasModel::listar_zona_fiscal();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
    public function tfechas_emp(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $r = EmpresasModel::edit($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_modulos(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $mod = implode(',', $_POST['mod']);
            $r = EmpresasModel::listar_modulos($mod);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $r = EmpresasModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {        
            $modo = "modify_user";
            $data = array();
            //Asignar valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if (empty($id)) {
                $modo = "create_user";
            }            
            try {
                //Almacenar imagenes
                if (isset($_FILES['url_photo']) && (count($_FILES['url_photo']['tmp_name']) != 0)) {
                    if ($_FILES['url_photo']['type'] == 'image/png' || $_FILES['url_photo']['type'] == 'image/jpeg') {
                        //Subimes el fichero al servidor
                        $nombreDelArchivo = $_FILES["url_photo"]["name"];
                        $ext = pathinfo($nombreDelArchivo, PATHINFO_EXTENSION);
                        $nombreDelArchivo = $id . '-' . $ext;
                        $ruta = ROOT . DS . 'Assets' . DS . 'img' . DS . 'companies';
                        $ruta = $ruta . DS . $nombreDelArchivo;
                        move_uploaded_file($_FILES['url_photo']['tmp_name'], $ruta);
                        $data += [
                            'logo' => $nombreDelArchivo ?? "",
                        ];
                    }
                }
                $data += [
                    'cod_emp' => limpiar($cod_emp),
                    'nombre_emp' => limpiar($nombre_emp),
                    'rif_empresa' => $rif_empresa,
                    'dir_emp' => limpiar($dir_emp),
                    'id_pais' => $id_pais,
                    'tel_emp' => limpiar($tel_emp),
                    'email_emp' => $email_emp,
                    'id_moneda' => $id_moneda,
                    'status' => $status,
                    $modo => $_SESSION['id_user'],
                    'host' => limpiar($host),
                    'usuario' => limpiar($usuario),
                    'pass_email' => limpiar($pass_email),
                    'puerto_send' => limpiar($puerto_send),
                    'fec_ini_fis' => $fec_ini_fis,
                    'fec_fin_fis' => $fec_fin_fis,
                    'fec_ctb' => $fec_ctb,
                    'fec_ban' => $fec_ban,
                    'fec_cxc' => $fec_cxc,
                    'fec_cxp' => $fec_cxp,
                    'fec_nom' => $fec_nom,
                    'id_iva' => $id_iva,
                    'especial_contrib' => $especial_contrib,
                    'iva_deb_fis' => $iva_deb_fis,
                    'iva_cre_fis' => $iva_cre_fis
                ];
                if (empty($id)) {
                    $r = EmpresasModel::guardar($data);
                    $title = "Registro se ha agregado satisfactoriamente";
                } else {
                    $r = EmpresasModel::actualizar($id, $data);
                    $title = "Registro se ha modificado satisfactoriamente";
                }
                $msg = "Se ha salvado satisfactoriamente la Empresa $cod_emp $nombre_emp";
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
    public function get_empresa_config(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id_emp = $_POST['id_emp'];
            $r = EmpresasModel::get_empresa_config($id_emp);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
