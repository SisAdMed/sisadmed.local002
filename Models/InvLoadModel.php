<?php
class InvLoadModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all() {
        return $r = DB::query("SELECT * FROM f4009c a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tInvLoad INNER JOIN f4002 d ON d.id_alm = a.id_alm");
    }
    static function guardar($data){
        return $r = DB::insert('f4009c', $data);
    }
    static function actualizar($id, $data) {
        return $r = DB::update('f4009c', $data, ['id_movinv' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f4009c a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tInvLoad WHERE a.id_movinv = {$id}");
        return $r[0];
    }
    static function borrarDetInvMov($id){
        return $r = DB::delete('f40091c', ['id_InvLoad' => $id], 1000);
    }
   static function guardarDetMovin($data){
         return $r = DB::insert('f40091c', $data);
    }
    static function cons_producto($id){
        $r = DB::query("SELECT * FROM f4005 WHERE id_prod = {$id}");
        return $r[0];
    }
      static function getNextNumer($id){
        $r = DB::query("SELECT proximo_tmoinv FROM f4006 WHERE id_tmoinv = {$id}");
        return $r[0];
    }
    static function setNextNumber($id){
        return $r = DB::query("UPDATE f4006 SET proximo_tmoinv = proximo_tmoinv + 1 WHERE id_tmoinv = {$id}");
    }
    static function showrow($id){
        $r = DB::query("SELECT a.id_emp, a.id_tInvLoad, a.num_InvLoad, a.fecha_comp, a.id_moneda, a.tasa_cambio, a.id_alm, a.descrip_InvLoad, a.status, d.id_prod, d.id_ubi, d.lote, d.fec_venc, d.cantidad, c.cod_tmoinv, e.nom_prod, u.nom_ubi FROM f4009c a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tInvLoad INNER JOIN f40091c d on d.id_InvLoad = a.id_movinv INNER JOIN f4005 e ON e.id_prod = d.id_prod INNER JOIN f4001 u ON u.id_ubi = d.id_ubi WHERE a.id_movinv = {$id}");
        return $r;
    }
    static function movemen_type($id, $id_prod, $modo){
        if($modo == 'A'){
            $r = DB::query("SELECT a.id_emp, a.id_tmovinv, a.num_movinv, a.fecha_comp, a.id_moneda, a.tasa_cambio, a.id_alm, a.descrip_movinv, a.status, d.id_prod, d.id_ubi, d.lote, d.fec_venc, d.cantidad, c.tipo_tmoinv FROM f4009c a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tmovinv INNER JOIN f40091c d on d.id_movinv = a.id_movinv WHERE a.id_movinv = {$id} AND d.id_prod = {$id_prod}");
        }
        return $r[0];
    }
    static function approve($id){
        $r = DB::query("UPDATE f4009c SET modo = 'D' WHERE id_movinv = {$id}");
        return $r;
    }
    static function guardar_mov($data){
        return $r = DB::insert('f4009', $data);
    }
    static function guardarDetMovin_mov($data){
         return $r = DB::insert('f40091', $data);
    }
    static function borrar_mov($id){
        DB::delete('f40091', ['id_movinv' => $id], 1000);
        return $r = DB::delete('f4009', ['id_movinv' => $id], 1000);
    }
    static function GetAllProducts(){
        return $r = DB::sql("CALL GetAllProducts");
    }
    static function exist_det_mov($id, $id_prod, $id_ubi, $lote, $fec_venc){
        return $r = DB::query("SELECT * FROM f40091c WHERE id_InvLoad = {$id} AND id_prod = {$id_prod} AND lote = '".$lote."' AND id_ubi = {$id_ubi} AND fec_venc = '".$fec_venc."' ");
    }
    static function updateDetMovin($id, $data){
        return $r = DB::update('f40091c', $data, ['id_moinvdet' => $id]);
    }
    static function borrarDetCarInvMov($id){
        return $r = DB::delete('f40091c', ['id_InvLoad' => $id], 1000);
    }
    static function borrarEncCarInvMov($id){
        return $r = DB::delete('f4009c', ['id_movinv' => $id], 1000);
    }
}