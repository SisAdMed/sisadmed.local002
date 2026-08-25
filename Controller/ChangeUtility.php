<?php
class ChangeUtility extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(204);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $data = [
            'page_name' => "Consulta de Cambio de Utilidad Masiva",
            'function_js' => "ChangeUtility.js?v=" . SITE_VERSION,
            'function_js_mod' => "INVFun.js?v=" . SITE_VERSION,
        ];
        $this->views->getView($this, 'index', $data);
    }
    public function nuevo()
    {
        $data = [
            'page_name' => "Nuevo Cambio de Utilidad Masiva",
            'function_js' => "ChangeUtility.js?v=" . SITE_VERSION,
            'function_js_mod' => "INVFun.js?v=" . SITE_VERSION,
        ];
        $this->views->getView($this, 'new', $data);
    }
    public function edit(string $idn)
    {
        $valor = '';
        $status = explode("-", $idn);
        $id = $status[0];
        if (isset($status[1])) {
            $valor = $status[1];
        }
        $id = intval(limpiar($id));
        if ($id > 0) {
            $r = ChangeUtilityModel::edit($id);
            if (empty($r)) {
                Alertas::new('El registro no existe', 'warning');
                header('Location:' . base_url . '/ChangeUtility');
            }
            $fechaBD = new DateTime($r[0]['fecha']);
            $this->views->getView($this, "edit", [
                'page_name' => "Editando el registro " . $fechaBD->format('d-m-Y h:i A'),
                'function_js' => "ChangeUtility.js?v=" . SITE_VERSION,
                'function_js_mod' => "INVFun.js?v=" . SITE_VERSION,
                'valor' => $r[0]['id']
            ]);
        } else {
            header('Location:' . base_url . '/ChangeUtility');
        }
        return;
    }
    public function cargar_screen_main()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos_tabla = [];
            //Buscar rwos
            $r = ChangeUtilityModel::cargar_screen_main();
            //Crear los tokens
            foreach ($r as $p) {
                $datos_tabla[] = array_merge(
                    $p,
                    [
                        'token_edit' => encriptar_url((json_encode(['accion' => 'edit', 'id' => $p['id']])))
                    ]
                );
            }
            echo json_encode($datos_tabla, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store_utilidad()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $dataJson = [];
            $registros = json_decode($_POST['registros'], true);
            $id = $_POST['id'];
            try {
                $r = ChangeUtilityModel::store_utilidad($id, $registros);
                $dataJson = [
                    'status' => true,
                    'icon' => 'success',
                    'title' => 'Guardado!',
                    'msg' => 'Registros actualizados satisfactoriamente',
                ];
            } catch (Exception $e) {
                $dataJson = [
                    'status' => false,
                    'icon' => 'error',
                    'title' => 'Error!',
                    'msg' => $e->getMessage()
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function gestion($token = null)
    {
        if (!$token) {
            return;
        }
        $datos = desencriptar_url($token);
        switch ($datos['accion']) {
            case 'edit':
                $this->edit($datos['id']);
                break;
            default:
                // Acción no permitida
                break;
        }
    }
    public function show_row()
    {
        if ($_SERVER['REQUEST_METHOD']) {
            $id = $_POST['id'];
            $r = ChangeUtilityModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row_det()
    {
        if ($_SERVER['REQUEST_METHOD']) {
            $id = $_POST['id'];
            $r = ChangeUtilityModel::show_row_det($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy()
    {
        $id = intval(limpiar($_POST['id']));
        $code = $_POST['code'];
        $dataJson = [];
        try {
            $r = ChangeUtilityModel::destroy($id);
            $title = "Registro guardado satisfactoriamente";
            $fechaBD = new DateTime($code);
            $msg = sprintf('El registro %s, de fecha %s se ha eliminado correctamente', $id, $fechaBD->format('d-m-Y h:i:s '));
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
    public function approve()
    {
        $id = intval(limpiar($_POST['id']));
        $code = $_POST['code'];
        $dataJson = [];
        try {
            $r = ChangeUtilityModel::approve($id);
            $title = "Registro aprobado satisfactoriamente";
            $fechaBD = new DateTime($code);
            $msg = sprintf('El registro %s, de fecha %s se ha aprobado correctamente', $id, $fechaBD->format('d-m-Y h:i:s '));
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
