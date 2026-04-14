<?php
class MovDocCxcModel extends  DB{
    public function __construct(){
        parent::__construct();
    }
    static function show_rows($id_emp, $id_tdoc, $num_tdo, $id_ent, $fec_ini, $fec_fin, $origen){
        $filter = '';
        if($id_emp){
            $filter .= " AND a.id_emp = {$id_emp}";
        }
        if ($id_tdoc) {
            $filter .= " AND d.id_tdoc = {$id_tdoc}";
        }
        if ($num_tdo) {
            $filter .= " AND e.num_tdo = '$num_tdo'";
        }
        if ($id_ent) {
            $filter .= " AND f.id_ent = {$id_ent}";
        }
        if ($fec_ini) {
            $filter .= " AND a.fecha_comp BETWEEN '$fec_ini' AND '$fec_fin'";
        }
        if($origen === 'C'){
            $sql = "SELECT g.nombre_emp, c.cod_tmocxc, c.des_tmocxc, a.movem_number, a.fecha_comp fecha_mov, a.movem_descrip, h.codigo_moneda moneda_mov, a.tasa_cambio tasa_mov, f.nom_ent, ROUND(e.mon_doc, 2) mon_doc, e.sal_doc, d.tipo_codigo, d.nom_tdoc, e.num_tdo, e.fecha_comp fec_fact, i.codigo_moneda moneda_fac, e.tasa_cambio tasa_fac, CASE WHEN c.acc_tmocxc = 'A' THEN ROUND(IFNULL(b.monto_doc_for, b.monto_doc),2) ELSE 0 END debe, CASE WHEN c.acc_tmocxc != 'A' THEN ROUND(IFNULL(b.monto_doc_for, b.monto_doc),2) ELSE 0 END haber, CONCAT(j.name_user, ' ', j.last_user) create_user, a.create_date, CONCAT(IFNULL(k.name_user, ''), ' ', IFNULL(k.last_user, '')) modify_user, a.modify_date, e.id_cot, a.movem_origen, a.id_movement FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f6004 c ON c.id_tmocxc = a.id_tmocxc INNER JOIN f6001 d ON d.id_tdoc = b.id_tdo INNER JOIN f6003 e ON e.id_cot = b.id_cot INNER JOIN f0014 f ON f.id_ent = a.id_cli INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f0005 h ON h.id_moneda = a.id_moneda INNER JOIN f0005 i ON i.id_moneda = e.id_moneda INNER JOIN f0002 j ON j.id_user = a.create_user LEFT OUTER JOIN f0002 k ON k.id_user = a.modify_user WHERE a.status = 1" . $filter . " ORDER BY g.nombre_emp, f.nom_ent, a.fecha_comp, d.tipo_codigo ";
        }else{
            $sql = "SELECT g.nombre_emp, c.cod_tmocxc, c.des_tmocxc, a.movem_number, a.fecha_comp fecha_mov, a.movem_descrip, h.codigo_moneda moneda_mov, a.tasa_cambio tasa_mov, f.nom_ent, ROUND(e.mon_doc, 2) mon_doc, e.sal_doc, d.tipo_codigo, d.nom_tdoc, e.num_tdo, e.fecha_comp fec_fact, i.codigo_moneda moneda_fac, e.tasa_cambio tasa_fac, CASE WHEN c.acc_tmocxc != 'A' THEN b.monto_doc ELSE 0 END debe, CASE WHEN c.acc_tmocxc != 'D' THEN CASE WHEN a.id_moneda = g.id_moneda AND e.id_moneda != a.id_moneda THEN ROUND(b.monto_doc / e.tasa_cambio,2) WHEN a.id_moneda != g.id_moneda AND e.id_moneda = a.id_moneda THEN ROUND(b.monto_doc,2) ELSE 0 END ELSE 0 END haber, CONCAT(j.name_user, ' ', j.last_user) create_user, a.create_date, CONCAT(IFNULL(k.name_user, ''), ' ', IFNULL(k.last_user, '')) modify_user, a.modify_date, e.id_cot, a.movem_origen, a.id_movement  FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement  INNER JOIN f3002 c ON c.id_tmocxc = a.id_tmocxp INNER JOIN f3001 d ON d.id_tdoc = b.id_tdo INNER JOIN f3004 e ON e.id_cot = b.id_cot INNER JOIN f0014 f ON f.id_ent = a.id_ent INNER JOIN f0011 g ON g.id_emp = a.id_emp INNER JOIN f0005 h ON h.id_moneda = a.id_moneda INNER JOIN f0005 i ON i.id_moneda = e.id_moneda INNER JOIN f0002 j ON j.id_user = a.create_user LEFT OUTER JOIN f0002 k ON k.id_user = a.modify_user WHERE a.status = 1 " .$filter ." ORDER BY g.nombre_emp, f.nom_ent, a.fecha_comp, d.tipo_codigo";
        }
        $r = DB::query($sql);
        return $r;
    }
}