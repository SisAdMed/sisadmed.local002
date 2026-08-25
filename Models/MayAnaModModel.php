<?php
class MayAnaModModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function AnaliticoBan($id_emp, $fec_ini, $fec_fin, $id_ctb='', $id_aux=''){
        $filter_mov = '';
        $filter_det = '';
        if ($id_ctb != '') {
            $filter_mov .= " AND c.id_cta = '$id_ctb' ";
            $filter_det .= " AND g.id_cta = '$id_ctb' ";
        }
        if($id_aux != ''){
            $filter_mov .= " AND m.id_aux LIKE '$id_aux' ";
            $filter_det .= " AND d.id_aux LIKE '$id_aux' ";
        }
        
        $sql = "SELECT a.fecha_comp, e.cod_bantmo, a.num_banmov, b.cuenta_bancue, a.des_banmov, 'MOV' ori, '' concepto, CASE WHEN e.acc_bantmo = 'A' THEN SUM(d.monto_nac * a.tasa_cambio) ELSE 0 END mon_debe, CASE WHEN e.acc_bantmo != 'A' THEN SUM(d.monto_nac * a.tasa_cambio) ELSE 0 END mon_habe FROM f5006 a INNER JOIN f5004 b ON b.id_bancue = a.id_bancue INNER JOIN f0010 c ON c.id_cta = b.id_ctb INNER JOIN f50061 d ON d.id_banmov = a.id_banmov INNER JOIN f5002 e ON e.id_bantmo = a.id_bantmo LEFT OUTER JOIN f0009 m ON m.id_aux = b.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter_mov GROUP BY 1, 2, 3, 4
        UNION 
        SELECT a.fecha_comp, e.cod_bantmo, a.num_banmov, b.cuenta_bancue, a.des_banmov, 'C' ori, f.nom_bancon concepto, CASE WHEN e.acc_bantmo = 'D' THEN (d.monto_nac * a.tasa_cambio) ELSE 0 END mon_debe, CASE WHEN e.acc_bantmo != 'D' THEN (d.monto_nac * a.tasa_cambio) ELSE 0 END mon_habe FROM f5006 a INNER JOIN f5004 b ON b.id_bancue = a.id_bancue INNER JOIN f0010 c ON c.id_cta = b.id_ctb INNER JOIN f50061 d ON d.id_banmov = a.id_banmov INNER JOIN f5002 e ON e.id_bantmo = a.id_bantmo INNER JOIN f5005 f ON f.id_bancon = d.id_bancon INNER JOIN f0010 g ON g.id_cta = f.id_ctb LEFT OUTER JOIN f0009 m ON m.id_aux = b.id_aux LEFT OUTER JOIN f0009 n on n.id_aux = d.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter_det ORDER BY 1, 2, 3, 4";
        return $r = DB::query($sql);
    }
    static function AnaliticoCxp($id_emp, $fec_ini, $fec_fin, $id_ctb = '', $id_aux = ''){
        $filter_mov = '';
        $filter_det = '';
        $filter_mov_e = '';
        $filter_mov_d = '';
        if ($id_ctb != '') {
            $filter_mov .= " AND cd.id_cta = '$id_ctb' ";
            $filter_det .= " AND cc.id_cta = '$id_ctb' ";
            $filter_mov_e = " AND cm.id_cta = '$id_ctb' ";
            $filter_mov_d = " AND cd.id_cta = '$id_ctb' ";
        }
        if($id_aux != ''){
            $filter_mov .= " AND ad.id_aux LIKE '$id_aux' ";
            $filter_det .= " AND ac.id_aux LIKE '$id_aux' ";
            $filter_mov_e = " AND am.id_cta = '$id_ctb' ";
            $filter_mov_d = " AND dd.id_cta = '$id_ctb' ";
        }
        //Documentos, Detalle de Documentos, Movimietnos, Detalle de Movimientos
        $sql = "SELECT a.fecha_comp, d.tipo_codigo cod_bantmo, a.num_tdo num_banmov, e.nom_ent cuenta_bancue, a.descrip_cot des_banmov, 'DOC' ori, '' concepto, CASE WHEN d.tipo_tdoc = 'A' THEN SUM(b.monto * a.tasa_cambio) ELSE 0 END mon_debe, CASE WHEN d.tipo_tdoc != 'A' THEN SUM(b.monto * a.tasa_cambio) ELSE 0 END mon_habe FROM f3004 a INNER JOIN f30041 b ON b.id_cot = a.id_cot INNER JOIN f3003 c ON c.id = b.id_concxp INNER JOIN f3001 d ON d.id_tdoc = a.id_tdo INNER JOIN f0014 e ON e.id_ent = a.id_cli INNER JOIN f0010 cd on cd.id_cta = d.id_ctb LEFT OUTER JOIN f0009 ad ON ad.id_aux = d.id_aux INNER JOIN f0010 cc ON cc.id_cta = c.id_ctb LEFT OUTER JOIN f0009 ac on ac.id_aux = b.id_aux INNER JOIN f0011 em ON em.id_emp = a.id_emp WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter_mov GROUP BY a.fecha_comp, d.tipo_codigo, a.num_tdo, e.nom_ent, a.descrip_cot
        UNION        
        SELECT a.fecha_comp, d.tipo_codigo cod_bantmo, a.num_tdo num_banmov, e.nom_ent cuenta_bancue, a.descrip_cot des_banmov, 'CON' ori, c.nombre_con concepto, CASE WHEN d.tipo_tdoc != 'A' AND b.monto > 0 THEN b.monto * a.tasa_cambio ELSE 0 END mon_debe, CASE WHEN d.tipo_tdoc != 'A' AND b.monto < 0 THEN ABS(b.monto * a.tasa_cambio) WHEN d.tipo_tdoc = 'A' THEN b.monto * a.tasa_cambio ELSE 0 END mon_habe FROM f3004 a INNER JOIN f30041 b ON b.id_cot = a.id_cot INNER JOIN f3003 c ON c.id = b.id_concxp INNER JOIN f3001 d ON d.id_tdoc = a.id_tdo INNER JOIN f0014 e ON e.id_ent = a.id_cli INNER JOIN f0010 cd on cd.id_cta = d.id_ctb LEFT OUTER JOIN f0009 ad ON ad.id_aux = d.id_aux INNER JOIN f0010 cc ON cc.id_cta = c.id_ctb LEFT OUTER JOIN f0009 ac on ac.id_aux = b.id_aux INNER JOIN f0011 em ON em.id_emp = a.id_emp WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter_det
        UNION        
        SELECT a.fecha_comp, c.cod_tmocxc tipo_codigo, a.movem_number num_tdo, e.nom_ent, CONCAT('APLICACION DE MOVIMIENTO ', f.tipo_codigo, '-', b.num_tdo) descrip_cot, 'MOV' ori, ' ' concepto, CASE WHEN c.acc_tmocxc = 'D' THEN SUM(b.monto_doc * a.tasa_cambio) ELSE 0 END mon_debe, CASE WHEN c.acc_tmocxc = 'A' THEN SUM(b.monto_doc * a.tasa_cambio) ELSE 0 END mon_habe FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement INNER JOIN f3002 c ON c.id_tmocxc = a.id_tmocxp INNER JOIN f0011 d ON d.id_emp = a.id_emp INNER JOIN f0014 e ON e.id_ent = a.id_ent INNER JOIN f3001 f ON f.id_tdoc = b.id_tdo INNER JOIN f3004 g ON g.id_cot = b.id_cot INNER JOIN f0010 cm ON cm.id_cta = c.id_ctbcue LEFT OUTER JOIN f0009 am ON am.id_aux = c.id_aux INNER JOIN f0010 cd ON cd.id_cta = f.id_ctb LEFT OUTER JOIN f0009 ad ON ad.id_aux = f.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.movem_origen = 'CXP' $filter_mov_e  $filter_mov_d GROUP BY a.fecha_comp, c.cod_tmocxc, a.movem_number, e.nom_ent, CONCAT('APLICACION DE MOVIMIENTO ', f.tipo_codigo, '-', b.num_tdo)
        UNION        
        SELECT a.fecha_comp, c.cod_tmocxc tipo_codigo, a.movem_number num_tdo, e.nom_ent, CONCAT('APLICACION DE MOVIMIENTO ', f.tipo_codigo, '-', b.num_tdo) descrip_cot, 'DEM' ori, '' concepto, CASE WHEN c.acc_tmocxc = 'A' THEN (b.monto_doc * a.tasa_cambio) ELSE 0 END mon_debe, CASE WHEN c.acc_tmocxc = 'D' THEN (b.monto_doc * a.tasa_cambio) ELSE 0 END mon_habe FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement INNER JOIN f3002 c ON c.id_tmocxc = a.id_tmocxp INNER JOIN f0011 d ON d.id_emp = a.id_emp INNER JOIN f0014 e ON e.id_ent = a.id_ent INNER JOIN f3001 f ON f.id_tdoc = b.id_tdo INNER JOIN f3004 g ON g.id_cot = b.id_cot INNER JOIN f0010 cm ON cm.id_cta = c.id_ctbcue LEFT OUTER JOIN f0009 am ON am.id_aux = c.id_aux INNER JOIN f0010 cd ON cd.id_cta = f.id_ctb LEFT OUTER JOIN f0009 ad ON ad.id_aux = f.id_aux WHERE a.id_emp = {$id_emp} AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.movem_origen = 'CXP' $filter_mov_e  $filter_mov_d  ORDER BY 1, 2, 3, 4";             
        return $r = DB::query($sql);
    }
}