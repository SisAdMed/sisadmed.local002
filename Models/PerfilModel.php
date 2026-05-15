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
        static function DatosTabla_001(string $fec_ini, string $fec_fin, int $id_emp){
            $filter = '';
            if($id_emp != 0){
                $filter = " AND b.id_emp = {$id_emp}";
            }
            //$sql = "SELECT a.id_emp, a.nombre_emp, SUM(a.sub_total) sub_total, SUM(a.costo) costo, SUM(a.dif_cambio) dif_cambio, SUM(a.utilidad) utilidad, SUM(a.adicional) adicional, SUM(a.mon_iva) + sal_cxc_graphic_fin(a.id_emp, '".$fec_fin."', 2) mon_iva, sal_cxc_graphic(a.id_emp, '".$fec_fin."') + IFNULL(sal_cxc_graphic_fin(a.id_emp, '".$fec_fin. "', 1),0) mon_cxc, sal_inv_ppal(a.id_emp, b.id_alm, '$fec_fin') AS sal_inv_ppal  FROM table_graphic a INNER JOIN f4999 b ON b.id_emp = a.id_emp WHERE a.status = 1 AND a.fecha_comp BETWEEN '".$fec_ini."' AND '".$fec_fin."' ".$filter." GROUP BY a.id_emp, a.nombre_emp ORDER BY a.nombre_emp";
            $sql = "SELECT c.nombre_emp, h.codigo_moneda, SUM(CASE WHEN b.id_cli = 11 AND e.c_consig = 1 AND b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio = 1 THEN a.sub_total / GetExchangeRateVal(b.fecha_comp, 2) WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio ELSE a.sub_total END) sub_total, SUM(a.costo) costo, SUM(a.utilidad) utilidad,(SUM(CASE WHEN b.id_cli = 11 AND e.c_consig = 1 AND b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio = 1 THEN a.sub_total / GetExchangeRateVal(b.fecha_comp, 2) WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio ELSE a.sub_total END)) - (SUM(a.costo) + SUM(a.utilidad)) adicional, sal_cxc_graphic_fin(b.id_emp, '$fec_fin', 1, 0) mon_cxc, sal_inv_ppal(b.id_emp, j.id_alm, '$fec_fin') AS sal_inv_ppal, sal_cxc_graphic_fin(b.id_emp, '$fec_fin', 2, 0) mon_iva, sal_inv_consig(c.id_emp, '$fec_fin') sal_inv_consig, (fn_sal_gastos(c.id_emp, '$fec_ini', '$fec_fin') + fn_sal_gastos_cxp(c.id_emp, '$fec_ini', '$fec_fin')) gastos, (SELECT count(*) total_cot FROM f4008 x LEFT JOIN f6003 y ON y.id_emp = x.id_emp AND y.id_cont = CONCAT('CO', '-', x.num_tdo) WHERE x.id_emp = b.id_emp AND x.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND EXISTS(SELECT * FROM f40081 WHERE id_cot = x.id_cot)) total_cot, (SELECT (SUM(CASE WHEN c2.id_cont IS NOT NULL THEN 1 ELSE 0 END) ) facturadas FROM f4008 c1 LEFT JOIN f6003 c2 ON c2.id_emp = c1.id_emp AND c2.id_cont = CONCAT('CO', '-', c1.num_tdo) WHERE c1.id_emp = b.id_emp AND c1.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND EXISTS(SELECT * FROM f40081 WHERE id_cot = c1.id_cot)) facturadas FROM f60031 a INNER JOIN f6003 b ON b.id_cot = a.id_cot INNER JOIN f0011 c ON c.id_emp = b.id_emp INNER JOIN f4005 d ON d.id_prod = a.id_prod INNER JOIN f0014 e ON e.id_ent = b.id_cli INNER JOIN f6001 f ON f.id_tdoc = b.id_tdo INNER JOIN f0012a g ON g.id_motcam = e.id_motcam INNER JOIN f0005 h ON h.id_moneda = b.id_moneda INNER JOIN f6001 i ON i.id_tdoc = b.id_tdo INNER JOIN f4999 j ON j.id_emp = b.id_emp AND ((i.id_tdoc = j.id_tdoc_not_no_fis AND IFNULL(e.c_consig,0) = 0) OR(i.id_tdoc != j.id_tdoc_not_no_fis)) AND (i.id_tdoc <> j.id_tdoc_not) INNER JOIN f4003 k ON k.id_fab = d.id_fab LEFT OUTER JOIN f60061 l ON l.id_cot = a.id_cot LEFT OUTER JOIN f6006 m ON m.id_movement = l.movem_id WHERE b.status = 1 AND b.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' $filter  GROUP BY c.nombre_emp ";
            $r = DB::query($sql);
            return $r;
        }
        static function DatosTabla_001_det(string $fec_ini, string $fec_fin, int $id_emp){
            $filter = '';
            if($id_emp != 0){
                $filter = " AND id_emp = {$id_emp}";
            }
            $r = DB::query("SELECT * FROM table_graphic WHERE status = 1 AND fecha_comp BETWEEN '".$fec_ini."' AND '".$fec_fin."' ".$filter);
            return $r;
        }
        static function DatosTabla_001_det_cli(string $fec_ini, string $fec_fin, int $id_emp){
            $sql = "SELECT c.id_emp, c.nombre_emp, e.id_ent, e.nom_ent, SUM(CASE WHEN b.id_cli = 11 AND e.c_consig = 1 AND b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio = 1 THEN a.sub_total / GetExchangeRateVal(b.fecha_comp, 2) WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio ELSE a.sub_total END) sub_total, SUM(a.costo) costo, SUM(a.utilidad) utilidad, (SUM(CASE WHEN b.id_cli = 11 AND e.c_consig = 1 AND b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio = 1 THEN a.sub_total / GetExchangeRateVal(b.fecha_comp, 2) WHEN b.id_moneda = c.id_moneda AND b.tasa_cambio != 1 THEN a.sub_total / b.tasa_cambio ELSE a.sub_total END)) - (SUM(a.costo) + SUM(a.utilidad)) adicional, sal_cxc_graphic_fin(b.id_emp, '$fec_fin', 1, b.id_cli) mon_cxc, sal_cxc_graphic_fin(b.id_emp, '$fec_fin', 2, b.id_cli) mon_iva FROM f60031 a INNER JOIN f6003 b ON b.id_cot = a.id_cot INNER JOIN f0011 c ON c.id_emp = b.id_emp INNER JOIN f4005 d ON d.id_prod = a.id_prod INNER JOIN f0014 e ON e.id_ent = b.id_cli INNER JOIN f6001 f ON f.id_tdoc = b.id_tdo INNER JOIN f0012a g ON g.id_motcam = e.id_motcam INNER JOIN f0005 h ON h.id_moneda = b.id_moneda INNER JOIN f6001 i ON i.id_tdoc = b.id_tdo INNER JOIN f4999 j ON j.id_emp = b.id_emp AND ((i.id_tdoc = j.id_tdoc_not_no_fis AND IFNULL(e.c_consig,0) = 0) OR(i.id_tdoc != j.id_tdoc_not_no_fis)) AND (i.id_tdoc <> j.id_tdoc_not) INNER JOIN f4003 k ON k.id_fab = d.id_fab LEFT OUTER JOIN f60061 l ON l.id_cot = a.id_cot LEFT OUTER JOIN f6006 m ON m.id_movement = l.movem_id WHERE b.status = 1 AND b.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin'  AND b.id_emp = {$id_emp} GROUP by c.id_emp, c.nombre_emp, e.id_ent, e.nom_ent";
            return $r = DB::query($sql);
        }
        /**
         * @param int $id_emp
         * @param string $fec_ini
         * @param string $fec_fin
         * @param string|null $id_fab
         * @param int|null $id_cli
         * @param string|null $id_gru
         * @param string|null $id_vend
         * @param string|null $id_tipocliente
         * @return mixed
         */
        static function ReportexConsumo($id_emp, $fec_ini, $fec_fin, $id_fab, $id_cli, $id_gru, $id_vend, $id_tipocliente){
            $filter = "";
            if($id_fab){                
                $filter .= " AND e.id_fab IN ($id_fab)";
            }
            if($id_cli){
                $filter .= " AND a.id_cli = $id_cli";
            }
            if($id_gru){
                $filter .= " AND d.id_grupo IN ($id_gru)";
            }
            if($id_vend){
                $filter .= " AND a.id_vend IN ($id_vend)";
            }
            if($id_tipocliente){
                $filter .= " AND i.id IN ($id_tipocliente)";
            }           
            //
             $sql = "DROP TABLE IF EXISTS temp_reporte_consumo";
             DB::query($sql);     
             
            $sql = "CREATE TABLE temp_reporte_consumo AS 
            SELECT * FROM (
                SELECT d.id_prod, d.cod_prod, d.cod2_prod, d.nom_prod, e.id_fab, e.nom_fab, d.ref_prod, DATE_FORMAT(a.fecha_comp, '%y') anio, ELT(MONTH(a.fecha_comp), 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic') mes, SUM(IFNULL(b.can_det, 0)) tot_row, fn_saldo_act_inv(a.id_emp, d.id_prod, g.id_alm, '$fec_fin') stock, SUM(b.utilidad) utilidad FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1 $filter AND EXISTS(SELECT id_cot FROM f60032 WHERE id_cot = a.id_cot) GROUP BY d.id_prod, d.cod_prod, d.cod2_prod, d.nom_prod, e.id_fab, e.nom_fab, d.ref_prod, DATE_FORMAT(a.fecha_comp, '%Y'), DATE_FORMAT(a.fecha_comp, '%Y-%m') 
                UNION
                SELECT d.id_prod, d.cod_prod, d.cod2_prod, d.nom_prod, e.id_fab, e.nom_fab, d.ref_prod, DATE_FORMAT(a.fecha_comp, '%y') anio, ELT(MONTH(a.fecha_comp), 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic') mes, SUM(IFNULL(b.can_det, 0)) tot_row, fn_saldo_act_inv(a.id_emp, d.id_prod, g.id_alm, '$fec_fin') stock, SUM(b.utilidad) utilidad FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1  AND h.c_consig != 1 $filter AND NOT EXISTS(SELECT id_cot FROM f60032 WHERE id_cot = a.id_cot) GROUP BY d.id_prod, d.cod_prod, d.cod2_prod, d.nom_prod, e.id_fab, e.nom_fab, d.ref_prod, DATE_FORMAT(a.fecha_comp, '%Y'), DATE_FORMAT(a.fecha_comp, '%Y-%m') ORDER BY 7 DESC
            ) AS reporte_consumo"; 
            DB::query($sql); 
            $sql = "SELECT id_prod, cod_prod, cod2_prod, nom_prod, nom_fab, ref_prod, anio, mes, stock, SUM(tot_row) AS tot_row FROM temp_reporte_consumo GROUP BY id_prod, cod_prod, cod2_prod, nom_prod, nom_fab, ref_prod, anio, mes, stock";            
            return $r = DB::query($sql);            
        }
        static function listar_marcas(?string $id_fab, ?string $anio, ?string $id_tipocliente, ?string $id_vend){
             $filter = '';  
            if($id_fab){                
                $filter .= " WHERE id_fab IN ($id_fab)";
            }
            if($anio){                       
                $filter .= $filter ? " AND " : " WHERE ";
                $filter .= " anio IN ($anio)";
            }   
            if($id_tipocliente){
                $filter .= $filter ? " AND " : " WHERE ";
                $filter .= " id_tipocliente IN ($id_tipocliente)";
            }
            if($id_vend){
                $filter .= $filter ? " AND " : " WHERE ";
                $filter .= " id_vend IN ($id_vend)";
            }
            $sql = "SELECT id_fab, nom_fab, ROUND(SUM(utilidad), 2) AS total_utilidad FROM temp_reporte_consumo $filter GROUP BY id_fab, nom_fab ORDER BY nom_fab";     
            $sql = "";       
            return $r = DB::query($sql);
        }
        static function listar_tipos_clientes(int $id_emp, string $fec_ini, string $fec_fin, ?string $id_fab, ?string $id_cli, ?string $id_gru, ?string $id_vend, ?string $id_tipocliente){
             $filter = '';
            if($id_fab){                
                $filter .= " AND e.id_fab IN ($id_fab)";
            }
            if($id_cli){
                $filter .= " AND a.id_cli = $id_cli";
            }
            if($id_gru){
                $filter .= " AND d.id_grupo IN ($id_gru)";
            }
            if($id_vend){
                $filter .= " AND a.id_vend IN ($id_vend)";
            }
            if($id_tipocliente){
                $filter .= " AND i.id IN ($id_tipocliente)";
            }           
            //
            $sql = "SELECT id, description, ROUND(sum(utilidad), 2) utilidad
            FROM (
            SELECT i.id, i.description, b.utilidad FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f60032 c ON c.id_cot = b.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo INNER JOIN f0016 k ON k.id_vend = h.id_vend WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1 $filter GROUP BY i.id, i.description, k.id_vend, CONCAT(k.nom_vend, ' ', k.ape_vend)
            UNION ALL
            SELECT i.id, i.description, b.utilidad FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo INNER JOIN f0016 k ON k.id_vend = h.id_vend WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1  AND h.c_consig != 1 $filter) AS union_table GROUP BY id, description ORDER BY description";     
            return $r = DB::query($sql);
        }   
        static function listar_vendedores(int $id_emp, string $fec_ini, string $fec_fin, ?string $id_fab, ?string $id_cli, ?string $id_gru, ?string $id_vend, ?string $id_tipocliente){
             $filter = '';
            if($id_fab){                
                $filter .= " AND e.id_fab IN ($id_fab)";
            }
            if($id_cli){
                $filter .= " AND a.id_cli = $id_cli";
            }
            if($id_gru){
                $filter .= " AND d.id_grupo IN ($id_gru)";
            }
            if($id_vend){
                $filter .= " AND a.id_vend IN ($id_vend)";
            }
            if($id_tipocliente){
                $filter .= " AND i.id IN ($id_tipocliente)";
            }           
            //
            $sql = "SELECT id, nom_vend
            FROM (
            SELECT k.id_vend id, CONCAT(k.nom_vend, ' ', k.ape_vend) nom_vend FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f60032 c ON c.id_cot = b.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo INNER JOIN f0016 k ON k.id_vend = h.id_vend WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1 $filter 
            UNION ALL
            SELECT k.id_vend id, CONCAT(k.nom_vend, ' ', k.ape_vend) nom_vend FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo INNER JOIN f0016 k ON k.id_vend = h.id_vend WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1  AND h.c_consig != 1 $filter) AS union_table GROUP BY id, nom_vend ORDER BY nom_vend";     
            return $r = DB::query($sql);
        }  
        static function listar_consumo_x_clientes(int $id_emp, string $fec_ini, string $fec_fin, ?string $id_fab, ?string $id_cli, ?string $id_gru, ?string $id_vend, ?string $id_tipocliente){
             $filter = '';
            if($id_fab){                
                $filter .= " AND e.id_fab IN ($id_fab)";
            }
            if($id_cli){
                $filter .= " AND a.id_cli = $id_cli";
            }
            if($id_gru){
                $filter .= " AND d.id_grupo IN ($id_gru)";
            }
            if($id_vend){
                $filter .= " AND a.id_vend IN ($id_vend)";
            }
            if($id_tipocliente){
                $filter .= " AND i.id IN ($id_tipocliente)";
            }           
            //
            $sql = "SELECT cliente, sum(unidades) unidades, sum(ventas) ventas, sum(utilidad) utilidad
            FROM (
            SELECT h.nom_ent cliente, b.can_det unidades, (b.can_det * b.sub_total) ventas, b.utilidad FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f60032 c ON c.id_cot = b.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo INNER JOIN f0016 k ON k.id_vend = h.id_vend WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1 $filter 
            UNION 
            SELECT h.nom_ent cliente, b.can_det unidades, (b.can_det * b.sub_total) ventas, b.utilidad FROM f6003 a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0011 f ON f.id_emp = a.id_emp INNER JOIN f4999 g ON g.id_emp = f.id_emp INNER JOIN f0014 h ON h.id_ent = a.id_cli INNER JOIN f4014 i On i.id = h.id_tipocliente INNER JOIN f6001 j ON j.id_tdoc = a.id_tdo INNER JOIN f0016 k ON k.id_vend = h.id_vend WHERE a.id_emp = $id_emp AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1  AND h.c_consig != 1 $filter GROUP BY h.nom_ent) AS union_table GROUP BY cliente ";     
            return $r = DB::query($sql);
        }  
        static function grafica_cotizaciones(int $id_emp, string $fec_ini, string $fec_fin){
            $sql = "SELECT b.id_user, CONCAT(b.name_user, ' ', b.last_user) name_user, DATE_FORMAT(a.fecha_comp, '%y') anio, ELT(MONTH(a.fecha_comp), 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic') mes, COUNT(a.id_cot) as total_cot, (SUM(CASE WHEN c.id_cont IS NOT NULL THEN 1 ELSE 0 END) ) facturadas FROM f4008 a INNER JOIN f0002 b ON b.id_user = a.create_user LEFT JOIN f6003 c ON c.id_emp = a.id_emp AND c.id_cont = CONCAT('CO', '-', a.num_tdo) WHERE a.id_emp = {$id_emp} AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND EXISTS(SELECT * FROM f40081 WHERE id_cot = a.id_cot) GROUP BY b.id_user, CONCAT(b.name_user, ' ', b.last_user), DATE_FORMAT(a.fecha_comp, '%y'), ELT(MONTH(a.fecha_comp), 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic')";
             return $r = DB::query($sql);
        }
    }
?>