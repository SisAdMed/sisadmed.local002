<?php
class BanConceptos extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent:: __construct();
        Permisos::getPermisos(84);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = BanConceptosModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Conceptos Bancarios',
            'function_js' => 'BanConceptos.js',
            'r' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo Concepto Bancario',
            'function_js' => 'BanConceptos.js'
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            if(empty($_POST['id'])){
                $modo = 'create_user';
            }
            try {
                $id_aux = $_POST['id_aux'];
                if(!$id_aux){
                    $id_aux = 0;
                }
                $data += [
                    'cod_bancon' => limpiar($_POST['cod_bancon']),
                    'nom_bancon' => limpiar($_POST['nom_bancon']),
                    'agr_bancon' => limpiar($_POST['agr_bancon']),
                    'id_bantdo' => limpiar($_POST['id_bantdo'] ??  'N'),
                    'status' => limpiar($_POST['status']),
                    'id_aux' => $id_aux,
                    $modo => $_SESSION['id_user']
                ];
                if(isset($_POST['id_ctb']) && $_POST['id_ctb'] != ''){
                    $data += [
                        'id_ctb' => limpiar($_POST['id_ctb']),
                    ];
                }
                if(isset($_POST['id_retislr']) && $_POST['id_retislr'] != ''){
                    $data += [
                        'id_retislr' => limpiar($_POST['id_retislr']),
                    ];
                }
                if(empty($_POST['id'])){
                    $id = BanConceptosModel::guardar($data);
                    Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nom_bancon'], $id));
                }else{
                    $id = BanConceptosModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nom_bancon'], $_POST['id']));
                }
                header('Location:' . base_url . '/BanConceptos');
            } catch (PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/BanConceptos');
            }
        }
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
            $ide = BanConceptosModel::destroy($id);
            if($ide){
                $dataJson = [
                    'title' => 'Registro eliminado',
                    'icon' => 'success',
                    'msg' => sprintf('El registro %s, con el código %s y la descripción %s se ha eliminado correctamente', $_POST['id'], $_POST['code'], $_POST['name'])
                ];
            }else{
                $dataJson = [
                    'title' => 'Ooops',
                    'icon' => 'error',
                    'msg' => sprintf('No se puede elimiar el registro %s con el código %s y de descripción %s, motivado a que tienes registros hijos y/o posee movimientos', $_POST['id'], $_POST['code'], $_POST['name'])
                ];
            }
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = BanConceptosModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/BanConceptos');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro " . $r['cod_bancon'] . ' - ' . $r['nom_bancon'],
                    'function_js' => "BanConceptos.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/BanConceptos');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/BanConceptos');
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = BanConceptosModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE); 
        }
    }
    public function listar_conceptos(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $r = BanConceptosModel::listar_conceptos();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function listar_conceptos_exc(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = BanConceptosModel::listar_conceptos_exc();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function nom_con_ban(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id_bancon = $_POST['id_bancon'];
         $r = BanConceptosModel::nom_con_ban($id_bancon);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}