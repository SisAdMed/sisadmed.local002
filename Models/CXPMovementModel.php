<?php
class CXPMovementModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f3008 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f3002 c ON c.id_tmocxc = a.id_tmocxp INNER JOIN f0014 d ON d.id_ent = a.id_ent INNER JOIN f0005 e on e.id_moneda = a.id_moneda");
    }
    static function listar_tipos_mov($efecto){
        $filter = '';
        if($efecto){
            $efecto = " AND acc_tmocxc = 'D' ";
        }
        return $r = DB::query("SELECT * from f3002 WHERE status = 1" . $efecto);
    }
    static function val_tmo($id){
        return $r = DB::query("SELECT * FROM f3002 WHERE id_tmocxc = {$id}");
    }
     static function nextNumber($id_emp, $id){
        $nextNumber = DB::query("SELECT * FROM f3002 WHERE id_emp = {$id_emp} AND id_tmocxc = {$id} LIMIT 1");
        return $nextNumber[0];
    }
    static function guardar($data){
        return $r = DB::insert('f3008', $data);
    }
     static function setNextNumber($id_emp, $id, $data){
        return $r = DB::update("f3002", $data, ['id_tmocxc' => $id]);
    }
    static function actualizar($id, $data){
        return $r= DB::update('f3008', $data, ['id_movement' => $id]);
    }
    static function borrarDetCXPMovement($id){
        return $r = DB::delete('f30081', ['movem_id' => $id]);
    }
     static function guardarDetMovement($data){
        return $r = DB::insert('f30081', $data);
    }
    static function updateDocBalance($id, $monto){
        return $r = DB::query("UPDATE f3004 SET sal_doc = sal_doc - '". $monto ."' WHERE id_cot = {$id}");
    }
    static function edit($id){
        return $r = DB::query("SELECT * FROM f3008 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f3002 c ON c.id_tmocxc = a.id_tmocxp INNER JOIN f0014 d ON d.id_ent = a.id_ent INNER JOIN f0005 e ON e.id_moneda = a.id_moneda WHERE a.id_movement = {$id}");
    }
    static function showrow($id){
        $r = DB::query("SELECT a.id_emp, a.id_tmocxp, a.movem_number, a.id_ent, a.fecha_comp, a.id_moneda, a.tasa_cambio, a.movem_descrip, a.status, b.id_tdo, c.num_tdo, c.fecha_comp fecha_emi, c.fecha_venci, c.id_cot, b.monto_doc, DATEDIFF(a.fecha_comp, c.fecha_comp) dias_venci, ROUND(c.mon_doc - (SELECT SUM(IFNULL(monto_doc, 0)) FROM f30081 WHERE id_cot = c.id_cot AND a.status = 1), 2) saldo, d.tipo_codigo, d.nom_tdoc, e.codigo_moneda, c.mon_doc, c.sal_doc, f.id_ent id_cli, f.nom_ent, a.movem_origen FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement INNER JOIN f3004 c ON c.id_cot = b.id_cot INNER JOIN f3001 d ON d.id_tdoc = c.id_tdo INNER JOIN f0005 e ON e.id_moneda = a.id_moneda INNER JOIN f0014 f ON f.id_ent = a.id_ent WHERE a.id_movement = {$id}");
        return $r;
    }
    static function delete_row($id){
        $r = DB::delete('f30081', ['movem_id' => $id]);
        $r = DB::delete('f3008', ['id_movement' => $id] );
        return $r;
    }
    static function print_movement($id){
        return $r = DB::query("SELECT c.id_emp, c.nombre_emp, c.rif_empresa, a.fecha_comp, d.des_tmocxc, a.movem_number, a.id_ent, e.nom_ent, f.tipo_codigo, f.nom_tdoc, b.monto_doc, g.mon_doc - (SELECT SUM(IFNULL(monto_doc, 0)) FROM f30081 WHERE id_cot = g.id_cot AND id_cxcmovendet != b.id_cxcmovendet) as sal_doc, CONCAT(h.name_user, ' ', h.last_user) creado, IFNULL(CONCAT(i.name_user, ' ', i.last_user), ' ') modificado, c.logo, g.num_tdo, d.cod_tmocxc, a.movem_descrip FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement INNER JOIN f0011 c ON c.id_emp = a.id_emp INNER JOIN f3002 d ON d.id_tmocxc = a.id_tmocxp INNER JOIN f0014 e ON e.id_ent = a.id_ent INNER JOIN f3001 f ON f.id_tdoc = b.id_tdo INNER JOIN f3004 g ON g.id_cot = b.id_cot INNER JOIN f0002 h ON h.id_user = a.create_user LEFT OUTER JOIN f0002 i ON i.id_user = a.modify_user WHERE a.id_movement = {$id}");
    }
    static function show_row_det($id){
        $sql = "SELECT row_number() OVER (ORDER BY a.fecha_comp) item, c.id_cot, e.tipo_codigo, e.nom_tdoc, c.num_tdo, c.fecha_comp fecha_emi, c.fecha_venci, f.codigo_moneda, f.id_moneda, a.tasa_cambio, ROUND(CASE WHEN a.id_moneda = g.id_moneda THEN c.mon_doc ELSE (c.mon_doc * a.tasa_cambio) END, 2) mon_doc_dom, ROUND(CASE WHEN a.id_moneda = g.id_moneda THEN c.sal_doc ELSE (c.sal_doc * a.tasa_cambio) END, 2) sal_doc_dom, ROUND(CASE WHEN a.id_moneda = g.id_moneda THEN (c.mon_doc / GetExchangeRateVal(c.fecha_comp, 2)) ELSE c.mon_doc END, 2) mon_doc_for, ROUND(CASE WHEN a.id_moneda = g.id_moneda THEN (c.sal_doc / GetExchangeRateVal(c.fecha_comp, 2)) ELSE c.sal_doc END, 2) sal_doc_for, ROUND(CASE WHEN a.id_moneda = g.id_moneda THEN b.monto_doc ELSE (b.monto_doc * a.tasa_cambio) END, 2) mon_can_dom, ROUND(CASE WHEN a.id_moneda = g.id_moneda THEN (b.monto_doc / GetExchangeRateVal(c.fecha_comp, 2)) ELSE b.monto_doc END, 2) mon_can_for FROM f3008 a INNER JOIN f30081 b ON b.movem_id = a.id_movement INNER JOIN f3004 c ON c.id_cot = b.id_cot INNER JOIN f0014 d ON d.id_ent = c.id_cli INNER JOIN f3001 e ON e.id_tdoc = c.id_tdo INNER JOIN f0005 f ON f.id_moneda = c.id_moneda INNER JOIN f0011 g ON g.id_emp = a.id_emp WHERE a.movem_origen = '$id'";
        return $r = DB::query($sql);
    }
}