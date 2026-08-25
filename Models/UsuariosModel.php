<?php
class UsuariosModel extends DB{
    public function __construct() {
        parent::__construct();
    }
    public static function all() {
        $respuesta = DB::SQL("SELECT * FROM f0002 u INNER JOIN f0007 r on u.id_rol = r.id_rol");
        return $respuesta;
    }
    public static function rolesAll() {
        $repuesta = DB::SQL("SELECT * FROM f0007 WHERE status_rol != 0");
        return $repuesta;
    }
    public static function save($data) {
        $idsave = DB::insert('f0002', $data);
        return $idsave;
    }
    public static function oneUser($idUser) {
        $repuesta = DB::SQL("SELECT * FROM f0002 u INNER JOIN f0007 r ON u.id_rol = r.id_rol WHERE u.id_user = $idUser");
        return $repuesta;
    }
    public static function updateUser($data, $id_user) {
        $idupdate = DB::update('f0002', $data, ['id_user' => $id_user]);
        return $idupdate;
    }
    public static function deleteUser($id_user) {
        $iddelete = DB::delete('f0002', ['id_user' => $id_user]);
        return $iddelete;
    }
    public static function tot_user(){
        $r = DB::query("SELECT count(*) tot_user FROM f0002");
        return $r[0];
    }
    public static function show_row($id){
        $r = DB::query("SELECT * FROM f0002 WHERE id_user = {$id}");
        return $r[0];
    }
    public static function save_not($data){
        $r = DB::insert('f0025', $data);
        return $r;
    }
    public static function upd_not($id, $data){
        $r = DB::update('f0025', $data, ['id' => $id]);
        return $r;
    }
    public static function pend_not_win(){
        $id_user = $_SESSION['id_user'];
        $sql = "SELECT DISTINCT tipo, url_destino FROM `f0025` WHERE leido = 0 AND id_receptor = $id_user";
        $r = DB::query($sql);
        return ($r);
    }
    public static function pend_not(){
        $id_user = $_SESSION['id_user'];
        $sql = "SELECT COUNT(*) AS totmsj FROM (SELECT tipo FROM `f0025`  WHERE leido = 0 AND id_receptor = $id_user GROUP BY tipo, id_receptor ) AS subconsulta;";
        $r = DB::query($sql);
        return $r[0];
    }
    public static function read_notify( int $id){
        return $r = DB::query("UPDATE fgenmsg SET is_read = 1 WHERE id_fgenmsg = {$id}");
    }
    public static function get_notification(){
        $id_user = $_SESSION['id_user'];
        $sql = "SELECT DISTINCT a.id_origen id, a.create_date, CONCAT(b.name_user, ' ', b.last_user) create_for, a.tipo, a.mensaje, a.leido, a.motivo, a.url_destino, a.approved FROM f0025 a INNER JOIN f0002 b ON b.id_user = a.id_receptor WHERE b.id_user = {$id_user} AND a.approved = 0 AND a.leido = 0
        UNION
        SELECT DISTINCT a.id_origen id, a.create_date, CONCAT(b.name_user, ' ', b.last_user) create_for, a.tipo, a.mensaje, a.leido, a.motivo, a.url_destino, a.approved FROM f0025 a INNER JOIN f0002 b ON b.id_user = a.id_receptor WHERE b.id_user = {$id_user} AND a.approved = 1 AND a.leido = 0";
        $r = DB::query($sql);
        return ($r);
    }
    static function users_approve(){
        $sql = "SELECT id_user FROM f0002 WHERE status_user = 1 AND appdis = 1";
        $r = DB::query($sql);
        return $r;
    }       
}
