<?php
class BancosModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f5003");
    }
    static function guardar($data){
        return $id = DB::insert('f5003', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f5003', $data, ['id_banco' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f5003 WHERE id_banco = {$id}");
        return $r[0];
    }
    static function editar_row($id){
        $r = DB::query("SELECT * FROM f5003 WHERE id_banco = {$id}");
        return $r[0];
    }
    static function destroy($id){
        return $r = DB::query("DELETE FROM f5003 WHERE id_banco = {$id}");
    }
    static function showrow($id){
        return $r = DB::query("SELECT * FROM f5003 WHERE id_banco = {$id}");
    }
}