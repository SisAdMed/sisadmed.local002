<?php
class CXCMovementModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        $sql = "SELECT a.id_movement, b.nombre_emp, c.cod_tmocxc, c.des_tmocxc, a.movem_number, a.fecha_comp, d.nom_ent, e.codigo_moneda, a.tasa_cambio, a.movem_amount, a.status FROM f6006 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f6004 c ON c.id_tmocxc = a.id_tmocxc INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f0005 e on e.id_moneda = a.id_moneda";
        return $r = DB::query($sql);
    }
    static function listar_tipos_mov($efecto){
        $filter = '';
        if($efecto){
            $efecto = " AND acc_tmocxc = 'D' ";
        }
        return $r = DB::query("SELECT * from f6004 WHERE status = 1" . $efecto);
    }
    static function val_tmo($id){
        return $r = DB::query("SELECT * FROM f6004 WHERE id_tmocxc = {$id}");
    }
     static function nextNumber($id_emp, $id){
        $nextNumber = DB::query("SELECT * FROM f6004 WHERE id_tmocxc = {$id} LIMIT 1");
        return $nextNumber[0];
    }
    static function guardar($data){
        return $r = DB::insert('f6006', $data);
    }
     static function setNextNumber($id_emp, $id, $data){
        return $r = DB::update("f6004", $data, ['id_tmocxc' => $id]);
    }
    static function actualizar($id, $data){
        return $r= DB::update('f6006', $data, ['id_movement' => $id]);
    }
    static function borrarDetCXCMovement($id){
        return $r = DB::delete('f60061', ['movem_id' => $id], 1000);
    }
     static function guardarDetMovement($data){
        return $r = DB::insert('f60061', $data);
    }
    static function updateDocBalance($id, $monto){
        return $r = DB::query("UPDATE f6003 SET sal_doc = (sal_doc - $monto)  WHERE id_cot = {$id}");
    }
    static function edit($id){
        return $r = DB::query("SELECT * FROM f6006 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f6004 c ON c.id_tmocxc = a.id_tmocxc INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f0005 e ON e.id_moneda = a.id_moneda WHERE a.id_movement = {$id}");
    }
    static function showrow($id){
        $r = DB::query("SELECT row_number() OVER (ORDER BY a.fecha_comp) item, a.id_emp, a.id_tmocxc, a.movem_number, a.id_cli, a.fecha_comp, a.id_moneda, a.tasa_cambio, a.movem_descrip, a.status, b.id_tdo, c.num_tdo, c.fecha_comp fecha_emi, c.fecha_venci, c.id_cot, b.monto_doc, b.mon_ret, b.num_ret, DATEDIFF(a.fecha_comp, c.fecha_comp) dias_venci, ROUND(c.mon_doc - (SELECT SUM(IFNULL(monto_doc, 0)) FROM f60061 WHERE id_cot = c.id_cot AND a.status = 1), 2) saldo, d.nom_ent, e.tipo_codigo, e.nom_tdoc, f.codigo_moneda, c.mon_doc, c.sal_doc, a.movem_origen FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f6003 c ON c.id_cot = b.id_cot INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f6001 e ON e.id_tdoc = c.id_tdo INNER JOIN f0005 f ON f.id_moneda = a.id_moneda WHERE a.id_movement = {$id}");
        return $r;
    }
    static function delete_row($id){
        $r = DB::delete('f60061', ['movem_id' => $id]);
        return $r = DB::delete('f6006', ['id_movement' => $id]);
    }
    static function print_mov($id){
        $sql = "SELECT row_number() OVER (ORDER BY c.id_cot) item, h.cod_emp, h.nombre_emp, h.rif_empresa, a.fecha_comp fecha_mov, d.tipo_codigo, d.nom_tdoc, c.num_tdo, c.fecha_comp, c.fecha_venci, e.codigo_moneda, a.tasa_cambio, b.monto_doc mon_doc, c.sal_doc, c.id_cot id_doc, b.mon_ret, b.num_ret, f.cod_tmocxc, f.des_tmocxc, a.movem_number, a.tasa_cambio, g.nom_ent, h.logo, a.movem_descrip, CONCAT(i.name_user, ' ', i.last_user) user_create FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f6003 c ON c.id_cot = b.id_cot INNER JOIN f6001 d ON d.id_tdoc = c.id_tdo INNER JOIN f0005 e ON e.id_moneda = a.id_moneda INNER JOIN f6004 f ON f.id_tmocxc = a.id_tmocxc INNER JOIN f0014 g ON g.id_ent = a.id_cli INNER JOIN f0011 h ON h.id_emp = a.id_emp INNER JOIN f0002 i ON i.id_user = a.create_user WHERE a.id_movement = {$id}";
        return $r = DB::query($sql);
    }
    static function show_row_det($id)  {
        //$sql = "SELECT row_number() OVER (ORDER BY a.fecha_comp) item, a.id_emp, a.id_tmocxc, a.movem_number, a.id_cli, a.fecha_comp, a.id_moneda, a.tasa_cambio, a.movem_descrip, a.status, b.id_tdo, c.num_tdo, c.fecha_comp fecha_emi, c.fecha_venci, c.id_cot, b.monto_doc, b.mon_ret, b.num_ret, DATEDIFF(a.fecha_comp, c.fecha_comp) dias_venci, ROUND(c.mon_doc - (SELECT SUM(IFNULL(monto_doc, 0)) FROM f60061 WHERE id_cot = c.id_cot AND a.status = 1), 2) saldo, d.nom_ent, e.tipo_codigo, e.nom_tdoc, f.codigo_moneda, c.mon_doc, c.sal_doc, a.movem_origen FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f6003 c ON c.id_cot = b.id_cot INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f6001 e ON e.id_tdoc = c.id_tdo INNER JOIN f0005 f ON f.id_moneda = a.id_moneda WHERE a.movem_origen = '$id'";
        $sql = "SELECT row_number() OVER (ORDER BY a.fecha_comp) item, a.id_emp, a.id_tmocxc, a.movem_number, a.id_cli, a.fecha_comp, a.id_moneda, c.tasa_cambio, a.movem_descrip, a.status, b.id_tdo, c.num_tdo, c.fecha_comp fecha_emi, c.fecha_venci, c.id_cot,  b.num_ret, DATEDIFF(a.fecha_comp, c.fecha_comp) dias_venci,  d.nom_ent, e.tipo_codigo, e.nom_tdoc, f.codigo_moneda, a.movem_origen, ROUND(CASE WHEN a.id_moneda = h.id_moneda THEN b.monto_doc ELSE (b.monto_doc * c.tasa_cambio) END, 2) mont_doc_dom, ROUND(CASE WHEN a.id_moneda = h.id_moneda THEN b.mon_ret ELSE (b.mon_ret * c.tasa_cambio) END, 2) mon_ret_dom, ROUND(CASE WHEN a.id_moneda = h.id_moneda THEN c.mon_doc ELSE (c.mon_doc * c.tasa_cambio) END, 2) mon_doc_dom, ROUND(CASE WHEN a.id_moneda = h.id_moneda THEN c.sal_doc ELSE (c.sal_doc * c.tasa_cambio) END, 2) sal_doc_dom, ROUND(CASE WHEN a.id_moneda != h.id_moneda THEN b.monto_doc ELSE (b.monto_doc * c.tasa_cambio) END, 2) mont_doc_for, ROUND(CASE WHEN a.id_moneda != h.id_moneda THEN b.mon_ret ELSE (b.mon_ret * c.tasa_cambio) END, 2) mon_ret_for, ROUND(CASE WHEN a.id_moneda != h.id_moneda THEN c.mon_doc ELSE (c.mon_doc * c.tasa_cambio) END, 2) mon_doc_for, ROUND(CASE WHEN a.id_moneda != h.id_moneda THEN c.sal_doc ELSE (c.sal_doc * c.tasa_cambio) END, 2) sal_doc_for FROM f6006 a INNER JOIN f60061 b ON b.movem_id = a.id_movement INNER JOIN f6003 c ON c.id_cot = b.id_cot INNER JOIN f0014 d ON d.id_ent = a.id_cli INNER JOIN f6001 e ON e.id_tdoc = c.id_tdo INNER JOIN f0005 f ON f.id_moneda = a.id_moneda INNER JOIN f0011 h ON h.id_emp = a.id_emp WHERE a.movem_origen = '$id'";
        $r = DB::query($sql);
        return $r;
    }
}