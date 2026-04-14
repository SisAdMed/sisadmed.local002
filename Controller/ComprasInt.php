<?php
class ComprasInt extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(78);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = ComprasIntModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Compras Internacionales",
            'function_js' => "ComprasInt.js",
            'r' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Compra Internacional",
            'function_js' => "ComprasInt.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ComprasIntModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ComprasInt');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r[0]['id_comint'] . " de proveedor " . $r[0]['nombre_provint'],
                    'function_js' => "ComprasInt.js",
                    'r' => $r
                ]);
            } else {
                header('Location:' . base_url . '/ComprasInt');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ComprasInt');
    }
    public function listar_proveeint(){
        $r = ComprasIntModel::listar_proveeint();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
    public function store(){
        $modo = 'modify_user';
        $data = array();
        if(empty($_POST['id'])){
            $modo = 'create_user';
        }
        try{
            $data = [
                'fecha_comint' => $_POST['fecha_comint'],
                'id_provint' => $_POST['id_provint'],
                'status' => $_POST['status'],
                'descrip_compint' => $_POST['descrip_compint'],
                $modo => $_SESSION['id_user'],
            ];
            //Guardar y/o actualizar encabezado
            if(empty($_POST['id'])){
                $id = ComprasIntModel::guardar($data);
                $id_det = $id;
                Alertas::new(sprintf('La Compra se ha creado exitosamente con el id %s', $id));
            }else{
                $id = ComprasIntModel::actualizar($_POST['id'], $data);
                //Borrar detalles
                $iddet = ComprasIntModel::borrardet($_POST['id']);
                $id_det = $_POST['id'];
                Alertas::new(sprintf('La Compra se ha modificado exitosamente con el id %s', $id_det));
            }
            //Detalles
            //Agregar detalles
            $modo = 'create_user';
            $totaldet = count($_POST['id_prod']);
            $datadet = array();
            for($i=0;$i<$totaldet;$i++){
                $datadet = [
                    'id_comint' => $id_det,
                    'id_provint' => $_POST['id_provint'],
                    'id_prod' => $_POST['id_prod'][$i],
                    'cantidad' => convert_string_to_number($_POST['cantidad'][$i]),
                    'costo' => convert_string_to_number($_POST['precio'][$i]),
                    $modo => $_SESSION['id_user'],
                ];
                $iddet = ComprasIntModel::agregardet($datadet);
            }
            header('Location:'. base_url . '/ComprasInt');
        }catch(\PDOException $e){
            Alertas::new($e->getMessage(), 'danger');
            header('Location:'. base_url . '/ComprasInt');
        }
    }
    public function cargar_data(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ComprasIntModel::cargar_data($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_row(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = ComprasIntModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dataJson = array();
            $id = $_POST['id'];
            $r = ComprasIntModel::destroy($id);
            try {
                if ($r) {
                    $dataJson = [
                        'title' => "Registro eliminado",
                        'icon' => "success",
                        'msg' => "El registro ha sido eliminado correctamente."
                    ];
                } else {
                    $dataJson = [
                        'title' => "Ha ocurrido un error",
                        'icon' => "error",
                        'msg' => "Error al momento de eliminar el registro, por favor inente luego"
                    ];
                }
            } catch (\PDOException $e) {
                $msg = sprintf("Error código %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
                $dataJson = [
                    'title' => "Ha ocurrido un error",
                    'icon' => "error",
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function printer($fuente){
        if(Permisos::read()){
            $id_ori = explode('|', $fuente);
            $id = $id_ori[0];
            $ori = $id_ori[1];
            if($id){
                $r = ComprasIntModel::print_data($id);
                if($ori == "PDF"){
                    $report = 'print_pdf';
                }else{
                    $report = 'print_excel';
                }
                $this->views->getView($this, $report, [
                    'r' => to_obj($r)
                ]);
            }
        }
    }
}