<?php
class CXCDocumentModel extends DB
{
    public function __construct()
    {
        parent::__construct();
    }
    static function all()
    {
        return $r = DB::query("SELECT DISTINCT a.id_cot, b.nombre_emp, c.tipo_codigo, c.nom_tdoc, a.num_tdo, a.fecha_comp, d.nom_ent, e.codigo_moneda, a.tasa_cambio, a.status, IFNULL(a.mon_doc, 0) mon_doc, IFNULL(a.sal_doc, 0) sal_doc, a.nro_control FROM f6003 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f6001 c on c.id_tdoc = a.id_tdo INNER JOIN f0014 d on d.id_ent = a.id_cli INNER JOIN f0005 e on e.id_moneda = a.id_moneda INNER JOIN f60032 f on f.id_cot = a.id_cot");
    }
    static function edit($id)
    {
        $r = DB::query("SELECT a.id_cot, b.nombre_emp, c.tipo_codigo, c.nom_tdoc, a.num_tdo, a.fecha_comp, d.nom_ent, e.codigo_moneda, a.tasa_cambio, a.status FROM f6003 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f6001 c on c.id_tdoc = a.id_tdo INNER JOIN f0014 d on d.id_ent = a.id_cli INNER JOIN f0005 e on e.id_moneda = a.id_moneda WHERE a.id_cot = {$id}");
        return $r[0];
    }
    static function listar_conceptos($id)
    {
        return $r = DB::query("SELECT * FROM f6002 WHERE id_emp = {$id} AND status = 1");
    }
    static function guardar($data)
    {
        return $r = DB::insert('f6003', $data);
    }
    static function actualizar($id, $data)
    {
        return $r = DB::update('f6003', $data, ['id_cot' => $id]);
    }
    static function borrarDetCXCDocument($id)
    {
        return $r = DB::delete('f60032', ['id_cot' => $id], 100);
    }
    static function guardarDetDocument($data)
    {
        return $r = DB::insert('f60032', $data);
    }
    static function nextNumber($id_emp, $id_tdoc)
    {
        $nextNumber = DB::query("SELECT * FROM f6001 WHERE id_emp = {$id_emp} AND id_tdoc = {$id_tdoc} LIMIT 1");
        return $nextNumber[0];
    }
    static function setNextNumber($id_emp, $id_tdoc, $data)
    {
        return $r = DB::update("f6001", $data, ['id_emp' => $id_emp, 'id_tdoc' => $id_tdoc]);
    }
    static function showrow_c($id)
    {
        $r = DB::query("SELECT DISTINCT a.id_emp, a.id_tdo, a.num_tdo, a.id_cli, a.fecha_comp, a.fecha_venci, a.id_moneda, a.tasa_cambio, a.descrip_cot, a.status, b.id_concxc, b.id_aux, b.monto, CASE WHEN b.mon_iva <> 0 THEN 'S' ELSE 'N' END iva, b.mon_iva, a.nro_control, a.id_doc_afec, a.doc_afectado, CONCAT(e.codigo_con, ' - ', e.nombre_con) nombre_con FROM f6003 a INNER JOIN f60032 b on b.id_cot = a.id_cot LEFT OUTER JOIN f60031 c on c.id_cot = a.id_cot LEFT OUTER JOIN f4005 d on d.id_prod = c.id_prod INNER JOIN f6002 e ON e.id = b.id_concxc WHERE a.id_cot = {$id}");
        return $r;
    }
    static function showrow_i($id)
    {
        $r = DB::query("SELECT a.id_emp, a.id_tdo, a.num_tdo, a.id_cli, a.fecha_comp, a.fecha_venci, a.id_moneda, a.tasa_cambio, a.descrip_cot, a.status, b.id_concxc, b.id_aux, b.monto, CASE WHEN b.mon_iva <> 0 THEN 'S' ELSE 'N' END iva, b.mon_iva, c.id_prod, d.nom_prod, c.can_det, c.uni_vta, c.pre_unit, c.pre_vta, c.iva_prod, c.sub_total FROM f6003 a INNER JOIN f60032 b on b.id_cot = a.id_cot LEFT OUTER JOIN f60031 c on c.id_cot = a.id_cot INNER JOIN f4005 d on d.id_prod = c.id_prod WHERE a.id_cot = {$id}");
        return $r;
    }
    static function print_CXCDocument($id_cot)
    {
        $r = DB::query("SELECT c.id_cli, em.nombre_emp nombre_emp, em.rif_empresa rif_empresa, em.dir_emp dir_emp, em.tel_emp tel_emp, em.email_emp email_emp, en.nom_ent nom_ent, en.rif_ent rif_ent, en.dir_ent dir_ent, en.postal_ent postal_ent, pa.nombre_pais nombre_pais, es.nombre_edo nombre_edo,  ci.nombre_ciudad nombre_ciudad, c.fecha_comp fecha_comp, c.id_cot id_cot, c.tasa_cambio tasa_cambio, c.num_tdo num_tdo, tdo.nom_tdoc nom_tdoc,  em.logo logo, moc.codigo_moneda codigo_moneda, moe.codigo_moneda moneda_emp, con.nombre_con, dc.mon_iva, dc.monto, c.doc_afectado, c.id_emp FROM f6003 c INNER JOIN f6001 tdo ON tdo.id_tdoc = c.id_tdo INNER JOIN f60032 dc ON dc.id_cot = c.id_cot INNER JOIN f0011 em ON em.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0004 pa ON pa.id_pais = en.id_pais INNER JOIN f00041 es ON es.id_edo = en.id_edo INNER JOIN f00042 ci ON ci.id_ciudad = en.id_ciudad INNER JOIN f0005 moc ON moc.id_moneda = c.id_moneda INNER JOIN f0005 moe ON moe.id_moneda = em.id_moneda INNER JOIN f6002 con ON con.id = dc.id_concxc WHERE c.id_cot = {$id_cot}");        
        return $r;
    }
    static function val_tdo($id)
    {
        $r = DB::query("SELECT * FROM f6001 WHERE id_tdoc = {$id}");
        return $r[0];
    }
    static function val_aux($id)
    {
        $r = DB::query("SELECT aux_cta FROM f6002 a INNER JOIN f0010 b on b.id_cta = a.id_ctbcue WHERE a.id = {$id}");
        return to_obj($r[0]);
    }
    static function doc_ped_cli_one($id_emp, $id_cli, $fecha_comp, $tipo_doc, $num_doc)
    {
        $filter = '';
        if ($tipo_doc) {
            $filter = " AND a.id_tdo = {$tipo_doc} ";
        }
        if ($num_doc) {
            $filter = " AND a.num_tdo = '$num_doc' ";
        }
        $r = DB::query("SELECT b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.mon_doc, a.sal_doc, a.id_cot as id_doc FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND a.sal_doc != 0  AND a.fecha_comp <= '$fecha_comp' " . $filter);
        return $r;
    }
    static function doc_ped_cli($id_emp, $id_cli, $fecha_comp, $id_moneda)
    {
        //José Vargas 29-04-20025 a las 09:09:00, a solicitud de Alejandra Belisario. Se comenta por ahora, para quitar la fecha de los documentos y los muestres todos sin importar la fecha del movimiento
        //$r = DB::query("SELECT row_number() OVER (ORDER BY a.fecha_comp) item,  b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.mon_doc, a.sal_doc, a.id_cot as id_doc, SUM(IFNULL(d.mon_iva, 0)) mon_iva FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda INNER JOIN f60032 d ON d.id_cot = a.id_cot WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND a.sal_doc != 0 AND a.fecha_comp <= '". $fecha_comp ."' GROUP BY b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.mon_doc, a.sal_doc, a.id_cot");
        //José Vargas 08-09-2025 a las 11:17:00, para mostrar correctamento los valores tanto en dolares como en bolivares 
        //$sql = "SELECT row_number() OVER (ORDER BY a.fecha_comp) item,  b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN a.mon_doc WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN a.mon_doc WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN a.mon_doc * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN a.mon_doc / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END mon_doc, CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN a.sal_doc WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN a.sal_doc WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN a.sal_doc * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN a.sal_doc / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END sal_doc, a.id_cot as id_doc, SUM(IFNULL(d.mon_iva, 0)) mon_iva, a.id_moneda, a.nro_control, 0 xmon_can, 0 xmon_ret FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda LEFT OUTER JOIN f60032 d ON d.id_cot = a.id_cot WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_cli} AND ROUND(a.sal_doc,2) != 0 GROUP BY b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.mon_doc, a.sal_doc, a.id_cot";
        $sql = "SELECT row_number() OVER (ORDER BY d.fecha_comp) item, td.tipo_codigo, td.nom_tdoc, d.num_tdo, d.fecha_comp, d.fecha_venci, m.codigo_moneda, CASE WHEN d.imp_crcd = 0 THEN d.tasa_cambio ELSE d.imp_tasa_cambio END tasa_cambio, d.id_cot as id_doc, d.id_moneda, d.nro_control, e.id_moneda, ROUND(CASE WHEN d.id_moneda = e.id_moneda THEN d.mon_doc ELSE (d.mon_doc * d.tasa_cambio) END, 2) mon_doc_dom, ROUND(CASE WHEN d.id_moneda = e.id_moneda THEN d.sal_doc ELSE (d.sal_doc * d.tasa_cambio) END, 2) sal_doc_dom, ROUND(CASE WHEN d.id_moneda = e.id_moneda THEN SUM(dd.mon_iva) ELSE (SUM(dd.mon_iva * d.tasa_cambio)) END, 2) mon_iva_dom, ROUND(CASE WHEN d.id_moneda = e.id_moneda AND d.imp_crcd = 0 AND d.tasa_cambio > 1 THEN (d.mon_doc / d.tasa_cambio) WHEN d.id_moneda = e.id_moneda AND d.imp_crcd = 0 AND d.tasa_cambio = 1 THEN (d.mon_doc / GetExchangeRateVal(d.fecha_comp, 2)) WHEN d.id_moneda = e.id_moneda AND d.imp_crcd != 0 THEN (d.mon_doc / d.imp_tasa_cambio) ELSE (d.mon_doc) END, 2) mon_doc_for, ROUND(CASE WHEN d.id_moneda = e.id_moneda AND d.imp_crcd = 0 AND d.tasa_cambio > 1 THEN (d.mon_doc / d.tasa_cambio) WHEN d.id_moneda = e.id_moneda AND d.imp_crcd = 0 AND d.tasa_cambio = 1 THEN (d.sal_doc / d.sal_doc / GetExchangeRateVal(d.fecha_comp, 2)) WHEN d.id_moneda = e.id_moneda AND d.imp_crcd != 0 THEN (d.mon_doc / d.imp_tasa_cambio) ELSE (d.sal_doc) END, 2) sal_doc_for, ROUND(CASE WHEN d.id_moneda = e.id_moneda AND d.imp_crcd = 0 AND d.tasa_cambio > 1 THEN SUM(dd.mon_iva / d.tasa_cambio) WHEN d.id_moneda = e.id_moneda AND d.imp_crcd = 0 AND d.tasa_cambio = 1 THEN (dd.mon_iva / GetExchangeRateVal(d.fecha_comp, 2)) WHEN d.id_moneda = e.id_moneda AND d.imp_crcd != 0 THEN SUM(dd.mon_iva / d.imp_tasa_cambio) ELSE (SUM(dd.mon_iva)) END, 2) mon_iva_for FROM `f6003` d INNER JOIN f0011 e ON e.id_emp = d.id_emp INNER JOIN f0014 c ON c.id_ent = d.id_cli INNER JOIN f6001 td ON td.id_tdoc = d.id_tdo LEFT OUTER JOIN f6005 dc ON dc.id_diascre = IFNULL(c.id_diascre, 0) INNER JOIN f0005 m ON m.id_moneda = d.id_moneda INNER JOIN f0012a mc ON mc.id_motcam = c.id_motcam INNER JOIN f4999 cfg ON cfg.id_emp = d.id_emp LEFT OUTER JOIN f60032 dd ON dd.id_cot = d.id_cot WHERE d.id_emp = {$id_emp} AND d.status = 1 AND d.id_cli = {$id_cli} GROUP BY  d.id_cot, td.tipo_codigo, td.nom_tdoc, d.num_tdo, d.fecha_comp, d.fecha_venci, d.id_moneda, m.codigo_moneda, GetExchangeRate(d.fecha_comp), d.tasa_cambio, CASE WHEN d.id_moneda = e.id_moneda THEN d.mon_doc ELSE (d.mon_doc * d.tasa_cambio) END, CASE WHEN d.id_moneda = e.id_moneda THEN d.sal_doc ELSE (d.sal_doc * d.tasa_cambio) END, CASE WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio = 1 THEN (d.mon_doc / GetExchangeRateVal(d.fecha_comp, 2)) WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio != 1 THEN (d.mon_doc / d.tasa_cambio) ELSE (d.mon_doc) END,CASE WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio = 1 THEN (d.sal_doc / GetExchangeRateVal(d.fecha_comp, 2)) WHEN d.id_moneda = e.id_moneda AND d.tasa_cambio != 1 THEN (d.sal_doc / d.tasa_cambio) ELSE (d.sal_doc) END ORDER BY c.nom_ent, d.fecha_comp, td.tipo_codigo";
        $r = DB::query($sql);
        return $r;
    }
    static function get_doc_cxc($id_cot, $id_moneda, $movem_origen){
        $filter = "";
        if($movem_origen){
            $filter = " AND ROUND(a.sal_doc, 2 ) != 0 ";
        }
        //$sql = "SELECT b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, ROUND(CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN a.mon_doc WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN a.mon_doc WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN a.mon_doc * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN a.mon_doc / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END, 2)  mon_doc, ROUND(CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN a.sal_doc WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN a.sal_doc WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN a.sal_doc * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN a.sal_doc / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END, 2)  sal_doc, a.id_cot as id_doc, ROUND(CASE WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio = 1 THEN SUM(IFNULL(d.mon_iva, 0)) WHEN a.id_moneda = {$id_moneda} AND a.tasa_cambio != 1 THEN SUM(IFNULL(d.mon_iva, 0)) WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio != 1 THEN SUM(IFNULL(d.mon_iva, 0)) * a.tasa_cambio WHEN a.id_moneda != {$id_moneda} AND a.tasa_cambio = 1 THEN SUM(IFNULL(d.mon_iva, 0)) / GetExchangeRateVal(a.fecha_comp, {$id_moneda}) ELSE 0 END, 2) mon_iva, a.id_moneda, IFNULL((SELECT mon_ret FROM f60061 aa INNER JOIN f6006 ab ON ab.id_movement = aa.movem_id WHERE aa.id_cot = {$id_cot} GROUP BY id_cot),0) mon_ret FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda LEFT OUTER JOIN f60032 d ON d.id_cot = a.id_cot WHERE a.id_cot = {$id_cot} AND ROUND(a.sal_doc,2) != 0 GROUP BY b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.mon_doc, a.sal_doc, a.id_cot";
        $sql = "SELECT b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.id_cot, a.id_moneda, 
        ROUND(CASE WHEN a.id_moneda = e.id_moneda AND a.tasa_cambio != 1 THEN a.mon_doc WHEN a.id_moneda = e.id_moneda AND a.tasa_cambio = 1 THEN 
        a.mon_doc ELSE a.mon_doc * a.tasa_cambio END, 2) mon_doc_dom, ROUND(CASE WHEN a.id_moneda = e.id_moneda AND a.tasa_cambio != 1 THEN a.sal_doc WHEN a.id_moneda = e.id_moneda AND a.tasa_cambio = 1 THEN a.sal_doc ELSE a.sal_doc * a.tasa_cambio END, 2) sal_doc_dom, ROUND(CASE WHEN a.id_moneda = e.id_moneda AND a.tasa_cambio != 1 THEN SUM(IFNULL(d.mon_iva, 0))  WHEN a.id_moneda = e.id_moneda AND a.tasa_cambio = 1 THEN SUM(IFNULL(d.mon_iva, 0)) ELSE SUM(IFNULL(d.mon_iva, 0)) * a.tasa_cambio END, 2) mon_iva_dom, ROUND(CASE WHEN a.id_moneda = e.id_moneda AND a.imp_crcd = 0 THEN (a.mon_doc / GetExchangeRateVal(a.fecha_comp, 2)) WHEN a.id_moneda = e.id_moneda AND a.imp_crcd != 0 THEN (a.mon_doc / a.imp_tasa_cambio) ELSE a.mon_doc END, 2) mon_doc_for, ROUND(CASE WHEN a.id_moneda = e.id_moneda AND a.imp_crcd = 0 THEN (a.sal_doc / GetExchangeRateVal(a.fecha_comp, 2)) WHEN a.id_moneda = e.id_moneda AND a.imp_crcd != 0 THEN (a.sal_doc / a.imp_tasa_cambio) ELSE a.sal_doc END, 2) sal_doc_for, ROUND(CASE WHEN a.id_moneda = e.id_moneda AND a.imp_crcd = 0 THEN SUM(IFNULL(d.mon_iva / GetExchangeRateVal(a.fecha_comp, 2), 0)) WHEN a.id_moneda = e.id_moneda AND a.imp_crcd != 0 THEN SUM(IFNULL(d.mon_iva / a.imp_tasa_cambio, 0)) ELSE SUM(IFNULL(d.mon_iva,0)) END, 2) mon_iva_for,  f.contr_esp especial_contrib, r.por_reten FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f0005 c on c.id_moneda = a.id_moneda INNER JOIN f0011 e on e.id_emp = a.id_emp INNER JOIN f0014 f ON f.id_ent = a.id_cli LEFT OUTER JOIN f60032 d ON d.id_cot = a.id_cot LEFT OUTER JOIN f0020 r ON r.id = f.id_por_ret_iva WHERE a.id_cot = {$id_cot} $filter GROUP BY b.tipo_codigo, b.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, c.codigo_moneda, a.tasa_cambio, a.id_cot, a.id_moneda, f.contr_esp, r.por_reten";
        $r = DB::query($sql);
        return $r[0];
    }
    static function getnro_control($id_emp)
    {
        $r = db::query("SELECT next_nroControl FROM f6007 WHERE id_emp = {$id_emp} AND status = 1 LIMIT 1");
        return $r[0];
    }
    static function nextnro_control($id_emp)
    {
        $r = db::query("UPDATE f6007 SET next_nroControl = next_nroControl + 1 WHERE id_emp = {$id_emp} AND status = 1 LIMIT 1");
        return $r;
    }
    static function DocCtrolCXC($id_emp, $id_tdo_ctrl, $fec_ini, $fec_fin)
    {
        $filter = '';
        if ($id_tdo_ctrl) {
            $filter = " AND e.id_tdoc = {$id_tdo_ctrl}";
        }
        $r = DB::query("SELECT DISTINCT a.id_cot, d.nombre_emp, a.fecha_comp, e.tipo_codigo, e.nom_tdoc, f.nom_ent, a.num_tdo, a.nro_control FROM f6003 a LEFT JOIN f60031 b ON b.id_cot = a.id_cot LEFT OUTER JOIN f60032 c ON c.id_cot = a.id_cot INNER JOIN f0011 d ON d.id_emp = a.id_emp INNER JOIN f6001 e ON e.id_tdoc = a.id_tdo INNER JOIN f0014 f ON f.id_ent = a.id_cli WHERE a.id_emp = {$id_emp} AND a.fecha_comp BETWEEN '" . $fec_ini . "' AND '" .  $fec_fin . "'" . $filter);
        return $r;
    }
    static function nro_control($id_cot, $data)
    {
        return $r = DB::update('f6003', $data, ['id_cot' => $id_cot]);
    }
    static function listar_facturas_clientes($id_emp, $id_ent)
    {
        return $r = DB::query("SELECT a.id_cot, CONCAT(c.tipo_codigo, ' - ', c.nom_tdoc, ' - ', a.num_tdo, ' - Saldo en ', d.codigo_moneda , ' ', REPLACE(REPLACE(REPLACE(FORMAT(a.sal_doc, 2), ',', ':'), '.', ','), ':', '.')) cliente FROM f6003 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo AND c.tipo_tdoc = 'F' INNER JOIN f0005 d ON d.id_moneda = a.id_moneda WHERE a.id_emp = {$id_emp} AND a.id_cli = {$id_ent} AND a.status = 1");
    }
    static function edo_cuenta($id_emp, $id_cli, $ori)
    {
        $fecha = date("Y-m-d");
        $filter = "";
        if ($id_cli) {
            $filter = " AND id_ent = {$id_cli}";
        }
        if ($ori == 0) {
            $sql = "SELECT nombre_emp, nom_ent, nom_tdoc, fecha_comp, cod_diascre, dias_calle, num_tdo, tasa_cambio, adic_01 motivo, status_doc, mon_exe_dom, mon_base_dom, mon_iva_dom, ret_iva_dom, dif_iva_dom, (por_cob_dom ) por_cob_dom, mon_exe_for, mon_base_for, mon_iva_for, ret_iva_for, dif_iva_for, (por_cob_for) por_cob_for FROM view_doc_pend_cxc WHERE id_emp = {$id_emp} $filter";
        }else{
            $sql = "SELECT nom_ent, des_diascre, SUM(mon_exe_for) mon_exe_for, SUM(mon_base_for) mon_base_for, SUM(mon_iva_for) mon_iva_for, SUM(ret_iva_for) ret_iva_for, SUM(dif_iva_for) dif_iva_for, SUM(por_cob_for) por_cob_for FROM view_doc_pend_cxc WHERE id_emp = {$id_emp} GROUP BY nom_ent, des_diascre;";
        }
        return $r = DB::query($sql);
    }
    static function saldos_vencidos($id_emp, $id_ent)
    {
        $filter = "";
        if ($id_emp > 0) {
            $filter .= " AND c.id_emp = {$id_emp} ";
        }
        if ($id_ent > 0) {
            $filter .=  " AND b.id_ent = {$id_ent} ";
        }
        $sql = "SELECT c.cod_emp, c.nombre_emp, b.id_ent, b.nom_ent, f.tipo_codigo, f.nom_tdoc, a.num_tdo, a.fecha_comp, e.codigo_moneda,  DATEDIFF(CURDATE(), a.fecha_comp) dias, fir_due_date, sec_due_date, a.sal_doc, thi_due_date, fou_due_date, CASE WHEN DATEDIFF(CURDATE(), a.fecha_comp) <= fir_due_date THEN (a.sal_doc) ELSE 0 END por_vencer, CASE WHEN DATEDIFF(CURDATE(), a.fecha_comp) > fir_due_date AND DATEDIFF(CURDATE(), a.fecha_comp) <= sec_due_date THEN (a.sal_doc) ELSE 0 END vencido_01, CASE WHEN DATEDIFF(CURDATE(), a.fecha_comp) > sec_due_date AND DATEDIFF(CURDATE(), a.fecha_comp) <= thi_due_date THEN (a.sal_doc) ELSE 0 END vencido_02, CASE WHEN DATEDIFF(CURDATE(), a.fecha_comp) > thi_due_date AND DATEDIFF(CURDATE(), a.fecha_comp) <= fou_due_date THEN (a.sal_doc) ELSE 0 END vencido_03, CASE WHEN DATEDIFF(CURDATE(), a.fecha_comp) >= fou_due_date THEN (a.sal_doc) ELSE 0 END vencido_04 FROM f6003 a INNER JOIN f0014 b ON b.id_ent = a.id_cli INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f6999 d ON d.id_emp = b.id_emp INNER JOIN f0005 e ON e.id_moneda = a.id_moneda INNER JOIN f6001 f ON f.id_tdoc = a.id_tdo INNER JOIN f4999 g ON g.id_emp = a.id_emp AND ((f.id_tdoc = g.id_tdoc_not_no_fis AND IFNULL(b.c_consig,0) = 0) OR(f.id_tdoc != g.id_tdoc_not_no_fis)) AND (f.id_tdoc <> g.id_tdoc_not) WHERE a.fecha_comp <= CURDATE() AND ROUND(a.sal_doc,2) != 0 AND a.status = 1 " . $filter . " ORDER BY c.cod_emp, c.nombre_emp, b.id_ent, b.nom_ent, DATEDIFF(CURDATE(), a.fecha_comp) DESC";
        return $r = DB::query($sql);
    }
    static function store_notify() {}
    static function ventas_globales()
   {
        $sql = "";
    }
    static function rep_fac_pag($id_emp, $fec_ini, $fec_fin, $id_cli){ 
        $sql = "SELECT b.id_emp, b.nombre_emp, c.id_ent, c.nom_ent, f.nom_tdoc, e.fecha_comp fec_fact, z.cod_diascre, e.num_tdo, a.fecha_comp fec_cob, ROUND(d.monto_doc,2) mon_cob, ROUND(IFNULL(d.mon_ret, 0),2) mon_ret, CASE WHEN ROUND(IFNULL(d.mon_ret, 0), 2) != 0 THEN d.num_ret ELSE ' ' END num_ret, a. movem_origen, g.des_tmocxc, fn_calc_dias(e.fecha_comp, a.fecha_comp, 2)  dias_pag FROM f6006 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli INNER JOIN f60061 d ON d.movem_id = a.id_movement INNER JOIN f6003 e ON e.id_cot = d.id_cot INNER JOIN f6001 f ON f.id_tdoc = e.id_tdo INNER JOIN f6004 g ON g.id_tmocxc = a.id_tmocxc LEFT OUTER JOIN f6005 z ON z.id_diascre = c.id_diascre WHERE a.id_emp = {$id_emp} AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin' AND a.status = 1 AND a.id_cli = {$id_cli} ORDER BY  a.fecha_comp";
        return DB::query($sql);
    }
        static function config_cxc($id){
        $r = DB::query("SELECT * FROM f6999 WHERE id_emp = {$id} AND status = 1");
        return $r[0];
    }
    static function show_row($id){
        $sql = "SELECT a.id_cot, a.id_emp, a.id_tdo, a.num_tdo, a.nro_control, a.id_cli, a.fecha_comp, a.fecha_venci, a.id_moneda, a.tasa_cambio, a.descrip_cot, a.id_doc_afec, a.doc_afectado, a.status, d.id_concxc, CONCAT(n.codigo_con, ' - ', n.nombre_con) nombre_con, o.id_aux, CONCAT(o.cod_aux, ' - ', o.nombre_aux) nombre_aux , d.monto, CASE WHEN d.mon_iva <> 0 THEN 'S' ELSE 'N' END iva, d.mon_iva, a.id_motcam, a.motivo, e.nom_ent FROM f6003 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo INNER JOIN f60032 d ON d.id_cot = a.id_cot INNER JOIN f0014 e ON e.id_ent = a.id_cli INNER JOIN f0005 f on f.id_moneda = a.id_moneda LEFT OUTER JOIN f60031 l ON l.id_cot = a.id_cot LEFT OUTER JOIN f6003 m ON m.id_cot = a.id_doc_afec INNER JOIN f6002 n ON n.id = d.id_concxc LEFT OUTER JOIN f0009 o ON o.id_aux = d.id_aux WHERE a.id_cot = {$id}";
        $r = DB::query($sql);
        return $r;
    }
    /**
     * Obtener documentos de CXC para un cliente específico
     * @param int $id Código de cliente
     * @param int $id_moneda Código de moneda
     * @return array
     */
    static function get_doc_cxc_cli($id, $id_moneda){
        $sql = "SELECT DISTINCT a.id_cot, b.nombre_emp, c.tipo_codigo, c.nom_tdoc, a.num_tdo, a.fecha_comp, a.fecha_venci, d.nom_ent, e.codigo_moneda, a.tasa_cambio, a.status, fn_sal_doc_cxc(a.id_cot, $id_moneda, 1) mon_doc, IFNULL(fn_sal_doc_cxc(a.id_cot, $id_moneda, 2), 0) sal_doc, CONCAT('00-', a.nro_control) nro_control FROM f6003 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f6001 c on c.id_tdoc = a.id_tdo AND c.tipo_tdoc IN( 'F', 'B', 'C') INNER JOIN f0014 d on d.id_ent = a.id_cli INNER JOIN f0005 e on e.id_moneda = a.id_moneda INNER JOIN f60032 f on f.id_cot = a.id_cot WHERE d.id_ent = {$id}";
        $r = DB::query($sql);
        return $r;
    }
    static function approved(int $id){        
        $db = new Conexion();
        $link = (object)$db->conect();
        try {
            //
            $modify_user = $_SESSION['id_user'];
            $modify_date = getAuditoria();   
            $modulo = 'C';
            //
            $link->beginTransaction();          
            //Verificar Notificación si es para aprobación 
            $sql_apro = "SELECT * FROM f0025 WHERE modulo = :modulo AND id_origen = :id_origen AND id_receptor = :id_receptor";
            $stmt_apro = $link->prepare($sql_apro);
            $stmt_apro->execute([
                ':modulo' => $modulo,
                ':id_origen' => $id,
                ':id_receptor' => $modify_user
            ]);
            $row_apro = $stmt_apro->fetch(PDO::FETCH_ASSOC);            
            $aprobado = $row_apro['approved'];
            $params_new = [];
            if($aprobado == 0){     
                $url_destino = 'CXCDocument/gestion/';
                $url_destino .= encriptar_url(json_encode(['id' => $id, 'accion' => 'printer']));            
                $params_new = [
                    ':id_emisor' => $row_apro['id_receptor'], 
                    ':id_receptor' => $row_apro['id_emisor'],
                    ':mensaje' =>$row_apro['mensaje'],
                    ':tipo' => str_replace('Aprobación pendiente', 'Documento Aprobado', $row_apro['tipo']), 
                    ':leido' => 0, 
                    ':modulo' => $modulo, 
                    ':id_origen' => $id, 
                    ':motivo' => $row_apro['motivo'],
                    ':approved' => 1, 
                    ':url_destino' => $url_destino,
                    ':create_user' => $modify_user, 
                    ':create_date' => $modify_date
                ];                                       
            }
            //Actualizar tabla de Notificaciones               
            $sql_upd_apro = "UPDATE f0025 SET leido = :leido, approved = :approved, modify_user = :modify_user, modify_date = :modify_date WHERE modulo = :modulo AND id_origen = :id_origen";
            //               
            $stmt_upd_apro = $link->prepare($sql_upd_apro);
            //
            $params_upd_apro = [
                ':leido' => 1, 
                ':approved' => 1, 
                ':modify_user' => $modify_user, 
                ':modify_date' => $modify_date,
                ':modulo' => $modulo,
                ':id_origen' => $id
            ];
            //
            $stmt_upd_apro->execute($params_upd_apro);
            //
            $stmt_upd_apro->closeCursor();
           
            //Actualizar tabla de documentos
            $sql_upd_doc = "UPDATE f6003 SET status = :status, modify_user = :modify_user, modify_date = :modify_date WHERE id_cot = :id_cot";
            //
            $stmt_upd_doc = $link->prepare($sql_upd_doc);
            //
            $params_upd_doc = [
                ':status' => 1, 
                ':modify_user' => $modify_user, 
                ':modify_date' => $modify_date ,
                ':id_cot' => $id
            ];
            //
            $stmt_upd_doc->execute($params_upd_doc);
            //Crear nuevo registro de notificación del usuario
            //
            $stmt_upd_doc->closeCursor();
            
            //Crear notificación del usuario
            if($aprobado == 0){  
                $sql_new = "INSERT INTO f0025 (id_emisor, id_receptor, mensaje, tipo, leido, modulo, id_origen, motivo, approved, url_destino, create_user, create_date) VALUES(:id_emisor, :id_receptor, :mensaje, :tipo, :leido, :modulo, :id_origen, :motivo, :approved, :url_destino, :create_user, :create_date)";               
                $stmt_new = $link->prepare($sql_new);                
                $stmt_new->execute($params_new);
                $stmt_new->closeCursor();
                
            }
            
            //
            $link->commit();
            return true;
        } catch (\PDOException $e) {
            //debug($e->getMessage());
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
    static function check_read(int $id){
        $db = new Conexion();
        $link = (object)$db->conect();        
        try {
             //
            $link->beginTransaction();  
            $leido = 1;
            $id_user = $_SESSION['id_user'];
            $date = getAuditoria();
            $sql_upd = "UPDATE f0025 SET leido = :leido, modify_user = :modify_user, modify_date = :modify_date WHERE id_receptor = :id_receptor AND id_origen = :id_origen";
            $params_upd = [
                ':leido' => $leido,
                ':id_receptor' => $id_user,
                ':id_origen' => $id,
                ':modify_user' => $id_user,
                ':modify_date' => $date
            ];                        
            $stmt_upd = $link->prepare($sql_upd);               
            $stmt_upd->execute($params_upd);            
            $stmt_upd->closeCursor();
            //            
            $link->commit();
            return true;        
        } catch (\PDOException $e) {   
            debug($e->getMessage());                 
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
    static function destroy(int $id){                 
        $db = new Conexion();
        $link = (object)$db->conect();                
        try {
             //            
            $link->beginTransaction();            
            //Consultar documento
            $sql_sel = "SELECT * FROM f6003 WHERE id_cot = :id_cot AND IFNULL(id_cont, '')  = ''";            
            $stmt_sel = $link->prepare($sql_sel);
            $stmt_sel->execute([':id_cot' => $id]);            
            $row_sel = $stmt_sel->fetch(PDO::FETCH_ASSOC);
            $stmt_sel->closeCursor();            
            $sal_doc = $row_sel['sal_doc'];
            $status = $row_sel['status'];
            if(round($sal_doc, 2) == 0){
                return 1; // Documento con saldo
            }                             
            //Desactivar documento            
            if($status == 1){                
                //Desactivar documento
                $status = 0;
                $doc_upd = "UPDATE f6003 SET status = :status WHERE id_cot = :id_cot";                
                $stmt_upd = $link->prepare($doc_upd);                                    
                $stmt_upd->execute([':id_cot' => $id, ':status' => $status]);                                                
                $stmt_upd->closeCursor();                                      
            }else if($status == 0){
                //Validar si es el ultimo numero y rvisar proximo numero del tipo de documento
                $sql_num = "SELECT a.id_emp, b.id_tdoc, a.num_tdo, b.num_tdoc , a.nro_control, c.next_nroControl FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f6007 c ON c.id_emp = a.id_emp WHERE a.id_cot = :id_cot";
                $stmt_num = $link->prepare($sql_num);
                $stmt_num->execute([':id_cot' => $id]);
                $row_num = $stmt_num->fetch(PDO::FETCH_ASSOC);                
                $stmt_num->closeCursor();
                $id_tdoc = $row_num['id_tdoc'];
                $id_emp = $row_num['id_emp'];
                $num_doc = $row_num['num_tdo'];
                $num_tdo = $row_num['num_tdoc'] - 1;
                $num_ctr = $row_num['nro_control'];
                $ctr_cfg = $row_num['next_nroControl'] - 1;
                //Eliminar doccumento detalle                
                $del_det = "DELETE FROM f60032 WHERE id_cot = :id_cot";                
                $stmt_del_det = $link->prepare($del_det);                
                $stmt_del_det->execute([':id_cot' => $id]);                
                $stmt_del_det->closeCursor();
                //Eliminar documento encabezado                                
                $del_enc = "DELETE FROM f6003 WHERE id_cot = :id_cot";                                
                $stmt_del_enc = $link->prepare($del_enc);                                
                $stmt_del_enc->execute([':id_cot' => $id]);
                $stmt_del_enc->closeCursor();                                                
                //Actualizar proximo numero de documento                
                if(($num_doc == $num_tdo) && ($num_ctr == $ctr_cfg)){                          
                    $upd_tdo = "UPDATE f6001 SET num_tdoc = :num_tdoc WHERE id_tdoc = :id_tdoc";
                    $stmt_ud_tdo = $link->prepare($upd_tdo);                    
                    $stmt_ud_tdo->execute([':num_tdoc' => $num_doc, ':id_tdoc' => $id_tdoc ]);                    
                    $stmt_ud_tdo->closeCursor();
                                        
                    $upd_ctr = "UPDATE f6007 SET next_nroControl = :next_nroControl WHERE id_emp = :id_emp";
                    $stmt_ud_cfg = $link->prepare($upd_ctr);
                    $stmt_ud_cfg->execute([':next_nroControl' => $ctr_cfg, ':id_emp' => $id_emp]);
                    $stmt_ud_cfg->closeCursor();
                    
                }
            }                        
            //            
            $link->commit();
            return true;        
        } catch (\PDOException $e) {   
            debug($e->getMessage());                 
            $link->rollback();            
            throw new Exception($e->getMessage(), $e->getCode());
            return false;
        }
    }
}

