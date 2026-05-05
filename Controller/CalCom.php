<?php
class CalCom extends Controller
{
   public function __construct()
   {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(184);
   }
   public function index()
   {
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $this->views->getView($this, 'index', [
         'page_name' => 'Calculo de Comisiones',
         'function_js' => 'CalCom.js',
      ]);
   }
   public function cargar_screen_main()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $r = CalComModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function new()
   {
      $this->views->getView($this, "new", [
         'page_name' => "Nuevo Cálculo de Comisiones",
         'function_js' => "CalCom.js",
      ]);
   }
   public function edit($id)
   {
      if (Permisos::read()) {
         if ($id) {
            $r = CalComModel::edit($id);
            $this->views->getView($this, "edit", [
               'page_name' => "Editar Cálculo de Comisiones",
               'function_js' => "CalCom.js",
               'r' => to_obj($r),
            ]);
         }
      }
   }
   public function listar_tabla()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_emp = $_POST['id_emp'];
         $fec_ini = $_POST['fec_ini'];
         $fec_fin = $_POST['fec_fin'];
         $id_vend = '';
         $id = '';
         if (isset($_POST['id_vend']) && $_POST['id_vend'] > 0) {
            $id_vend = $_POST['id_vend'];
         }
         if (isset($_POST['id']) && $_POST['id'] > 0) {
            $id = $_POST['id'];
         }

         $r = CalComModel::listar_tabla($id_emp, $fec_ini, $fec_fin, $id_vend, $id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function store() {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {         
         $modo = 'modify_user';
         $data = array();
         $dataJson = array();
         foreach ($_POST as $key => $value) {
            $$key =  $value;
         }
         if (empty($id)) {
            $modo = 'create_user';
         }
         try {
            $data += [
               'id_emp' => $id_emp,
               'id_vend' => $id_vend,
               'fec_ini' => $fec_ini,
               'fec_fin' => $fec_final,
               'status' => $status,
               $modo => $_SESSION['id_user']
            ];
            if (empty($id)) {
               $r = CalComModel::guardar($data);
               $id = $r;
            } else {
               $r = CalComModel::actualizar($id, $data);
               $del_detail = CalComModel::delete_details($id);
            }

            if ($id > 0) {
               //Guardar detalles                  
               $details = json_decode($dattab, true);               
               foreach ($details as $row) {                  
                  $dataDetail = array();
                  $tasa_cambio = $_POST['tasa_cambio'];
                  if ($tasa_cambio === 'NaN') {
                     $tasa_cambio =  $row['tasa_cambio'];
                  } else {
                     $tasa_cambio = $_POST['tasa_cambio'];
                  }
                  $tot_comision = ($row['sub_total'] * ($row['comi_vend'] / 100)) * $tasa_cambio;
                  $dataDetail += [
                     'id_calcom' => $id,
                     'id_vend' => $row['id_vend'],
                     'fec_doc' => $row['fec_fact'],
                     'fec_pag' => $row['fec_pag'],
                     'porcentaje' => $row['comi_vend'],
                     'sub_total_doc' => $row['sub_total'],
                     'sub_total_com' => $tot_comision,
                     'id_cot' => $row['id_cot'],
                     'id_ent' => $row['id_ent'],
                     'tasa_cambio' => $tasa_cambio,
                     'create_user' => $_SESSION['id_user']
                  ];
                  CalComModel::guardar_detalle($dataDetail);
               }
            }
            $title = "Operación realizada con éxito";
            $msg = "Los datos han sido almacenados correctamente.";
            $dataJson = [
               'title' => $title,
               'icon' => 'success',
               'msg' => $msg
            ];
         } catch (\PDOException $e) {
            $title = "Se ha presentado un error, intente luego";
            $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
            $dataJson = [
               'title' => $title,
               'icon' => 'error',
               'msg' => $msg
            ];
         }
         echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
      }
   }
   public function show_row()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id = $_POST['id'];
         $r = CalComModel::edit($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function destroy()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $dataJson = array();
         $id = $_POST['id'];
         $r = CalComModel::destroy($id);
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
   public function report($id)
   {
      $r = CalComModel::report_data($id);
      $this->views->getView($this, "report", [
         'r' => to_obj($r),
      ]);
   }
}
