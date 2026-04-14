<?php
class CloseModModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static function obtenerModulos(){
        $sql = "SELECT * FROM f0022 WHERE cierre = 1";
        $r = DB::query($sql);
        return $r;
    }
    static function leerRegistrosModulo($module, $id_emp, $fec_ini, $fec_fin){
        $r = '';
        if($module == "B"){
            $r = CloseModModel::close_ban($id_emp, $fec_ini, $fec_fin);
        }elseif($module == "C"){
            $r = CloseModModel::close_cxc($id_emp, $fec_ini, $fec_fin);
        }elseif($module == "P"){
            $r = CloseModModel::close_cxp($id_emp, $fec_ini, $fec_fin);
        }elseif($module == "M"){
            $r = CloseModModel::close_com($id_emp, $fec_ini, $fec_fin);
        }
        if($r){
            return $r;
        }
        return false;
    }
    static function generarAsiento($module, $registros, $id_emp, $fec_fin){
       
    }
    static function crearAsientoContable($registros){
        //Crear Encabezado
    }
    static function close_ban($id_emp, $fec_ini, $fec_fin) {
        $sql = "SELECT a.id_emp, f.id_cta, IFNULL(z.id_aux, ' ') id_aux, CASE e.acc_bantmo WHEN 'A' THEN 'D' ELSE 'H' END tipo, ROUND(CASE e.acc_bantmo WHEN 'A' THEN SUM(b.monto_nac * a.tasa_cambio) ELSE 0 END, 2) mon_debe, ROUND(CASE e.acc_bantmo WHEN 'D' THEN SUM(b.monto_nac * a.tasa_cambio) ELSE 0 END, 2) mon_habe FROM f5006 a INNER JOIN f50061 b ON b.id_banmov = a.id_banmov INNER JOIN f5004 c ON c.id_bancue = a.id_bancue INNER JOIN f5003 d ON d.id_banco = c.id_banco INNER JOIN f5002 e ON e.id_bantmo = a.id_bantmo INNER JOIN f0010 f ON f.id_cta = c.id_ctb INNER JOIN f5005 g ON g.id_bancon = b.id_bancon INNER JOIN f0010 h ON h.id_cta = g.id_ctb INNER JOIN f0011 i ON i.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = b.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = c.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' GROUP BY a.id_emp, f.id_cta, IFNULL(z.id_aux, ' '), CASE e.acc_bantmo WHEN 'A' THEN 'D' ELSE 'H' END 
        UNION
        SELECT a.id_emp, h.id_cta, IFNULL(y.id_aux, ' ') id_aux, CASE e.acc_bantmo WHEN 'D' THEN 'D' ELSE 'H' END tipo, ROUND(CASE e.acc_bantmo WHEN 'D' THEN SUM(b.monto_nac * a.tasa_cambio) ELSE 0 END, 2) mon_debe, ROUND(CASE e.acc_bantmo WHEN 'A' THEN SUM(b.monto_nac * a.tasa_cambio) ELSE 0 END, 2) mon_habe FROM f5006 a INNER JOIN f50061 b ON b.id_banmov = a.id_banmov INNER JOIN f5004 c ON c.id_bancue = a.id_bancue INNER JOIN f5003 d ON d.id_banco = c.id_banco INNER JOIN f5002 e ON e.id_bantmo = a.id_bantmo INNER JOIN f0010 f ON f.id_cta = c.id_ctb INNER JOIN f5005 g ON g.id_bancon = b.id_bancon INNER JOIN f0010 h ON h.id_cta = g.id_ctb INNER JOIN f0011 i ON i.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = b.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = c.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' GROUP BY a.id_emp, h.id_cta, IFNULL(y.id_aux, ' '), CASE e.acc_bantmo WHEN 'A' THEN 'D' ELSE 'H' END 
        ORDER BY 1, 2, 3, 4";
        return $r = DB::query($sql);
    }
    static function close_cxc($id_emp, $fec_ini, $fec_fin){      
        $sql = "SELECT a.id_emp, d.id_cta, IFNULL(y.id_aux, ' ') id_aux, 'D' tipo, SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN (b.monto + b.mon_iva) WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN (b.monto + b.mon_iva) ELSE (b.monto + b.mon_iva) * a.tasa_cambio END) mon_debe, 0 mon_habe FROM f6003 a INNER JOIN f60032 b ON b.id_cot = a.id_cot INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0010 d ON d.id_cta = c.id_cta INNER JOIN f6002 e ON e.id = b.id_concxc INNER JOIN f0010 f ON f.id_cta = e.id_ctbcue INNER JOIN f0011 g ON g.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux  LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_ctbaux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.id_cont = 'CXC' GROUP BY a.id_emp, g.id_moneda, c.id_cta, IFNULL(c.id_aux, ' ')  
        UNION 
        SELECT a.id_emp, f.id_cta, IFNULL(z.id_aux, ' ') id_aux, 'H' tipo, 0 mon_debe, SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN b.monto WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN (b.monto ) ELSE (b.monto * a.tasa_cambio) END) mon_habe FROM f6003 a INNER JOIN f60032 b ON b.id_cot = a.id_cot INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0010 d ON d.id_cta = c.id_cta INNER JOIN f6002 e ON e.id = b.id_concxc INNER JOIN f0010 f ON f.id_cta = e.id_ctbcue INNER JOIN f0011 g ON g.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux  LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_ctbaux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.id_cont = 'CXC' GROUP BY a.id_emp, g.id_moneda, f.id_cta, IFNULL(z.id_aux, ' '), IFNULL(z.id_aux, ' ')
        UNION
        SELECT a.id_emp, f.id_cta, ' ' id_aux, 'H' tipo, 0 mon_debe, SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN b.mon_iva WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN b.mon_iva ELSE b.mon_iva * a.tasa_cambio END) mon_habe FROM f6003 a INNER JOIN f60032 b ON b.id_cot = a.id_cot INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0010 d ON d.id_cta = c.id_cta INNER JOIN f6002 e ON e.id = b.id_concxc INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f0010 f ON f.id_cta = g.iva_deb_fis LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux  LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_ctbaux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.id_cont = 'CXC' GROUP BY a.id_emp, g.id_moneda, f.id_cta 
        UNION
        SELECT a.id_emp, d.id_cta, IFNULL(y.id_aux, ' ') id_aux, 'D' tipo, CASE WHEN c.acc_tmocxc = 'A' THEN SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN b.monto_doc WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN (b.monto_doc * a.tasa_cambio) END) ELSE 0 END mon_debe, CASE WHEN c.acc_tmocxc = 'D' THEN SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN b.monto_doc WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN (b.monto_doc * a.tasa_cambio) END) END mon_habe FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f6004 c ON c.id_tmocxc = a.id_tmocxc INNER JOIN f0010 d ON d.id_cta = c.id_ctbcue INNER JOIN f6001 e ON e.id_tdoc = b.id_tdo  INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f0010 f ON f.id_cta = e.id_cta LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.movem_origen = 'CXC' GROUP BY a.id_emp, d.id_cta, IFNULL(y.id_aux, ' ')
        UNION
        SELECT a.id_emp, e.id_cta, IFNULL(z.id_aux, ' ') id_aux, 'H' tipo, CASE WHEN c.acc_tmocxc = 'D' THEN SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN b.monto_doc WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN (b.monto_doc * a.tasa_cambio) END) ELSE 0 END mon_debe, CASE WHEN c.acc_tmocxc = 'A' THEN SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN b.monto_doc WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN (b.monto_doc * a.tasa_cambio) END) ELSE 0 END mon_habe FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f6004 c ON c.id_tmocxc = a.id_tmocxc INNER JOIN f0010 d ON d.id_cta = c.id_ctbcue INNER JOIN f6001 e ON e.id_tdoc = b.id_tdo  INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f0010 f ON f.id_cta = e.id_cta LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.movem_origen = 'CXC' GROUP BY a.id_emp, d.id_cta, IFNULL(y.id_aux, ' ')        
        ORDER BY 1, 2, 3, 4";        
        return $r = DB::query($sql);
    }
    static function close_cxp($id_emp, $fec_ini, $fec_fin){
        $sql = "SELECT a.id_emp, d.id_cta, IFNULL(y.id_aux, ' ') id_aux, 'H' tipo, 0 mon_debe,  SUM(CASE WHEN a.id_moneda = g.id_moneda THEN (b.monto + b.mon_iva) ELSE (b.monto + b.mon_iva) * a.tasa_cambio END) mon_habe FROM f3004 a INNER JOIN f30041 b ON b.id_cot = a.id_cot INNER JOIN f3001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0010 d ON d.id_cta = c.id_ctb INNER JOIN f3003 e ON e.id = b.id_concxp INNER JOIN f0010 f ON f.id_cta = e.id_ctb INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f3999 h ON h.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_aux WHERE a.id_emp = {$id_emp} ANd a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND IFNULL(a.origen, ' ') = ' '  GROUP BY 1, 2, 3, 4 
        UNION 
        SELECT a.id_emp, f.id_cta, IFNULL(z.id_aux, ' ') id_aux, 'D' tipo, SUM(CASE WHEN a.id_moneda = g.id_moneda THEN b.monto  ELSE b.monto * a.tasa_cambio END) mon_debe, 0 mon_habe FROM f3004 a INNER JOIN f30041 b ON b.id_cot = a.id_cot INNER JOIN f3001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0010 d ON d.id_cta = c.id_ctb INNER JOIN f3003 e ON e.id = b.id_concxp INNER JOIN f0010 f ON f.id_cta = e.id_ctb INNER JOIN f0011 g ON g.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_aux WHERE a.id_emp = {$id_emp} ANd a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND IFNULL(a.origen, ' ') = ' ' GROUP BY 1, 2, 3, 4
        UNION 
        SELECT a.id_emp, f.id_cta, IFNULL(z.id_aux, ' ') id_aux, 'D' tipo,  SUM(CASE WHEN a.id_moneda = g.id_moneda THEN b.mon_iva ELSE b.mon_iva * a.tasa_cambio END) mon_debe, 0 mon_habe FROM f3004 a INNER JOIN f30041 b ON b.id_cot = a.id_cot INNER JOIN f3001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0010 d ON d.id_cta = c.id_ctb INNER JOIN f3003 e ON e.id = b.id_concxp INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f0010 f ON f.id_cta = g.iva_deb_fis LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_aux WHERE a.id_emp = {$id_emp} ANd a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND IFNULL(a.origen, ' ') = ' '  GROUP BY 1, 2, 3, 4
        UNION SELECT a.id_emp, d.id_cta, IFNULL(y.id_aux, ' ') id_aux, 'D' tipo, SUM(CASE WHEN a.id_moneda = g.id_moneda THEN b.monto_doc ELSE b.monto_doc * a.tasa_cambio END) mon_debe, 0 mon_habe FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement INNER JOIN f3002 c ON c.id_tmocxc = a.id_tmocxp INNER JOIN f0010 d ON d.id_cta = c.id_ctbcue INNER JOIN f3001 e ON e.id_tdoc = b.id_tdo INNER JOIN f0010 f ON f.id_cta = e.id_ctb INNER JOIN f0011 g ON g.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.movem_origen = 'CXP' GROUP BY 1, 2, 3, 4 
        UNION 
        SELECT a.id_emp, d.id_cta, IFNULL(z.id_aux, ' ') id_aux, 'H' tipo, 0 mon_debe, SUM(CASE WHEN a.id_moneda = g.id_moneda THEN b.monto_doc ELSE b.monto_doc * a.tasa_cambio END) mon_habe FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement INNER JOIN f3002 c ON c.id_tmocxc = a.id_tmocxp INNER JOIN f0010 d ON d.id_cta = c.id_ctbcue INNER JOIN f3001 e ON e.id_tdoc = b.id_tdo INNER JOIN f0010 f ON f.id_cta = e.id_ctb INNER JOIN f0011 g ON g.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.movem_origen = 'CXP' GROUP BY 1, 2, 3, 4 ORDER BY 1, 2, 3, 4";
        return $r = DB::query($sql);
    }
    static function close_com($id_emp, $fec_ini, $fec_fin){
        $sql = "SELECT a.id_emp, jcdoc.id_cta, IFNULL(kadoc.id_aux, ' ') id_aux, 'H' tipo, 0 mon_debe, SUM(c.mon_doc * a.tasa_cambio) mon_habe FROM f8020 a INNER JOIN f80201 b ON b.id_cot = a.id_cot INNER JOIN f3004 c ON c.origen = a.id_cot INNER JOIN f30041 d ON d.id_cot = c.id_cot INNER JOIN f3001 e ON e.id_tdoc = a.id_tdo INNER JOIN f0014 f ON f.id_ent = a.id_cli INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f0005 h ON h.id_moneda = a.id_moneda INNER JOIN f3003 i ON i.id = d.id_concxp INNER JOIN f0010 jcdoc ON jcdoc.id_cta = d.id_concxp LEFT OUTER JOIN f0009 kadoc ON kadoc.id_aux = e.id_aux INNER JOIN f0010 icdet ON icdet.id_cta = i.id_ctb LEFT OUTER JOIN f0009 jadet ON jadet.id_aux = d.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' GROUP BY a.id_emp, jcdoc.id_cta, IFNULL(kadoc.id_aux, ' ') 
        UNION 
        SELECT a.id_emp, icdet.id_cta, IFNULL(jadet.id_aux, ' ') id_aux, 'D' tipo, SUM((d.monto + d.mon_iva) * a.tasa_cambio) mon_debe, 0 mon_habe FROM f8020 a INNER JOIN f80201 b ON b.id_cot = a.id_cot INNER JOIN f3004 c ON c.origen = a.id_cot INNER JOIN f30041 d ON d.id_cot = c.id_cot INNER JOIN f3001 e ON e.id_tdoc = a.id_tdo INNER JOIN f0014 f ON f.id_ent = a.id_cli INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f0005 h ON h.id_moneda = a.id_moneda INNER JOIN f3003 i ON i.id = d.id_concxp INNER JOIN f0010 jcdoc ON jcdoc.id_cta = d.id_concxp LEFT OUTER JOIN f0009 kadoc ON kadoc.id_aux = e.id_aux INNER JOIN f0010 icdet ON icdet.id_cta = i.id_ctb LEFT OUTER JOIN f0009 jadet ON jadet.id_aux = d.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' GROUP BY a.id_emp, jcdoc.id_cta, IFNULL(kadoc.id_aux, ' ') ORDER BY 1, 2, 3";
        return $r = DB::query($sql);
    }
    static function close_fac($id_emp, $fec_ini, $fec_fin){
        $sql = "SELECT a.id_emp, d.id_cta, IFNULL(y.id_aux, ' ') id_aux, 'D' tipo, SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN (b.monto + b.mon_iva) WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN (b.monto + b.mon_iva) ELSE (b.monto + b.mon_iva) * a.tasa_cambio END) mon_debe, 0 mon_habe FROM f6003 a INNER JOIN f60032 b ON b.id_cot = a.id_cot INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0010 d ON d.id_cta = c.id_cta INNER JOIN f6002 e ON e.id = b.id_concxc INNER JOIN f0010 f ON f.id_cta = e.id_ctbcue INNER JOIN f0011 g ON g.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux  LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_ctbaux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.id_cont = 'CXC' GROUP BY a.id_emp, g.id_moneda, c.id_cta, IFNULL(c.id_aux, ' ')  
        UNION 
        SELECT a.id_emp, f.id_cta, IFNULL(z.id_aux, ' ') id_aux, 'H' tipo, 0 mon_debe, SUM(CASE WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio = 1 THEN b.monto WHEN a.id_moneda = g.id_moneda AND a.tasa_cambio != 1 THEN (b.monto ) ELSE (b.monto * a.tasa_cambio) END) mon_habe FROM f6003 a INNER JOIN f60032 b ON b.id_cot = a.id_cot INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0010 d ON d.id_cta = c.id_cta INNER JOIN f6002 e ON e.id = b.id_concxc INNER JOIN f0010 f ON f.id_cta = e.id_ctbcue INNER JOIN f0011 g ON g.id_emp = a.id_emp LEFT OUTER JOIN f0009 y ON y.id_aux = c.id_aux  LEFT OUTER JOIN f0009 z ON z.id_aux = e.id_ctbaux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.id_cont = 'CXC' GROUP BY a.id_emp, g.id_moneda, f.id_cta, IFNULL(z.id_aux, ' '), IFNULL(z.id_aux, ' ')";
        return $r = DB::query($sql);
    }
    static function guardar($data) {
        return $r = DB::insert('', $data);
    }
    static function upd_fec_cie($id_emp, $data) {
        return $r = DB::update('f0011', $data, ['id_emp' => $id_emp]);
    }
    static function borrar($id) {
        return $r = DB::delete('', ['' => $id]);
    }
    static function delete_journal_entries($id_emp, $fec_comp){
        $str_fecha = str_replace("-", "", $fec_comp);
        $sql = "DELETE FROM f00121 WHERE id_emp = {$id_emp} AND fecha_comp = '$fec_comp' AND RIGHT(ori_comp, 8) = '$str_fecha'";
        return $r = DB::query($sql);
    }
}