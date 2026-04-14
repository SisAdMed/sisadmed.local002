<?php
class CreditDaysModel extends DB {
     public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f6005");
    }
    static function guardar($data){
        return $id = DB::insert('f6005', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f6005', $data, ['id_diascre' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f6005 WHERE id_diascre = {$id}");
        return $r[0];
    }
    static function editar_row($id){
        $r = DB::query("SELECT * FROM f6005 WHERE id_diascre = {$id}");
        return $r[0];
    }
    static function destroy($id){
        return $r = DB::query("DELETE FROM f6005 WHERE id_diascre = {$id}");
    }
    static function show_row($id){
        return $r = DB::query("SELECT * FROM f6005 WHERE id_diascre = {$id}");
    }
}