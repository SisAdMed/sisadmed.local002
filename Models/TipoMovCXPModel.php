<?php
class TipoMovCXPModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_tmocxc, b.nombre_emp, a.cod_tmocxc, a.des_tmocxc, CASE acc_tmocxc WHEN 'A' THEN 'Aumento' ELSE 'Disminución' END acc_tmocxc, CASE rec_tmocxc WHEN 'S' THEN 'Si' ELSE 'No' END rec_tmocxc, con_tmocxc, next_tmocxc, CONCAT(cod_cta, ' - ', nombre_cta) nombre_cta, CONCAT(cod_aux, ' - ', nombre_aux) nombre_aux, a.status FROM f3002 a INNER JOIN f0011 b on b.id_emp = a.id_emp INNER JOIN f0010 c on c.id_cta = a.id_ctbcue LEFT OUTER JOIN f0009 d on d.id_aux = a.id_aux");
    }
    static function guardar($data){
        return $id = DB::insert('f3002', $data);
    }
     static function edit($id){
        $r = DB::query("SELECT * FROM f3002 WHERE id_tmocxc = {$id}");
        return $r[0];
    }
    static function show_row($id){
        $r = DB::query("SELECT * FROM f3002 WHERE id_tmocxc = {$id}");
        return $r[0];
    }
    static function datosCue($id){
        $r = DB::query("SELECT * FROM f0010 WHERE id_cta = {$id}");
        return $r[0];
    }
    static function actualizar($id, $data){
        return $r = DB::update('f3002', $data, ['id_tmocxc' => $id]);
    }
    static function destroy($id){
        return $r = DB::query("DELETE FROM f3002 WHERE id_tmocxc = {$id}");
    }
     static function listar_tipos_mov($efecto){
        $filter = '';
        if($efecto){
            $efecto = " AND acc_tmocxc = 'D' ";
        }
        return $r = DB::query("SELECT * from f3002 WHERE status = 1" . $efecto);
    }
}