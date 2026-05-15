<?php
class FabricantesModel extends DB{
   public function __construct(){
      parent::__construct();
   }
   public static function all(){
      $objeto = DB::SQL("SELECT * FROM f4003");
      return $objeto;
   }
   public static function validar($codigo){
      return $r = DB::query("SELECT * FROM f4003 WHERE nom_fab = '". $codigo ."'");
   }
   static function guardar($data){
        return $id = DB::insert('f4003', $data);
    }
    static function actualizar($id, $data){
        return $res = DB::update('f4003', $data, ['id_fab' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f4003 WHERE id_fab  = {$id}");
        return $r[0];
    }
    static function borrar($id){
      return $id = DB::delete('f4003', ['id_fab' => $id], 1);
    }
    static function listar_marcas(){
      return $r = DB::query("SELECT * FROM f4003 WHERE status = 1 ORDER BY nom_fab");
    }
     static function listar_grupos(){
      return $r = DB::query("SELECT * FROM f4007 WHERE status = 1 ORDER BY grupo_nombre");
    }
    static function showrowfab($id){
        $r = DB::query("SELECT * from f4003 WHERE id_fab = {$id}");
        return $r;
    }
    static function getMarcas(){
        $sql = "SELECT id_fab, nom_fab FROM f4003 WHERE status = 1 ORDER BY nom_fab";
        return $r = DB::query($sql);
    }
}