<?php
class ProveeIntModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f8001");
    }
    static function guardar($data)
    {
        return $id = DB::insert('f8001', $data);
    }
    static function actualizar($id, $data)
    {
        return $res = DB::update('f8001', $data, ['id_provint' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f8001 WHERE id_provint = {$id}");
        return $r[0];
    }
    static function borrar($id){
        return $id = DB::delete('f8001', ['id_provint' => $id], 1);
    }
    static function listar_almacenes($id){
        return $r = DB::query("SELECT * FROM f8001 WHERE id_emp = {$id}");
    }
    static function cargar_data($id){
        $r = DB::query("SELECT * FROM f8001 WHERE id_provint = {$id}");
        return $r[0];
    }
}