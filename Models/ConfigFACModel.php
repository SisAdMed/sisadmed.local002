<?php
class ConfigFACModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_config_fac, b.nombre_emp nom_empresa, a.status FROM f4999 a INNER JOIN f0011 b on b.id_emp = a.id_emp");
    }
    static function guardar($data){
        return $r = DB::insert('f4999', $data);
    }
     static function actualizar($data, $id){
        return $r = DB::update('f4999', $data, ['id_config_fac' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT a.id_config_fac, b.nombre_emp nom_empresa FROM f4999 a INNER JOIN f0011 b ON b.id_emp = a.id_emp WHERE id_config_fac = {$id}");
        return $r[0];
    }
    static function showrow($id){
        $r = DB::query("SELECT * FROM f4999 a INNER JOIN f0011 b on b.id_emp = a.id_emp WHERE id_config_fac = {$id}");
        return $r[0];
    }
    static function show_config_fac($id){
        $r = DB::query("SELECT * FROM f4999 WHERE id_config_fac = {$id}");
        return $r[0];
    }
    static function act_glo ($data){
        $fac_stock = $data['fac_stock'];
        $loc_pri_cot = $data['loc_pri_cot'];
        $locked_invoice = $data['locked_invoice'];
        $cot_stock = $data['cot_stock'];
        $loc_pri_inv = $data['loc_pri_inv'];
        $r = DB::query("UPDATE f4999 SET fac_stock = $fac_stock, cot_stock = $cot_stock, loc_pri_cot = $loc_pri_cot, locked_invoice = $locked_invoice, loc_pri_inv = $loc_pri_inv");
        return $r;
    }
}