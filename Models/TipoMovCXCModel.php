<?php
class TipoMovCXCModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_tmocxc, b.nombre_emp, a.cod_tmocxc, a.des_tmocxc, CASE acc_tmocxc WHEN 'A' THEN 'Aumento' ELSE 'Disminución' END acc_tmocxc, CASE rec_tmocxc WHEN 'S' THEN 'Si' ELSE 'No' END rec_tmocxc, con_tmocxc, next_tmocxc, cod_cta, nombre_cta, cod_aux, nombre_aux, a.status FROM f6004 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f0010 c on c.id_cta = a.id_ctbcue LEFT OUTER JOIN f0009 d on d.id_aux = a.id_aux");
    }
    static function guardar($data){
        return $id = DB::insert('f6004', $data);
    }
     static function edit($id){
        $r = DB::query("SELECT * FROM f6004 WHERE id_tmocxc = {$id}");
        return $r[0];
    }
    static function show_row($id){
        $sql = "SELECT * FROM f6004 WHERE id_tmocxc = {$id}";
        $r = DB::query($sql);
        return $r[0];
    }
    static function datosCue($id){
        $r = DB::query("SELECT * FROM f0010 WHERE id_cta = {$id}");
        return $r[0];
    }
    static function actualizar($id, $data){
        return $r = DB::update('f6004', $data, ['id_tmocxc' => $id]);
    }
    static function destroy($id){
        return $r = DB::query("DELETE FROM f6004 WHERE id_tmocxc = {$id}");
    }
}