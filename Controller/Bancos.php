<?php
/**
 *Clase para los Tipos de Movimientos Bancarios
 *Creado por Josvar Sistemas, C.A.
 *Autor José Vargas
 *Fecha de creación: 28-05-2024 a las 10:34:00
 */
class Bancos extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(82);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $r = BancosModel::all();
        $this->views->getView($this, "index", [
            'page_name' => 'Instituciones Bancarias',
            'function_js' => 'Bancos.js',
            'r' => to_obj($r),
        ]);
    }
     public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nueva Institución Bancaria',
            'function_js' => 'Bancos.js'
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
                    'cod_banco' => $_POST['cod_banco'],
                    'nombre_banco' => $_POST['nombre_banco'],
                    'dir_banco' => $_POST['dir_banco'] ?? '',
                    'tel_banco' => $_POST['tel_banco'] ?? '',
                    'extranjero_ban' => $_POST['extranjero_ban'],
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user']
                ];
                if(empty($_POST['id'])){
                    $id = BancosModel::guardar($data);
                     Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['cod_banco']. ' - ' . $data['nombre_banco'], $id));
                }else{
                    $id = BancosModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['cod_banco'] . ' - ' . $data['nombre_banco'], $_POST['id']));
                }
                header('Location:' .base_url . '/Bancos');
            } catch (PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:' .base_url . '/Bancos');
            }
        }
    }
    public function edit($id){
        if(Permisos::read()){
            $id = limpiar(intval($id));
            if($id>0){
                $r = BancosModel::edit($id);
                if(empty($r)){
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/Bancos');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => 'Editando el registro ' . $r['cod_banco'] . ' - ' . $r['nombre_banco'],
                    'function_js' => 'Bancos.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/Bancos');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/Bancos');
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = BancosModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = intval($_POST['id']);
         $r = BancosModel::destroy($id);
         if($r){
            echo json_encode(['msg' => 'Registro eliminado satisfactoriamente', 'icon' => 'success', 'title' => 'Ok'], JSON_UNESCAPED_UNICODE);
         }else{
            echo json_encode(['msg' => 'No se pudo eliminar el registro ya que se encuentra asociada a una cotización', 'icon' => 'error', 'title' => 'Error...'], JSON_UNESCAPED_UNICODE);
         }
      }
   }
}