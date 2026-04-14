<?php
class Vendedores extends Controller
{
   public function __construct()
   {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(57);
   }
   public function index()
   {
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objeto = VendedoresModel::all();
      $this->views->getView($this, 'index', [
         'page_name' => 'Consulta de Vendedores',
         'function_js' => 'Vendedores.js',
         'objeto' => to_obj($objeto),
      ]);
   }
   public function nuevo()
   {
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Vendedor",
         'function_js' => "Vendedores.js",
      ]);
   }
   public function store()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $modo = 'modify_user';
         $data = array();
         $dataJson = array();
         //Asignar valores a variables
         foreach ($_POST as $key => $value) {
            $$key = $value;
         }

         if (empty($id)) {
            $data = ['ced_vend' => $ced_vend];
            $modo = 'create_user';
         }
         try {
            $data += [
               'nom_vend' => $nom_vend,
               'ape_vend' => $ape_vend,
               'email_vend' => $email_vend,
               'fecing_vend' => $fecing_vend,
               'comi_vend' => $comi_vend,
               'id_pais' => $id_pais,
               'id_edo' => $id_edo,
               'id_ciudad' => $id_ciudad,
               'dir_vend' => $dir_vend,
               'status' => $status,
               $modo => $_SESSION['id_user'],
            ];
            if (empty($id)) {
               $id = VendedoresModel::guardar($data);
               $msg = sprintf('El vendendor %s se ha creado exitosamente con  el id %s', $nom_vend . ' ' . $ape_vend, $id);
               $title = "Registro agregado satisfactoriamente";
               $icon = "success";
            } else {
               $id = VendedoresModel::actualizar($id, $data);
               $msg = sprintf('El vendedor %s se ha modificado exitosamente con el id %s', $nom_vend . ' ' . $ape_vend, $_POST['id']);
               $title = "Registro modificado satisfactoriamente";
               $icon = "success";
            }
            if ($id) {
               $dataJson = [
                  'title' => $title,
                  'icon' => $icon,
                  'msg' => $msg
               ];
            } else {
               $dataJson = [
                  'title' => "Ha ocurrido un error",
                  'icon' => "error",
                  'msg' => "Error al momento de crear y/o actualizar el registro, por favor inente luego"
               ];
            }
         } catch (\PDOException $e) {
            $title = "Se ha presentado un error, intente luego";
            $msg = sprintf("Error código %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
            $icon = "error";
            $dataJson = [
               'title' => $title,
               'icon' => $icon,
               'msg' => $msg
            ];
         }
         echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
      }
   }
   public function edit($id)
   {
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id) {
            $r = VendedoresModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/Vendedores');
            }
            $this->views->getview($this, 'edit', [
               'page_name' => 'Editando el vendedor ' . $r['nom_vend'] . ' ' . $r['ape_vend'],
               'function_js' => 'Vendedores.js',
               'r' => to_obj($r),
            ]);
         } else {
            header('Location:' . base_url . '/Vendedores');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Vendedores');
   }
   public function destroy()
   {
      $dataJson = [];
      $id = intval(limpiar($_POST['id']));
      $ide = VendedoresModel::borrar($id);
      try {
         if ($ide) {
            $dataJson = [
               'title' => 'Registro Eliminado satisfactoriamente',
               'icon' => 'success',
               'msg' => sprintf('El registro %s, con la descripción %s se ha eliminado correctamente', $_POST['id'], $_POST['name'])
            ];
         } else {
            $dataJson = [
               'title' => 'No se pudo Eliminar el registro',
               'icon' => 'warning',
               'msg' => sprintf('No se puede elimiar el registro %s con la descripción %s, motivado a que tienes registros hijos y/o posee movimientos', $_POST['id'], $_POST['name'])
            ];
         } 
      } catch (\Throwable $th) {
         $dataJson = [
            'title' => 'No se pudo Eliminar el registro',
            'icon' => 'warning',
            'msg' => sprintf('Error al elimianr el registro: %s', $th->getMessage())
         ];
      }
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function showrow()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id = $_POST['id'];
         $r = VendedoresModel::showrow($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function cargar_screen_main()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $r = VendedoresModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}
