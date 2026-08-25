<?php
class Tutorials extends Controller {
    public function __construct() {
        parent::__construct();
        session_start();
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url . '/login');
        }
        Auth::noAuth();
        Permisos::getPermisos(203);
    }

    public function index() {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $this->views->getView($this, "index", [
            'page_name' => "Consulta de Tutoriales",
            'function_js' => "Tutorials.js?v=" . SITE_VERSION,
        ]);
    }
    public function nuevo() {        
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }        
        $this->views->getView($this, "new", [
            'page_name' => "Nuevo Tutorial",
            'function_js' => "Tutorials.js?v=" . SITE_VERSION,
        ]);        
    }
    public function gestion($token = null) {
        if (!$token) {
            return;
        }
        $datos = desencriptar_url($token);
        $accion = $datos['accion'];
        switch ($accion) {
            case 'edit':
                $this->edit($datos['id']);
                break;
            default:
                break;
        }
    }
    public function edit(int $id) {
        $r = TutorialsModel::edit($id);
        $this->views->getView($this, "edit", [
            'page_name' => "Editar Tutorial" . htmlspecialchars($r['titulo']),
            'function_js' => "Tutorials.js?v=" . SITE_VERSION,
            'r' => to_obj($r)
        ]);
    }
    public function show_row() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = TutorialsModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos_tabla = [];
            $r = TutorialsModel::cargar_screen_main();
            // Creamos los tokens para cada acción
            foreach ($r as $p) {
                $datos_tabla[] = array_merge($p, [
                    "token_edit" => encriptar_url(json_encode(['accion' => 'edit', 'id' => $p['id']]))
                ]);
            }
            echo json_encode($datos_tabla, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {            
            $data = array();
            $dataJson = array();
            //Asignar valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            $modu = 'modify_user';
            $modd = 'modify_date';
            if (empty($id)) {
                $modu = 'create_user';
                $modd = 'create_date';
            }
            try {
                $data += [
                    'titulo' => htmlspecialchars($titulo),
                    'url' => htmlspecialchars($url),
                    'resumen' => htmlspecialchars($resumen),
                    'contenido' => htmlspecialchars($contenido),
                    'fecha' => $fecha,
                    'view_internet' => !empty($view_internet) ? 1 : 0,
                    'status' => $status,
                    $modu => $_SESSION['id_user'],
                    $modd => getAuditoria()
                ];                
                if (empty($id)) {
                    $id = TutorialsModel::guardar($data);                    
                    $title = "Tutorial Registrado";
                    $msg = "El tutorial ha sido registrado exitosamente.";
                } else {
                    $id = TutorialsModel::actualizar($data, $id);
                    $id = $_POST['id'];
                    $title = "Tutorial Actualizado";
                    $msg = "El tutorial ha sido actualizado exitosamente.";
                }
                $icon = "success";
                //Almacenar imagen destacada                       
                if (isset($_FILES['nuevaImagen']) && $_FILES['nuevaImagen']['error'] === UPLOAD_ERR_OK) {
                    $nombreArchivo = $_FILES['nuevaImagen']['name'];
                    $ruta = ROOT . DS .  'Assets' . DS . 'img' . DS . 'tutorials';
                    if (!is_dir($ruta)) {
                        mkdir($ruta, 0755, true);
                        chmod($ruta, 0777);
                    }
                    $log_ent = TutorialsModel::getImageNew($id);
                    $logo_ent = $log_ent['logo_ent'];
                    if ($logo_ent) {
                        $rutalogo = ROOT .  $logo_ent;
                        if (file_exists(($rutalogo))) {
                            unlink($rutalogo);
                        }
                    }
                    $nuevoNombre =  $_FILES['nuevaImagen']['name'];
                    $ruta = ROOT . DS .  'Assets' . DS . 'img' . DS . 'tutorials';
                    if (move_uploaded_file($_FILES['nuevaImagen']['tmp_name'], $ruta . DS . $nuevoNombre)) {
                        $data = [
                            'imagen' => $_FILES['nuevaImagen']['name'],
                        ];
                        TutorialsModel::actualizar($data, $id);
                    }
                }
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg,
                ];
            } catch (\PDOException $e) {
                debug($e->getMessage());
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
    public function destroy(){ 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $dataJson = array();
            $title1 = '';            
            try {
                $r = TutorialsModel::destroy($id);                      ;
                $title1 = "Registro eliminado";
                $msg = "El tutorial " . $title . ' se ha eliminado satisfactoriamente';
                $icon = "success";
                if($r === 0){
                    $title1 = "Error elimiando registro";
                    $icon = "error";
                    $msg = "La noticia $title que desea eliminar se encuentra publicada en la web, debe desmarcar y vovler a intentar eliminar";
                }                
                $dataJson = [
                    'title' => $title1,
                    'icon' => $icon,
                    'msg' => $msg
                ];
            } catch (\PDOException $e) {
                $title1 = "Se ha presentado un error, intente luego";
                $msg = sprintf("Error códoigo: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => $title1,
                    'icon' => "error",
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
}
