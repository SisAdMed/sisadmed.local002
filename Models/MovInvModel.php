<?php
class MovInvModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all() {
        return $r = DB::query("SELECT DISTINCT a.id_movinv, b.nombre_emp, c.cod_tmoinv, c.nom__tmoinv, a.fecha_comp, a.status, a.num_movinv, a.origen FROM f4009 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tmovinv INNER JOIN f40091 d ON d.id_movinv = a.id_movinv;");
    }
    static function guardar($data){
        return $r = DB::insert('f4009', $data);
    }
    static function actualizar($id, $data) {
        return $r = DB::update('f4009', $data, ['id_movinv' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f4009 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tmovinv WHERE id_movinv = {$id}");
        return $r[0];
    }
    static function borrarDetInvMov($id){
        return $r = DB::delete('f40091', ['id_movinv' => $id], 1000);
    }
    static function borrarEncInvMov($id){
        return $r = DB::delete('f4009', ['id_movinv' => $id], 1000);
    }
   static function guardarDetMovin($data){
         return $r = DB::insert('f40091', $data);
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
        $r = DB::query("SELECT a.id_emp, a.id_tmovinv, a.num_movinv, a.fecha_comp, a.id_moneda, a.tasa_cambio, a.id_alm, a.descrip_movinv, a.status, d.id_prod, d.id_ubi, d.lote, d.fec_venc, d.cantidad, a.origen, e.nom_prod, f.nom_ubi FROM f4009 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tmovinv INNER JOIN f40091 d on d.id_movinv = a.id_movinv INNER JOIN f4005 e ON e.id_prod = d.id_prod INNER JOIN f4001 f ON f.id_ubi = d.id_ubi WHERE a.id_movinv = {$id}");
        return $r;
    }
    static function movemen_type($id, $id_prod, $modo){
        if($modo == 'A'){
            $r = DB::query("SELECT a.id_emp, a.id_tmovinv, a.num_movinv, a.fecha_comp, a.id_moneda, a.tasa_cambio, a.id_alm, a.descrip_movinv, a.status, d.id_prod, d.id_ubi, d.lote, d.fec_venc, d.cantidad, c.tipo_tmoinv FROM f4009 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f4006 c ON c.id_tmoinv = a.id_tmovinv INNER JOIN f40091 d on d.id_movinv = a.id_movinv WHERE a.id_movinv = {$id} AND d.id_prod = {$id_prod}");
        }
        return $r[0];
    }
    static function conmovinv($id_emp, $fec_ini, $fec_fin, $id_alm, $id_fab, $id_prod, $id_ubi ){
        $filter = '';
        $filter_prod = '';
        if(!$id_emp){            
            //$filter .= " AND b.id_emp = {$id_emp}";
            $id_emp = 0;
            $id_ubi = 0;
        }
        if($id_fab){
            $filter .= ' AND e.id_fab IN ('.$id_fab.') ';
        }
        if($id_prod){
            $filter .= " AND b.id_prod = '{$id_prod}'";
            $filter_prod .= " WHERE a.id_prod = '{$id_prod}' ";
        }       
        if($id_ubi){
            $filter .= " AND b.id_ubi = {$id_ubi}";
        }else{
            $id_ubi = 0;
        }
        if($id_ubi === null){
            $id_ubi = 0;
        }
        $sql = "SELECT $id_emp id_emp,  a.id_prod, ' ' fecha_comp, 0 num_movin, ' ' nombre_emp, CONCAT(a.nom_prod, ' Referencia: ', a.ref_prod) nom_prod, 0 id_alm, ' ' nom_alm, ' ' cod_tmoinv, ' ' nom_tmoinv, ' ' descrip_movinv, ' 'origen, 0 id_cot, 0 id_fab, nom_fab, 0 entradas, 0 salidas, ' ' nom_ent, fn_saldo_ant_inv($id_emp, a.id_prod, '$id_alm', '$fec_ini',  $id_ubi) saldo, ' ' cod_prod, ' ' ref_prod  FROM f4005 a INNER JOIN f4003 b ON b.id_fab = a.id_fab $filter_prod UNION SELECT d.id_emp, e.id_prod, a.fecha_comp, a.num_movinv, d.nombre_emp,  CONCAT(e.nom_prod, ' Referencia: ', e.ref_prod) nom_prod,  f.id_alm, f.nom_alm, c.cod_tmoinv, c.nom__tmoinv, a.descrip_movinv, IFNULL(a.origen, ' ') origen, h.id_cot, i.id_fab, i.nom_fab, CASE c.tipo_tmoinv WHEN 'E' THEN SUM(b.cantidad) ELSE 0 END entradas, CASE c.tipo_tmoinv WHEN 'S' THEN SUM(b.cantidad) ELSE 0 END salidas, IFNULL(j.nom_ent, IFNULL(LTRIM(RTRIM(z.nom_ent)), a.descrip_movinv)) nom_ent, fn_saldo_ant_inv($id_emp, b.id_prod, '$id_alm', '$fec_ini',  $id_ubi) saldo, e.cod_prod, e.ref_prod FROM f4009 a INNER JOIN f40091 b ON b.id_movinv = a.id_movinv INNER JOIN f4006 c ON c.id_tmoinv = a.id_tmovinv INNER JOIN f0011 d ON d.id_emp = a.id_emp INNER JOIN f4005 e ON e.id_prod = b.id_prod INNER JOIN f4002 f ON f.id_alm = a.id_alm  INNER JOIN f4003 i ON i.id_fab = e.id_fab LEFT OUTER JOIN f6001 g ON g.id_emp = substr(a.origen, 8,1) AND g.tipo_codigo = substr(a.origen, 5,2) LEFT OUTER JOIN f6003 h ON h.id_emp = g.id_emp AND h.id_tdo = g.id_tdoc AND h.num_tdo = substr(a.origen, 10) LEFT OUTER JOIN f0014 j ON j.id_ent = h.id_cli AND substr(a.origen, 1,3) != 'COM' LEFT OUTER JOIN f3001 x ON x.id_emp = substr(a.origen, 8,1) AND x.tipo_codigo = substr(a.origen, 5,2) AND substr(a.origen, 1,3) = 'COM' LEFT OUTER JOIN f8020 y ON y.id_emp = x.id_emp AND y.id_tdo = x.id_tdoc AND y.num_tdo = substr(a.origen, 10) AND substr(a.origen, 1,3) = 'COM' LEFT OUTER JOIN f0014 z ON z.id_ent = y.id_cli AND substr(a.origen, 1,3) = 'COM' WHERE a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.id_alm IN ($id_alm) " .  $filter . " GROUP BY d.id_emp, e.id_prod, a.fecha_comp, a.num_movinv, d.nombre_emp, CONCAT(e.nom_prod, ' Referencia: ', e.ref_prod), f.id_alm, f.nom_alm, c.cod_tmoinv, c.nom__tmoinv, a.descrip_movinv, IFNULL(a.origen, ' '), h.id_cot, i.id_fab, i.nom_fab, IFNULL(j.nom_ent, IFNULL(LTRIM(RTRIM(z.nom_ent)), a.descrip_movinv)), fn_saldo_ant_inv($id_emp, b.id_prod, '$id_alm', '$fec_ini', $id_ubi), e.cod_prod, e.ref_prod ORDER BY 2, 3, 4;";
        $r = DB::query($sql);
        return $r;
    }
    static function print_movement($id){
        $sql = "SELECT d.nombre_emp, d.logo, c.cod_tmoinv, c.nom__tmoinv, a.num_movinv, a.fecha_comp, e.codigo_moneda, a.tasa_cambio, a.origen, a.descrip_movinv, h.cod_prod, h.recar_prod, h.nom_prod, h.ref_prod, b.cantidad, b.lote, b.fec_venc, b.costo, b.flete, b.otros_cargos, b.costo1, f.cod_alm, f.nom_alm, j.cod_ubi, j.nom_ubi, CONCAT(g.name_user, ' ', g.last_user) create_user, a.create_date, CONCAT(z.name_user, ' ', z.last_user) modify_user, a.modify_date FROM `f4009` a INNER JOIN `f40091` b ON b.id_movinv = a.id_movinv INNER JOIN `f4006` c ON c.id_tmoinv = a.id_tmovinv INNER JOIN `f0011` d ON d.id_emp = a.id_emp INNER JOIN `f0005` e ON e.id_moneda = a.id_moneda INNER JOIN `f4002` f ON f.id_alm = a.id_alm LEFT OUTER JOIN `f0002` g ON g.id_user = a.create_user INNER JOIN `f4005` h ON h.id_prod = b.id_prod INNER JOIN `f4003` i ON i.id_fab = h.id_fab INNER JOIN `f4001` j ON j.id_ubi = b.id_ubi LEFT OUTER JOIN `f0002` z ON z.id_user = a.modify_user WHERE a.id_movinv = {$id}" ;
        $r = DB::query($sql);
        return $r;
    }
    static function update_lotes($origen){
        $sql = "SELECT c.id_movinv FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f4009 c ON c.origen = CONCAT(a.id_cont, '-', b.tipo_codigo, '-', a.id_emp, '-', a.num_tdo ) INNER JOIN f4999 d ON d.id_emp = a.id_emp AND d.tmov_fac = c.id_tmovinv INNER JOIN f0014 e ON e.id_ent = a.id_cli AND e.req_exc_rat = 1 WHERE c.origen = '$origen'";
        return $r = DB::query($sql);
    }
}