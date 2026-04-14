<?php
class DelnotnotfisModel extends DB{
    public function __construct() {
        parent::__construct();
    }
    static function all($tipo){
        return $r = DB::query("SELECT c.id_cot, e.nombre_emp, t.nom_tdoc, c.num_tdo, a.nom_ent, c.fecha_comp, m.codigo_moneda, c.tasa_cambio, v.nom_vend, c.status, CONCAT('FAC-', td.tipo_codigo, '-', SUBSTRING(c.id_cont,1,LOCATE('-',c.id_cont) - 1)) fuente, ap.status penapro FROM f6003 c INNER JOIN f0011 e ON e.id_emp = c.id_emp INNER JOIN f6001 t ON t.id_tdoc = c.id_tdo INNER JOIN f0014 a ON a.id_ent = c.id_cli INNER JOIN f0005 m ON m.id_moneda = c.id_moneda INNER JOIN f0016 v ON v.id_vend = c.id_vend LEFT OUTER JOIN f4008 co ON co.id_cot = SUBSTRING(c.id_cont,1,LOCATE('-', c.id_cont) - 1) LEFT OUTER JOIN f6001 td ON td.id_tdoc = co.id_tdo AND td.tipo_tdoc = '.$tipo.' LEFT OUTER JOIN fgenmsg ap ON ap.id_cot = c.id_cot AND ap.status = 1 AND ap.tipo_fgenmsgcol = 1 INNER JOIN f4999 cfg ON cfg.id_emp = c.id_emp AND cfg.id_tdoc_not_no_fis = c.id_tdo");
    }
    static function guardar($data){
        return $r = DB::insert('f6003', $data);
    }
    static function actualizar($id, $data){
        return $r= DB::update('f6003', $data, ['id_cot' => $id]);
    }
    static function nextNumber($id_emp, $id_tdoc){
        $nextNumber = DB::query("SELECT * FROM f6001 WHERE id_emp = {$id_emp} AND id_tdoc = {$id_tdoc} LIMIT 1");
        return $nextNumber;
    }
    static function setNextNumber($id_emp, $id_tdoc, $data){
        return $r = DB::update("f6001", $data, ['id_emp' => $id_emp, 'id_tdoc' => $id_tdoc]);
    }
    static function borrarDetfactura($id_cot){
        return $id = DB::query("DELETE FROM f60031 WHERE id_cot = {$id_cot}");

    }
    static function guardarDetfactura($data){
        return $id = DB::insert('f60031', $data);
    }
     static function guardarDetfactura_CXC($data){
        return $id = DB::insert('f60032', $data);
    }
    static function edit($id){
        $r = DB::query("SELECT c.id_cot id_cot, c.id_emp id_emp, c.id_tdo id_tdo, c.num_tdo num_tdo, c.id_cli id_cli, c.fecha_comp fecha_comp, c.id_moneda id_moneda, c.tasa_cambio tasa_cambio, c.id_vend id_vend, e.nombre_emp nombre_emp, en.nom_ent nom_ent, concat(v.nom_vend,' ',v.ape_vend) vendedor, dc.id_prod id_prod, dc.can_det can_det, dc.uni_vta uni_vta, dc.pre_unit pre_unit, dc.pre_vta pre_vta, dc.iva_prod iva_prod, dc.sub_total sub_total, dc.mon_iva mon_iva, dc.tota_prod tota_prod, pr.cod_prod cod_prod FROM f6003 c INNER JOIN f0011 e ON e.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0005 m ON m.id_moneda = c.id_moneda INNER JOIN f0016 v ON v.id_vend = c.id_vend INNER JOIN f6001 t ON t.id_tdoc = c.id_tdo INNER JOIN f60031 dc ON dc.id_cot = c.id_cot INNER JOIN f4005 pr ON pr.id_prod = dc.id_prod WHERE c.id_cot = {$id}");
        return $r[0];
    }
    static function edit_deta($id){
        return $r = DB::query("SELECT a.id_emp, a.id_tdo, a.num_tdo, a.fecha_comp, a.fecha_venci, a.id_cli, d.nom_ent, a.id_moneda, a.tasa_cambio, a.id_vend, e.id_prod, f.nom_prod, e.can_det, e.uni_vta, e.pre_unit, e.pre_vta,  e.iva_prod, e.sub_total FROM f6003 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f6001 c ON c.id_tdoc = a.id_tdo INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f60031 e ON e.id_cot = a.id_cot INNER JOIN f4005 f ON f.id_prod = e.id_prod LEFT OUTER JOIN f60032 g ON g.id_cot = a.id_cot WHERE a.id_cot = {$id}");
    }
    static function consulta_adic01($id_emp, $fecha_precio){
        $r = DB::query("SELECT * FROM f0006 p INNER JOIN f0011 e ON e.id_emp = p.id_emp WHERE p.id_emp = {$id_emp} AND fecha_precio <= '". $fecha_precio ."' ORDER BY fecha_precio DESC LIMIT 1");
        return $r[0];
    }
    static function consulta_adic02($id_cli){
        $r = DB::query("SELECT m.adic_01, m.adic_02 FROM f0014 e INNER JOIN f0012a m ON m.id_motcam = e.id_motcam WHERE e.id_ent = {$id_cli} LIMIT 1");
        return $r[0];
    }
    static function print_factura($id_cot){
        $r = DB::query("SELECT em.nombre_emp nombre_emp, em.rif_empresa rif_empresa, em.dir_emp dir_emp, em.tel_emp tel_emp, em.email_emp email_emp, c.num_tdo num_tdo, tdo.nom_tdoc nom_tdoc, c.fecha_comp fecha_comp, en.nom_ent nom_ent, en.rif_ent rif_ent, en.dir_ent dir_ent, en.postal_ent postal_ent, pa.nombre_pais nombre_pais, es.nombre_edo nombre_edo, ci.nombre_ciudad nombre_ciudad, ve.nom_vend nom_vend, ve.ape_vend ape_vend, pro.cod_prod cod_prod, pro.cod2_prod cod2_prod, pro.nom_prod nom_prod, dc.iva_prod iva_prod, dc.can_det can_det, dc.pre_vta pre_vta, dc.sub_total sub_total, c.id_cot id_cot, em.logo logo, moc.codigo_moneda codigo_moneda, moe.codigo_moneda moneda_emp, c.tasa_cambio tasa_cambio, cfg.id_tdoc_fac, cfg.note_fac, cfg.id_tdoc_cre, cfg.note_cre, cfg.id_tdoc_pre, cfg.note_pre, cfg.id_tdoc_not, cfg.note_not, cfg.id_tdoc_dev, cfg.note_dev, cfg.note_not_no_fis, en.note_fac note_fac_custom, fab.nom_fab, pro.ref_prod, c.oc_cliente, c.descrip_cot, fab_fac.nom_Fab  nom_fab_fac  FROM f6003 c INNER JOIN f6001 tdo ON tdo.id_tdoc = c.id_tdo INNER JOIN f60031 dc ON dc.id_cot = c.id_cot INNER JOIN f0011 em ON em.id_emp = c.id_emp INNER JOIN f0014 en ON en.id_ent = c.id_cli INNER JOIN f0004 pa ON pa.id_pais = en.id_pais INNER JOIN f00041 es ON es.id_edo = en.id_edo INNER JOIN f00042 ci ON ci.id_ciudad = en.id_ciudad INNER JOIN f0016 ve ON ve.id_vend = en.id_vend INNER JOIN f4005 pro ON pro.id_prod = dc.id_prod INNER JOIN f0005 moc ON moc.id_moneda = c.id_moneda INNER JOIN f0005 moe ON moe.id_moneda = em.id_moneda INNER JOIN f4003 fab ON fab.id_fab = pro.id_fab INNER JOIN f4999 cfg ON cfg.id_emp = c.id_emp LEFT OUTER JOIN f4003 fab_fac ON fab_fac.id_fab = pro.id_fab_fac WHERE c.id_cot = {$id_cot}");
        return $r;
    }
    static function borrar($id){
        $r = DB::query("UPDATE f6003 SET status = 99 WHERE id_cot = {$id}");
        return $r;
    }
    static function selectEncyDetmovinv($id){
        return $r = DB::query("SELECT c.id_movinv FROM f6003 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo INNER JOIN f4009 c ON c.origen = CONCAT(a.id_cont, '-', b.tipo_codigo, '-', a.id_emp, '-', a.num_tdo ) WHERE a.id_cot = {$id}");
    }
    static function borrarEncyDetmovinv($id){
        DB::delete('f40091', ['id_movinv' => $id], 1000);
        return $r = DB::delete('f4009', ['id_movinv' => $id], 1);
    }
    static function create_express($id_fab){
        $r = DB::query("SELECT * FROM f4005 WHERE id_fab IN ($id_fab) AND status = 1");
        return $r;
    }
    static function listar_factura($id_emp){
        $r = DB::query("SELECT id_cot, concat(num_tdo, ' - ', id_cli, ' - ', nom_ent) cliente FROM f6003 INNER JOIN f0014 on f0014.id_ent = f6003.id_cli WHERE f6003.id_emp = {$id_emp} AND f6003.status = 1");
        return $r;
    }
    static function con_ventas(){
        $r = DB::query("SELECT * FROM f6002 WHERE id = 7");
        return $r[0];
    }
    static function detalle_venta($id){
        return $r = DB::query("SELECT a.id_cot, a.iva_prod, SUM(a.sub_total) monto, SUM(a.mon_iva) mon_iva FROM f60031 a WHERE a.id_cot = {$id} GROUP BY a.id_cot, a.iva_prod");
    }
    static function borrarDetCXCDocument($id){
        return $r = DB::delete('f60032', ['id_cot' => $id], 100);
    }
    static function tip_doc_fac($id){
        $r = DB::query("SELECT * FROM f4999 WHERE id_emp = {$id} AND status = 1");
        return $r[0];
    }
    static function set_cotiza($id, $data){
        return $r= DB::update('f4008', $data, ['id_cot' => $id]);
        return $r;
    }
    static function getNextNumer($id){
        $r = DB::query("SELECT proximo_tmoinv FROM f4006 WHERE id_tmoinv = {$id}");
        return $r[0];
    }
    static function guardar_mov_inv($data){
        return $r = DB::insert('f4009', $data);
    }
    static function guardar_Det_Movin($data){
         return $r = DB::insert('f40091', $data);
    }
    static function consult_mov_in_ppal($origen, $tipo_mov){
        $r = DB::query("SELECT * FROM f4009 WHERE origen = '". $origen ."' AND id_tmovinv = {$tipo_mov}");
        return $r;
    }
    static function borrarDetInvMov($id){
        DB::delete('f40091', ['id_movinv' => $id], 1000);
        return $r = DB::delete('f4009', ['id_movinv' => $id]);
    }
    static function setNextNumber_Mov_Inv($id){
        return $r = DB::query("UPDATE f4006 SET proximo_tmoinv = proximo_tmoinv + 1 WHERE id_tmoinv = {$id}");
    }
    static function consulta_prod($id){
        $r = DB::query("SELECT * FROM f4010 WHERE id_prod = {$id}");
        return $r;
    }
    static function aprobacion($data){
        return $r = DB::insert('fgenmsg', $data);
    }
    static function show_row_des($id){
        $r = DB::query("SELECT * FROM f7001 WHERE id = {$id}");
        return $r[0];
    }
    static function origen($id){
        $r = DB::query("SELECT a.num_tdo, b.tipo_codigo FROM f4008 a INNER JOIN f6001 b ON b.id_tdoc = a.id_tdo WHERE a.id_cot = {$id}");
        return $r[0];
    }
      static function consulta_vend($id_ent){
        $r = DB::query("SELECT DISTINCT a.id_vend, a.id_moneda, case when ISNULL(b.cod_diascre) THEN 0 ELSE b.cod_diascre END cod_diascre, a.nom_ent, a.handling_conver, a.id_alm, a.id_ubi, a.c_consig FROM f0014 a LEFT OUTER JOIN f6005 b on b.id_diascre = a.id_diascre WHERE a.id_ent = {$id_ent}");
        return $r[0];
    }
    static function getNumberMovim($origen){
        $r = DB::query("SELECT * FROM f4009 WHERE origen = '".$origen."'");
        return $r[0];
    }
     static function actualizar_mov_inv($id, $data){
        return $r= DB::update('f4009', $data, ['id_movinv' => $id]);
    }
      static function consulta_prod_consig($id, $id_cli, $id_alm, $id_ubi){
        $r = DB::query("SELECT * FROM f4010 WHERE id_prod = {$id} AND id_ent = {$id_cli} AND id_alm  = {$id_alm} AND id_ubi = {$id_ubi}");
        return $r;
    }
    static function consulta01($id){
        $r = DB::query("SELECT * FROM f4005 WHERE id_prod = {$id}");
        return $r[0];
    }
    static function listar_notas($id_emp, $id_cli, $fuente){
        if($fuente == 0){
            $sql0 = "SELECT a.id_cot, concat('Nota de Entrega: ', a.num_tdo, ' - Cliente: ', a.id_cli, ' - ', c.nom_ent) cliente FROM f6003 a INNER JOIN f4999 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli WHERE a.id_emp = {$id_emp} AND a.id_tdo = b.id_tdoc_not_no_fis AND a.invoice IS NULL AND a.id_cli = {$id_cli} AND a.status = 1";
        }elseif($fuente=="N"){
            $sql0 = "SELECT a.id_cot, concat('Nota de Entrega: ', a.num_tdo, ' - Cliente: ', a.id_cli, ' - ', c.nom_ent) cliente FROM f6003 a INNER JOIN f4999 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli WHERE a.id_emp = {$id_emp} AND a.id_tdo = b.id_tdoc_not AND a.invoice IS NULL AND a.id_cli = {$id_cli} AND a.status = 1";
        }else{
            $sql0 = "SELECT a.id_cot, concat('Nota de Entrega: ', a.num_tdo, ' - Cliente: ', a.id_cli, ' - ', c.nom_ent) cliente FROM f6003 a INNER JOIN f4999 b ON b.id_emp = a.id_emp INNER JOIN f0014 c ON c.id_ent = a.id_cli WHERE a.id_emp = {$id_emp} AND a.id_tdo = b.id_tdoc_not_no_fis AND a.invoice = '$fuente' AND a.id_cli = {$id_cli} AND a.status = 1";
        }
        
        $r = DB::query($sql0);
        return $r;
    }
    static function consultar_nota($id){
        $sql = "SELECT b.id_prod, c.nom_prod, b.can_det, 0 stock, b.uni_vta, b.pre_unit, b.pre_vta, b.iva_prod, b.sub_total, a.tasa_cambio FROM `f6003`  a INNER JOIN f60031 b ON b.id_cot = a.id_cot INNER JOIN f4005 c ON c.id_prod = b.id_prod WHERE a.id_cot = {$id}";
        $r = DB::query($sql);
        return $r;
    }
}