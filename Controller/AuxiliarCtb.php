<?php
class AuxiliarCtb extends Controller
{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(11);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $auxiliar = AuxiliarCtbModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Auxiliares contables",
            'function_js' => "AuxiliarCtb.js",
            'function_js_mod' => "CTBFun.js",
            'auxiliar' => to_obj($auxiliar)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Auxiliar contable",
            'function_js' => "AuxiliarCtb.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = AuxiliarCtbModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/AuxiliarCtb');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r['nombre_aux'],
                    'function_js' => "AuxiliarCtb.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/AuxiliarCtb');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/AuxiliarCtb');
    }
    public function delete_row(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = [];
            $id = limpiar($_POST['id']);
            $cod_aux = limpiar($_POST['cod_aux']);
            try {
                $r = AuxiliarCtbModel::borrar($id,$cod_aux);
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
                        'title' => 'No se puede eliminar el registro ya que posee registros hijos'
                    ];
                }
            } catch (\PDOException $e) {
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
                $title = "Se ha presentado un error, intente luego";
                $dataJson = [
                    'status' => false,
                    'title' => $title,
                    'icon' => 'error',
                    'msg' => $msg
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
            //Asignar valroes a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if (empty($_POST['id'])) {
                $modo = 'create_user';
            }
            try {
                $data += [
                    'cod_aux' => $cod_aux,
                    'nombre_aux' => $nombre_aux,
                    'agrupa_aux' => $agrupa_aux,
                    'status_aux' => $status_aux,
                    $modo => $_SESSION['id_user'],
                ];
                if (empty($_POST['id'])) {
                    $id = AuxiliarCtbModel::guardar($data);
                    $msg = sprintf('El  Auxiliar Contable Código %s con la descripción %s, se ha creado exitosamente con el id %s', $cod_aux, $nombre_aux, $id);
                } else {
                    $id = AuxiliarCtbModel::actualizar($_POST['id'], $data);
                    $msg = sprintf('El Auxiliar Contable Código %s con la descripción %s, se ha modificado exitosamente con el id %s', $cod_aux, $nombre_aux, $_POST['id']);
                }
                $dataJson = [
                    'status' => true,
                    'title' => 'Operación exitosa',
                    'icon' => 'success',
                    'msg' => $msg
                ];
            } catch (\PDOException $e) {
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
                $title = "Se ha presentado un error, intente luego";
                $dataJson = [
                    'status' => false,
                    'title' => $title,
                    'icon' => 'error',
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_aux_ctbles(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idAux = AuxiliarCtbModel::listar_aux_ctbles();
            echo json_encode($idAux, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_aux_ctbles_mod(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_emp = $_POST['id_emp'];
            $mod = $_POST['mod'];
            $idAux = AuxiliarCtbModel::listar_aux_ctbles_mod($id_emp, $mod);
            echo json_encode($idAux, JSON_UNESCAPED_UNICODE);
        }
    }
    public function datosAux(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idAux = AuxiliarCtbModel::edit($_POST['id']);
            echo json_encode($idAux, JSON_UNESCAPED_UNICODE);
        }
    }
    /**
     * Description: Valida los auxiliares y cuentas antes de ser creadas y validar si usan padres
     */
    public function val_agrupador(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $codigo = $_POST['codigo'];
            $r = AuxiliarCtbModel::validar_cod_aux($codigo);
            if (!$r) {
                echo json_encode($r, JSON_UNESCAPED_UNICODE);
            } else if ($r) {
                echo json_encode(true, JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode(false, JSON_UNESCAPED_UNICODE);
            }
        }
    }
    public function modal_AuxiliarCtb(){
        $r = AuxiliarCtbModel::modal_AuxiliarCtb();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
    public function nom_aux($id){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_aux = $_POST['id_aux'];
            $r = AuxiliarCtbModel::nom_aux($id_aux);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = AuxiliarCtbModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $id = $_POST["id"];
            $r = AuxiliarCtbModel::edit($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
