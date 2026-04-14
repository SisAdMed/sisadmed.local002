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
        $r = DB::insert('fgenmsg', $data);
        return $r;
    }
    public static function pend_not_win(){
        $id_rol = $_SESSION['id_rol'];
        $sql = "SELECT * FROM `fgenmsg` WHERE is_read = 0 AND user_notifi = $id_rol AND status = 1";
        $r = DB::query($sql);
        return ($r);
    }
    public static function pend_not($tipo){
        $id_rol = $_SESSION['id_rol'];
        if($tipo == 1){
            $sql = "SELECT count(*) totmsj FROM `fgenmsg` WHERE is_read = 0 AND user_notifi = $id_rol LIMIT 1";
            $r = DB::query($sql);
            return $r[0];
        }else if($tipo == 2){
            $sql = "SELECT count(*) totmsj, tipo_fgenmsgcol, CASE WHEN tipo_fgenmsgcol = 1 THEN 'Aprob. Notas de Crédito' ELSE '' END tipo, TIMESTAMPDIFF(MINUTE, fecha_genmsgcol, NOW()) tiempo FROM `fgenmsg` WHERE is_read = 0 AND user_notifi = $id_rol AND status = 1 GROUP BY  tipo_fgenmsgcol, CASE WHEN tipo_fgenmsgcol = 1 THEN 'Aprob. Notas de Crédito' ELSE '' END, TIMESTAMPDIFF(MINUTE, fecha_genmsgcol, NOW())";
            $r = DB::query($sql);
            return $r;
        }
    }
    public static function read_notify($id){
        return $r = DB::query("UPDATE fgenmsg SET is_read = 1 WHERE id_fgenmsg = {$id}");
    }
}
