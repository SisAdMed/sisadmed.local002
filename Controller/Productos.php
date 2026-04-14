<?php
class Productos extends Controller
{
   public function __construct()
   {
      Auth::noAuth();
      parent::__construct();
      Permisos::getPermisos(52);
   }
   public function index()
   {
      if (empty($_SESSION['permisosMod']['r'])) {
         header('Location:' . base_url . '/Perfil');
      }
      $objeto = ProductosModel::all();
      $this->views->getView($this, "index", [
         'page_name' => "Consulta de Productos",
         'function_js' => "Productos.js",
         'objeto' => to_obj($objeto)
      ]);
   }
   public function nuevo()
   {
      $this->views->getView($this, "nuevo", [
         'page_name' => "Nuevo Producto",
         'function_js' => "Productos.js"
      ]);
   }
   public function store()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

         $modo = 'modify_user';
         $data = array();
         $stock_minimo = 0;
         if (!isset($_POST['stock_minimo'])) {
            $stock_minimo = $_POST['stock_minimo'];
         }
         $commet_prod = '';
         if (isset($_POST['commet_prod'])) {
            $commet_prod = limpiar($_POST['commet_prod']);
         }
         if (empty($_POST['id'])) {
            $data = ['cod_prod' => limpiar($_POST['cod_prod'])];
            $modo = 'create_user';
         }
         try {
            $id_fab_fac = null;
            if (isset($_POST['id_fab_fac']) && !empty($_POST['id_fab_fac'])) {
               $id_fab_fac = $_POST['id_fab_fac'];
            }
            $costo1 = 0;
            if ($_SESSION['administrator'] == 1) {
               $costo_prod = floatval($_POST['costo_prod']);
               $flete_prod = floatval($_POST['flete_prod']);
               $otros_prod = floatval($_POST['otros_prod']);
               $door_costo = floatval($_POST['door_costo']);
               $costo1 =   $costo_prod + $flete_prod + $otros_prod + $door_costo;
               $data += [
                  'costo_prod' => str_replace(',', '', $_POST['costo_prod']),
                  'flete_prod' => str_replace(',', '', $_POST['flete_prod']),
                  'otros_prod' => str_replace(',', '', $_POST['otros_prod']),
                  'door_costo' => str_replace(',', '', $_POST['door_costo']),
                  'costo1' => $costo1,
                  'recar_prod' => str_replace(',', '', $_POST['recar_prod']),
                  'ventas_prod' => str_replace(',', '', $_POST['ventas_prod']),
                  'recar2_prod' => str_replace(',', '', $_POST['recar2_prod']),
                  'venta2_prod' => str_replace(',', '', $_POST['venta2_prod']),
                  'commet_prod' => $commet_prod,
               ];
            }
            $data += [
               'cod_prod' => limpiar($_POST['cod_prod']),
               'cod2_prod' => limpiar($_POST['cod2_prod']),
               'nom_prod' => limpiar($_POST['nom_prod']),
               'id_pre' => limpiar($_POST['id_pre']),
               'id_grupo' => limpiar($_POST['id_grupo']),
               'id_sub_grupo' => limpiar($_POST['id_sub_grupo']),
               'conv_prod_cons' => limpiar($_POST['conv_prod_cons']),
               'id_fab' => limpiar($_POST['id_fab']),
               'id_fab_fac' => $id_fab_fac,
               'ref_prod' => limpiar($_POST['ref_prod']),
               'gen_prod' => limpiar($_POST['gen_prod']),
               'des_prod' => limpiar($_POST['des_prod']),
               'uni_com_prod' => limpiar($_POST['uni_com_prod']),
               'uni_ven_prod' => limpiar($_POST['uni_ven_prod']),
               'iva_prod' => limpiar(!empty($_POST['iva_prod']) ? 1 : 0),
               'con_cons_prod' => limpiar($_POST['con_cons_prod']),
               'lote_prod' => limpiar(!empty($_POST['lote_prod']) ? 1 : 0),
               'interno_prod' => limpiar(!empty($_POST['interno_prod']) ? 1 : 0),
               'door_prod' => limpiar(!empty($_POST['door_prod']) ? 1 : 0),
               'status' => limpiar($_POST['status']),
               'alto' => limpiar($_POST['alto']),
               'ancho' => limpiar($_POST['ancho']),
               'largo' => limpiar($_POST['largo']),
               'origen' => limpiar($_POST['origen']),
               'id_presen1' => limpiar($_POST['id_presen1']),
               'id_presen2' => limpiar($_POST['id_presen2']),
               'stock_minimo' => limpiar($stock_minimo),
            ];
            if (empty($_POST['id'])) {
               $id = ProductosModel::guardar($data);
               Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['nom_prod'], $id));
            } else {
               $id = ProductosModel::actualizar($_POST['id'], $data);
               Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $data['nom_prod'], $_POST['id']));
            }
            //Almacenar imagenes
            if (isset($_FILES['url_photo']) && (count($_FILES['url_photo']) != 0)) {
               if (empty($_POST['id'])) {
                  $id = $id;
               } else {
                  $id = $_POST['id'];
               }
               //$idimg = ProductosModel::borrarimg($id);
               $ruta = ROOT . DS . 'Assets' . DS . 'img' . DS . 'products';
               //listarArchivos($ruta, $id);
               $cantidad = count($_FILES['url_photo']['tmp_name']);
               for ($i = 0; $i < $cantidad; $i++) {
                  $nombreDelArchivo = $_FILES["url_photo"]["name"][$i];
                  //Se comprueba de que el archivo sea una imagen
                  if (
                     $_FILES['url_photo']['type'][$i] == 'image/png' ||
                     $_FILES['url_photo']['type'][$i] == 'image/jpeg' ||
                     $_FILES['url_photo']['type'][$i] == 'image/apng' ||
                     $_FILES['url_photo']['type'][$i] == 'image/avif' ||
                     $_FILES['url_photo']['type'][$i] == 'image/gif' ||
                     $_FILES['url_photo']['type'][$i] == 'image/svg+xml' ||
                     $_FILES['url_photo']['type'][$i] == 'image/webp' ||
                     $_FILES['url_photo']['type'][$i] == 'image/bmp' ||
                     $_FILES['url_photo']['type'][$i] == 'image/tiff' ||
                     $_FILES['url_photo']['type'][$i] == 'image/x-icon'
                  ) {
                     //Subimes el fichero al servidor
                     $nombreDelArchivo = $_FILES["url_photo"]["name"][$i];
                     $ext = pathinfo($nombreDelArchivo, PATHINFO_EXTENSION);
                     //$nombreDelArchivo = $id . '-' . $i . '.' . $ext;
                     $ruta = ROOT . DS . 'Assets' . DS . 'img' . DS . 'products';
                     $ruta = $ruta . DS . $nombreDelArchivo;
                     move_uploaded_file($_FILES['url_photo']['tmp_name'][$i], $ruta);
                     //Almacenar en la base de datos
                     $data = [
                        'id_prod' => $id,
                        'default_photo' => '0',
                        'url_photo' => '/Assets/img/products/' . $nombreDelArchivo,
                        'filename' => $nombreDelArchivo,
                        'create_user' =>  $_SESSION['id_user'],
                     ];
                     $idimg = ProductosModel::guardarimg($data);
                  }
               }
            }
            //Guardar datos de etiquetas
            if (isset($_POST['nomcor_prod']) && !empty($_POST['nomcor_prod'])) {
               $modo = 'modify_user';
               $data = array();
               if (empty($_POST['id'])) {
                  $modo = 'create_user';
               }
               $data += [
                  'id_prod' => intval($id),
                  'nomcor_prod' => limpiar($_POST['nomcor_prod']),
                  'marcom_prod' => limpiar($_POST['marcom_prod']),
                  'fabpor_prod' => limpiar($_POST['fabpor_prod']),
                  'cpe_prod' => limpiar($_POST['cpe_prod']),
                  'connetpro_prod' => limpiar($_POST['connetpro_prod']),
                  'connetcaj_prod' => limpiar($_POST['connetcaj_prod']),
                  'regsan_prod' => limpiar($_POST['regsan_prod']),
                  'uso_prod' => limpiar($_POST['uso_prod']),
                  $modo => $_SESSION['id_user'],
               ];
               $idlabel = ProductosModel::borrar_etiqueta($id);
               $ide = ProductosModel::guardar_etiqueta($data);
            }
            header('Location:' . base_url . '/Productos');
         } catch (\PDOException $e) {
            Alertas::new($e->getMessage(), 'danger');
            header('Location:' . base_url . '/Productos');
         }
      }
   }
   public function edit($idn)
   {
      $valor = '';
      $status = explode("-", $idn);
      $id = $status[0];
      if (isset($status[1])) {
         $valor = $status[1];
      }

      //if(Permisos::read()){
      $id = intval(limpiar($id));
      if ($id > 0) {
         $r = ProductosModel::edit($id);
         if (empty($r)) {
            Alertas::new('El registro no existe', 'warning');
            header('Location:' . base_url . '/Productos');
         }
         $this->views->getView($this, "edit", [
            'page_name' => "Editando el registro " . $r['nom_prod'],
            'function_js' => "Productos.js",
            'r' => to_obj($r),
            'valor' => $valor
         ]);
      } else {
         header('Location:' . base_url . '/Productos');
      }
      return;
      //}
      Alertas::new('No tiene permiso para realizar esta acción', 'warning');
      header('Location:' . base_url . '/Productos');
   }
   function listarArchivos($path, $id)
   {
      // Abrimos la carpeta que nos pasan como parámetro
      $dir = opendir($path);
      // Leo todos los ficheros de la carpeta
      while ($elemento = readdir($dir)) {
         // Tratamos los elementos . y .. que tienen todas las carpetas
         if ($elemento != "." && $elemento != "..") {
            // Si es una carpeta
            if (is_dir($path . $elemento)) {
               // Muestro la carpeta
               echo "<p><strong>CARPETA: " . $elemento . "</strong></p>";
               // Si es un fichero
            } else {
               // Muestro el fichero
               $findme = $id . '-';
               $pos = strpos($elemento, $findme);
               if ($pos !== false) {
                  //echo "<br />". $path. DS . $elemento;
                  unlink($path . DS . $elemento);
               }
            }
         }
      }
   }
   public function destroy()
   {
      $dataJson = [];
      $id = intval(limpiar($_POST['id']));
      $idimg = ProductosModel::borrarimg($id);
      $idlabel = ProductosModel::borrar_etiqueta($id);
      $ide = ProductosModel::borrar($id);
      if ($ide) {
         $dataJson = [
            'status' => true,
            'icon' => 'success',
            'title' => 'Borrado!',
            'msg' => sprintf('El registro %s, con la descripción %s y el código %s se ha eliminado correctamente', $_POST['id'], $_POST['name'], $_POST['code'])
         ];
      }
      echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
   }
   public function borrarimg() {
      $dataJson = [];
      $id = intval(limpiar($_POST['id']));
      $ruta = limpiar($_POST['ruta']);
      $code = limpiar($_POST['code']);
      $idimg = ProductosModel::borrarimg($id);
      unlink($code);
   }
   function listar_productos()
   {
      $r = ProductosModel::listar_productos();
      echo json_encode($r);
   }
   function consulta()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $origen = "";
         $id_alm = "";
         $id_ubi = '';
         if (isset($_POST['origen'])) {
            $origen = $_POST['origen'];
         }
         if(isset($_POST['id_ubi'])){
            $id_ubi = $_POST['id_ubi'];
         }
         if (isset($_POST['id_alm_def'])) {
            $id_alm = $_POST['id_alm_def'];
         }
         if (isset($_POST['id_ubi_def'])) {
            $id_ubi = $_POST['id_ubi_def'];
         }
         if (isset($_POST['id_alm'])) {
            $id_alm = $_POST['id_alm'];
         }
         
         $r = ProductosModel::consulta($_POST['id_prod'], $origen, $id_alm, $id_ubi);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   function consulta_presu()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $origen = "";
         $id_alm = "";
         $id_ubi = '';
         $r = ProductosModel::consulta_presu($_POST['id_prod'], $origen, $id_alm, $id_ubi);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   function consulta01()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_prod = $_POST['id_prod'];
         $id_fab = $_POST['id_fab'];
         $id_emp = $_POST['id_emp'];
         $fecha = $_POST['fecha'];
         $r = ProductosModel::consulta($id_prod, $id_fab, $id_emp, $fecha);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   function consulta99()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_prod = 0;
         $id_fab = 0;
         $id_emp = $_POST['id_emp'];
         if (isset($_POST['id_prod']) && $_POST['id_prod'] != '') {
            $id_prod = $_POST['id_prod'];
         }
         if (isset($_POST['id_fab']) && $_POST['id_fab'] != '') {
            $id_fab = $_POST['id_fab'];
         }
         $r = ProductosModel::consulta99($id_prod, $id_emp, $id_fab);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   //Llamar impresion de etiquetas
   function printer_labels()
   {
      //$rl = ProductosModel::labels($_POST['id_prod']);
      //echo json_encode($rl);
   }
   //Mostrar imagenes del producto
   function showImg()
   {
      $img = ProductosModel::showImg($_POST['id_prod_img']);
      echo json_encode($img);
   }
   //Total productos
   public function tot_prod()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $r = ProductosModel::tot_prod();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   //Stock de Productos
   public function stockProducto()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id = $_POST['id'];
         $id_ent = 0;
         if (isset($_POST['id_ent'])) {
            $id_ent = $_POST['id_ent'];
         }
         $r = ProductosModel::stockProducto($id, $id_ent);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_productos_modal()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_fab = '';
         $id_alm = '';
         $id_emp = 0;
         $stock = 0;
         if (isset($_POST['stock'])) {
            $stock = $_POST['stock'];
         }
         //if(isset($_POST['id_emp'])){
         //   $id_emp = $_POST['id_emp'];
         //}
         if (isset($_POST['id_fab'])) {
            $id_fab = implode(',', $_POST['id_fab']);
         }
         $id_alm_sql = ProductosModel::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999");
         $id_alm = $id_alm_sql[0]['id_alm'];
         if (isset($_POST['id_alm'])) {
            $id_alm = implode(',', $_POST['id_alm']);
         }

         $r = ProductosModel::listar_productos_modal($stock, $id_emp, $id_alm, $id_fab);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_productos_modal_consig()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_emp = '';
         $fec_fin = '';
         $id_alm = '';
         $id_ubi = 0;
         $id_fab = '';
         $id_prod = '';
         if(isset($_POST['id_emp'])){
            $id_emp = $_POST['id_emp'];
         }
         if(isset($_POST['fec_fin'])){
            $fec_fin = $_POST['fec_fin'];
         }
         if(isset($_POST['id_alm'])){
            $id_alm = $_POST['id_alm'];
         }
         if (!empty($_POST['id_ubi'])) {
            $id_ubi = $_POST['id_ubi'];
         }
         if(isset($_POST['id_fab'])){
            $id_fab = $_POST['id_fab'];
         }
         if(isset($_POST['id_prod'])){
            $id_prod = $_POST['id_prod'];
         }
         $r = ProductosModel::listar_productos_modal_consig($id_emp, $fec_fin, $id_alm, $id_ubi, $id_fab, $id_prod);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function listar_productos_modal_reserva()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_alm = $_POST['id_alm'];
         $id_ent = $_POST['id_cli'];
         $r = ProductosModel::listar_productos_modal_reserva($id_alm, $id_ent);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function val_cod_pro()
   {
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $jsonData = array();
         $cod_prod = limpiar($_POST['cod_prod']);
         $id = $_POST['id'];
         $rows = ProductosModel::val_cod_prod($cod_prod, $id);
         if ($rows[0]['totrows'] <= 0) {
            $jsonData['success'] = 0;
            $jsonData['message'] = '';
         } else {
            $jsonData['success'] = 1;
            $jsonData['message'] = 'Código ya existe...';
         }
         echo json_encode($jsonData);
      }
   }
   public function charge_history(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id = $_POST['id'];
         $r = ProductosModel::charge_history($id);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function cargar_screen_main() {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $r = ProductosModel::all();
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function stock_consig(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_fab = '';
         $id_alm = '';
         $id_ubi = '0';
         $id_emp = $_POST['id_emp'];
         $id_alm = $_POST['id_alm'];
         $id_ubi = $_POST['id_ubi'];
         $fec_fin = $_POST['fec_fin'];
         $id_prod = '0';
         if (isset($_POST['id_alm'])) {
            $id_alm = implode(',', $_POST['id_alm']);
         }
         if (isset($_POST['id_fab'])) {
            $id_fab = implode(',', $_POST['id_fab']);
         }
         if (isset($_POST['id_ubi']) && $_POST['id_ubi'] != '0') {
            $id_ubi = $_POST['id_ubi'];
         }
         if(isset($_POST['id_prod']) && $_POST['id_prod'] != 0){
            $id_prod = $_POST['id_prod'];
         }
         $r = ProductosModel::stock_consig($id_emp, $id_alm, $id_ubi, $fec_fin, $id_fab, $id_prod);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function stock_ppal(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
         $id_emp = 0;
         $id_fab = '0';
         $id_alm = '';
         $id_ubi = '0';
         $id_emp = $_POST['id_emp'];
         $id_alm = $_POST['id_alm'];
         $id_ubi = $_POST['id_ubi'];
         $fec_fin = $_POST['fec_fin'];
         if (isset($_POST['id_emp']) && $_POST['id_emp'] != '0') {
            $id_emp = $_POST['id_emp'];
         }
         if (isset($_POST['id_alm'])) {
            $id_alm = implode(',', $_POST['id_alm']);
         }
         if (isset($_POST['id_fab'])) {
            $id_fab = implode(',', $_POST['id_fab']);
         }
         if (isset($_POST['id_ubi']) && $_POST['id_ubi'] != '0') {
            $id_ubi = $_POST['id_ubi'];
         }
         $r = ProductosModel::stock_ppal($id_emp, $id_alm, $id_ubi, $fec_fin, $id_fab);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
   public function repxconsumo(){
      $this->views->getView($this, "repxconsumo", [
         'page_name' => "Reporte por Consumo",
         'function_js' => "repxconsumo.js"
      ]);
   }
   public function repxconsumo_data(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
         $id_emp = $_POST['id_emp'];
         $fec_ini = $_POST['fec_ini'];
         $fec_fin = $_POST['fec_fin'];
         $id_cli = $_POST['id_cli'];
         $id_fab = '';
         if(isset($_POST['id_fab'])){
            $id_fab = implode(',', $_POST['id_fab']);
         }
         $id_tipocliente = $_POST['id_tipocliente'];
         $r = ProductosModel::repxconsumo_data($id_emp, $id_cli, '', $id_tipocliente, $id_fab, $fec_ini, $fec_fin);
         echo json_encode($r, JSON_UNESCAPED_UNICODE);
      }
   }
}
