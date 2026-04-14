<?php
class LoginModel extends DB
{
    public function __construct(){
        parent::__construct();
    }
    public static function login(string $usuario, string $pass){
        $sql = "SELECT * FROM f0002 WHERE code_user = :usuario AND password_user = :pass AND status_user = :status_user LIMIT 1";
        return ($rows = parent::query($sql, ['usuario' => $usuario, 'pass' => $pass, 'status_user' => 1])) ? $rows[0] : [];
    }
    static function ChangePassword($id, $data){
        return $res = DB::update('f0002', $data, ['id_user' => $id]);
    }
}