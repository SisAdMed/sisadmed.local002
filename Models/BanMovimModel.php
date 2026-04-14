<?php
/**
 * Clase creada por José Vargas
 * Fecha 20-12-2024 Hora 11:35 a.m.
 * Transacciones de Movimientos Bancarios
 */
class BanMovimModel extends DB{
    function __construct(){
        parent::__construct();
    }
    static function all(){
        //$sql = "SELECT DISTINCT a.id_banmov, c.nombre_emp, d.nom_bantmo, CONCAT( g.nombre_banco, ' - ' , e.cuenta_bancue) bancue, a.num_banmov, a.fecha_comp, f.codigo_moneda id_moneda, a.status, IFNULL(IFNULL(CONCAT(i.cod_tmocxc, ' - ', i.des_tmocxc, ' - ', h.movem_number), CONCAT(k.cod_tmocxc, ' - ',k.des_tmocxc, ' - ', j.movem_number)) , ' ') cont FROM f5006 a INNER JOIN f50061 b ON b.id_banmov = a.id_banmov INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f5002 d ON d.id_bantmo = a.id_bantmo INNER JOIN f5004 e ON e.id_bancue = a.id_bancue INNER JOIN f0005 f ON f.id_moneda = a.id_moneda INNER JOIN f5003 g ON e.id_banco = g.id_banco LEFT OUTER JOIN f6006 h ON d.efe_bantmo = 'C' AND CONVERT(h.movem_origen, UNSIGNED) = a.id_banmov LEFT OUTER JOIN f6004 i ON i.id_tmocxc = h.id_tmocxc LEFT OUTER JOIN f3008 j ON d.efe_bantmo = 'P' AND CONVERT(j.movem_origen, UNSIGNED) = a.id_banmov LEFT OUTER JOIN f3002 k ON k.id_tmocxc = j.id_tmocxp";
        $sql = "SELECT DISTINCT a.id_banmov, c.nombre_emp, d.nom_bantmo, CONCAT( g.nombre_banco, ' - ' , e.cuenta_bancue) bancue, a.num_banmov, a.fecha_comp, f.codigo_moneda id_moneda, a.status FROM f5006 a INNER JOIN f50061 b ON b.id_banmov = a.id_banmov INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f5002 d ON d.id_bantmo = a.id_bantmo INNER JOIN f5004 e ON e.id_bancue = a.id_bancue INNER JOIN f0005 f ON f.id_moneda = a.id_moneda INNER JOIN f5003 g ON e.id_banco = g.id_banco LEFT OUTER JOIN f6006 h ON d.efe_bantmo = 'C' AND h.movem_origen != 'CXC' AND h.movem_origen = a.id_banmov LEFT OUTER JOIN f6004 i ON i.id_tmocxc = h.id_tmocxc LEFT OUTER JOIN f3008 j ON d.efe_bantmo = 'P' AND j.movem_origen != 'CXP' AND j.movem_origen = a.id_banmov LEFT OUTER JOIN f3002 k ON k.id_tmocxc = j.id_tmocxp";
        $r = DB::query($sql);
        return $r;
    }
    static function guardar($data){
        return $r = DB::insert('f5006', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f5006', $data, ['id_banmov' => $id]);
    }
    static function borrar_det_mov($id){
        return $r = DB::delete('f50061', ['id_banmov' => $id]);
    }
    static function guardar_det($data){
        return $r = DB::insert('f50061', $data);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f5006 WHERE id_banmov = {$id}");
        return $r[0];
    }
    static function show_row($id){
        return $r = DB::query("SELECT a.id_emp, a.id_bantmo, e.id_bancue, a.fecha_comp, a.num_banmov, a.id_moneda, a.tasa_cambio, a.des_banmov, a.status, b.id_bancon, CONCAT(h.cod_bancon, ' - ', h.nom_bancon) nom_bancon, IFNULL(b.id_aux, ' ') id_aux, IFNULL(CASE WHEN IFNULL(b.id_aux, ' ')  = ' ' THEN ' ' ELSE CONCAT(i.cod_aux, ' - ', i.nombre_aux) END, ' ') nom_aux, b.monto_nac, b.monto_for, d.efe_bantmo, a.id_ent, d.acc_bantmo, IFNULL(a.benef_banmov, ' ') benef_banmov FROM f5006 a INNER JOIN f50061 b ON b.id_banmov = a.id_banmov INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f5002 d ON d.id_bantmo = a.id_bantmo INNER JOIN f5004 e ON e.id_bancue = a.id_bancue INNER JOIN f5003 f ON f.id_banco = e.id_banco INNER JOIN f0005 g ON g.id_moneda = a.id_moneda INNER JOIN f5005 h ON h.id_bancon = b.id_bancon LEFT OUTER JOIN f0009 i ON i.id_aux = b.id_aux WHERE a.id_banmov = {$id} ORDER BY CONCAT(h.cod_bancon, ' - ', h.nom_bancon)");
    }
    static function det_doc_can($id, $efecto){
        if($efecto=='C'){
            $sql = "SELECT row_number() OVER (ORDER BY c.id_cot)     item, d.tipo_codigo, d.nom_tdoc, c.num_tdo, c.fecha_comp, c.fecha_venci, e.codigo_moneda, a.tasa_cambio, c.id_cot id_doc,  b.num_ret, f.cod_tmocxc, f.des_tmocxc, a.movem_number, a.tasa_cambio, g.nom_ent,  CASE WHEN c.id_moneda = h.id_moneda THEN c.mon_doc WHEN c.id_moneda != h.id_moneda THEN c.mon_doc * c.tasa_cambio END mon_doc, CASE WHEN c.id_moneda = h.id_moneda THEN c.sal_doc WHEN c.id_moneda != h.id_moneda THEN c.sal_doc * c.tasa_cambio END sal_doc, CASE WHEN c.id_moneda = h.id_moneda THEN b.monto_doc WHEN c.id_moneda != h.id_moneda THEN b.monto_doc * c.tasa_cambio END mon_can, CASE WHEN c.id_moneda = h.id_moneda THEN b.mon_ret WHEN c.id_moneda != h.id_moneda THEN b.mon_ret * c.tasa_cambio END mon_ret FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f6003 c ON c.id_cot = b.id_cot  INNER JOIN f6001 d ON d.id_tdoc = c.id_tdo  INNER JOIN f0005 e ON e.id_moneda = a.id_moneda INNER JOIN f6004 f ON f.id_tmocxc = a.id_tmocxc INNER JOIN f0014 g ON g.id_ent = a.id_cli INNER JOIN f5006 h ON h.id_banmov = a.movem_origen WHERE a.movem_origen = {$id}";            
        }else{
            $sql = "SELECT row_number() OVER (ORDER BY c.id_cot)  item, d.tipo_codigo, d.nom_tdoc, c.num_tdo, c.fecha_comp, c.fecha_venci, e.codigo_moneda, a.tasa_cambio, c.id_cot id_doc,  f.cod_tmocxc, f.des_tmocxc, a.movem_number, a.tasa_cambio, g.nom_ent, CASE WHEN c.id_moneda = h.id_moneda THEN c.mon_doc WHEN c.id_moneda != h.id_moneda THEN c.mon_doc * c.tasa_cambio END mon_doc, CASE WHEN c.id_moneda = h.id_moneda THEN c.sal_doc WHEN c.id_moneda != h.id_moneda THEN c.sal_doc * c.tasa_cambio END sal_doc, CASE WHEN c.id_moneda = h.id_moneda THEN b.monto_doc WHEN c.id_moneda != h.id_moneda THEN b.monto_doc * c.tasa_cambio END mon_can FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement INNER JOIN f3004 c ON c.id_cot = b.id_cot  INNER JOIN f3001 d ON d.id_tdoc = c.id_tdo INNER JOIN f0005 e ON e.id_moneda = a.id_moneda INNER JOIN f3002 f ON f.id_tmocxc = a.id_tmocxp INNER JOIN f0014 g ON g.id_ent = a.id_ent INNER JOIN f5006 h ON h.id_banmov = a.movem_origen WHERE a.movem_origen = {$id}";
        }
        $r = DB::query($sql);
        return $r;
    }
    static function delete_row($id){
        $r = DB::query("SELECT a.id_banmov, IFNULL(c.id_movement, p.id_movement) id_movement FROM f5006 a LEFT OUTER JOIN f6006 c ON c.movem_origen = a.id_banmov LEFT OUTER JOIN f3008 p ON p.movem_origen = a.id_banmov WHERE a.id_banmov = {$id}");
        if($r){
            if($r[0]['id_movement']){
                $row = $r[0]['id_movement'];
                if(is_iterable($r)){
                    foreach($r as $mov){
                        //CXC
                        $r = DB::delete('f60061', ['movem_id' => $mov['id_movement']] );
                        $r = DB::delete('f6006', ['id_movement' => $mov['id_movement']] );
                        //CXP
                        $r = DB::delete('f30081', ['movem_id' => $mov['id_movement']] );
                        $r = DB::delete('f3008', ['id_movement' => $mov['id_movement']] );
                    }
                }
                //CXC
                $r = DB::delete('f60061', ['movem_id' => $row] );
                $r = DB::delete('f6006', ['id_movement' => $row] );
                //CXP
                $r = DB::delete('f30081', ['movem_id' => $row] );
                $r = DB::delete('f3008', ['id_movement' => $row] );
            }
            $r = DB::delete('f50061', ['id_banmov' => $id] );
            $r = DB::delete('f5006', ['id_banmov' => $id] );
        }
        return $r;
    }
    static function print_mov($id){
        $r = DB::query("SELECT row_number() OVER (ORDER BY b.id_bandet) item, c.id_emp, c.nombre_emp, d.id_bantmo, d.cod_bantmo, d.nom_bantmo, d.acc_bantmo, f.cod_banco, f.nombre_banco, e.id_bancue, e.cuenta_bancue, a.fecha_comp, a. num_banmov, a.id_moneda, a.tasa_cambio, a.benef_banmov, a.des_banmov, a.monto_nac, a.monto_for, CONCAT(j.name_user, ' ', j.last_user) user_create, h.cod_bancon, h.nom_bancon, i.cod_cta, i.nombre_cta, k.cod_aux, k.nombre_aux, b.monto_nac, b.monto_for, g.codigo_moneda, g.nombre_moneda, c.logo, c.rif_empresa, a.id_banmov, d.efe_bantmo 
        FROM `f5006` a
        INNER JOIN f50061 b ON b.id_banmov = a.id_banmov
        INNER JOIN f0011 c ON c.id_emp = a.id_emp
        INNER JOIN f5002 d ON d.id_bantmo = a.id_bantmo
        INNER JOIN f5004 e ON e.id_bancue = a.id_bancue
        INNER JOIN f5003 f ON f.id_banco = e.id_banco
        INNER JOIN f0005 g ON g.id_moneda = a.id_moneda
        INNER JOIN f5005 h ON h.id_bancon = b.id_bancon
        INNER JOIN f0010 i ON i.id_cta = h.id_ctb
        INNER JOIN f0002 j ON j.id_user = a.create_user
        LEFT OUTER JOIN f0009 k ON k.id_aux = h.id_aux
        WHERE a.id_banmov = {$id} 
        ORDER BY b.id_bandet");
        return $r;
    }
    static function banmov_cuentas($id_emp, $fec_ini, $fec_fin, $id_bancue, $id_bancon){
        $filter =  "WHERE a.id_emp = {$id_emp} AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' ";
        $filter_con = "";
        if($id_bancue){
            $filter .= "AND a.id_bancue = {$id_bancue} " ;
        }
        if($id_bancon){
            $filter .= "AND b.id_bancon = {$id_bancon} " ;
            $filter_con = " AND id_bancon = {$id_bancon}";
        }
         $sql = "SELECT row_number() OVER (ORDER BY a.fecha_comp) item, c.id_emp, c.nombre_emp, d.id_bantmo, d.cod_bantmo, CASE WHEN IFNULL(m.id_ent,0) > 0 THEN CONCAT(d.nom_bantmo, ' - ' , m.nom_ent) WHEN IFNULL(a.benef_banmov, '') = '' THEN d.nom_bantmo  ELSE CONCAT(d.nom_bantmo, ' - ' , a.benef_banmov) END  nom_bantmo, d.acc_bantmo, f.cod_banco, f.nombre_banco, e.id_bancue, e.cuenta_bancue, a.fecha_comp, a. num_banmov, a.id_moneda, a.tasa_cambio, a.des_banmov, g.codigo_moneda, g.nombre_moneda, c.logo, c.rif_empresa, fn_saldo_banco(a.id_emp, a.fecha_comp, a.id_bancue) saldo, (SELECT SUM(monto_nac) FROM f50061 WHERE id_banmov = a.id_banmov $filter_con) mon_mov_nac, (SELECT SUM(monto_for) FROM f50061 WHERE id_banmov = a.id_banmov $filter_con) mon_mov_for
        FROM `f5006` a
        INNER JOIN f50061 b ON b.id_banmov = a.id_banmov
        INNER JOIN f0011 c ON c.id_emp = a.id_emp
        INNER JOIN f5002 d ON d.id_bantmo = a.id_bantmo
        INNER JOIN f5004 e ON e.id_bancue = a.id_bancue
        INNER JOIN f5003 f ON f.id_banco = e.id_banco
        INNER JOIN f0005 g ON g.id_moneda = a.id_moneda
        INNER JOIN f5005 h ON h.id_bancon = b.id_bancon
        INNER JOIN f0010 i ON i.id_cta = h.id_ctb
        INNER JOIN f0002 j ON j.id_user = a.create_user
        LEFT OUTER JOIN f0009 k ON k.id_aux = h.id_aux
        LEFT OUTER JOIN f6006 l ON l.movem_origen = a.id_banmov
        LEFT OUTER JOIN f0014 m ON m.id_ent = l.id_cli
        " . $filter . "
        GROUP BY c.id_emp, c.nombre_emp, d.id_bantmo, d.cod_bantmo, CASE WHEN IFNULL(m.id_ent,0) > 0 THEN CONCAT(d.nom_bantmo, ' - ' , m.nom_ent) WHEN IFNULL(a.benef_banmov, '') = '' THEN d.nom_bantmo  ELSE CONCAT(d.nom_bantmo, ' - ' , a.benef_banmov) END, d.acc_bantmo, f.cod_banco, f.nombre_banco, e.id_bancue, e.cuenta_bancue, a.fecha_comp, a. num_banmov, a.id_moneda, a.tasa_cambio, a.des_banmov, g.codigo_moneda, g.nombre_moneda, c.logo, c.rif_empresa, fn_saldo_banco(a.id_emp, a.fecha_comp, a.id_bancue) ORDER BY a.id_emp, a.fecha_comp, d.acc_bantmo";
        $r = DB::query($sql);
        return $r;
    }
}
