<?php
/**
 *Clase para los Tipos de Documentos Bancarios
 *Creado por Josvar Sistemas, C.A.
 *Autor José Vargas
 *Fecha de creación: 27-05-2024 a las 08:25:00
 */
class BanTipoDoc extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(81);
    }
    public function index(){
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $r = BanTipoDocModel::all();
        $this->views->getView($this, "index", [
            'page_name' => 'Tipos de Documentos de Bancos',
            'function_js' => 'BanTipoDoc.js',
            'r' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, 'nuevo', [
            'page_name' => 'Nuevo Tipo de Documento de Banco',
            'function_js' => 'BanTipoDoc.js'
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
                $data = [
                    'cod_bantdo' => limpiar($_POST['cod_bantdo']),
                    'nom_bantdo' => limpiar($_POST['nom_bantdo']),
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user']
                ];
                if(empty($_POST['id'])){
                    $id = BanTipoDocModel::guardar($data);
                    Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['cod_bantdo']. ' - ' . $data['nom_bantdo'], $id));
                }else{
                    $id = BanTipoDocModel::actualizar($_POST['id'], $data);
                    Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['cod_bantdo'] . ' - ' . $data['nom_bantdo'], $_POST['id']));
                }
                header('Location:' .base_url . '/BanTipoDoc');
            } catch (PDOException $e) {
                 Alertas::new($e->getMessage(), 'danger');
                header('Location:' .base_url . '/BanTipoDoc');
            }
        }
    }
    public function edit($id){
        if(Permisos::read()){
            $id = limpiar(intval($id));
            if($id>0){
                $r = BanTipoDocModel::edit($id);
               /* if(empty($r)){
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/BanTipoDoc');
                }*/
                $this->views->getView($this, "edit", [
                    'page_name' => 'Editando el registro ' . $r['cod_bantdo'] . ' - ' . $r['nom_bantdo'],
                    'function_js' => 'BanTipoDoc.js',
                    'r' => to_obj($r)
                ]);
            }else{
                header('Location:' . base_url . '/BanTipoDoc');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/BanTipoDoc');
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = BanTipoDocModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id = intval($_POST['id']);
         $r = BanTipoDocModel::destroy($id);
         if($r){
            echo json_encode(['msg' => 'Registro eliminado satisfactoriamente', 'icon' => 'success', 'title' => 'Ok'], JSON_UNESCAPED_UNICODE);
         }else{
            echo json_encode(['msg' => 'No se pudo eliminar el registro ya que se encuentra asociada a una cotización', 'icon' => 'error', 'title' => 'Error...'], JSON_UNESCAPED_UNICODE);
         }
      }
   }
}