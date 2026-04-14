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
        
        $sql = "SELECT a.fecha_comp, e.cod_bantmo, a.num_banmov, b.cuenta_bancue, a.des_banmov, CASE WHEN e.acc_bantmo = 'A' THEN SUM(d.monto_nac * a.tasa_cambio) ELSE 0 END mon_debe, CASE WHEN e.acc_bantmo != 'A' THEN SUM(d.monto_nac * a.tasa_cambio) ELSE 0 END mon_habe FROM f5006 a INNER JOIN f5004 b ON b.id_bancue = a.id_bancue INNER JOIN f0010 c ON c.id_cta = b.id_ctb INNER JOIN f50061 d ON d.id_banmov = a.id_banmov INNER JOIN f5002 e ON e.id_bantmo = a.id_bantmo LEFT OUTER JOIN f0009 m ON m.id_aux = b.id_aux WHERE a.id_emp = 2 AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter_mov GROUP BY 1, 2, 3, 4
        UNION 
        SELECT a.fecha_comp, e.cod_bantmo, a.num_banmov, b.cuenta_bancue, a.des_banmov, CASE WHEN e.acc_bantmo = 'D' THEN (d.monto_nac * a.tasa_cambio) ELSE 0 END mon_debe, CASE WHEN e.acc_bantmo != 'D' THEN (d.monto_nac * a.tasa_cambio) ELSE 0 END mon_habe FROM f5006 a INNER JOIN f5004 b ON b.id_bancue = a.id_bancue INNER JOIN f0010 c ON c.id_cta = b.id_ctb INNER JOIN f50061 d ON d.id_banmov = a.id_banmov INNER JOIN f5002 e ON e.id_bantmo = a.id_bantmo INNER JOIN f5005 f ON f.id_bancon = d.id_bancon INNER JOIN f0010 g ON g.id_cta = f.id_ctb LEFT OUTER JOIN f0009 m ON m.id_aux = b.id_aux LEFT OUTER JOIN f0009 n on n.id_aux = d.id_aux WHERE a.id_emp = 2 AND a.status = 1 AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter_det ORDER BY 1, 2, 3, 4";
        return $r = DB::query($sql);
    }
}