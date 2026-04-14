<?php
    /***
     * Clase para el Módulo de Perfil
     */
    class PerfilModel extends DB{
        public function __construct(){
            parent::__construct();
        }
       static function grafica_001(){
            $r = DB::query("SELECT YEAR(fecha_comp) anio, sum(can_det) as can_det FROM grafica_001 GROUP BY YEAR(fecha_comp) ORDER BY 2 DESC");
            return $r;
        }
        static function grafica_002(){
            $r = DB::query("SELECT ELT(MONTH(fecha_comp), 'Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic') title, sum(can_det) can_det FROM grafica_001 GROUP BY ELT(MONTH(fecha_comp), 'Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.', 'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nov.', 'Dic') ORDER BY 2 DESC");
            return $r;
        }
        static function grafica_003(){
            $r = DB::query("SELECT nom_prod title, sum(can_det) can_det  FROM grafica_001 GROUP by nom_prod  ORDER BY 1");
            return $r;
        }
         static function grafica_004(){
            $r = DB::query("SELECT nom_prod, sum(sub_total) sub_total FROM `grafica_001` GROUP BY nom_prod ORDER BY 2 DESC LIMIT 10");
            return $r;
        }
        static function DatosTabla_001($fec_ini, $fec_fin, $id_emp){
            $filter = '';
            if($id_emp != 0){
                $filter = " AND b.id_emp = {$id_emp}";
            }
            //$sql = "SELECT a.id_emp, a.nombre_emp, SUM(a.sub_total) sub_total, SUM(a.costo) costo, SUM(a.dif_cambio) dif_cambio, SUM(a.utilidad) utilidad, SUM(a.adicional) adicional, SUM(a.mon_iva) + sal_cxc_graphic_fin(a.id_emp, '".$fec_fin."', 2) mon_iva, sal_cxc_graphic(a.id_emp, '".$fec_fin."') + IFNULL(sal_cxc_graphic_fin(a.id_emp, '".$fec_fin. "', 1),0) mon_cxc, sal_inv_ppal(a.id_emp, b.id_alm, '$fec_fin') AS sal_inv_ppal  FROM table_graphic a INNER JOIN f4999 b ON b.id_emp = a.id_emp WHERE a.status = 1 AND a.fecha_comp BETWEEN '".$fec_ini."' AND '".$fec_fin."' ".$filter." GROUP BY a.id_emp, a.nombre_emp ORDER BY a.nombre_emp";
            $sql = "SELECT c.nombre_emp, h.codigo_moneda, SUM(CASE WHEN b.id_cli = 11 AND e.c_consig = 1 AND b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio = 1 THEN a.sub_total / GetExchangeRateVal(b.fecha_comp, 2) WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio ELSE a.sub_total END) sub_total, SUM(a.costo) costo, SUM(a.utilidad) utilidad,(SUM(CASE WHEN b.id_cli = 11 AND e.c_consig = 1 AND b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio = 1 THEN a.sub_total / GetExchangeRateVal(b.fecha_comp, 2) WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio ELSE a.sub_total END)) - (SUM(a.costo) + SUM(a.utilidad)) adicional, sal_cxc_graphic_fin(b.id_emp, '$fec_fin', 1, 0) mon_cxc, sal_inv_ppal(b.id_emp, j.id_alm, '$fec_fin') AS sal_inv_ppal, sal_cxc_graphic_fin(b.id_emp, '$fec_fin', 2, 0) mon_iva, sal_inv_consig(c.id_emp, '$fec_fin') sal_inv_consig, (fn_sal_gastos(c.id_emp, '$fec_ini', '$fec_fin') + fn_sal_gastos_cxp(c.id_emp, '$fec_ini', '$fec_fin')) gastos FROM f60031 a INNER JOIN f6003 b ON b.id_cot = a.id_cot INNER JOIN f0011 c ON c.id_emp = b.id_emp INNER JOIN f4005 d ON d.id_prod = a.id_prod INNER JOIN f0014 e ON e.id_ent = b.id_cli INNER JOIN f6001 f ON f.id_tdoc = b.id_tdo INNER JOIN f0012a g ON g.id_motcam = e.id_motcam INNER JOIN f0005 h ON h.id_moneda = b.id_moneda INNER JOIN f6001 i ON i.id_tdoc = b.id_tdo INNER JOIN f4999 j ON j.id_emp = b.id_emp AND ((i.id_tdoc = j.id_tdoc_not_no_fis AND IFNULL(e.c_consig,0) = 0) OR(i.id_tdoc != j.id_tdoc_not_no_fis)) AND (i.id_tdoc <> j.id_tdoc_not) INNER JOIN f4003 k ON k.id_fab = d.id_fab LEFT OUTER JOIN f60061 l ON l.id_cot = a.id_cot LEFT OUTER JOIN f6006 m ON m.id_movement = l.movem_id WHERE b.status = 1 AND b.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter  GROUP BY c.nombre_emp ";
            $r = DB::query($sql);
            return $r;
        }
        static function DatosTabla_001_det($fec_ini, $fec_fin, $id_emp){
            $filter = '';
            if($id_emp != 0){
                $filter = " AND id_emp = {$id_emp}";
            }
            $r = DB::query("SELECT * FROM table_graphic WHERE status = 1 AND fecha_comp BETWEEN '".$fec_ini."' AND '".$fec_fin."' ".$filter);
            return $r;
        }
        static function DatosTabla_001_det_cli($fec_ini, $fec_fin, $id_emp){
            $sql = "SELECT c.id_emp, c.nombre_emp, e.id_ent, e.nom_ent, SUM(CASE WHEN b.id_cli = 11 AND e.c_consig = 1 AND b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio = 1 THEN a.sub_total / GetExchangeRateVal(b.fecha_comp, 2) WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio ELSE a.sub_total END) sub_total, SUM(a.costo) costo, SUM(a.utilidad) utilidad, (SUM(CASE WHEN b.id_cli = 11 AND e.c_consig = 1 AND b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio = 1 THEN a.sub_total / GetExchangeRateVal(b.fecha_comp, 2) WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio ELSE a.sub_total END)) - (SUM(a.costo) + SUM(a.utilidad)) adicional, sal_cxc_graphic_fin(b.id_emp, '$fec_fin', 1, b.id_cli) mon_cxc, sal_cxc_graphic_fin(b.id_emp, '$fec_fin', 2, b.id_cli) mon_iva FROM f60031 a INNER JOIN f6003 b ON b.id_cot = a.id_cot INNER JOIN f0011 c ON c.id_emp = b.id_emp INNER JOIN f4005 d ON d.id_prod = a.id_prod INNER JOIN f0014 e ON e.id_ent = b.id_cli INNER JOIN f6001 f ON f.id_tdoc = b.id_tdo INNER JOIN f0012a g ON g.id_motcam = e.id_motcam INNER JOIN f0005 h ON h.id_moneda = b.id_moneda INNER JOIN f6001 i ON i.id_tdoc = b.id_tdo INNER JOIN f4999 j ON j.id_emp = b.id_emp AND ((i.id_tdoc = j.id_tdoc_not_no_fis AND IFNULL(e.c_consig,0) = 0) OR(i.id_tdoc != j.id_tdoc_not_no_fis)) AND (i.id_tdoc <> j.id_tdoc_not) INNER JOIN f4003 k ON k.id_fab = d.id_fab LEFT OUTER JOIN f60061 l ON l.id_cot = a.id_cot LEFT OUTER JOIN f6006 m ON m.id_movement = l.movem_id WHERE b.status = 1 AND b.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin'  AND b.id_emp = {$id_emp} GROUP by c.id_emp, c.nombre_emp, e.id_ent, e.nom_ent";
            return $r = DB::query($sql);
        }
        static function ReportexConsumo($id_emp, $fec_ini, $fec_fin, $id_fab, $id_cli, $id_gru, $id_vend, $id_tipocliente){
            $filter = '';
            if($id_fab){                
                $filter .= " AND e.id_fab IN ($id_fab)";
            }
            if($id_cli){
                $filter .= " AND a.id_cli = $id_cli";
            }
            if($id_gru){
                $filter .= " AND d.id_grupo = $id_gru";
            }
            if($id_vend){
                $filter .= " AND a.id_vend = $id_vend";
            }
            if($id_tipocliente){
                $filter .= " AND i.id = $id_tipocliente";
            }           
            //
            $sql = "SELECT d.id_prod, d.cod_prod, d.cod2_prod, d.nom_prod, e.nom_fab, d.ref_prod, DATE_FORMAT(a.fecha_comp, '%y') anio, ELT(MONTH(a.fecha_comp), 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic') mes, SUM(IFNULL(b.can_det, 0)) tot_row, fn_saldo_act_inv(a.id_emp, d.id_prod, g.id_alm, '$fec_fin') stock, SUM(b.utilidad) utilidad FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f60032 c ON c.id_cot = b.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1 $filter GROUP BY d.id_prod, d.cod_prod, d.cod2_prod, d.nom_prod, e.nom_fab, d.ref_prod, DATE_FORMAT(a.fecha_comp, '%Y'), DATE_FORMAT(a.fecha_comp, '%Y-%m') 
            UNION
            SELECT d.id_prod, d.cod_prod, d.cod2_prod, d.nom_prod, e.nom_fab, d.ref_prod, DATE_FORMAT(a.fecha_comp, '%y') anio, ELT(MONTH(a.fecha_comp), 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic') mes, SUM(IFNULL(b.can_det, 0)) tot_row, fn_saldo_act_inv(a.id_emp, d.id_prod, g.id_alm, '$fec_fin') stock, SUM(b.utilidad) utilidad FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1  AND h.c_consig != 1 $filter GROUP BY d.id_prod, d.cod_prod, d.cod2_prod, d.nom_prod, e.nom_fab, d.ref_prod, DATE_FORMAT(a.fecha_comp, '%Y'), DATE_FORMAT(a.fecha_comp, '%Y-%m') 
            ORDER BY 7 DESC";
            return $r = DB::query($sql);
        }
    }
?>