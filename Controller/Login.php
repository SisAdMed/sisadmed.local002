<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
class Login extends Controller{
    public function __construct(){
        if (isset(($_SESSION['login']))) {
            header('Location:' . base_url . '/Perfil');
        }
        parent::__construct();
    }
    public function index(){
        $data['title'] = 'Ingreso al sistema';
        $data['function_js'] = 'Login.js';
        $this->views->getview($this, 'index', $data);
    }
    public function ingresar(){        
        $arrJson = [];
        $usuario = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $val = new Validations();
            $usuarioValue = isset($_POST['usuario']) ? limpiar($_POST['usuario']) : '';
            $passwordValue = isset($_POST['password']) ? limpiar($_POST['password']) : '';          
            if (isset($usuarioValue) && isset($passwordValue)) {
                //Loguearse
                $usuario = LoginModel::login($usuarioValue, hash("sha256", $passwordValue));
                if (empty($usuario)) {
                    $arrJson = ['error' => 'Estas credenciales no existen en el sistema o el usuario no existe'];
                }elseif ($usuario['status_user'] != 1){
                    $arrJson = ['error' => 'Usuario se encuentra inactivo. Favor contactar al Administrador del Sistema'];
                } else {
                    //crear sesiones
                    $_SESSION['id_user'] = $usuario['id_user'];
                    $_SESSION['code_user'] = $usuario['code_user'];
                    $_SESSION['name_user'] = !empty($usuario['name_user']) ? $usuario['name_user'] : '';
                    $_SESSION['last_user'] = !empty($usuario['last_user']) ? $usuario['last_user'] : '';
                    $_SESSION['full_name'] = !empty($usuario['name_user']) ? $usuario['name_user'] : '' . ' ' ;
                    $_SESSION['full_name'] .= !empty($usuario['last_user']) ? ' ' . $usuario['last_user'] : '';
                    $_SESSION['last_login'] = $usuario['last_login'];
                    $_SESSION['photo_user'] = !empty($usuario['photo_user']) ? IMG . 'users/'. $usuario['photo_user'] : '';
                    $_SESSION['id_rol'] = $usuario['id_rol'];
                    $_SESSION['login'] = true;
                    $_SESSION['time'] = time();
                    $_SESSION['appdis'] = $usuario['appdis'];
                    $_SESSION['ori'] = '';
                    $_SESSION['tipo_fact'] = '';
                    $_SESSION['page_name'] = '';
                    $_SESSION['administrator'] = $usuario['administrator'] ?? 0;
                    //$_SESSION['show_rows'] = $usuario['show_rows'];
                    Auth::sessionUser($_SESSION['id_user']);
                    $arrJson = ['msg' => 'Usuario logueado'];
                }
            } else {
                $arrJson = ['error' => $val->getErrors()];
            }
        }        
        echo json_encode($arrJson, JSON_UNESCAPED_UNICODE);
    }
    public function ChangePassword(){
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_user = $_SESSION['id_user'];
            $password = hash("sha256", limpiar($_POST['password']));
            $data = array();
            $data = ['password_user' => $password];
            $ChangePassword = LoginModel::ChangePassword($id_user, $data);
            echo json_encode($ChangePassword, JSON_UNESCAPED_UNICODE);
        }
    }
}