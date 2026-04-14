<?php
class VatTaxModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f0017");
    }
    static function guardar($data){
        return $r = DB::insert('f0017', $data);
    }
     static function actualizar($data, $id){
        return $r = DB::update('f0017', $data, ['id_iva' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0017 WHERE id_iva = {$id}");
        return $r[0];
    }
    static function showrow($id){
        return $r = DB::query("SELECT * FROM f0017 WHERE id_iva = {$id}");
    }
    static function ratevatTax($fecha, $vatTax){
        $sql = "SELECT * FROM f0017 WHERE cod_iva = '".$vatTax."' AND fec_iva <= '".$fecha ."' AND STATUS = 1 LIMIT 1";
        $rows = DB::query($sql);
        return $rows;
    }
}