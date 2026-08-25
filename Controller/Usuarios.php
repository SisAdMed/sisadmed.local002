<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

require 'Assets/vendor/autoload.php';
class Usuarios extends Controller
{
    public function __construct()
    {
        Auth::noAuth();
        Permisos::getPermisos(3);
        parent::__construct();
    }
    public function index()
    {
        if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = UsuariosModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Consulta de Usuarios",
            'function_js' => "Usuarios.js",
            'function_js_mod' => "GENFUN.js",
            'objeto' => to_obj($objeto)
        ]);
    }
    public function edit($id)
    {
        if (Permisos::read()) {
            $idusuario = intval(limpiar($id));
            if ($idusuario > 0) {
                $usuario = UsuariosModel::oneUser($idusuario);
                $roles = UsuariosModel::rolesAll();
                $this->views->getView($this, "edit", [
                    'page_name' => "Editando el usuario " . $usuario[0]['name_user'] . ' ' . $usuario[0]['last_user'],
                    'function_js' => "Usuarios.js",
                    'usuario' => to_obj($usuario[0]),
                    'roles' => $roles,
                ]);
            } else {
                header('Location:' . base_url . '/Usuarios');
            }
        }
    }
    public function all()
    {
        $arrJson = [];
        $users = UsuariosModel::all();
        if (empty($users)) {
            $arrJson = ['msg' => 'No se encontraron registros'];
        } else {
            for ($i = 0; $i < count($users); $i++) {
                if ($users[$i]['status_user'] == 1) {
                    $users[$i]['status_user'] = '<span class="badge badge-success">Activo</span>';
                } else {
                    $users[$i]['status_user'] = '<span class="badge badge-danger">Inactivo</span>';
                }
            }
            $arrJson = $users;
        }
        echo json_encode($arrJson, JSON_UNESCAPED_UNICODE);
    }

    public function nuevo()
    {
        $roles = UsuariosModel::rolesAll();
        $data['roles'] = $roles;
        $data['page_name'] = 'Nuevo Usuario';
        $data['function_js'] = 'Usuarios.js';
        $data['function_js_mod'] = 'GENFUN.js';

        $this->views->getView($this, 'nuevo', $data);
    }
    public function store()
    {
        //Guardar
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $data = array();
            $dataJson = [];
            $modo_user = 'create_user';
            $modo_date = 'create_date';
            $send_mail = false;
            //Asignar valores a variables
            foreach ($_POST as $key => $value) {
                $$key = $value;
            }
            try {
                if (!empty($id)) {
                    $modo_user = 'modify_user';
                    $modo_date = 'modify_date';
                }
                $data = [
                    'id_rol' => limpiar($id_rol),
                    'code_user' => limpiar($code_user),
                    'name_user' => limpiar($name_user),
                    'last_user' => limpiar($last_user),
                    'status_user' => limpiar($status_user),
                    'last_login' => isset($last_login) ? $last_login : null,
                    'email_user' => limpiar($email_user),
                    'administrator' => isset($administrator) ? 1 : 0,
                    'appdis' => isset($appdis) ? 1 : 0,
                    $modo_user => $_SESSION['id_user'],
                    $modo_date => date("Y-m-d H:i:s"),
                ];
                if ($password_user != ''  && $repassword_user != '') {
                    $send_mail = true;
                    $data += [
                        'password_user' => hash("sha256", limpiar($password_user))
                    ];
                }
                if (empty($_POST['id'])) {
                    $id = UsuariosModel::save($data);
                    $id_img = $id;
                    $msg = sprintf('El usuario %s %s se ha creado exitosamente con el id %s', $data['name_user'], $data['last_user'], $id);
                } else {
                    $id = UsuariosModel::updateUser($data, $_POST['id']);
                    $id_img = $_POST['id'];
                    $msg = sprintf('El usuario %s %s se ha modificado exitosamente con el id %s', $data['name_user'], $data['last_user'], $id);
                }
                //Almacenar imagenes
                $nombreDelArchivo = 'iuser.jpg';
                if (isset($_FILES['url_photo']) && ($_FILES['url_photo']['tmp_name']) != 0) {
                    if ($_FILES['url_photo']['type'] == 'image/png' || $_FILES['url_photo']['type'] == 'image/jpeg') {
                        //Subimes el fichero al servidor
                        $nombreDelArchivo = $_FILES["url_photo"]["name"];
                        $ext = pathinfo($nombreDelArchivo, PATHINFO_EXTENSION);
                        $nombreDelArchivo = $id . '-' . $ext;
                        $ruta = ROOT . DS . 'Assets' . DS . 'img' . DS . 'users';
                        $ruta = $ruta . DS . $nombreDelArchivo;
                        if (move_uploaded_file($_FILES['url_photo']['tmp_name'], $ruta)) {
                            //Almacenar en la base de datos
                            $data = array();
                            $data = ['photo_user' => $nombreDelArchivo];
                            $save = UsuariosModel::updateUser($data, $id_img);
                            $msg .= ' y se ha subido la imagen correctamente';
                        } else {
                            $msg .= ' pero no se ha podido subir la imagen';
                        }
                    }
                }
                //$send_mail = false;
                if ($send_mail) {

                    $filename       = 'CambiodeClave.pdf';
                    $path   = '/home/cpuj4gbphmxn/Manuals';
                    $file  = $path . "/" . $filename;;
                    $content = file_get_contents($file);
                    $content = chunk_split(base64_encode($content));
                    // a random hash will be necessary to send mixed content
                    $separator = md5(time());
                    $eol = '<br>';
                    // 2. Enviar correo usando PHPMailer                    
                    $to      = $email_user;
                    $subject = 'Datos de Ingreso al Sistema SisAdMed';
                    $mensaje = 'Buen día <b>' . $name_user . ' ' . $last_user . '</b>' . $eol . $eol;
                    $mensaje .= 'A continuación le indicamos su usuario y contraseña para ingresr al Sistema SisAdMed ' . $eol . $eol;
                    $mensaje .= '<ul><li>Usuario: <b>' . $code_user . '</b>' . '</li>' . $eol;
                    $mensaje .= ' <li>Contraseña: <b>' . $password_user . '</b>' . '</li></ul>' . $eol;
                    $mensaje .= "Link para ingresar al Sistema <a href='https://sisadmed.corp-mmq.com' target='_blank'>SisAdMed</a>" . $eol . $eol;
                    $mensaje .= 'Se sugiere que al ingresar por primera vez, proceda con el cambio de clave, siguiendo las instrucciones del archivo adjunto.' . $eol . $eol;
                    $mensaje .= 'Saludos cordiales' . $eol . $eol;
                    $mensaje .= '<b>Sistema Administrativo Médico</b>' . $eol . $eol;
                    $mensaje .= 'PD: Cuenta de correo no monitoreado, por favor no responda este correo.' . $eol . $eol;
                    // main header (multipart mandatory)
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "From: SisAdMed <sisadmed@corp-mmq.com>" . "\r\n";
                    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . "\r\n";
                    $headers .= "Content-Transfer-Encoding: 7bit" . "\r\n";
                    $headers .= "This is a MIME encoded message." . "\r\n";
                    // message
                    $body = "--" . $separator . "\r\n";
                    $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . "\r\n";
                    $body .= "Content-Transfer-Encoding: 8bit" . "\r\n" . "\r\n";
                    $body .= $mensaje . "\r\n";
                    // attachment
                    $body .= "--" . $separator . "\r\n";
                    $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . "\r\n";
                    $body .= "Content-Transfer-Encoding: base64" . "\r\n";
                    $body .= "Content-Disposition: attachment" . "\r\n" . "\r\n";
                    $body .= $content . "\r\n";
                    $body .= "--" . $separator . "--";

                    if (mail($to, $subject, $body, $headers)) {
                        $msg .= ' y correo enviado exitosamente';
                    } else {
                        $msg .= ' y dío error enviado correo';
                    }
                }
                if ($id) {
                    $dataJson = [
                        'title' => 'Operación exitosa',
                        'icon' => 'success',
                        'msg' => $msg
                    ];
                } else {
                    $dataJson = [
                        'title' => 'Error al guardar',
                        'icon' => 'success',
                        'msg' => 'No se ha podido guardar el registro, por favor intente luego'
                    ];
                }
            } catch (\PDOException $e) {
                $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
                $title = "Se ha presentado un error, intente luego";
                $dataJson = [
                    'title' => $title,
                    'icon' => 'error',
                    'msg' => $msg
                ];
            }
            echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
        }
    }
    public function destroy()
    {
        $dataJson = [];
        $id = $_POST['id'];
        $name = $_POST['name'];
        try {
            $ide = UsuariosModel::deleteUser($id);
            if ($ide) {
                $dataJson = [
                    'status' => true,
                    'icon' => 'success',
                    'msg' => sprintf('El usuario %s se ha eliminado correctamente', $name),
                    'title' => 'Registro eliminado'
                ];
            } else {
                $dataJson = [
                    'status' => false,
                    'icon' => 'error',
                    'msg' => 'Error',
                    'title' => 'No se puede eliminar el registro'
                ];
            }
        } catch (\PDOException $e) {
            $msg = sprintf("Error código: %s, Descripción del Error: %s", $e->getCode(), $e->getMessage());
            $title = "Se ha presentado un error, intente luego";
            $dataJson = [
                'status' => false,
                'icon' => 'error',
                'msg' => $msg,
                'title' => $title,

            ];
        }
        echo json_encode($dataJson, JSON_UNESCAPED_UNICODE);
    }
    public function tot_user()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = UsuariosModel::tot_user();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        };
    }
    public function show_row()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = UsuariosModel::show_row($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_notification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = UsuariosModel::pend_not();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function show_notification_win()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = UsuariosModel::pend_not_win();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function read_notify()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $r = UsuariosModel::read_notify($id);
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    public function get_permisos()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            Permisos::getPermisos($id);
            $r = $_SESSION['permisosMod'];
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    //Creado por José Vargas el 09-03-2026 a las 10:25:00
    public function cargar_screen_main()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = UsuariosModel::all();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    /**Creado por José Vargas el 17-05-2026 */
    public function view_notification()
    {
        $data['page_name'] = 'Aprobaciones pendientes';
        $data['function_js'] = 'Usuarios.js?v=' . SITE_VERSION;
        $data['function_js_mod'] = 'GENFUN.js?v=' . SITE_VERSION;

        $this->views->getView($this, 'notification', $data);
    }
    /**Creado por José Vargas el 17-05-2026 */
    public function get_notification()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $r = UsuariosModel::get_notification();
            echo json_encode($r, JSON_UNESCAPED_UNICODE);
        }
    }
    /**
     * Guarda la suscripción enviada desde el frontend JS
     */
    public static function guardarSuscripcion()
    {

        // Desactivar impresión de warnings directos para no romper el JSON
        ini_set('display_errors', 0);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpiar cualquier salida previa en el búfer de PHP (espacios, warnings, etc.)
        if (ob_get_length()) {
            ob_clean();
        }

        //header('Content-Type: application/json; charset=utf-8');

        try {
            // Verificar sesión activa del usuario en SisAdMed
            if (!isset($_SESSION['id_user'])) { // Ajusta a la variable de sesión de tu sistema
                echo json_encode(['status' => false, 'message' => 'Usuario no autenticado']);
                return;
            }

            $id_user = $_SESSION['id_user'];
            $json = file_get_contents('php://input');
            $datos = json_decode($json, true);

            if (!$datos || !isset($datos['endpoint'])) {
                echo json_encode(['status' => false, 'message' => 'Estructura de datos inválida']);
                return;
            }

            $endpoint = $datos['endpoint'];
            $p256dh   = $datos['keys']['p256dh'] ?? '';
            $auth     = $datos['keys']['auth'] ?? '';

            $db = new Conexion(); //Obtener la instancia del PDO
            $link = (object)$db->conect();       // O la variable de conexión PDO/MySQLi que utilices en SisAdMed

            // Verificar si la suscripción de este endpoint ya existe
            $stmt = $link->prepare("SELECT id FROM f00021 WHERE endpoint = ?");
            $stmt->execute([$endpoint]);
            $existe = $stmt->fetch();

            if ($existe) {
                // Actualizar si cambió de usuario o llaves
                $stmt = $link->prepare("UPDATE f00021 SET id_user = ?, p256dh = ?, auth = ? WHERE endpoint = ?");
                $stmt->execute([$id_user, $p256dh, $auth, $endpoint]);
            } else {
                // Insertar nueva suscripción
                $stmt = $link->prepare("INSERT INTO f00021 (id_user, endpoint, p256dh, auth) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_user, $endpoint, $p256dh, $auth]);
            }
            echo json_encode(['status' => true, 'message' => 'Suscripción guardada correctamente']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'message' => $e->getMessage()]);
            exit;
        }
    }
    /**
     * Envía una notificación Push a un usuario determinado
     */
    public static function enviarNotificacionPush(int $id_user, string $titulo, string $mensaje, $urlDestino = '/')
    {

        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();

        // Obtener todas las suscripciones activas del usuario (puede tener varias si usa distintas computadoras o navegadores)
        $stmt = $link->prepare("SELECT endpoint, p256dh, auth FROM usuario_notificaciones WHERE usuario_id = ?");
        $stmt->execute([$id_user]);
        $suscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($suscripciones)) {
            return false; // El usuario no tiene suscripciones o no ha aceptado permisos
        }

        $auth = [
            'VAPID' => [
                'subject' => VAPID_SUBJECT,
                'publicKey' => VAPID_PUBLIC_KEY,
                'privateKey' => VAPID_PRIVATE_KEY,
            ],
        ];

        $webPush = new WebPush($auth);

        $payload = json_encode([
            'title' => $titulo,
            'body'  => $mensaje,
            'icon'  => '/assets/img/logo-sisadmed.png', // Ruta del icono
            'url'   => $urlDestino
        ]);

        foreach ($suscripciones as $sub) {
            $subscription = Subscription::create([
                'endpoint'        => $sub['endpoint'],
                'publicKey'       => $sub['p256dh'],
                'authToken'       => $sub['auth'],
            ]);

            $webPush->queueNotification($subscription, $payload);
        }

        // Procesar y enviar los envíos pendientes en cola
        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if (!$report->isSuccess()) {
                // Si la suscripción expiró o el usuario revocó permisos en su navegador, eliminarla de la BD
                if ($report->isSubscriptionExpired()) {
                    $stmtDel = $link->prepare("DELETE FROM usuario_notificaciones WHERE endpoint = ?");
                    $stmtDel->execute([$endpoint]);
                }
            }
        }

        return true;
    }
    public static function enviarPushAUsuario(int $id_user, string $titulo, string $mensaje, string $urlAction = '/')
    {        

        $db = new Conexion(); //Obtener la instancia del PDO
        $link = (object)$db->conect();

        // 1. Obtener todas las suscripciones activas del usuario destino
        // (Un usuario puede estar suscrito desde su PC y su Laptop)
        $stmt = $link->prepare("SELECT * FROM f00021 WHERE id_user = ?");
        $stmt->execute([$id_user]);
        $suscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($suscripciones)) {
            return false; // El usuario no tiene dispositivos suscritos
        }

        // 2. Claves VAPID y configuración de Guzzle (con verify => false para tu entorno local)
        $vapidKeys = [
            'VAPID' => [
                'subject' => VAPID_SUBJECT,
                'publicKey' => VAPID_PUBLIC_KEY,
                'privateKey' => VAPID_PRIVATE_KEY,
            ],
        ];

        $defaultOptions = [
            'client' => new \GuzzleHttp\Client(['verify' => false])
        ];

        $webPush = new WebPush($vapidKeys, $defaultOptions);

        // 3. Payload que recibirá el Service Worker
        $payload = json_encode([
            'title' => $titulo,
            'body'  => $mensaje,
            'url'   => $urlAction
        ]);

        // 4. Enviar la notificación a cada dispositivo registrado del usuario
        foreach ($suscripciones as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub['endpoint'],
                'keys' => [
                    'p256dh' => $sub['p256dh'],
                    'auth'   => $sub['auth']
                ]
            ]);
            $webPush->queueNotification($subscription, $payload);
        }

        // Ejecutar el envío en lote
        foreach ($webPush->flush() as $report) {
            // Se ejecutó la cola
        }

        return true;
    }
}

// Ruteador básico para la petición AJAX
if (isset($_GET['action']) && $_GET['action'] === 'subscribe') {
    Usuarios::guardarSuscripcion();
    exit; // <--- OBLIGATORIO: Detiene a PHP para que no renderice el menú ni el footer HTML
}
