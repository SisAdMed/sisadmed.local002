<?php
class PresentacionesModel extends DB  
{
   public function __construct()
   {
      parent::__construct();
   }
   public static function all()
   {
      $objeto = DB::SQL("SELECT * FROM f4004");
      return $objeto;
   }
   public static function validar($codigo)
   {
      return $r = DB::query("SELECT * FROM f4004 WHERE cod_pre = '". $codigo ."'");
   }
   static function guardar($data)
    {
        return $id = DB::insert('f4004', $data);
    }
    static function actualizar($id, $data)
    {
        return $res = DB::update('f4004', $data, ['id_pre' => $id]);
    }  
    static function edit($id){
        $r = DB::query("SELECT * FROM f4004 WHERE id_pre = {$id}");
        return $r[0];
    }
    static function borrar($id, $cod){       
      return $id = DB::delete('f4004', ['id_pre' => $id], 1);       
    }
    static function getPresentacion(){
        $sql = "SELECT id_pre, nom_pre FROM f4004 WHERE status = 1 ORDER BY nom_pre";
        return $r = DB::query($sql);
    }
}