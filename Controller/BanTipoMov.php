<?php
/**
 *Clase para los Tipos de Movimientos Bancarios
 *Creado por Josvar Sistemas, C.A.
 *Autor José Vargas 
 *Fecha de creación: 27-05-2024 a las 15:34:00
 */
class BanTipoMov extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(80);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $r = BanTipoMovModel::all();
        $this->views->getView($this, "index", [
            'page_name' => 'Tipos de Movimientos Bancarios',
            'function_js' => 'BanTipoMov.js',
            'r' => to_obj($r),
        ]);
    }
     public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo Tipo de Movimiento Bancario',
            'function_js' => 'BanTipoMov.js'
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
                $data += [
                    'cod_bantmo' => $_POST['cod_bantmo'],
                    'nom_bantmo' => $_POST['nom_bantmo'],
                    'acc_bantmo' => $_POST['acc_bantmo'],
                    'idb_bantmo' => $_POST['idb_bantmo'],
                    'con_bantmo' => $_POST['con_bantmo'],
                    'cash_bantmo' => $_POST['cash_bantmo'],
                    'che_bantmo' => $_POST['che_bantmo'],
                    'tra_bantmo' => $_POST['tra_bantmo'],
                    'efe_bantmo' => $_POST['efe_bantmo'],
                    'id_cxtmo' => $_POST['id_cxtmo'],
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user']
                ];
                if(empty($_POST['id'])){
                    $id = BanTipoMovModel::guardar($data);
                     Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['cod_bantmo']. ' - ' . $data['nom_bantmo'], $id));
                }else{
                    $id = BanTipoMovModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['cod_bantmo'] . ' - ' . $data['nom_bantmo'], $_POST['id']));
                }
                header('Location:' .base_url . '/BanTipoMov');
            } catch (PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:' .base_url . '/BanTipoMov');
            }
        }
    }
    public function edit($id){
        if(Permisos::read()){
            $id = limpiar(intval($id));
            if($id>0){
                $r = BanTipoMovModel::edit($id);
                if(empty($r)){
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/BanTipoMov');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => 'Editando el registro ' . $r['cod_bantmo'] . ' - ' . $r['nom_bantmo'],
                    'function_js' => 'BanTipoMov.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/BanTipoMov');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/BanTipoMov');
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $id_emp = $_POST['id_emp'];
            $r = BanTipoMovModel::showrow($id, $id_emp); 
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = intval($_POST['id']);
         $r = BanTipoMovModel::destroy($id);
         if($r){
            echo json_encode(['msg' => 'Registro eliminado satisfactoriamente', 'icon' => 'success', 'title' => 'Ok'], JSON_UNESCAPED_UNICODE);
         }else{
            echo json_encode(['msg' => 'No se pudo eliminar el registro ya que se encuentra asociada a una cotización', 'icon' => 'error', 'title' => 'Error...'], JSON_UNESCAPED_UNICODE);
         }
      }
   }
   public function listar_tipomov_bancos(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $r = BanTipoMovModel::listar_tipomov_bancos();
        echo json_encode($r, JSON_UNESCAPED_UNICODE);
    }
   }
}