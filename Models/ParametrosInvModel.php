<?php
class ParametrosInvModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static public function all() {
        return $r = DB::query("SELECT * FROM f4012 p INNER JOIN f0011 e ON e.id_emp = p.id_emp ");
    }
    static public function guardar($datos) {
        return $id = DB::insert('f4012', $datos);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f4012 p INNER JOIN f0011 e ON e.id_emp = p.id_emp WHERE p.id = {$id}");
        return $r[0];
    }
     static function actualizar($id, $data) {
        return $res = DB::update('f4012', $data, ['id' => $id]);
    }
    static function getParam(){
        $r = DB::query("SELECT * FROM f4012");
        return to_obj($r[0]);
    }
}