<?php
class Productos extends Controller{
	public function __construct(){
		Auth::noAuth();
		parent::__construct();
		Permisos::getPermisos(52);
	}
	public function index(){
		if (empty($_SESSION['permisosMod']['r'])) {
			header('Location:' . base_url . '/Perfil');
		}
		$objeto = ProductosModel::all();
		$this->views->getView($this, "index", [
			'page_name' => "Consulta de Productos",
			'function_js' => "Productos.js",
			'function_js_mod' => "INVFun.js",
			'objeto' => to_obj($objeto)
		]);
	}
	public function nuevo(){
		$this->views->getView($this, "nuevo", [
			'page_name' => "Nuevo Producto",
			'function_js' => "Productos.js",
			'function_js_mod' => "INVFun.js",
		]);
	}
	public function edit(string $idn){
		$valor = '';
		$status = explode("-", $idn);
		$id = $status[0];
		if (isset($status[1])) {
			$valor = $status[1];
		}
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
				'function_js_mod' => "INVFun.js",
				'r' => to_obj($r),
				'valor' => $valor
			]);
		} else {
			header('Location:' . base_url . '/Productos');
		}
		return;
	}
	function listarArchivos(string $path, int $id){
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
	public function destroy(){
		$dataJson = [];
		$id = intval(limpiar($_POST['id']));
		$name = $_POST['name'];
		$code = $_POST['code'];
		//$idimg = ProductosModel::borrarimg($id);
		//$idlabel = ProductosModel::borrar_etiqueta($id);
		$ide = ProductosModel::borrar($id);
		if ($ide) {
			$dataJson = [
				'status' => true,
				'icon' => 'success',
				'title' => 'Inactivado!',
				'msg' => sprintf('El registro %s, con la descripción %s y el código %s se ha inactivado correctamente', $id, $name, $code),
			];
		}
		echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
	}
	public function borrarimg(){
		$dataJson = [];
		$id = intval(limpiar($_POST['id']));
		$ruta = limpiar($_POST['ruta']);
		$code = limpiar($_POST['code']);
		$idimg = ProductosModel::borrarimg($id);
		unlink($code);
	}
	function listar_productos(){
		$r = ProductosModel::listar_productos();
		echo json_encode($r);
	}
	function consulta(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$origen = "";
			$id_alm = "";
			$id_ubi = '';
			if (isset($_POST['origen'])) {
				$origen = $_POST['origen'];
			}
			if (isset($_POST['id_ubi'])) {
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
	function consulta_presu(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$r = ProductosModel::consulta_presu($_POST['id_prod']);
			echo json_encode($r, JSON_UNESCAPED_UNICODE);
		}
	}
	function consulta01(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id_prod = $_POST['id_prod'];
			$id_fab = $_POST['id_fab'];
			$id_emp = $_POST['id_emp'];
			$fecha = $_POST['fecha'];
			$r = ProductosModel::consulta($id_prod, $id_fab, $id_emp, $fecha);
			echo json_encode($r, JSON_UNESCAPED_UNICODE);
		}
	}
	function consulta99(){
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
	function printer_labels(){
		//$rl = ProductosModel::labels($_POST['id_prod']);
		//echo json_encode($rl);
	}
	//Mostrar imagenes del producto
	function showImg(){
		if($_SERVER["REQUEST_METHOD"] == 'POST'){
			$id = $_POST['id'];
			$img = ProductosModel::showImg($id);
			echo json_encode($img);
		}
		
	}
	//Total productos
	public function tot_prod(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$r = ProductosModel::tot_prod();
			echo json_encode($r, JSON_UNESCAPED_UNICODE);
		}
	}
	//Stock de Productos
	public function stockProducto(){
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
	public function listar_productos_modal(){
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
	public function listar_productos_modal_consig(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id_emp = '';
			$fec_fin = '';
			$id_alm = '';
			$id_ubi = 0;
			$id_fab = '';
			$id_prod = '';
			if (isset($_POST['id_emp'])) {
				$id_emp = $_POST['id_emp'];
			}
			if (isset($_POST['fec_fin'])) {
				$fec_fin = $_POST['fec_fin'];
			}
			if (isset($_POST['id_alm'])) {
				$id_alm = $_POST['id_alm'];
			}
			if (!empty($_POST['id_ubi'])) {
				$id_ubi = $_POST['id_ubi'];
			}
			if (isset($_POST['id_fab'])) {
				$id_fab = $_POST['id_fab'];
			}
			if (isset($_POST['id_prod'])) {
				$id_prod = $_POST['id_prod'];
			}
			$r = ProductosModel::listar_productos_modal_consig($id_emp, $fec_fin, $id_alm, $id_ubi, $id_fab, $id_prod);
			echo json_encode($r, JSON_UNESCAPED_UNICODE);
		}
	}
	public function listar_productos_modal_reserva(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id_alm = $_POST['id_alm'];
			$id_ent = $_POST['id_cli'];
			$r = ProductosModel::listar_productos_modal_reserva($id_alm, $id_ent);
			echo json_encode($r, JSON_UNESCAPED_UNICODE);
		}
	}
	public function val_cod_pro(){
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
	public function cargar_screen_main(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$datos_tabla = [];
			$r = ProductosModel::all();
			// Creamos los tokens para cada acción
			foreach($r as $p){
				$token_editar = encriptar_url(json_encode(['accion' => 'edit', 'id' => $p['id_prod']]));
				$datos_tabla[] = [
					'adicional' => $p['adicional'],
					'admin' => $p['admin'],
					'alto' => $p['alto'],
					'ancho' => $p['ancho'],
					'bultos' => $p['bultos'],
					'cod2_prod' => $p['cod2_prod'],					
					'cod_prod' => $p['cod_prod'],
					'con_cons_prod' => $p['con_cons_prod'],
					'conv_prod_cons' => $p['conv_prod_cons'],
					'costo_prod' => $p['costo_prod'],
					'des_prod' => $p['des_prod'],
					'door_costo' => $p['door_costo'],
					'door_prod' => $p['door_prod'],
					'empaque' => $p['empaque'],
					'flete_prod' => $p['flete_prod'],
					'fotos' => $p['fotos'],
					'gen_prod' => $p['gen_prod'],
					'grupo_nombre' => $p['grupo_nombre'],
					'id_prod' => $p['id_prod'],
					'interno_prod' => $p['interno_prod'],
					'iva_prod' => $p['iva_prod'],
					'largo' => $p['largo'],					
					'lote_prod' => $p['lote_prod'],
					'nom_fab' => $p['nom_fab'],
					'nom_pre' => $p['nom_pre'],
					'nom_prod' => $p['nom_prod'],
					'origen' => $p['origen'],
					'otros_prod' => $p['otros_prod'],
					'recar2_prod' => $p['recar2_prod'],
					'recar_prod' => $p['recar_prod'],
					'ref_prod' => $p['ref_prod'],
					'status' => $p['status'],
					'stock' => $p['stock'],
					'uni_com_prod' => $p['uni_com_prod'],
					'uni_ven_prod' => $p['uni_ven_prod'],
					'venta2_prod' => $p['venta2_prod'],
					'ventas_prod' => $p['ventas_prod'],
					'creado_por' => $p['creado_por'],
					'create_date' => $p['create_date'],
					'modificado_por' => $p['modificado_por'],
					'modify_date' => $p['modify_date'],
					"token_edit"  => $token_editar,
				];
			}
			echo json_encode($datos_tabla, JSON_UNESCAPED_UNICODE);
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
			if (isset($_POST['id_prod']) && $_POST['id_prod'] != 0) {
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
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id_emp = $_POST['id_emp'];
			$fec_ini = $_POST['fec_ini'];
			$fec_fin = $_POST['fec_fin'];
			$id_cli = $_POST['id_cli'];
			$id_fab = '';
			if (isset($_POST['id_fab'])) {
				$id_fab = implode(',', $_POST['id_fab']);
			}
			$id_tipocliente = $_POST['id_tipocliente'];
			$r = ProductosModel::repxconsumo_data($id_emp, $id_cli, '', $id_tipocliente, $id_fab, $fec_ini, $fec_fin);
			echo json_encode($r, JSON_UNESCAPED_UNICODE);
		}
	}
	public function store(){
		if ($_SERVER["REQUEST_METHOD"] == "POST") {					
			$data = array();
			$dataJson = array();
			//Asignar valores a variables
			$stock_minimo = 0;
			$commet_prod = '';
			$id_fab_fac = null;
			foreach ($_POST as $key => $value) {
				$$key = $value;
			}
			if (empty($id)) {
				$modo = "create_user";
			}
			try {
				$data += [
					'cod_prod' => $cod_prod,
					'cod2_prod' => $cod2_prod,
					'nom_prod' => $nom_prod,
					'id_pre' => $id_pre,
					'id_grupo' => $id_grupo,
					'id_sub_grupo' => $id_sub_grupo,
					'conv_prod_cons' => $conv_prod_cons,
					'id_fab' => $id_fab,
					'id_fab_fac' => $id_fab_fac ? : null,
					'ref_prod' => $ref_prod,
					'gen_prod' => $gen_prod,
					'des_prod' => $des_prod,
					'uni_com_prod' => $uni_com_prod ?: 0,
					'uni_ven_prod' => $uni_ven_prod ?: 0,
					'iva_prod' => !empty($iva_prod) ? 1 : 0,
					'con_cons_prod' => $con_cons_prod ? : 0,
					'lote_prod' => !empty($lote_prod) ? 1 : 0,
					'interno_prod' => !empty($interno_prod) ? 1 : 0,
					'door_prod' => !empty($door_prod) ? 1 : 0,
					'status' => $status,
					'alto' => convert_string_to_number($alto) ? : 0,
					'ancho' => convert_string_to_number($ancho) ? : 0,
					'largo' => convert_string_to_number($largo) ? : 0,
					'origen' => $origen,
					'id_presen1' => $id_presen1,
					'id_presen2' => $id_presen2,
					'stock_minimo' => $stock_minimo ?:  0,
					'commet_prod' => $commet_prod
				];				
				if ($_SESSION['administrator'] == 1) {
					$costo_prod = convert_string_to_number($costo_prod) ?: 0;
					$flete_prod = convert_string_to_number($flete_prod) ?: 0;
					$otros_prod = convert_string_to_number($otros_prod) ?: 0;
					$door_costo = convert_string_to_number($door_costo) ?: 0;
					$costo1 = convert_string_to_number($costo1) ?: 0;
					$recar_prod = convert_string_to_number($recar_prod) ?: 0;
					$ventas_prod = convert_string_to_number($ventas_prod) ?: 0;
					$recar2_prod = convert_string_to_number($recar2_prod) ?: 0;
					$venta2_prod = convert_string_to_number($venta2_prod) ?: 0;
					$data += [
						'costo_prod' => $costo_prod,
						'flete_prod' => $flete_prod,
						'otros_prod' => $otros_prod,
						'door_costo' => $door_costo,
						'costo1' => $costo1,
						'recar_prod' => $recar_prod,
						'ventas_prod' => $ventas_prod,
						'recar2_prod' => $recar2_prod,
						'venta2_prod' => $venta2_prod,
					];					
				}
				if (empty($id)) {
					$modo = 'create_user';
					$data += [$modo => $_SESSION['id_user']];
					$id = ProductosModel::guardar($data);
					$title = "Se ha guardado el Producto satisfactoriamente";
				} else {
					$modo = 'modify_user';
					$data += [$modo => $_SESSION['id_user']];
					$id = ProductosModel::actualizar($_POST['id'], $data);
					$id = $_POST['id'];
					$title = "Se ha modificado el Producto satisfactoriamente";
				}
				$icon = "success";
				//Guardar Etiqueta
				if (isset($nomcor_prod) && !empty($nomcor_prod)) {					
					$data = array();
					$data += [
						'id_prod' => intval($id),
						'nomcor_prod' => $nomcor_prod,
						'marcom_prod' => $marcom_prod,
						'fabpor_prod' => $fabpor_prod,
						'cpe_prod' => $cpe_prod,
						'connetpro_prod' => $connetpro_prod,
						'connetcaj_prod' => $connetcaj_prod,
						'regsan_prod' => $regsan_prod,
						'uso_prod' => $uso_prod,
					];
					$existe = ProductosModel::consultar_etiqueta($id);
					if ($existe) {
						$modo = 'modify_user';
						$data += [$modo => $_SESSION['id_user']];
						$r = ProductosModel::editar_etiqueta($id, $data);
					} else {
						$modo = 'create_user';
						$data += [$modo => $_SESSION['id_user']];
						$r = ProductosModel::guardar_etiqueta($data);
					}
				}							
				//ALmacenar Imagenes
				if (isset($_FILES['url_photo']) && (count($_FILES['url_photo']) != 0)) {
					$ruta = ROOT . DS . 'Assets' . DS . 'img' . DS . 'products';
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
				$msg = sprintf("El Producto código: %s, Descripción %s se ha salvado satisfactoriamente, con el ID %s", $cod_prod, $nom_prod, $id);
				$dataJson = [
					'title' => $title,
					'icon' => "success",
					'msg' => $msg
				];
			} catch (\PDOException $e) {
				$title = "Se ha presentado un error, intente luego";
				$msg = sprintf("Error código: %s, Descripción del Error %s", $e->getCode(), $e->getMessage());
				$dataJson = [
					'title' => $title,
					'icon' => "error",
					'msg' => $msg
				];
			}
			echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
		}
	}
	public function show_row(){
		if($_SERVER["REQUEST_METHOD"] == 'POST'){
			$id = $_POST['id'];
			$r = ProductosModel::show_row($id);
			echo json_encode($r, JSON_UNESCAPED_UNICODE);
		}
	}
	public function gestion($token = null){
		if(!$token){
			return;
		}
		$datos = desencriptar_url($token);	
		switch ($datos['accion']) {
        case 'edit':
            $this->edit($datos['id']);
            break;
        default:
            // Acción no permitida
            break;
    }
	}
}
