<?php

/**
 * Clase para los metodos de Conceptos de Nómina
 * Creado por José Vargas
 * Fecha: 23-10-2024
 * Hora: 14:33:00
 * Corporación MMQ
 */
class NomCon extends Controller
{
    function __construct()
    {
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(144);
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = NomConModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Listado Conceptos de Nómina",
            'function_js' => "NomCon.js",
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo()
    {
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Concepto de Nómina",
            'function_js' => "NomCon.js"
        ]);
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $modo = 'modify_user';
            $data = array();
            if (empty($_POST['id'])) {
                $modo = 'create_user';
            }
            try {
                $factop = 0;
                if (isset($_POST['factop'])) {
                    $factop = str_replace(',', '.', $_POST['factop']);
                }
                $nomuni = '';
                if (isset($_POST['nomuni'])) {
                    $nomuni = $_POST['nomuni'];
                }
                $data = [
                    'codigo' => limpiar($_POST['codigo']),
                    'nombre' => limpiar($_POST['nombre']),
                    'tipo' => $_POST['tipo'],
                    'parametro' => $_POST['parametro'],
                    'factop' => $factop,
                    'nomuni' => $nomuni,
                    'nomfju' => $_POST['nomfju'],
                    'id_ctb' => $_POST['id_ctb'],
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user'],

                ];
                if (empty($_POST['id'])) {
                    $id_nomcue = NomConModel::guardar($data);
                    Alertas::new(sprintf('El concepto %s - %s, se ha creado exitosamente', $_POST['codigo'], $_POST['nombre']));
                } else {
                    $id_nomcue = NomConModel::actualizar($_POST['id'], $data);
                    $id_nomcue = $_POST['id'];
                    //Borrar cuentas de integración actuales
                    $id_del_nomcue = NomConModel::borrarConcepInteNom($id_nomcue);
                    Alertas::new(sprintf('El concepto %s - %s, se ha modificado exitosamente', $_POST['codigo'], $_POST['nombre']));
                }
                //Cargar si tiene cuentas de integración
                if (isset($_POST['id_nomcue_int'])) {
                    $tot_rows = count($_POST['id_nomcue_int']);
                    $modo = 'create_user';
                    //
                    \array_splice($data, 0, count($data));
                    for ($i = 0; $i < $tot_rows; $i++) {
                        $id_nomcue_int = $_POST['id_nomcue_int'][$i];
                        if ($id_nomcue_int != '') {
                            $data = [
                                'id_nomcue' => $id_nomcue,
                                'id_nomcue_int' => $id_nomcue_int,
                                $modo => $_SESSION['id_user'],
                            ];
                            $id_det_nomcue = NomConModel::guardarConcepInt($data);
                        }
                    }
                }
                header('Location: ' . base_url . '/NomCon');
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location: ' . base_url . '/NomCon');
            }
        }
    }
    public function modal_ConceptosNOM()
    {
        $r = NomConModel::modal_ConceptosNOM();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
    public function nom_conceptoNOM($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_nomcue_int = $_POST['id_nomcue_int'];
            $r = NomConModel::nom_conceptoNOM($id_nomcue_int);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = NomConModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/NomCon');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el concepto " . $r[0]['codigo'] . ' - ' . $r[0]['nombre'],
                    'function_js' => "NomCon.js",
                    'r' => $r
                ]);
            } else {
                header('Location:' . base_url . '/NomCon');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/NomCon');
    }
    public function show_row_NomConcepto()
    {
        $id = intval($_POST['id']);
        if ($id > 0) {
            $r = NomConModel::show_row_NomConcepto($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function val_codigo_nomcon()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $rows = to_obj(NomConModel::total_rows($id));
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
    public function cargar_screen_main(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = NomConModel::all();
            echo  json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}
