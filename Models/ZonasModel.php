<?php
class ZonasModel extends DB{
   public function __construct() {
        parent::__construct();
    }
     static function all() {
        return $r = DB::query("SELECT * FROM f0003");
    }
    static function guardar($data){
      return $r = DB::insert('f0003', $data);
    }
    static function edit($id){
      $r = DB::query("SELECT * FROM f0003 WHERE id_zona = {$id}");
      return $r[0];
    }
    static function actualizar($id, $data){
      return $r = DB::update('f0003', $data, ['id_zona' => $id]);
    }
    static function listar_zonas(){
      return $r = DB::query("SELECT * FROM f0003 WHERE status = 1 ORDER BY nombre_zona");
    }
    static function destroy($id){
      return $id = DB::delete('f0003', ['id_zona' => $id]);
    }
    static function next_zone(){
      $sql = "SELECT MAX(cod_zona) cod_zona FROM `f0003`";
      $r = DB::query($sql);
      return $r[0];
    }
}