<?php
class Auth
{
    public static function sessionUser(int $id_user)
    {
        $repuesta = DB::SQL("SELECT * FROM f0002 u INNER JOIN f0007 r ON u.id_rol = r.id_rol WHERE u.id_user = {$id_user}");
        $_SESSION['user_data'] = $repuesta[0];
        return $repuesta[0];
    }
    /**
     * @void sessions
     *
     */
    public static function noAuth()
    {
        if (!isset(($_SESSION['login']))) {
            header('Location:' . base_url . '/');
        }
    }
    public static function logout()
    {
        /**
         * Actualizar la fecha del ultimo login
         */
        $fecha = date('Y-m-d H:i:s');
        $id = intval($_SESSION['id_user']);
        session_destroy();
        $_SESSION = [];
        header('Location:' . base_url . '/');
        $sql = DB::SQL("UPDATE f0002 SET last_login = '".$fecha."' WHERE id_user = " . $id);
    }
}