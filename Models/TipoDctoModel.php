<?php
class TipoDctoModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f7001");
    }
    static function guardar($data){
        return $id = DB::insert('f7001', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f7001', $data, ['id' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f7001 WHERE id = {$id}");
        return $r[0];
    }
    static function borrar($id){
        return $r = DB::delete('f7001', ['id' => $id], 1);
    }
    static function listar_descuentos(){
        return $r = DB::query("SELECT * FROM f7001 WHERE status = 1");
    }
    static function show_row($id){
        $r = DB::query("SELECT * FROM f7001 WHERE id = {$id}");
        return $r[0];
    }
}