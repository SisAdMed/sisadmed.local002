<?php
class Grupos extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(176);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = GruposModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta de Grupo',
            'function_js' => 'Grupos.js',
            'function_js_mod' => 'INVFun.js',
            'objeto' => to_obj($objeto)
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, 'new', [
            'page_name' => 'Nuevo registro de Grupo',
            'function_js' => 'Grupos.js',
            'function_js_mod' => 'INVFun.js'
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = GruposModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Grupos');
                }
                $this->views->getView($this, 'edit', [
                    'page_name' => 'Modificando el registro de Grupo',
                    'function_js' => 'Grupos.js',
                    'function_js_mod' => 'INVFun.js',
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/Grupos');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Grupos');
    }
    public function store()
    {        
        $modo = 'modify_user';
        $data = array();
        $id = $_POST['id'];
        $eliminarPdf         = isset($_POST['eliminar_pdf']) && $_POST['eliminar_pdf'] == '1';
        $archivoActualNombre = isset($_POST['archivo_actual_nombre']) ? trim($_POST['archivo_actual_nombre']) : '';
        $nombrePdfFinal = $archivoActualNombre; // Mantener el actual por defecto
        $grupo_codigo = $_POST['grupo_codigo'];
        $grupo_nombre = strtoupper($_POST['grupo_nombre']);
        $status = $_POST['status'];
        $user = $_SESSION['id_user'];
        $view_internet = (isset($_POST['view_internet']) && !empty($_POST['view_internet'])) ? 1 : 0;
        $descripcion = htmlspecialchars_decode($_POST['descripcion'] ?? '');
        $catalogo = htmlspecialchars_decode($_POST['catalogo'] ?? '');
        $id_fab = implode(',', $_POST['id_fab'] ?? []); // Convertir el array en una cadena separada por comas
        $data = [
            'grupo_codigo' => $grupo_codigo,
            'grupo_nombre' => $grupo_nombre,
            'status' => $status,
            'view_internet' => $view_internet,
            'icono' => $_POST['icono'] ?? '',
            'descripcion' => $descripcion,
            'id_fab' => $id_fab,
            'catalogo' => $catalogo,
        ];        
        if (empty($id)) {
            $modo = 'create_user';
            $data += [
                $modo => $user,
                'create_date' => getAuditoria(),
            ];
            $r = GruposModel::guardar($data);
            $id = $r;
            $msg = sprintf('Registro Código %s con la Descripción %s creado satisfactoriamente', $grupo_codigo, $grupo_nombre);
            $title = 'Registro agregado';
        } else {
             // Caso A: El usuario presionó el botón Quitar y NO subió un archivo nuevo
            if ($eliminarPdf && empty($_FILES['ruta_catalogo']['name'])) {
                if (!empty($archivoActualNombre)) {
                    $rutaFisica = ROOT . DS . 'Assets' . DS . 'pdf' . DS . 'groups' . DS . $archivoActualNombre;
                    if (file_exists($rutaFisica)) {
                        unlink($rutaFisica); // Borra el archivo físico del disco
                    }
                }
                $nombrePdfFinal = null; // O cadena vacía '' según tu esquema de BD
            }
            $data += [
                'ruta_catalogo' => $nombrePdfFinal,
                $modo => $user,
                'modify_date' => getAuditoria(),
            ];
            $r = GruposModel::actualizar($id, $data);
            $msg = sprintf('Registro Código %s con la Descripción %s actualizado satisfactoriamente', $grupo_codigo, $grupo_nombre);
            $title = 'Registro modificado';
        }       
        //Gaurdar documento PDF
        //Almacenar imagen destacada                       
        if (isset($_FILES['ruta_catalogo']) && $_FILES['ruta_catalogo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath   = $_FILES['ruta_catalogo']['tmp_name'];
            $fileName      = $_FILES['ruta_catalogo']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            //Validar que sea un archivo PDF
            // 1. Validar que la extensión sea PDF
            if ($fileExtension !== 'pdf') {
                echo json_encode(['status' => false, 'message' => 'Solo se permiten archivos PDF.']);
                exit;
            }
            // 2. Definir datos dinámicos (pueden venir de $_POST o sesión)
            $codigoProducto = 'CAT';
            $idRegistro     = $grupo_codigo;
            $timestamp      = date('Ymd_His');
            // Opción A: Nombre con Código + ID + Fecha/Hora (Legible y ordenado)
            // Resultado ej: CAT_250100206_ID15_20260819_123500.pdf
            $nombreDinamico = "{$codigoProducto}_ID{$idRegistro}_{$timestamp}.{$fileExtension}";
            $ruta = ROOT . DS .  'Assets' . DS . 'pdf' . DS . 'groups' . DS;
            if (!is_dir($ruta)) {
                mkdir($ruta, 0755, true);
                chmod($ruta, 0777);
            }
            $log_ent = GruposModel::getDocumentNew($id);            
            $doc_pdf = $log_ent['ruta_catalogo'];
            if ($doc_pdf) {
                $ruta_catalogo = $ruta .  $doc_pdf;
                if (file_exists(($ruta_catalogo))) {
                    unlink($ruta_catalogo);
                }
            }
            $nuevoNombre = $nombreDinamico;
            if (move_uploaded_file($_FILES['ruta_catalogo']['tmp_name'], $ruta . $nuevoNombre)) {
                $data = [
                    'ruta_catalogo' => $nuevoNombre,
                ];
                GruposModel::actualizar($id, $data);
            }
        }
        if ($r) {
            $dataJson = [
                'status' => true,
                'title' => $title,
                'icon' => 'success',
                'msg' => $msg
            ];
        } else {
            $dataJson = [
                'status' => false,
                'title' => $title,
                'icon' => 'error',
                'msg' => 'Error al momento de crar y/o actualizar el registro, por favor inente luego',
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);       
    }
    public function destroy()
    {
        $dataJson = [];
        if (empty($_POST['id'])) {
            $dataJson = [
                'status' => false,
                'msg' => 'No se recibieron los datos'
            ];
        } else {
            $id = intval(limpiar($_POST['id']));
            $ide = GruposModel::borrar($id);
            $dataJson = [
                'status' => true,
                'msg' => sprintf('El registro %s se ha eliminado correctamente',)
            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function next_codigo()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = GruposModel::next_codigo();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = GruposModel::edit($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function delete_row()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = GruposModel::borrar($id);
            if ($r) {
                $dataJson = ['status' => true, 'msg' => 'Eliminado', 'icon' => 'success', 'title' => 'Registro eliminado satisfactoriamente'];
            } else {
                $dataJson = ['status' => false, 'msg' => 'Error', 'icon' => 'error', 'title' => 'Se generó un error al eliminar el registro. Favor ponerse en contacto con el Administrador del Sistema'];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function getGrupos()
    {
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $r = GruposModel::getGrupos();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
