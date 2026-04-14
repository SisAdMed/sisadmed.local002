
<?php 
    if(isset($_GET['tipo'])){
        $tipo = $_GET['tipo'];
        $title = 'MAYOR ANALITICO DETALLADO DEL MODULO DE ';
        switch($tipo){
            case 'B':
                $title .= 'BANCOS';
                break;
            case 'C':
                $title = 'CUENTAS POR COBRAR';
                break;
            case 'P':
                $title .= 'CUENTAS POR PAGAR';
                break;
            case 'I':
                $title = 'INVENTARIOS';
                break;
            case 'I':
                $title = 'COMPRAS';
                break;
            case 'F':
                $title = 'FACTURACIÖN';
                break;
            case 'N':
                $title = 'NOMINAS';
                break;
        }
        if(isset($_SESSION['mayanamod'])){
            unset($_SESSION['mayanamod']);            
        }
        $_SESSION['mayanamod'] = $tipo;
        if(isset($_SESSION['mayanamod_name'])){
            unset($_SESSION['mayanamod_name']);            
        }
        $_SESSION['mayanamod_name'] = $title;
    }
    class MayAnaMod extends Controller{
        public function __construct(){
            Auth::noAuth();
            parent::__construct();
            Permisos::getPermisos(187);
        }
        public function index(){
            $this->views->getView($this, 'index', [
                'page_name' =>$_SESSION['mayanamod_name'],
                'function_js' => 'MayAnaMod.js'
            ]);
        }
        public function AnaliticoBan(){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $id_aux = '';
                $id_emp = $_POST['id_emp'];
                $fec_ini = $_POST['fec_ini'];
                $fec_fin = $_POST['fec_fin'];
                $id_ctb = $_POST['id_ctb'];
                if(isset($_POST['id_aux'])){
                    $id_aux = $_POST['id_aux'];
                }
                
                $r = MayAnaModModel::AnaliticoBan($id_emp, $fec_ini, $fec_fin, $id_ctb, $id_aux);
                echo json_encode($r, JSON_UNESCAPED_UNICODE);
            }
        }
    }