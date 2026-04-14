<?php
session_start();
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

error_reporting(E_ALL);
//ini_set('ignore_repeated_errors', false);
//ini_set('display_errors', true); // Cambiar a false en producción
//ini_set('log_errors', true);
//ini_set('memory_limit', '1024M');
//ini_set('error_log', '/www/sisadmed/php-errors.log');
//error_log('Inicio del sistema de SisAdMed');

require_once 'Config/Config.php';
require_once 'Helpers/Helpers.php';
$ruta = !empty($_GET['url']) ? $_GET['url'] : CONTROLLER_DEFAULT . "/" . METHOD_DEFAULT;
$array = explode("/", $ruta);
$controller = $array[0];
$metodo = METHOD_DEFAULT;
$parametro = "";
if (!empty($array[1])) {
    if (!empty($array[1]) != "") {
        $metodo = $array[1];
    }
}
if (!empty($array[2])) {
    if (!empty($array[2]) != "") {
        for ($i = 2; $i < count($array); $i++) {
            $parametro .= $array[$i] . ",";
        }
        $parametro = trim($parametro, ",");
    }
}
require_once 'Config/App/autoload.php';
$didController = CONTROLLER . DS . $controller . ".php";
$errorController = CONTROLLER . DS . CONTROLLER_ERROR . ".php";
if (file_exists($didController)) {
    require_once $didController;
    $controller = new $controller();
    if (method_exists($controller, $metodo)) {
        $controller->$metodo($parametro);
    } else {
        require_once $errorController;
        $controller = new Error404;
        $controller->index();
        echo "No existe el Metodo";
    }
} else {
    require_once $errorController;
    $controller = new Error404;
    $controller->index();
    echo "No existe el Controller";
}
