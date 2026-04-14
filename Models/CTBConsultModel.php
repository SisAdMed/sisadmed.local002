<?php
class CTBConsultModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function saldosCuentas_mov($id_emp, $fec_ini, $fec_fin, $cod_cta = '', $cod_aux = ''){
        $filter_cta = "";
        $filter_aux = "";
        if(!empty($cod_cta)){
            $filter_cta = " AND d.cod_cta = '". $cod_cta ."'";
        }
        if(!empty($cod_aux)){
            $filter_aux = " AND e.cod_aux = '". $cod_aux ."'";
        }
        $r = DB::query("SELECT d.id_cta, c.id_emp, c.nombre_emp, a.fecha_comp, f.codigo_moneda, a.tasa_cambio, a.num_comp, a.ori_comp, d.cod_cta, d.nombre_cta, CASE WHEN e.cod_aux IS NOT NULL THEN e.cod_aux ELSE ' ' END cod_aux, CASE WHEN e.nombre_aux IS NOT NULL THEN e.nombre_aux ELSE ' ' END nombre_aux,  0 sald_ant, CASE WHEN b.det_tipo = 'D' THEN det_monto ELSE 0 END debe, CASE WHEN b.det_tipo = 'H' THEN det_monto ELSE 0 END haber, a.desc_comp FROM f00121 a INNER JOIN f00122 b ON b.id_comp = a.id_comp INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f0010 d ON d.id_cta = b.id_cue INNER JOIN f0005 f ON f.id_moneda = a.id_moneda LEFT OUTER JOIN f0009 e ON e.id_aux = b.id_aux
            WHERE a.id_emp = {$id_emp} AND a.fecha_comp BETWEEN '". $fec_ini ."' AND '". $fec_fin ."'" .$filter_cta . $filter_aux);
        return $r;
    }
}