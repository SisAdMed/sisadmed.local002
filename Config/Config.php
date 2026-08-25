<?php
// URL Base del proyecto (cambia esto cuando lo subas a producción)
define('base_url', 'https://sisadmed.local002');
define('DB_NAME', "josvarsi_db_sisadmed_20072026");

//define('base_url', 'https://sisadmed.corp-mmq.com');
//define('DB_NAME', "josvarsi_db_sisadmed");

//define('base_url', 'https://sisadmed.josvarsistemas.com.ve');
//define('DB_NAME', "josvarsi_db_sisadmed");


//QA

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
const DB_USER = "josvarsi_usersisadmed";

const DB_PASSWORD = "SaimiCaimi96.";
const DB_PORT = "3306";
const DB_CHARSET = "utf8mb4";
/*----------------------------------------------------*/
/*  INFORMACION DEL SITIO*                            */
/*----------------------------------------------------*/
define('SITE_NAME', 'SisAdMed');
define('SITE_CHARSET', 'UTF-8');
define('SITE_VERSION', trim(file_get_contents(dirname(__DIR__ ). '/VERSION')));
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
define('RUTA_PDF_GRO', ASSETS . '/pdf/groups');
define('UPLOADS', ASSETS. '/uploads');
define('FAVICON', ASSETS. '/favicon/');
define('IMG_CARRUSEL', IMG . 'carousel/');
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

//Notificaciones Push
// Reemplaza con tus claves VAPID generadas previamente
define('VAPID_PUBLIC_KEY', 'BGhSxPWMmmWwhGtTvzaE_nCe6q96yIYZu10dpEAQWP5XFH1-Hdo7sQw-oFWSNF9aep8aOoMpeSC_A8T6TBedaes');
define('VAPID_PRIVATE_KEY', 'iLMJfGz_d9BKufRi1lX8SDGWaBtzDfPAHewyT60W8f0');
define('VAPID_SUBJECT', 'mailto:cheovargas@gmail.com'); // Correo o URL de contacto del sistema

