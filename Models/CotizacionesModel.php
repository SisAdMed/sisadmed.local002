<?php
class CotizacionesModel extends DB{
    public function __construct() {
        parent::__construct();
    }
    static function all(){
        $sql = "SELECT DISTINCT c.id_cot, e.nombre_emp, t.nom_tdoc, c.num_tdo, a.nom_ent, c.fecha_comp, m.codigo_moneda, c.tasa_cambio, v.nom_vend, c.status, c.id_cont, CONCAT(uc.name_user, ' ', uc.last_user) creado_por, IFNULL(CONCAT(um.name_user, ' ', um.last_user), ' ')  modificdo_por FROM f4008 c INNER JOIN f0011 e ON e.id_emp = c.id_emp INNER JOIN f6001 t ON t.id_tdoc = c.id_tdo INNER JOIN f0014 a ON a.id_ent = c.id_cli INNER JOIN f0005 m ON m.id_moneda = c.id_moneda INNER JOIN f0016 v ON v.id_vend = c.id_vend INNER JOIN f40081 d on d.id_cot = c.id_cot INNER JOIN f0002 uc ON uc.id_user = c.create_user LEFT OUTER JOIN f0002 um ON um.id_user = c.modify_user";
        return $r = DB::query($sql);
    }
    static function guardar($data){
        return $r = DB::insert('f4008', $data);
    }
    static function actualizar($id, $data){
        return $r= DB::update('f4008', $data, ['id_cot' => $id]);
    }
    static function nextNumber($id_emp, $id_tdoc){
        $nextNumber = DB::query("SELECT * FROM f6001 WHERE id_emp = {$id_emp} AND id_tdoc = {$id_tdoc} LIMIT 1");
        return $nextNumber[0];
    }
    static function setNextNumber($id_emp, $id_tdoc, $data){
        return $r = DB::update("f6001", $data, ['id_emp' => $id_emp, 'id_tdoc' => $id_tdoc]);
    }
    static function borrarDetCotizacion($id_cot){
        return $id = DB::query("DELETE FROM f40081 WHERE id_cot = {$id_cot}");

    }
    static function guardarDetCotizacion($data){ 
        return $id = DB::insert('f40081', $data);

    }
    static function edit($id){
        $r = DB::query("SELECT c.id_cot id_cot, c.id_emp id_emp, c.id_tdo id_tdo, c.num_tdo num_tdo, c.id_cli id_cli, c.fecha_comp fecha_comp, c.id_moneda id_moneda, c.tasa_cambio tasa_cambio, c.id_vend id_vend, e.nombre_emp nombre_emp, en.nom_ent nom_ent, concat(v.nom_vend,' ',v.ape_vend) vendedor, dc.id_prod id_prod, dc.can_det can_det, dc.uni_vta uni_vta, dc.pre_unit pre_unit, dc.pre_vta pre_vta, dc.iva_prod iva_prod, dc.sub_total sub_total, dc.mon_iva mon_iva, dc.tota_prod tota_prod, pr.cod_prod cod_prod, pr.nom_prod, c.observa FROM f4008 c INNER JOIN f0011 e ON e.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0005 m ON m.id_moneda = c.id_moneda INNER JOIN f0016 v ON v.id_vend = c.id_vend INNER JOIN f6001 t ON t.id_tdoc = c.id_tdo INNER JOIN f40081 dc ON dc.id_cot = c.id_cot INNER JOIN f4005 pr ON pr.id_prod = dc.id_prod WHERE c.id_cot = {$id}");
        return $r[0];
    }
    static function edit_deta($id){
        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        $sql = "SELECT c.id_cot id_cot, c.id_emp id_emp, c.id_tdo id_tdo, c.num_tdo num_tdo, c.id_cli id_cli, c.fecha_comp fecha_comp, c.id_moneda id_moneda, c.tasa_cambio tasa_cambio, c.id_vend id_vend, e.nombre_emp nombre_emp, en.nom_ent nom_ent, concat(v.nom_vend,' ',v.ape_vend) vendedor, dc.id_prod id_prod, dc.can_det can_det, dc.uni_vta uni_vta, dc.pre_unit pre_unit, dc.pre_vta pre_vta, dc.iva_prod iva_prod, dc.sub_total sub_total, dc.mon_iva mon_iva, dc.tota_prod tota_prod, pr.cod_prod cod_prod, pr.nom_prod, c.observa,fn_saldo_ant_inv(0, dc.id_prod, '$id_alm', c.fecha_comp, 0) stock FROM f4008 c INNER JOIN f0011 e ON e.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0005 m ON m.id_moneda = c.id_moneda INNER JOIN f0016 v ON v.id_vend = c.id_vend INNER JOIN f6001 t ON t.id_tdoc = c.id_tdo INNER JOIN f40081 dc ON dc.id_cot = c.id_cot INNER JOIN f4005 pr ON pr.id_prod = dc.id_prod WHERE c.id_cot = {$id}";
        $r = DB::query($sql);
        return $r;
    }
    static function consulta_adic01($id_emp, $fecha_precio){
        $r = DB::query("SELECT * FROM f0006 p INNER JOIN f0011 e ON e.id_emp = p.id_emp WHERE p.id_emp = {$id_emp} AND fecha_precio <= '". $fecha_precio ."' ORDER BY fecha_precio DESC LIMIT 1");
        return $r[0];
    }
    static function consulta_adic02($id_cli){
        $r = DB::query("SELECT m.adic_01, m.adic_02 FROM f0014 e INNER JOIN f0012a m ON m.id_motcam = e.id_motcam WHERE e.id_ent = {$id_cli} LIMIT 1");
        return $r[0];
    }
    static function printer_cotiza($id_cot){
        $sql = "SELECT em.nombre_emp, em.rif_empresa, em.dir_emp ,em.tel_emp, em.email_emp, c.num_tdo, tdo.nom_tdoc, c.fecha_comp, en.nom_ent, en.rif_ent, en.dir_ent, en.postal_ent, pa.nombre_pais, es.nombre_edo, ci.nombre_ciudad, ve.nom_vend, ve.ape_vend, pro.cod_prod, pro.cod2_prod, pro.nom_prod, dc.iva_prod, dc.can_det, (dc.sub_total / dc.can_det)pre_vta, dc.sub_total, c.id_cot, em.logo, moc.codigo_moneda, moe.codigo_moneda, c.tasa_cambio, fab.nom_fab, pro.ref_prod, (SELECT IFNULL(f4010.fec_ven,'') FROM f4010 WHERE f4010.id_prod = pro.id_prod LIMIT 1) AS fec_ven, fc.note_pre, tdo.nom_tdoc, pro.lote_prod, IFNULL(cre.des_diascre,' ') AS des_diascre, IFNULL(c.observa,'') AS observa, fab_fac.nom_fab AS nom_fab_fac, em.id_moneda moneda_emp FROM f4008 c INNER JOIN f6001 tdo ON tdo.id_tdoc = c.id_tdo INNER JOIN f40081 dc ON dc.id_cot = c.id_cot INNER JOIN f0011 em ON em.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0004 pa ON pa.id_pais = en.id_pais INNER JOIN f00041 es ON es.id_edo = en.id_edo INNER JOIN f00042 ci ON ci.id_ciudad = en.id_ciudad INNER JOIN f0016 ve ON ve.id_vend = en.id_vend INNER JOIN f4005 pro ON pro.id_prod = dc.id_prod INNER JOIN f0005 moc ON moc.id_moneda = c.id_moneda INNER JOIN f0005 moe ON moe.id_moneda = em.id_moneda INNER JOIN f4003 fab ON fab.id_fab = pro.id_fab INNER JOIN f4999 fc ON fc.id_emp = c.id_emp AND fc.id_tdoc_pre = c.id_tdo LEFT OUTER JOIN f6005 cre ON cre.id_diascre = en.id_diascre LEFT OUTER JOIN f4003 fab_fac ON fab_fac.id_fab = pro.id_fab_fac WHERE c.id_cot = {$id_cot}";
        $r = DB::query($sql);
        return $r;
    }
    static function borrar($id){
        $r = DB::query("UPDATE f4008 SET status = 0 WHERE id_cot = {$id} AND id_cont IS NULL");
        return $r;
    }
    static function create_express($id_fab, $id_ent){ 
        $fecha = Date('Y-m-d');
        $x = DB::query("SELECT GROUP_CONCAT(id_alm) id_alm FROM f4999;");
        $id_alm = $x[0]['id_alm'];
        //$sql = "SELECT a.*, ventas_prod / (SELECT IFNULL(y.adic_01,1) adic_01 FROM f0014 x INNER JOIN f0012a y ON y.id_motcam = x.id_motcam WHERE x.id_ent = {$id_ent}) pv1 FROM f4005 a WHERE a.id_fab IN ($id_fab) AND EXISTS (SELECT id_prod FROM f4010 WHERE id_prod = a.id_prod)";
        $sql = "SELECT a.id_prod, a.nom_prod, a.uni_ven_prod, a.iva_prod, a.ventas_prod / (SELECT IFNULL(y.adic_01,1) adic_01 FROM f0014 x INNER JOIN f0012a y ON y.id_motcam = x.id_motcam WHERE x.id_ent = {$id_ent}) pv1, fn_saldo_ant_inv(0, a.id_prod, '$id_alm',  '$fecha', 0) stock FROM f4005 a WHERE a.id_fab IN ($id_fab) AND a.status = 1 AND fn_saldo_ant_inv(0, a.id_prod, '$id_alm', '$fecha', 0) > 0 ORDER BY a.nom_prod;"; 
        $r = DB::query($sql);
        return $r;
    }
    static function listar_cotizacones($id_emp){
        $r = DB::query("SELECT id_cot, concat('Cotización: ', num_tdo, ' - Cliente: ', id_cli, ' - ', nom_ent) cliente FROM f4008 INNER JOIN f0014 on f0014.id_ent = f4008.id_cli WHERE f4008.id_emp = {$id_emp} AND f4008.id_cont is NULL AND f4008.status = 1");
        return $r;
    }
    //Llenar listado entidad modal
    static function listar_entidad_modal($id, $tipo){
        $r = DB::query("SELECT a.id_ent id_ent, a.rif_ent rif_ent, a.nom_ent nom_ent, b.nom_vend nom_vend, c.nombre_zona, a.handling_conver FROM f0014 a INNER JOIN f0016 b ON b.id_vend = a.id_vend INNER JOIN f0003 c ON c.id_zona = a.id_zona WHERE a.status = 1 AND id_emp = {$id} AND tip_ent = '".$tipo."'");
        return $r;
    }
}