<?php
    /**
     * Clase del Modelo de Datos de la Factura de Compras
     * Creado por José Vargas el 20-09-2004
     */
    class PurInvModel extends DB{
        public function __construct() {
            parent::__construct();
        }
        static function all($ori){
            $sql = "SELECT a.id_cot, a.id_emp, b.nombre_emp, c.tipo_codigo, c.nom_tdoc, a.num_tdo, e.nom_ent, a.fecha_comp, d.codigo_moneda, a.tasa_cambio, a.status, a.origen FROM f8020 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f3001 c on c.id_tdoc = a.id_tdo INNER JOIN f0005 d on d.id_moneda = a.id_moneda INNER JOIN f0014 e on e.id_ent = a.id_cli WHERE c.tipo_tdoc = '$ori'";
            return $r = DB::query($sql);
        }
        static function tip_doc_com($id){
            $r = DB::query("SELECT a.tdoc_pur, a.tdoc_purord, a.tdoc_purcrenot, a.tdoc_purdelnot, a.tdoc_purretnot, b.especial_contrib, a.con_purcon, a.id_alm, a.id_ubi, a.id_typmovinwar, a.id_typmovoutwar, b.id_moneda  FROM f8999 a INNER JOIN f0011 b ON b.id_emp = a.id_emp WHERE a.id_emp = {$id} AND a.status = 1;");
            return $r[0];
        }
        static function listar_entidad_modal($tipo){
            $r = DB::query("SELECT a.id_ent, a.rif_ent, a.nom_ent FROM f0014 a WHERE a.status = 1 AND tip_ent = '".$tipo."'");
            return $r;
        }
        static function guardar($data){
            return $r = DB::insert('f8020', $data);
        }
        static function borrar($id){
            $r = DB::delete('f80201', ['id_cot' => $id]);
            if($r){
                $r = DB::delete('f8020', ['id_cot' => $id]);
                if($r){
                    return true;
                }else{
                    return false;
                }
            }
        }
        static function borrarDet($id){
            return $r= DB::delete('f80201', ['id_cot' => $id]);
        }
        static function guardarDet($data){
            return $r = DB::insert('f80201', $data);
        }
        static function selectEncyDetmovinv($origen, $id_emp){
            $r = DB::query("SELECT * FROM f4009 WHERE id_emp = {$id_emp} AND origen = '$origen'");
            return $r[0];
        }
        static function borrarEncyDetmovinv($id_movinv){
            DB::delete('f40091', ['id_movinv' => $id_movinv]);
            return $r = DB::delete('f4009', ['id_movinv' => $id_movinv]);
        }
        static function selectEncyDetCXP($origen, $id_emp){
            $r = DB::query("SELECT * FROM f3004 WHERE id_emp = {$id_emp} AND origen = '{$origen}'");
            return $r[0];
        }
        static function borrarEncyDetCXP($id){
            DB::delete('f30041', ['id_cot' => $id]);
            return $r = DB::delete('f3004', ['id_cot' => $id]);
        }
        static function edit($id){
            $r = DB::query("SELECT * FROM f8020 WHERE id_cot = {$id}");
            return $r[0];
        }
        static function showrow($id){
            return $r = DB::query("SELECT a.id_cot, a.id_emp, a.id_tdo, a.num_tdo, a.id_cli, a.fecha_comp, a.id_moneda, a.fec_fact, a.num_control, a.fecha_venci, b.id_prod, g.nom_prod, b.can_det, b.uni_vta, b.pre_unit, b.pre_vta, b.iva_prod, b.sub_total,b.mon_iva, b.tota_prod, a.tasa_cambio, e.nom_ent, a.id_retiva, b.lote, b.fec_venc, a.id_cont, a.status, h.id_alm, i.id_ubi FROM f8020 a INNER JOIN f80201 b ON b.id_cot = a.id_cot INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f3001 d ON d.id_tdoc = a.id_tdo INNER JOIN f0014 e ON e.id_ent = a.id_cli INNER JOIN f0005 f ON f.id_moneda = a.id_moneda INNER JOIN f4005 g ON g.id_prod = b.id_prod INNER JOIN f4009 h ON h.id_emp = a.id_emp AND h.origen = CONCAT('COM-', d.tipo_codigo, '-', a.id_emp, '-', a.num_tdo) INNER JOIN f40091 i ON i.id_movinv = h.id_movinv WHERE a.id_cot = {$id} GROUP BY a.id_cot, a.id_emp, a.id_tdo, a.num_tdo, a.id_cli, a.fecha_comp, a.id_moneda, a.fec_fact, a.num_control, a.fecha_venci, b.id_prod, g.nom_prod, b.can_det, b.uni_vta, b.pre_unit, b.pre_vta, b.iva_prod, b.sub_total,b.mon_iva, b.tota_prod, a.tasa_cambio, e.nom_ent, a.id_retiva, b.lote, b.fec_venc, a.id_cont, a.status, h.id_alm, i.id_ubi");
        }
        static function actualizar($id, $data){
            return $r = DB::update('f8020', $data, ['id_cot' => $id]);
        }
        static function print_retiva_enca($id, $mod){
            $xorigen = 'CXP';
            $table = `f3004`;
            if($mod=='O'){
                $xorigen = 'COM';
                $table = `f8020`;
            }
            if($mod=='O'){
                return $r = DB::query("SELECT row_number() OVER (ORDER BY a.fecha_pago) item, b.nombre_emp, b.rif_empresa, a.fecha_pago, Date_format(a.fecha_pago, '%m') mes, Date_format(a.fecha_pago, '%Y') anio, b.dir_emp, c.nom_ent, c.rif_ent, a.por_retiva, d.fec_fact, d.num_tdo, d.num_control, a.tot_compras, a.tot_exento, a.tot_base, a.tasa_iva, a.tot_iva, a.tot_ret, CONCAT(e.name_user, ' ', e.last_user) ela_por, b.logo, a.num_retiva, d.fecha_comp, f.tipo_tdoc FROM f3006 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_ent INNER JOIN f8020 d ON d.id_cot = a.id_cot INNER JOIN f0002 e ON e.id_user = a.create_user INNER JOIN f3001 f ON f.id_tdoc = d.id_tdo WHERE a.id_cot = {$id} AND a.origen = '".$xorigen."' ");
            }else{
                return $r = DB::query("SELECT row_number() OVER (ORDER BY a.fecha_pago) item, b.nombre_emp, b.rif_empresa, a.fecha_pago, Date_format(a.fecha_pago, '%m') mes, Date_format(a.fecha_pago, '%Y') anio, b.dir_emp, c.nom_ent, c.rif_ent, a.por_retiva, d.fec_fact, d.num_tdo, d.num_control, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_compras ELSE a.tot_compras * d.tasa_cambio END tot_compras, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_exento ELSE a.tot_exento * d.tasa_cambio END tot_exento, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_base ELSE a.tot_base * d.tasa_cambio END tot_base, a.tasa_iva, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_iva ELSE a.tot_iva * d.tasa_cambio END tot_iva, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_ret ELSE a.tot_ret * d.tasa_cambio END tot_ret, CONCAT(e.name_user, ' ', e.last_user) ela_por, b.logo, a.num_retiva, d.fecha_comp, f.tipo_tdoc FROM f3006 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_ent INNER JOIN f3004 d ON d.id_cot = a.id_cot INNER JOIN f0002 e ON e.id_user = a.create_user INNER JOIN f3001 f ON f.id_tdoc = d.id_tdo WHERE a.id_cot = {$id} AND a.origen = '".$xorigen."' ");
            }
        }
        static function print_retiva_deta($id, $mod){
            $xorigen = 'CXP';
            $table = `f3004`;
            if($mod=='O'){
                $xorigen = 'COM';
                $table = `f8020`;
            }
            if($mod=='O'){
                $sql = "SELECT row_number() OVER (ORDER BY a.fecha_pago) item, a.fecha_pago, CASE WHEN f.tipo_tdoc = 'M' THEN d.num_tdo ELSE '' END num_factura, d.num_control, CASE WHEN f.tipo_tdoc = 'B' THEN d.num_tdo ELSE '' END num_debito, CASE WHEN f.tipo_tdoc = 'A' THEN d.num_tdo ELSE '' END num_credito, CASE WHEN f.tipo_tdoc = 'M' THEN '01' WHEN f.tipo_tdoc = 'B' THEN '02' ELSE '03' END tipo_tran, a.tot_compras * d.tasa_cambio tot_compras, a.tot_exento * d.tasa_cambio tot_exento, a.tot_base * d.tasa_cambio tot_base,  a.tasa_iva, a.tot_iva * d.tasa_cambio tot_iva, a.tot_ret * d.tasa_cambio tot_ret FROM f3006 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_ent INNER JOIN f8020  d ON d.id_cot = a.id_cot INNER JOIN f0002 e ON e.id_user = a.create_user INNER JOIN f3001 f ON f.id_tdoc = d.id_tdo WHERE a.id_cot = {$id} AND a.origen = '".$xorigen."' ";
            }else{
                $sql = "SELECT row_number() OVER (ORDER BY a.fecha_pago) item, b.nombre_emp, b.rif_empresa, a.fecha_pago, Date_format(a.fecha_pago, '%m') mes, Date_format(a.fecha_pago, '%Y') anio, b.dir_emp, c.nom_ent, c.rif_ent, a.por_retiva, d.fec_fact, d.num_tdo, d.num_control, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_compras ELSE a.tot_compras * d.tasa_cambio END tot_compras, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_exento ELSE a.tot_exento * d.tasa_cambio END tot_exento, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_base ELSE a.tot_base * d.tasa_cambio END tot_base, a.tasa_iva, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_iva ELSE a.tot_iva * d.tasa_cambio END tot_iva, CASE WHEN d.id_moneda = b.id_moneda THEN a.tot_ret ELSE a.tot_ret * d.tasa_cambio END tot_ret, CONCAT(e.name_user, ' ', e.last_user) ela_por, b.logo, a.num_retiva, d.fecha_comp, f.tipo_tdoc, CASE WHEN f.tipo_tdoc = 'M' THEN d.num_tdo ELSE '' END num_factura, d.num_control, CASE WHEN f.tipo_tdoc = 'B' THEN d.num_tdo ELSE '' END num_debito, CASE WHEN f.tipo_tdoc = 'A' THEN d.num_tdo ELSE '' END num_credito, CASE WHEN f.tipo_tdoc = 'M' THEN '01' WHEN f.tipo_tdoc = 'B' THEN '02' ELSE '03' END tipo_tran FROM f3006 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_ent INNER JOIN f3004 d ON d.id_cot = a.id_cot INNER JOIN f0002 e ON e.id_user = a.create_user INNER JOIN f3001 f ON f.id_tdoc = d.id_tdo WHERE a.id_cot = {$id} AND a.origen = '".$xorigen."' ";
            }
            return $r = DB::query($sql);
            
        }
        static function print_retislr($id){
            return $r = DB::query("SELECT DISTINCT  b.cod_emp, b.nombre_emp, b.rif_empresa, b.dir_emp, c.id_ent, c.nom_ent, c.rif_ent, a.fecha_comp, a.num_tdo, a.fec_fact, a.num_control, g.id, CASE WHEN a.id_moneda = b.id_moneda THEN h.total_monto ELSE h.total_monto * a.tasa_cambio END total_monto, CASE WHEN a.id_moneda = b.id_moneda THEN h.total_base ELSE h.total_base * a.tasa_cambio END total_base, CASE WHEN a.id_moneda = b.id_moneda THEN h.base_imp ELSE h.base_imp * a.tasa_cambio END base_imp, h.por_reten, h.deducible, CASE WHEN a.id_moneda = b.id_moneda THEN h.total_retenido ELSE h.total_retenido * a.tasa_cambio END total_retenido, b.logo, CONCAT(j.name_user, ' ' , j.last_user) ela_por, d.id_retislr, c.dir_ent FROM f3004 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli INNER JOIN f3007 d ON d.id_cot = a.id_cot INNER JOIN f30041 e ON e.id_cot = a.id_cot INNER JOIN f3003 f ON f.id = e.id_concxp INNER JOIN f0021 g ON g.id = f.id_retislr INNER JOIN f3007 h ON h.id_cot = a.id_cot INNER JOIN f3999 i ON i.id_emp = a.id_emp AND e.id_concxp <> i.id_retislr AND e.id_concxp <> i.id_retiva INNER JOIN f0002 j ON j.id_user = a.create_user WHERE a.id_cot = {$id}");
        }
        static function listar_doc_fuentes($id_emp, $id, $tipo, $id_cli, $tipo_doc_ori){
            $filter = '';
            if($id != 0){
                $filter = " AND a.id_cot = {$id} ";
            }
            $sql = "SELECT a.id_cot, CONCAT(c.nom_tdoc, ': ', a.num_tdo, ' - Proveedor: ', b.nom_ent) doc FROM `f8020` a INNER JOIN f0014 b ON b.id_ent = a.id_cli
            INNER JOIN f3001 c ON c.id_tdoc = a.id_tdo WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND c.tipo_tdoc != '$tipo' AND c.id_tdoc = {$tipo_doc_ori} " . $filter;
            $r = DB::query($sql);
            return $r;
        }
    }
?>