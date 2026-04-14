<?php
class BanTipoDocModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f5001");
    }
    static function guardar($data){
        return $r = DB::insert('f5001', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f5001', $data, ['id_bantdo' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f5001 a WHERE a.id_bantdo = {$id}");
        return $r[0];
    }
    static function destroy($id){
        return $r = DB::delete('f5001', ['id_bantdo' => $id], 1);
    }
    static function showrow($id){
        return $r = DB::query("SELECT * FROM f5001 a WHERE a.id_bantdo = {$id}");
    }
}