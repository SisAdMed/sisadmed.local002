<?php
class RetIvaModel extends DB {
    public function __construct() {
        parent::__construct();
    }
    static function all() {
        return $r = DB::query('SELECT * FROM f3005');
    }
    static function guardar($data) {
        return $r = DB::insert('f3005', $data);
    }
    static function actualizar($id, $data) {
        return $r = DB::update('f3005', $data, ['id' => $id]);
    }
    static function borrar($id) {
        return $r = DB::delete('f3005', ['id' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f3005 WHERE id = {$id}");
        return $r[0];
    }
    static function show_row($id){
        $r = DB::query("SELECT a.fecha_vigenc, a.desc_retiva, a.tasa_retiva, a.min_retiva, a.id_ctb, a.id_aux, a.status, b.nombre_cta, c.nombre_aux, CONCAT(b.cod_cta, ' - ', b.nombre_cta) nom_ctb, CONCAT(c.cod_aux, ' - ', c.n   ombre_aux) nom_aux FROM f3005 a INNER JOIN f0010 b ON b.id_cta = a.id_ctb LEFT OUTER JOIN f0009 c ON c.id_aux = a.id_aux WHERE a.id = {$id}");
        return $r[0];
    }
    static function listar_retiva(){
        return $r = DB::query("SELECT * FROM f3005 WHERE status = 1");
    }
    static function report_retiva($id_emp, $fecha_ini, $fecha_fin){
        return $r = DB::query("SELECT d.id_emp, d.cod_emp, d.nombre_emp, d.rif_empresa, e.rif_ent, e.nom_ent, CASE WHEN ISNULL(b.num_tdo)  THEN c.num_tdo ELSE b.num_tdo END num_tdo, CASE WHEN ISNULL(b.num_control) THEN c.num_control ELSE b.num_control END num_control, a.fecha_pago, CONCAT(YEAR(a.fecha_pago), LPAD(MONTH(a.fecha_pago), 2, '0'), LPAD(a.num_retiva, 8, '0')) num_retiva, (a.tot_compras * IFNULL(b.tasa_cambio, c.tasa_cambio)) tot_compras, (a.tot_exento *  IFNULL(b.tasa_cambio, c.tasa_cambio)) tot_exento, (a.tot_base * IFNULL(b.tasa_cambio, c.tasa_cambio)) tot_base, (a.tot_iva * IFNULL(b.tasa_cambio, c.tasa_cambio)) tot_iva, f.tasa_retiva, (a.tot_ret * IFNULL(b.tasa_cambio, c.tasa_cambio)) tot_ret, d.logo FROM f3006 a LEFT  JOIN f3004 b ON b.id_cot = a.id_cot AND a.origen = 'CXP' LEFT JOIN f8020 c ON c.id_cot = a.id_cot AND a.origen = 'COM' INNER JOIN f0011 d ON d.id_emp = a.id_emp INNER JOIN f0014 e ON e.id_ent = a.id_ent INNER JOIN f3005 f ON f.id = a.id_retiva WHERE a.id_emp = {$id_emp} AND a.fecha_pago BETWEEN '$fecha_ini' AND '$fecha_fin'");
    }
    static function file_text($id_emp, $fecha_ini, $fecha_fin){
        return $r = DB::query("SELECT  d.rif_empresa, CONCAT(YEAR(a.fecha_pago), LPAD(MONTH(a.fecha_pago),2, '0')) periodo, a.fecha_pago, 'C' tipo_oper, CASE WHEN g.tipo_tdoc = 'M' THEN '01' WHEN g.tipo_tdoc = 'A' THEN '03' WHEN h.tipo_tdoc = 'M' THEN '01' WHEN h.tipo_tdoc = 'A' THEN '03' ELSE '02' END tipo_doc, e.rif_ent, IFNULL(b.num_tdo, c.num_tdo) nro_doc, IFNULL(b.num_control, c.num_control) nro_ctrl, ROUND(a.tot_compras * IFNULL(b.tasa_cambio, c.tasa_cambio),2) mon_doc, ROUND(a.tot_base * IFNULL(b.tasa_cambio, c.tasa_cambio),2) mon_bas, ROUND(a.tot_ret * IFNULL(b.tasa_cambio, c.tasa_cambio),2) mon_iva, '0' afectado, CONCAT(YEAR(a.fecha_pago), LPAD(MONTH(a.fecha_pago),2, '0'), LPAD(a.num_retiva, 8, '0')) num_ret,  CASE WHEN a.tot_exento != 0 THEN ROUND(a.tot_exento * IFNULL(b.tasa_cambio, c.tasa_cambio),2) ELSE '0' END mon_exe, ROUND(a.tasa_iva,2) alicuota, '0' expediente FROM f3006 a LEFT JOIN f3004 b ON b.id_cot = a.id_cot AND a.origen = 'CXP' LEFT JOIN f8020 c ON c.id_cot = a.id_cot AND a.origen = 'COM'  INNER JOIN f0011 d ON d.id_emp = a.id_emp INNER JOIN f0014 e ON e.id_ent = a.id_ent INNER JOIN f3005 f ON f.id = a.id_retiva LEFT JOIN f3001 g ON g.id_tdoc = b.id_tdo LEFT JOIN f3001 h ON h.id_tdoc = c.id_tdo WHERE a.id_emp = {$id_emp} AND a.fecha_pago BETWEEN '$fecha_ini' AND '$fecha_fin'");
    }
}