<?php
class LibVentasModel extends DB{
    public function __construct() {
        parent::__construct();
    }
    static function ventas($id_emp, $fec_ini, $fec_fin){
        /*$filter = '';
        if($id_tdo){
            $filter = " AND a.id_tdo = {$id_tdo} ";
        }
        if($id_ent){
            $filter = " AND a.id_cli = {$id_ent} ";
        }*/
        $sql = "SELECT a.id_emp, e.nombre_emp, a.fecha_comp, d.rif_ent, d.nom_ent, ' ' exporta, CASE WHEN f.tipo_tdoc = 'F' THEN CONCAT( f.  tipo_codigo , '-', a.num_tdo) ELSE ' ' END  factura, CASE WHEN f.tipo_tdoc = 'B' THEN CONCAT( f.tipo_codigo , '-', a.num_tdo) ELSE ' ' END debito, CASE WHEN f.tipo_tdoc = 'C' THEN CONCAT( f.tipo_codigo , '-', a.num_tdo) ELSE ' ' END credito, CASE WHEN f.tipo_tdoc = 'F' THEN 1 WHEN f.tipo_tdoc = 'B' THEN 2 ELSE 3 END tipo_tra, IFNULL(a.doc_afectado, ' ') afectado, CONCAT('00-', LPAD(a.nro_control, 8, '0')) nro_control, a.mon_doc * CASE WHEN a.id_moneda != e.id_moneda THEN a.tasa_cambio ELSE 1 END total_venta_mas_iva,  SUM(CASE WHEN c.iva = 'N' THEN c.monto ELSE 0 END)* CASE WHEN a.id_moneda != e.id_moneda THEN a.tasa_cambio ELSE 1 END total_exento, SUM(CASE WHEN c.iva = 'S' THEN c.monto ELSE 0 END) * CASE WHEN a.id_moneda = e.id_moneda THEN 1 ELSE a.tasa_cambio END total_gravable, IFNULL(g.txr1_iva, 0) trr1_iva, SUM(CASE WHEN c.iva = 'S' THEN c.mon_iva ELSE 0 END)* CASE WHEN a.id_moneda = e.id_moneda THEN 1 ELSE a.tasa_cambio END mon_iva, e.logo FROM f6003 a INNER JOIN f60032 c ON c.id_cot = a.id_cot INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f0011 e ON e.id_emp = a.id_emp INNER JOIN f6001 f ON f.id_tdoc = a.id_tdo LEFT OUTER JOIN f0017 g ON g.fec_iva <= a.fecha_comp AND c.iva = 'S' WHERE a.id_emp = {$id_emp} AND a.fecha_comp BETWEEN '" . $fec_ini . "' AND '" . $fec_fin . "' GROUP BY 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ORDER BY 1, 3, a.num_tdo";
        $r = DB::query($sql);
       return $r;
    }
    static function compras($id_emp, $fec_ini, $fec_fin){
        $sql = "SELECT b.nombre_emp, a.fecha_comp, d.rif_ent, d.nom_ent, CASE WHEN e.tipo_tdoc = 'M' THEN CONCAT( e.tipo_codigo, '-', a.num_tdo) ELSE ' ' END factura, CASE WHEN e.tipo_tdoc = 'A' THEN CONCAT( e.tipo_codigo, '-', a.num_tdo) ELSE ' ' END credito, CASE WHEN e.tipo_tdoc = 'V' THEN CONCAT( e.tipo_codigo, '-', a.num_tdo) ELSE ' ' END debito, CONCAT('00-', LPAD(a.num_control, 8, '0')) nro_control, CASE e.tipo_tdoc WHEN 'M' THEN '01' WHEN 'A' then '03' ELSE '02' END tipo_tra, SUM((g.monto + g.mon_iva) * a.tasa_cambio) total_venta_mas_iva, ( SELECT SUM(monto * a.tasa_cambio) FROM f30041 WHERE id_cot = a.id_cot AND mon_iva != 0 AND id_concxp <> f.id_retislr AND id_concxp <> f.id_retiva ) total_gravable, ( SELECT SUM(monto * a.tasa_cambio) FROM f30041 WHERE id_cot = a.id_cot AND mon_iva = 0 AND id_concxp <> f.id_retislr AND id_concxp <> f.id_retiva ) total_exento, ( SELECT SUM(mon_iva * a.tasa_cambio) FROM f30041 WHERE id_cot = a.id_cot AND mon_iva != 0 ) mon_iva, b.logo, ' ' afectado, (SELECT txr1_iva FROM f0017 WHERE fec_iva <= a.fecha_comp ORDER BY fec_iva DESC LIMIT 1) trr1_iva FROM f3004 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0005 c ON c.id_moneda = a.id_moneda INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f3001 e ON e.id_tdoc = a.id_tdo INNER JOIN f3999 f ON f.id_emp = a.id_emp INNER JOIN f30041 g ON g.id_cot = a.id_cot AND g.id_concxp <> f.id_retislr AND g.id_concxp <> f.id_retiva INNER JOIN f8999 h ON h.id_emp = a.id_emp AND h.tdoc_purdelnot != e.id_tdoc WHERE a.id_emp = {$id_emp} AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND e.id_tdoc NOT IN (10,11,12) GROUP BY b.nombre_emp, a.fecha_comp, d.rif_ent, d.nom_ent, CASE WHEN e.tipo_tdoc = 'M' THEN CONCAT( e.tipo_codigo, '-', a.num_tdo) ELSE ' ' END, CASE WHEN e.tipo_tdoc = 'A' THEN CONCAT( e.tipo_codigo, '-', a.num_tdo) ELSE ' ' END, CASE WHEN e.tipo_tdoc = 'V' THEN CONCAT( e.tipo_codigo, '-', a.num_tdo) ELSE ' ' END, a.num_control, CASE e.tipo_tdoc WHEN 'M' THEN '01' WHEN 'A' then '03' ELSE '02' END";
        $r = DB::query($sql);
        return $r;
    }
}
