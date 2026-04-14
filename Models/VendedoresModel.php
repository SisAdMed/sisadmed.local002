<?php
class VendedoresModel extends DB{
   public function __construct(){
      parent::__construct();
   }
   static function all(){
      return $r = DB::query('SELECT * FROM f0016');
   }
   static function guardar($data){
      return $id = DB::insert('f0016', $data);
   }
   static function edit($id){
      $r = DB::query("SELECT * from f0016 v INNER JOIN f0004 p on p.id_pais = v.id_pais INNER JOIN f00041 e on e.id_edo = v.id_edo INNER JOIN f00042 c on c.id_ciudad = v.id_ciudad WHERE v.id_vend = {$id}");
      return $r[0];
   }
   static function actualizar($id, $data){
      return $r = DB::update('f0016', $data, ['id_vend' => $id]);
   }
   static function borrar($id){
      return $id = DB::delete('f0016', ['id_vend' => $id]);
    }
    static function showrow($id){
      return $r = DB::query("SELECT * FROM f0016 WHERE id_vend = {$id}");
    }
}