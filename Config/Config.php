<?php
const base_url = 'http://sisadmed.local002';
//const base_url = 'https://sisadmed.corp-mmq.com';
//QA
//const base_url = 'https://sisadmed.josvarsistemas.com.ve';
//Establecer zona
date_default_timezone_set("America/Caracas");
//Definir lenguaje
define('SITE_LANG', 'es');
setlocale(LC_TIME, 'es_VE.UTF-8','esp');
//Definir Moneda
setLocale(LC_ALL, 'America/Caracas');
setlocale(LC_ALL, 'es_VE');
/*----------------------------------------------------*/
/*  CONTANTES PAGINAS DE NAVEGACION                  */
/*----------------------------------------------------*/
const PERFIL = 4;
const DASHBOARD = 13;
const USUARIOS = 3;
const ROLES = 2;
const PRODUCTOS = 26;
/*----------------------------------------------------*/
/*  CONTANTES PARA CONEXION A LA DB                   */
/*----------------------------------------------------*/
const DB_HOST = "localhost";
//Main
const DB_NAME = "josvarsi_db_sisadmed";
const DB_USER = "josvarsi_usersisadmed";
//QA
//const DB_NAME = "josvarsi_db_qa_sisadmed";
//const DB_USER = "josvarsi_usersisadmed";

const DB_PASSWORD = "SaimiCaimi96.";
const DB_PORT = "3306";
const DB_CHARSET = "utf8";
/*----------------------------------------------------*/
/*  INFORMACION DEL SITIO*                            */
/*----------------------------------------------------*/
define('SITE_NAME', 'SisAdMed');
define('SITE_CHARSET', 'UTF-8');
define('SITE_VERSION', '1.1.115');
define('SITE_LOGO', 'logo.png');
define('SITE_FAVICON', 'favicon.ico');
define('SITE_DESC', 'Sistema Administrativo y Médico' .' - ' . SITE_NAME);
define('SITE_LOGO_MAIN', 'main.logo.png');
define('SITE_TIME_LOGIN', 36000);
/*----------------------------------------------------*/
/*  DIRECTORIOS DEL LA APP                            */
/*----------------------------------------------------*/
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));
define('CONTROLLER', ROOT . DS . 'Controller');
define('MODELS', ROOT . DS . 'Models' . DS);
define('VIEW', ROOT . DS . 'Views');
define('TEMPLATE', VIEW . DS . 'Templates');
define('IMAGE_PATH', ROOT. DS. "Assets" . DS . 'img' .DS);
define('FPDF1', ROOT. DS. "Assets" . DS . 'fpdf' .DS);
define('PHPPDF', ROOT. DS. "Assets" . DS . 'smalot' .DS . 'vendor' . DS . 'smalot' . DS . 'pdfparser' . DS) ;
define('SPREADEXCEL', ROOT .DS. "Assets" .DS. 'generate_excel' .DS );
define('EMAILER', ROOT. DS. "Assets" .DS);
define('VARTAX', ROOT.DS.'Models'.DS.'VatTaxModel.php');
define('CXPDOCMODEL', ROOT. DS . 'Models' . DS . 'CXPDocumentModel.php');
define('CXPCONMODEL', ROOT. DS . 'Models' . DS . 'ConcepCXPModel.php');
define('DELNOTFISMODEL', ROOT. DS . 'Models' . DS . 'DelnotnotfisModel.php');
define('TIPDOCCXPMODEL', ROOT. DS . 'Models' . DS . 'TipoDocCXPModel.php');
define('MOVINMODEL', ROOT. DS . 'Models' . DS . 'MovInvModel.php');
define('PRODUCTOMODEL', ROOT. DS . 'Models' . DS . 'ProductosModel.php');
define('CAMBIOMODEL', ROOT. DS . 'Models' . DS . 'CambiosModel.php');
define('COTIZAMODEL', ROOT. DS . 'Models' . DS . 'CotizacionesModel.php');
define('EMPRESAMODEL', ROOT. DS . 'Models' . DS . 'EmpresasModel.php');
define('PHPMAILER', ROOT. DS . 'Assets' . DS . 'vendor' . DS . 'phpmailer' . DS . 'phpmailer');
/*----------------------------------------------------*/
/*  ARCHIVOS PUBLICOS                                 */
/*----------------------------------------------------*/
define('ASSETS', base_url. '/Assets');
define('CSS', ASSETS. '/css');
define('JS', ASSETS. '/js');
define('PLUGINS', ASSETS. '/plugins');
define('IMG', ASSETS. '/img/');
define('UPLOADS', ASSETS. '/uploads');
define('FAVICON', ASSETS. '/favicon/');
/*----------------------------------------------------*/
/*  CONTANTES CONTROLLER - METHODO ERROR POR DEFAULT  */
/*----------------------------------------------------*/
define('CONTROLLER_DEFAULT', 'Login');
define('METHOD_DEFAULT', 'index');
define('CONTROLLER_ERROR', 'Error404');
/*----------------------------------------------------*/
/*           DEFINE UNA LLAVE SECRETA UNICA           */
/*----------------------------------------------------*/
// Define una llave secreta única para tu proyecto
define('METODO_CIFRADO', 'AES-256-CBC');
define('LLAVE_SECRETA', 'RI9AfsEFL9qEakxa4DLHyuuCWQ2sB5GYnCqDEv0odQxQ0aKhWEhSqeig3DFCzko7IXxOgHBkUN5S6q4w44OrjQvUyhAGooMsM3kG3shzhNvW5TMmhGiAZiQFgaECyYA0Y4msreRrz6XMeqRnOzxGZU86YHmLuxz3QjxKxn0JXaGz7iUTszbPYeIlpaXaSROSaQCCZSif'); // Cambia esto por algo complejo
define('IV_SECRETO', '4081541229806746'); // Debe ser de 16 caracteres
function encriptar_url(string $data) {
    // Convertimos el array de datos en un string (ej: "editar-5")
    $dato_serializado = is_array($data) ? json_encode($data) : $data;
    $encriptado = openssl_encrypt($dato_serializado, METODO_CIFRADO, LLAVE_SECRETA, 0, IV_SECRETO);
    // Usamos urlencode para que sea seguro pasarlo por la barra de direcciones
    return urlencode(base64_encode($encriptado));
}

function desencriptar_url(string $token) {
    $token_base64 = base64_decode(urldecode($token));
    $desencriptado = openssl_decrypt($token_base64, METODO_CIFRADO, LLAVE_SECRETA, 0, IV_SECRETO);
    // Intentamos decodificar si era un JSON, si no, devolvemos el string
    $resultado = json_decode($desencriptado, true);
    return $resultado ? $resultado : $desencriptado;
}