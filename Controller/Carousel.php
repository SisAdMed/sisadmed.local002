<?php
class Carousel extends Controller {
    public function __construct() {
        parent::__construct();        
        if (empty($_SESSION['login'])) {
            header('Location: ' . base_url . '/login');
        }
        Auth::noAuth();
        Permisos::getPermisos(206);
    }

    public function index() {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $this->views->getView($this, "index", [
            'page_name' => "Consulta de Carousel",
            'function_js' => "Carousel.js?v=" . SITE_VERSION,
        ]);
    }
    public function nuevo() {        
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }        
        $this->views->getView($this, "new", [
            'page_name' => "Nuevo Carousel",
            'function_js' => "Carousel.js?v=" . SITE_VERSION,
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
        $r = CarouselModel::edit($id);
        $r1 = CarouselModel::getImageNew($id);
        $directorio = DS .  'Assets' . DS . 'img' . DS . 'carousel' . DS; 
        $this->views->getView($this, "edit", [
            'page_name' => "Editar Carousel" . htmlspecialchars($r['titulo']),
            'function_js' => "Carousel.js?v=" . SITE_VERSION,
            'r' => to_obj($r),
            'imagenesExistentes' => $r1,
            'directorio' => $directorio,
        ]);
    }
    public function show_row() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = CarouselModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function cargar_screen_main() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos_tabla = [];
            $r = CarouselModel::cargar_screen_main();
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
                    'fecha' => $fecha,
                    'view_internet' => !empty($view_internet) ? 1 : 0,                                                            
                    'status' => $status,
                    $modu => $_SESSION['id_user'],
                    $modd => getAuditoria()
                ];        
                if (empty($id)) {
                    $id = CarouselModel::guardar($data);                    
                    $title = "Carousel Registrado";
                    $msg = "El carousel ha sido registrado exitosamente.";
                } else {
                    $id = CarouselModel::actualizar($data, $id);
                    $id = $_POST['id'];
                    $title = "Carousel Actualizado";
                    $msg = "El carousel ha sido actualizado exitosamente.";
                }             
                $dataJson = [
                    'title' => $title,
                    'icon' => 'success',
                    'msg' => $msg,
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
    public function destroy(){ 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {            
            $id = $_POST['id'];
            $title = $_POST['title'];
            $dataJson = array();
            $title1 = '';                        
            try {                              
                $r = CarouselModel::destroy($id);                
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
    public function crearTablas(){        
        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();
        header('Content-Type: text/html; charset=utf-8');
        try {
            // ----------------------------------------------------
            // TABLA 1: Ejemplo Maestra (f0028)
            // ----------------------------------------------------
            $sql_f0028 = "CREATE TABLE IF NOT EXISTS `f0028` (
                `id` int NOT NULL AUTO_INCREMENT COMMENT 'Id Registro',
                `titulo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Titulo',
                `subtitulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sub Titulo',
                `fecha` date NOT NULL COMMENT 'Fecha',
                `status` tinyint(1) DEFAULT '1' COMMENT 'Status',
                `view_internet` int NOT NULL COMMENT 'Ver en Internet',
                `create_user` int DEFAULT NULL COMMENT 'Creado por',
                `create_date` datetime DEFAULT NULL COMMENT 'Creado el',
                `modify_user` int DEFAULT NULL COMMENT 'Modificado por',
                `modify_date` datetime DEFAULT NULL COMMENT 'Modificado el',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            // ----------------------------------------------------
            // TABLA 2: Ejemplo Detalle (f00281)
            // ---------------------------------------------------- 
            $sql_f00281 = "CREATE TABLE IF NOT EXISTS `f00281` (
                `id` int NOT NULL AUTO_INCREMENT,
                `carrusel_id` int NOT NULL,
                `imagen` varchar(255) NOT NULL,
                `mensaje_izq` varchar(255) DEFAULT NULL,
                `mensaje_der` varchar(255) DEFAULT NULL,
                `orden` int DEFAULT '0',
                `create_user` int DEFAULT NULL,
                `create_date` datetime DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `f0028_id` (`carrusel_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

            // 2. Ejecutar DDL con exec()
            $link->exec($sql_f0028);    
            echo "✔ Tabla 1 creada o verificada correctamente.<br>";

            $link->exec($sql_f00281);
            echo "✔ Tabla 2 creada o verificada correctamente.<br>";

            echo "<br><b>¡Migración completada con éxito!</b>";

        } catch (\PDOException $e) {
            echo "Error al crear las tablas: " . $e->getMessage();
        }
    }
}
