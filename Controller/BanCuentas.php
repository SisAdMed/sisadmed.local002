<?php
class BanCuentas extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent:: __construct();
        Permisos::getPermisos(83);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = BanCuentasModel::all();

        $this->views->getView($this, "index", [
            'page_name' => "Cuentas Bancarias",
            'function_js' => "BanCuentas.js",
            'r' => to_obj($objeto)
        ]);
    }
    public function nuevo(){
            $this->views->getView($this, "nuevo", [
            'page_name' => "Nueva Cuenta Bancaria",
            'function_js' => "BanCuentas.js"
        ]);
    }
    public function next_codigo(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            //$codigo1 = BanCuentasModel::next_codigo($_POST['id_emp']);
            //echo json_encode($codigo1, JSON_UNESCAPED_UNICODE);
        }
    }
    public function store(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $codigo = '';
            $data = array();
            if(empty($_POST['id'])){
                $data = [
                    'id_emp' => limpiar($_POST['id_emp']),
                ];
                $modo = 'create_user';
            }
            try {
                $data += [
                    'id_banco' => limpiar($_POST['id_banco']),
                    'suc_bancue' => limpiar($_POST['suc_bancue']),
                    'con_bancue' => limpiar($_POST['con_bancue']),
                    'cue_bancue' => limpiar($_POST['cue_bancue']),
                    'cuenta_bancue' => limpiar($_POST['cuenta_bancue']),
                    'id_ctb' => limpiar($_POST['id_ctb']),
                    'status' => limpiar($_POST['status']),
                    $modo => $_SESSION['id_user'],
                ];
                if(isset($_POST['id_aux']) && $_POST['id_aux'] != ''){
                    $data += [
                        'id_aux' => limpiar($_POST['id_aux']),
                    ];
                }
                if(empty($_POST['id'])){
                    $id = BanCuentasModel::guardar($data);
                    Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['cuenta_bancue'], $id));
                }else{
                    $id = BanCuentasModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['cuenta_bancue'], $_POST['id']));
                }
               header('Location:' . base_url . '/BanCuentas');
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
               header('Location:' . base_url . '/BanCuentas');
            }
        }
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = BanCuentasModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/BanCuentas');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando la Cuenta Bancaria " . $r['cuenta_bancue'],
                    'function_js' => "BanCuentas.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/BanCuentas');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/BanCuentas');
    }
    public function destroy(){
        $dataJson = [];
        if (empty($_POST['id'])) {
            $dataJson = [
                'status' => false,
                'type' => 'warning',
                'msg' => 'No se recibieron los datos'
            ];
        } else {
            $id = intval(limpiar($_POST['id']));
            $ide = BanCuentasModel::destroy($id);
            if($ide){
                $dataJson = [
                    'status' => true,
                    'icon' => 'success',
                    'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['id'], $_POST['cue_bancue'])
                ];
            }else{
                $dataJson = [
                    'status' => false,
                    'icon' => 'warning',
                    'msg' => sprintf('No se puede elimiar el registro %s con la descripción %s, motivado a que tienes registros hijos y/o posee movimientos', $_POST['id'], $_POST['cue_bancue'])
                ];
            }
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function listar_bancos(){
        $r = BanCuentasModel::listar_bancos();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
    public function val_cod_banco(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = BanCuentasModel::val_cod_banco($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = BanCuentasModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function datosCue(){
      if($_SERVER["REQUEST_METHOD"] == "POST"){
         $id = $_POST['id_cue'];
         $r = BanCuentasModel::datosCue($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_cuentas_ban(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $id_emp = $_POST['id_emp'];
        $r = BanCuentasModel::listar_cuentas_ban($id_emp);
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
   }
}