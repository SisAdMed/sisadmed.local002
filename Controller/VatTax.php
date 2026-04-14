<?php
class VatTax extends Controller{
    public function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(92);
    }
    public function index(){
        if(empty($_SESSION['permisosMod']['r'])){
            header('Location:' . base_url . '/Perfil');
        }
        $r = VatTaxModel::all();
        $this->views->getView($this, 'index', [
            'page_name' => 'Impuestos IVA',
            'function_js' => 'VatTax.js',
            'objeto' => to_obj($r)
        ]);
    }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Impuesto IVA",
            'function_js' => "VatTax.js"
        ]);
    }
    public function edit($id){
        if (Permisos::read()) {
            $id = intval(limpiar($id));
            if ($id > 0) {
                $r = VatTaxModel::edit($id);
                if (empty($r)) {
                    Alertas::new('El registro no existe', 'warning');
                    header('Location:' . base_url . '/VatTax');
                }
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el Impuesto  " . $r['cod_iva'] . ' ' . $r['des_iva'] . ' de fecha ' . formatFecha($r['fec_iva']) ,
                    'function_js' => "VatTax.js",
                    'r' => to_obj($r)
                ]);
            } else {
                header('Location:' . base_url . '/VatTax');
            }
            return;
        }
        Alertas::new('No tiene permiso para realizar esta acción', 'warning');
        header('Location:' . base_url . '/VatTax');
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
                    'cod_iva' => limpiar($_POST['cod_iva']),
                    'des_iva' => limpiar($_POST['des_iva']),
                    'fec_iva' => $_POST['fec_iva'],
                    'status' => $_POST['status'],
                    $modo => $_SESSION['id_user']
                ];
                if($_POST['txr1_iva'] != ''){
                    $data += ['txr1_iva' => str_replace(',', '.', number_format($_POST['txr1_iva'], 4))];
                }
                if($_POST['txr2_iva'] != ''){
                    $data += ['txr2_iva' => str_replace(',', '.', number_format($_POST['txr2_iva'], 4))];
                }
                if($_POST['txr3_iva'] != ''){
                    $data += ['txr3_iva' => str_replace(',', '.', number_format($_POST['txr3_iva'], 4))];
                }
                if($_POST['txr4_iva'] != ''){
                    $data += ['txr4_iva' => str_replace(',', '.', number_format($_POST['txr4_iva'], 4))];
                }
                if($_POST['txr5_iva'] != ''){
                    $data += ['txr5_iva' => str_replace(',', '.', number_format($_POST['txr5_iva'], 4))];
                }
                if(empty($_POST['id'])){
                    $id = VatTaxModel::guardar($data);
                    Alertas::new(sprintf('El impuesto %s - %s de fecha  %s se ha creado exitosamente', $_POST['cod_iva'], $_POST['des_iva'], $$_POST['fec_iva']));
                }
                else{
                    $id = VatTaxModel::actualizar($data, $_POST['id']);
                    $id = $_POST['id'];
                    Alertas::new(sprintf('El impuesto %s - %s de fecha %s se ha modificado exitosamente',$_POST['cod_iva'], $_POST['des_iva'], $$_POST['fec_iva']));
                }
                header('Location:' . base_url . '/VatTax');
            } catch (Exception $e) {
                 Alertas::new($e->getMessage(), 'danger');
                header('Location:' . base_url . '/VatTax');
            }
        }
    }
    public function showrow(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $r = VatTaxModel::showrow($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function ratevatTax(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $fecha = $_POST['fecha'];
            $vatTax = $_POST['vatTax'];
            $r = VatTaxModel::ratevatTax($fecha, $vatTax);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
}