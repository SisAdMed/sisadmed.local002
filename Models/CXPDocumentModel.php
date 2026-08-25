<?php
class CXPDocumentModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        $sql = "SELECT DISTINCT a.id_cot, b.nombre_emp, c.tipo_codigo, c.nom_tdoc, a.num_tdo, a.fecha_comp, d.nom_ent, e.codigo_moneda, a.tasa_cambio, a.status, a.mon_doc, a.sal_doc FROM f3004 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f3001 c on c.id_tdoc = a.id_tdo INNER JOIN f0014 d on d.id_ent = a.id_cli INNER JOIN f0005 e on e.id_moneda = a.id_moneda INNER JOIN f30041 f on f.id_cot = a.id_cot LEFT OUTER JOIN f3006 g ON g.id_emp = a.id_emp AND g.id_cot = a.id_cot AND g.origen = 'CXP' LEFT OUTER JOIN f3007 h ON h.id_cot = a.id_cot";
        return $r = DB::query($sql);
    }
    static function edit($id){
        $r = DB::query("SELECT a.id_cot, b.nombre_emp, c.tipo_codigo, c.nom_tdoc, a.num_tdo, a.fecha_comp, d.nom_ent, e.codigo_moneda, a.tasa_cambio, a.status FROM f3004 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f3001 c on c.id_tdoc = a.id_tdo INNER JOIN f0014 d on d.id_ent = a.id_cli INNER JOIN f0005 e on e.id_moneda = a.id_moneda WHERE a.id_cot = {$id}");
        return $r[0];
    }
     static function listar_conceptos($id){
        return $r = DB::query("SELECT * FROM     WHERE id_emp = {$id} AND status = 1"); 
    }
    static function guardar($data){
        return $r = DB::insert('f3004', $data);
    }
    static function actualizar($id, $data){
        return $r= DB::update('f3004', $data, ['id_cot' => $id]);
    } 
    static function borrar($id){
        return $r = DB::delete('f3004', ['id_cot' => $id], 100);
    }
    static function borrarDetCXPDocument($id, $modo = 0, $origen = "CXP"){
        if($modo==0){
            if(DB::delete('f3004', ['id_cot' => $id], 100)){
                //Borrar Retencion de IVA e ISLR
                DB::delete('f3006', ['id_cot' => $id, "origen" => $origen], 100);
                DB::delete('f3007', ['id_cot' => $id], 100);
                return $r = DB::delete('f30041', ['id_cot' => $id], 100);
            }
        }else{
            return $r = DB::delete('f30041', ['id_cot' => $id], 100);
        }
       

    }
    static function guardarDetDocument($data){
        return $r = DB::insert('f30041', $data);
    }
    static function nextNumber($id_emp, $id_tdoc){
        $nextNumber = DB::query("SELECT * FROM f3001 WHERE id_emp = {$id_emp} AND id_tdoc = {$id_tdoc} LIMIT 1");
        return $nextNumber[0];
    }
    static function setNextNumber($id_emp, $id_tdoc, $data){
        return $r = DB::update("f3001", $data, ['id_emp' => $id_emp, 'id_tdoc' => $id_tdoc]);
    }
    static function show_row($id){
        $r = DB::query("SELECT DISTINCT a.id_emp, a.id_tdo, a.num_tdo, a.id_cli, a.fecha_comp, a.fecha_venci, a.id_moneda, a.tasa_cambio, a.descrip_cot, a.status, b.id_concxp, CONCAT(c.codigo_con, ' - ', c.nombre_con) nombre_con, CASE WHEN NOT ISNULL(e.id_aux ) THEN CONCAT(e.cod_aux, ' - ', e.nombre_aux) ELSE ' ' END nombre_aux, b.monto, b.iva, b.mon_iva, (b.monto + b.iva) total, a.id_retiva, a.num_control, IFNULL(f.id_cot, 0) doc_abo FROM f3004 a INNER JOIN f30041 b ON b.id_cot = a.id_cot INNER JOIN f3003 c ON c.id = b.id_concxp INNER JOIN f0011 d ON d.id_emp = a.id_emp LEFT OUTER JOIN f0009 e ON e.id_aux = b.id_aux LEFT OUTER JOIN f30081 f ON f.id_cot = a.id_cot WHERE a.id_cot = {$id}");
        return $r;
    }
    static function print_CXPDocument($id_cot){
        $r = DB::query("SELECT em.nombre_emp nombre_emp, em.rif_empresa rif_empresa, em.dir_emp dir_emp, em.tel_emp tel_emp, em.email_emp email_emp, en.nom_ent nom_ent, en.rif_ent rif_ent, en.dir_ent dir_ent, en.postal_ent postal_ent, pa.nombre_pais nombre_pais, es.nombre_edo nombre_edo,  ci.nombre_ciudad nombre_ciudad, c.fecha_comp fecha_comp, c.id_cot id_cot, c.tasa_cambio tasa_cambio, c.num_tdo num_tdo, tdo.nom_tdoc nom_tdoc,  em.logo logo, moc.codigo_moneda codigo_moneda, moe.codigo_moneda moneda_emp, con.nombre_con, dc.mon_iva, dc.monto FROM f3004 c INNER JOIN f3001 tdo ON tdo.id_tdoc = c.id_tdo INNER JOIN f30041 dc ON dc.id_cot = c.id_cot INNER JOIN f0011 em ON em.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0004 pa ON pa.id_pais = en.id_pais INNER JOIN f00041 es ON es.id_edo = en.id_edo INNER JOIN f00042 ci ON ci.id_ciudad = en.id_ciudad INNER JOIN f0005 moc ON moc.id_moneda = c.id_moneda INNER JOIN f0005 moe ON moe.id_moneda = em.id_moneda INNER JOIN f3003 con ON con.id = dc.id_concxp WHERE c.id_cot = {$id_cot}");
        return $r;
    }
    static function val_tdo_CXP($id){
        $r = DB::query("SELECT * FROM f3001 WHERE id_tdoc = {$id}");
        return $r[0];
    }
    static function val_aux($id){
        $r = DB::query("SELECT aux_cta FROM f3003 a INNER JOIN f0010 b on b.id_cta = a.id_ctbcue WHERE a.id = {$id}");
        return to_obj($r[0]);
    }
    static function doc_ped_cli_one($id_emp, $id_cli, $fecha_comp, $tipo_doc, $num_doc){ 
        $r = DB::query("SELECT b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.mon_doc, a.sal_doc, a.id_cot as id_doc FROM f3004 a INNER JOIN f3001 b ON B.id_tdoc = A.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND a.sal_doc != 0 AND a.id_tdo = $tipo_doc AND a.num_tdo = '". $num_doc ."' AND a.fecha_comp <= '". $fecha_comp ."'");
        return $r;
    }
    static function doc_ped_cli($id_emp, $id_cli, $fecha_comp, $id_moneda){
        //$sql = "SELECT  row_number() OVER (ORDER BY a.fecha_comp) item, b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.mon_doc, a.sal_doc, a.id_cot as id_doc FROM f3004 a INNER JOIN f3001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND a.sal_doc != 0 AND a.fecha_comp <= '$fecha_comp'";
        //$sql = "SELECT  row_number() OVER (ORDER BY a.fecha_comp) item, b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.mon_doc, a.sal_doc, a.id_cot as id_doc, a.num_control nro_control FROM f3004 a INNER JOIN f3001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND ROUND(a.sal_doc, 0) != 0";
        //$sql = "SELECT b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.id_cot as id_doc, a.num_control nro_control, a.id_moneda, CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN a.mon_doc WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN a.mon_doc WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN a.mon_doc * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN a.mon_doc / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END mon_doc, CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN a.sal_doc WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN a.sal_doc WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN a.sal_doc * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN a.sal_doc / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END sal_doc FROM f3004 a  INNER JOIN f3001 b ON b.id_tdoc = a.id_tdo  INNER JOIN f0005 c on c.id_moneda = a.id_moneda WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND ROUND(a.sal_doc, 0) != 0";
        $sql = "SELECT row_number() OVER (ORDER BY a.fecha_comp) item, d.tipo_codigo, d.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, e.codigo_moneda, a.tasa_cambio, a.id_cot as id_doc, a.id_moneda, IFNULL(a.num_control, ' ') nro_control, b.id_moneda id_moneda_cia, ROUND(CASE WHEN a.id_moneda = b.id_moneda THEN a.mon_doc ELSE (a.mon_doc * a.tasa_cambio) END, 2) mon_doc_dom, ROUND(CASE WHEN a.id_moneda = b.id_moneda THEN a.sal_doc ELSE (a.sal_doc * a.tasa_cambio) END, 2) sal_doc_dom, ROUND(CASE WHEN a.id_moneda = b.id_moneda THEN (a.mon_doc / GetExchangeRateVal(a.fecha_comp, 2)) ELSE (a.mon_doc) END, 2) mon_doc_for, ROUND(CASE WHEN a.id_moneda = b.id_moneda THEN (a.sal_doc / GetExchangeRateVal(a.fecha_comp, 2)) ELSE (a.sal_doc) END, 2) sal_doc_for FROM f3004 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli INNER JOIN f3001 d ON d.id_tdoc = a.id_tdo INNER JOIN f0005 e ON e.id_moneda = a.id_moneda WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND ROUND(a.sal_doc,2) !=0";
        $r = DB::query($sql);
        return $r;
    }
    static function sel_doc_origen($origen){
         $r = DB::query("SELECT id_cot FROM f3004 WHERE origen = {$origen}");
         return $r;
    }
    static function ret_iva($id){
        $r = DB::query("SELECT * FROM f3005 WHERE id = {$id} AND status = 1");
        return $r[0];
    }
    static function config_cxp($id){
        $r = DB::query("SELECT * FROM f3999 WHERE id_emp = {$id} AND status = 1");
        return $r[0];
    }
    static function config_cxp_up($id, $data){
        return $r = DB::update('f3999', $data, ['id_config' => $id]);
    }
    static function save_retiva($data){
        return $r = DB::insert('f3006', $data);
    }
    static function con_retiva($id_cot, $origen){
        $r = DB::query("SELECT * FROM f3006 WHERE id_cot = {$id_cot} AND origen = '".$origen."'");
        return $r;
    }
    static function update_retiva($id, $data){
        return $r = DB::update('f3006', $data, ['id' => $id]);
    }
    static function destroy_retislr($id, $doc){
        return $r = DB::delete('f3007', ['id_emp' => $id, 'id_cot' => $doc]);
    }
    static function save_retislr($data){
        return $r = DB::insert('f3007', $data);
    }
    static function get_doc_cxc($id_cot, $id_moneda){
        //$sql = "SELECT b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, ROUND(CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN a.mon_doc WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN a.mon_doc WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN a.mon_doc * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN a.mon_doc / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END, 2)  mon_doc, ROUND(CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN a.sal_doc WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN a.sal_doc WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN a.sal_doc * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN a.sal_doc / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END, 2)  sal_doc, a.id_cot as id_doc, ROUND(CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN SUM(IFNULL(d.mon_iva, 0)) WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN SUM(IFNULL(d.mon_iva, 0)) WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN SUM(IFNULL(d.mon_iva, 0)) * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN SUM(IFNULL(d.mon_iva, 0)) / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END, 2) mon_iva, a.id_moneda FROM f3004 a INNER JOIN f3001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda INNER JOIN f30041 d ON d.id_cot = a.id_cot WHERE a.id_cot = {$id_cot} AND ROUND(a.sal_doc,2) != 0";
        $sql = "SELECT DISTINCT  b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.id_cot as id_doc, a.id_moneda, ROUND(CASE WHEN a.id_moneda = e.id_moneda THEN a.mon_doc ELSE a.mon_doc * a.tasa_cambio END, 2) mon_doc_dom, ROUND(CASE WHEN a.id_moneda = e.id_moneda THEN a.sal_doc ELSE a.sal_doc * a.tasa_cambio END, 2) sal_doc_dom, ROUND(CASE WHEN a.id_moneda = e.id_moneda THEN a.mon_doc / GetExchangeRateVal(a.fecha_comp, 2) ELSE a.mon_doc END, 2)  mon_doc_for, ROUND(CASE WHEN a.id_moneda = e.id_moneda THEN a.sal_doc / GetExchangeRateVal(a.fecha_comp, 2)ELSE a.sal_doc END, 2)  sal_doc_for FROM f3004 a INNER JOIN f3001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda INNER JOIN f30041 d ON d.id_cot = a.id_cot INNER JOIN f0011 e ON e.id_emp = a.id_emp WHERE a.id_cot = {$id_cot} AND ROUND(a.sal_doc,2) != 0";
        $r = DB::query($sql);
        return $r[0];
    }
    static function edo_cuenta($id_emp, $id_cli){
        $fecha = date("Y-m-d");
        $filter = "";
        if ($id_cli) {
            $filter .= " AND d.id_cli = {$id_cli}";
        }
        if($id_emp){
            $filter .= " AND d.id_emp = {$id_emp}";
        }
        $sql = "SELECT row_number() OVER (ORDER BY d.fecha_comp) item, e.id_emp, e.cod_emp, e.nombre_emp, e.rif_empresa, c.id_ent, c.rif_ent, c.nom_ent, dc.cod_diascre, dc.des_diascre, d.id_cot, td.tipo_codigo, td.nom_tdoc, d.num_tdo, d.fecha_comp, d.id_moneda, m.codigo_moneda, GetExchangeRate(d.fecha_comp) tasa_dia, d.tasa_cambio tasa_doc, e.id_moneda moneda_cia, c.contr_esp especial_contrib, DATEDIFF('$fecha', d.fecha_comp) dias_calle, CASE WHEN d.mon_doc = d.sal_doc THEN 0 ELSE 1 END abonado, CASE WHEN d.id_moneda = e.id_moneda THEN d.sal_doc ELSE (d.sal_doc * d.tasa_cambio) END sal_doc_dom, CASE WHEN d.id_moneda = e.id_moneda THEN SUM(IF(dd.iva = 'S', dd.monto, 0)) ELSE SUM(IF(dd.iva = 'S', dd.monto, 0) * d.tasa_cambio) END mon_base_dom, CASE WHEN d.id_moneda = e.id_moneda THEN SUM(IF(dd.iva = 'N', dd.monto, 0)) ELSE SUM(IF(dd.iva = 'N', dd.monto, 0) * d.tasa_cambio) END mon_exe_dom, CASE WHEN d.id_moneda = e.id_moneda THEN SUM(dd.mon_iva) ELSE (SUM(dd.mon_iva * d.tasa_cambio)) END mon_iva_dom,
        CASE WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio = 1 THEN (d.sal_doc / GetExchangeRateVal(d.fecha_comp, 2)) WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio != 1 THEN (d.sal_doc / d.tasa_cambio) ELSE (d.sal_doc) END sal_doc_for, CASE WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio = 1 THEN SUM(IF(dd.iva = 'S', dd.monto / GetExchangeRateVal(d.fecha_comp, 2), 0)) WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio != 1 THEN SUM(IF(dd.iva = 'S', (dd.monto / d.tasa_cambio),0)) ELSE SUM(IF(dd.iva = 'S', dd.monto, 0)) END mon_base_for, CASE WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio = 1 THEN SUM(IF(dd.iva = 'N', dd.monto / GetExchangeRateVal(d.fecha_comp, 2), 0)) WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio != 1 THEN (SUM(IF(dd.iva = 'N', dd.monto / d.tasa_cambio, 0))) ELSE (IF(dd.iva = 'N', dd.monto, 0))  END mon_exe_for, CASE WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio = 1 THEN SUM(dd.mon_iva / GetExchangeRateVal(d.fecha_comp, 2)) WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio != 1 THEN SUM(dd.mon_iva / d.tasa_cambio) ELSE (SUM(dd.mon_iva)) END mon_iva_for FROM f3004 d INNER JOIN f0011 e ON e.id_emp = d.id_emp INNER JOIN f0014 c ON c.id_ent = d.id_cli INNER JOIN f3001 td ON td.id_tdoc = d.id_tdo INNER JOIN f6005 dc ON dc.id_diascre = IFNULL(c.id_diascre, 0) INNER JOIN f0005 m ON m.id_moneda = d.id_moneda INNER JOIN f30041 dd ON dd.id_cot = d.id_cot INNER JOIN f3999 cfg ON cfg.id_emp = d.id_emp AND dd.id_concxp != cfg.id_retislr AND dd.id_concxp != cfg.id_retiva WHERE ROUND(d.sal_doc, 2) != 0 $filter GROUP BY e.id_emp, e.cod_emp, e.nombre_emp, e.rif_empresa, c.id_ent, c.rif_ent, c.nom_ent, dc.cod_diascre, dc.des_diascre, d.id_cot, td.tipo_codigo, td.nom_tdoc, d.num_tdo, d.fecha_comp, d.id_moneda, m.codigo_moneda, GetExchangeRate(d.fecha_comp), d.tasa_cambio, e.id_moneda, c.contr_esp, DATEDIFF('$fecha', d.fecha_comp), CASE WHEN d.mon_doc = d.sal_doc THEN 0 ELSE 1 END ORDER BY e.nombre_emp, c.nom_ent";
        return $r = DB::query($sql);
    }
    //Actualizar Monto y Saldo del Documento
    static function upd_mon_sal_doc($id_cot){
        $sql = "SELECT SUM(b.monto + b.mon_iva) mon_doc FROM f3004 a INNER JOIN f30041 b ON b.id_cot = a.id_cot WHERE a.id_cot = {$id_cot}";
        $r = DB::query($sql);
        return $r[0];
    }
}