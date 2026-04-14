<?php
class InvLoaRepModel extends DB{
   public function __construct() {
      parent::__construct();
   }
   static function reportExcel($id_fab, $id_grupo){
      if($id_fab && $id_grupo){
         $r = DB::query("SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.nom_prod, a.ref_prod, b.nom_fab FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab WHERE a.id_fab  = {$id_fab} AND a.id_grupo = {$id_grupo}");
      }elseif($id_fab){
         $r = DB::query("SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.nom_prod, a.ref_prod, b.nom_fab FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab WHERE a.id_fab  = {$id_fab}");
      }elseif($id_grupo){
         $r = DB::query("SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.nom_prod, a.ref_prod, b.nom_fab FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab WHERE a.id_grupo = {$id_grupo}");
      }else{
         $r = DB::query("SELECT a.id_prod, a.cod_prod, a.cod2_prod, a.nom_prod, a.ref_prod, b.nom_fab FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab");
      }
      return $r;
   }
   static function listar_ubicaciones($id_emp = null){
      if($id_emp){
         $r = DB::query("SELECT * FROM f4001 WHERE agru_ubi = 'N' AND status = 1 AND id_emp = {$id_emp}");
      }else{
         $r = DB::query("SELECT * FROM f4001 WHERE agru_ubi = 'N' AND status = 1");
      }
      return $r;
   }
}