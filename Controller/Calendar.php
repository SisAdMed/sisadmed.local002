<?php
class Calendar extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(194);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $this->views->getView($this, 'index', [
            'page_name' => 'Menú de Calendarios',
            'function_js' => 'Calendar.js',
            'function_js_mod' => 'GENFUN.js'
        ]);
    }
    public function cargar_screen_main(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = CalendarModel::cargar_screen_main();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function new(){
        $this->views->getView($this, "new", [
            'page_name' => "Nuevo Calendario",
            'function_js' => 'Calendar.js',
            'function_js_mod' => 'GENFUN.js'
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = CalendarModel::show_row($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Calendar');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r['title'] . " Año " . $r['year'],
                    'function_js' => "Calendar.js",
                    'function_js_mod' => 'GENFUN.js',
                    'r' => $r
                ]);
            } else {
                header('Location:' . base_url . '/Calendar');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Calendar');
    }

    public function store(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $modo = 'modify_user';
            debug($_POST);
            $data = array();
            $dataJson = array();
            //Asignacion de valores
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            if(empty($id)){ 
                $modo = 'create_user';
            }
            try {
                $data += [
                    'title' => limpiar($title),
                    'description' => limpiar($description),
                    'year' => $year,
                    'background' => limpiar($background),
                    'text' => limpiar($text),
                    'all_day' => isset($all_day) ? 1 : 0,
                    'status' => isset($status) ? 1 : 0,
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($id)){
                    $id = CalendarModel::guardar($data);
                    $title = "Registro se ha agregado con éxito";
                }else{
                    $id_upd = CalendarModel::actualizar($data, $id);
                    $title= "Registro se ha actualizado con éxito";
                }
                $msg = "Operación realizada correctamente";
                $dataJson = [
                    'title' => $title,
                    'msg' => $msg,
                    'icon' => 'success',
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
            //echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = intval(limpiar($_POST['id']));
            $r = CalendarModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }   
}
