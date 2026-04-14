<?php
class NotiEnvioModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f4011");
    }
}