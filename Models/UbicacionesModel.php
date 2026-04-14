<?php
class UbicacionesModel extends DB {
   public function __construct() {
      parent::__construct();
   }
   public static function all(){
      $respuesta = DB::SQL("SELECT * FROM f4001 a INNER JOIN f0011 b on b.id_emp = a.id_emp");
      return $respuesta;
   }
   static function edit($id){
      $r = DB::query("SELECT * FROM f4001 WHERE id_ubi = {$id}");
      return $r[0];
   }
   static function validar_cod($codigo){
      $xcodigo = $codigo;
      if( strpos($codigo, '.')!== false){
         $xcodigo = $codigo;
      }
      return $r = DB::query("SELECT * FROM f4001 WHERE cod_ubi = '".$xcodigo."'");
   }
   static function guardar($data){
      return $id = DB::insert('f4001', $data);
   }
   static function actualizar($id, $data) {
      return $res = DB::update('f4001', $data, ['id_ubi' => $id]);
   }
   static function borrar($id, $codigo){
      $sql = "SELECT * FROM f4001 WHERE cod_ubi LIKE '".$codigo.".%'" ;
      $row = DB::query($sql);
      if(empty($row)){
         $row1 = DB::query("SELECT * FROM f4001 WHERE id_ubi = {$id}");
         if(!empty($row1)){
            $id = DB::delete('f4001', ['id_ubi' => $id], 1);
            return true;
         }else{
            return false;
         }
      }else{
         return false;
      }
   }
   static function listar_ubicaciones($id_emp = '', $agru_ubi = ''){
      if($id_emp){
         $r = DB::query("SELECT * FROM f4001 WHERE agru_ubi = 'N' AND status = 1 AND id_emp = {$id_emp}");
      }else if($agru_ubi){
         $r = DB::query("SELECT * FROM f4001 WHERE agru_ubi = 'S' AND status = 1");
      }else{
         $r = DB::query("SELECT * FROM f4001 WHERE agru_ubi = 'N' AND status = 1");
      }
      return $r;
   }
   static function con_ubi($id){
      $r = DB::query("SELECT * FROM f4001 WHERE id_ubi = {$id}");
      return $r[0];
   }
}