<?php
class Monedas extends Controller
{
   public function __construct()
   {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(25);
   }
   public function index()
   {
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objeto = MonedasModel::all();

      $this->views->getView($this, "index", [
         'page_name' => "Monedas",
         'function_js' => "Monedas.js",
         'objeto' => to_obj($objeto)
      ]);
   }
   public function nuevo()
   {
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nueva moneda",
         'function_js' => "Monedas.js"
      ]);
   }
   public function edit($id)
   {
      if (Permisos::read()) {
         $id = intval(limpiar($id));
         if ($id > 0) {
            $r = MonedasModel::edit($id);
            if (empty($r)) {
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/Monedas');
            }
//mostrar registro
            $this->views->getView($this, "edit", [
               'page_name' => "Editando el registro " . $r['nombre_moneda'],
               'function_js' => "Monedas.js",
               'r' => to_obj($r)
            ]);
         } else {
            header('Location:' . base_url . '/Monedas');
         }
         return;
      }
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Monedas');
   }
   public function store()
   {
      if (empty($_POST['id'])) {
         if (Permisos::create()) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               try {
//validar formulario
                  $val = new Validations();
                  $val->name('País')->value(limpiar($_POST['id_pais']))->required();
                  $val->name('Código')->value(limpiar($_POST['codigo_moneda']))->required();
                  $val->name('Nombre')->value(limpiar($_POST['nombre_moneda']))->required();                        
                  if ($val->isSuccess()) {
//guardar registro
                     $data = [
                        'id_pais' => limpiar($_POST['id_pais']),
                        'codigo_moneda' => limpiar($_POST['codigo_moneda']),
                        'nombre_moneda' => limpiar($_POST['nombre_moneda']),                                
                        'create_user' => $_SESSION['id_user']
                     ];
                     $id = MonedasModel::guardar($data);
                     Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nombre_moneda'], $id));
                     header('Location:' . base_url . '/Monedas');
                  } else {
                     Alertas::new($val->getErrors(), 'danger');
                     header('Location:' . base_url . '/Monedas/nuevo');
                  }
               } catch (\PDOException $e) {
                  Alertas::new($e->getMessage(), 'danger');
                  header('Location:' . base_url . '/Monedas/nuevo');
               }
            }
         }
      }else{
         if (Permisos::updater()) {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
               try {
//validar formulario
                  $val = new Validations();
                  $val->name('País')->value(limpiar($_POST['id_pais']))->required();
                  $val->name('Código')->value(limpiar($_POST['codigo_moneda']))->required();
                  $val->name('Nombre')->value(limpiar($_POST['nombre_moneda']))->required();                        
                  if ($val->isSuccess()) {
//guardar registro
                     $data = [
                        'id_pais' => limpiar($_POST['id_pais']),
                        'codigo_moneda' => limpiar($_POST['codigo_moneda']),
                        'nombre_moneda' => limpiar($_POST['nombre_moneda']),                                
                        'modify_user' => $_SESSION['id_user']
                     ];
                     $id = MonedasModel::actualizar($_POST['id'], $data);
                     Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nombre_moneda'], $_POST['id']));
                     header('Location:' . base_url . '/Monedas');
                  } else {
                     Alertas::new($val->getErrors(), 'danger');
                     header('Location:' . base_url . '/Monedas/edit');
                  }
               } catch (\PDOException $e) {
                  Alertas::new($e->getMessage(), 'danger');
                  header('Location:' . base_url . '/Monedas/edit');
               }
            }
         }
      }
   }
   public function destroy()
   {
      $dataJson = [];
      if (empty($_POST['id'])) {
         $dataJson = [
            'status' => false,
            'msg' => 'No se recibieron los datos'
         ];
      } else {
         $id = intval(limpiar($_POST['id']));
         $ide = MonedasModel::borrar($id);
         $dataJson = [
            'status' => true,
            'msg' => sprintf('El rol %s se ha eliminado correctamente', $_POST['name'])
         ];
      }        
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function listar_monedas(){
      $objeto = MonedasModel::listar_monedas();
      echo json_encode($objeto);
   }
}