<?php
class TipoDcto extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(68);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = TipoDctoModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Consulta Tipos de Descuentos',
            'function_js' => 'TipoDcto.js',
            'r' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Tipos de Descuentos",
            'function_js' => "TipoDcto.js"
        ]);
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $codigo = '';
            $appreq = 0;
            $data = array();
            if(empty($_POST['id'])){
                $data = ['codigo_tipdes' => limpiar($_POST['codigo_tipdes']),
            ];
            $modo = 'create_user';
        }
        try {
            if(isset($_POST['appreq'])){
                $appreq = 1;
            }
            $data += [
                'valor_tipdes' => limpiar($_POST['valor_tipdes']),
                'appreq' => $appreq,
                'status' => limpiar($_POST['status']),
                $modo => $_SESSION['id_user'],
            ];
            if(empty($_POST['id'])){
                $id = TipoDctoModel::guardar($data);
                Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['valor_tipdes'], $id));
            }else{
                $id = TipoDctoModel::actualizar($_POST['id'], $data);
                Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['valor_tipdes'], $_POST['id']));
            }
            header('Location:' . base_url . '/TipoDcto');
        } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/TipoDcto');
        }
    }
}
public function edit($id){
    if (Permisos::read()) {
        $id = intval(limpiar($id));
        if ($id > 0) {
            $r = TipoDctoModel::edit($id);
            if (empty($r)) {
                Alertas::new('El registro no existe', 'warning');
                header('Location:' . base_url . '/TipoDcto');
            }
            $this->views->getView($this, "edit", [
                'page_name' => "Editando el registro " . $r['codigo_tipdes'] . ' - ' . $r['valor_tipdes'],
                'function_js' => "TipoDcto.js",
                'r' => to_obj($r)
            ]);
        } else {
            header('Location:' . base_url . '/TipoDcto');
        }
        return;
    }
    Alertas::new('No tiene permiso para realizar esta acción', 'warning');
    header('Location:' . base_url . '/TipoDcto');
}
public function destroy(){
    if(!empty($_POST['id'])){
        $id = intval(limpiar($_POST['id']));
        $descrip = limpiar($_POST['descrip']);
        $ide = TipoDctoModel::borrar($id);
        if($ide){
            $dataJson = [
                'status' => true,
                'type' => 'success',
                'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $id, $descrip)
            ];
        }else{
            $dataJson = [
                'status' => false,
                'type' => 'warning',
                'msg' => sprintf('No se puede elimiar el registro %s con la descripción %s, motivado a que está relacionado en otros registros', $_id, $descrip)
            ];
        }
        echo json_encode($dataJson,JSON_UNESCAPED_UNICODE);
    }
}
public function listar_descuentos(){
    $r = TipoDctoModel::listar_descuentos();
    echo json_encode($r,JSON_UNESCAPED_UNICODE);
}
public function show_row(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id = $_POST['id'];
        $r = TipoDctoModel::show_row($id);
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
}
}