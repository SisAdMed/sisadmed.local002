<?php
//Para las lecturas de excel
require_once(SPREADEXCEL . "/vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\Hyperlink;
use PhpOffice\PhpSpreadsheet\NamedRange;
use PhpOffice\PhpSpreadsheet\Reader\Security\XmlScanner;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\AutoFilter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Chart;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\ColumnAndRowAttributes;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\ConditionalStyles;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\DataValidations;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Hyperlinks;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Properties as PropertyReader;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\SheetViewOptions;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\SheetViews;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx\Styles;
use PhpOffice\PhpSpreadsheet\ReferenceHelper;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Settings;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Shared\Drawing;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Shared\StringHelper;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing;


class ActPrecios extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(60);
    }
    public function index($tipo){
         if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = ActPreciosModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Consulta de Actualizador de precios",
            'function_js' => "ActPrecios.js",
            'objeto' => to_obj($objeto),
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Actualizador de precios ",
            'function_js' => "ActPrecios.js"
        ]);
    }
    public function store(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $modo = 'modify_user';
            $data = array();
            if(empty($_POST['id'])){
                $data = ['fecha_creacion' => $_POST['fecha_creacion']];
                $modo = 'create_user';
            }
            try {
                $archivo_histo = '';
                if(isset($_FILES['archivo_histo']) && (count($_FILES['archivo_histo']) !=0 )){
                    $archivo_histo = $_FILES['archivo_histo']['name'];
                }
                $data += [
                    'fecha_aprobado' => $_POST['fecha_aprobado'],
                    'fecha_vigencia' => $_POST['fecha_vigencia'],
                    'status' => $_POST['status'],
                    'observa' => limpiar($_POST['observa']),
                    'archivo_histo' => $archivo_histo,
                    $modo => $_SESSION['id_user'],
                ];
                if(empty($_POST['id'])){
                    $id = ActPreciosModel::guardar($data);
                    $id_histo = $id;
                    Alertas::new(sprintf('El registro %s se ha creado exitosamente con el id %s', $data['fecha_creacion'], $id));
                }else{
                    $id = ActPreciosModel::actualizar($_POST['id'], $data);
                    $id_histo = $_POST['id'];
                    Alertas::new(sprintf('El registro %s se ha modificado exitosamente con el id %s', $_POST['fecha_creacion'], $id));
                }
                //Cargar datos de archivo
                //Verificar si se esta cargando un archivo nuevo
                if(isset($_FILES['archivo_histo']) && (count($_FILES['archivo_histo']) !=0 )){
                    $ruta = ROOT . DS .'Assets' . DS . 'doc' . DS . 'ActPrecios';
                    if($_FILES['archivo_histo']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' || $_FILES['archivo_histo']['type'] == 'application/vnd.ms-excel' || $_FILES['archivo_histo']['type'] == 'application/vnd.ms-excel.sheet.binary.macroEnabled.12'){
                        //Subimes el fichero al servidor
                        $ext = pathinfo($_FILES['archivo_histo']['name'], PATHINFO_EXTENSION);
                        //$nombreDelArchivo = $_POST['fecha_creacion'] . '.'. $ext;
                        $nombreDelArchivo = date("Y-m-d-h-i-s");
                        $nombreDelArchivo = $nombreDelArchivo ."__".$_FILES['archivo_histo']['name'];
                        $ruta = ROOT . DS .'Assets' . DS . 'doc' . DS . 'ActPrecios';
                        $ruta = $ruta . DS . $nombreDelArchivo;
                        move_uploaded_file($_FILES['archivo_histo']['tmp_name'], $ruta);
                        //
                        //cargar_his_precios($ruta, $id_histo);
                        $spreadsheet = IOFactory::load($ruta);
                        $worksheet = $spreadsheet->getActiveSheet();
                        $rows = $worksheet->toArray();
                        $id_his = ActPreciosModel::borrar_detalles_hisorico_precios($id_histo);
                        $item =0;
                        session_write_close();
                        foreach ($rows as $row) {
                            $item += 1;
                            if($item>2){
                                $id_pro = $row[0];
                                $costo_prod = $row[6];
                                $flete_prod = $row[7];
                                $otros_prod = $row[8];
                                $door_costo = $row[9];
                                $recar_prod = $row[11];
                                $ventas_prod = $row[12];
                                $recar2_prod = $row[27];
                                $venta2_prod = $row[28];
                                $data_det_his = [
                                    'id_pro_his' => $id_histo,
                                    'id_pro' => $id_pro,
                                    'costo_prod' => $costo_prod,
                                    'flete_prod' => $flete_prod,
                                    'otros_prod' => $otros_prod,
                                    'door_costo' => $door_costo,
                                    'recar_prod' => $recar_prod,
                                    'ventas_prod' => $ventas_prod,
                                    'recar2_prod' => $recar2_prod,
                                    'venta2_prod' => $venta2_prod,
                                    'create_user' => $_SESSION['id_user']
                                ];
                                $id_his = ActPreciosModel::guardar_detalles_hisorico_precios($data_det_his);
                            }
                        }
                    }
                }
                if($_POST['status'] == 'A'){
                    $rows = ActPreciosModel::detalle_productos_actualizar($id_histo);
                    foreach ($rows as $value) {
                        $data_up_pro = array();
                        $id_pro =$value->id_pro;
                        $data_up_pro += [
                            'costo_prod' => $value->costo_prod,
                            'flete_prod' => $value->flete_prod,
                            'otros_prod' => $value->otros_prod,
                            'recar_prod' => $value->recar_prod,
                            'ventas_prod' => $value->ventas_prod,
                            'door_costo' => $value->door_costo,
                            'recar2_prod' => $value->recar2_prod,
                            'venta2_prod' => $value->venta2_prod,
                            'create_user' => $_SESSION['id_user'],
                        ];
                        $id = ActPreciosModel::productos_actualizar($id_pro, $data_up_pro);
                    }
                }
            //Volver a la pagina principal
            header('Location:' . base_url . '/ActPrecios');
            //
            } catch (\PDOException $e) {
                Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/ActPrecios');
            }
        }
    }
    public function edit($id){
        if(Permisos::read()){
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = ActPreciosModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/ActPrecios');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el registro de fecha " . formatFecha($r->fecha_creacion) ,
                    'function_js' => "ActPrecios.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/ActPrecios');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/ActPrecios');
    }
}