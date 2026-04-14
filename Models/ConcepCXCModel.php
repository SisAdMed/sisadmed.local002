<?php
class ConcepCXCModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id, b.nombre_emp, a.codigo_con, a.nombre_con, a.agrupa_con, CONCAT(c.cod_cta, ' - ', c.nombre_cta) id_ctbcue, CONCAT(d.cod_aux, ' - ', d.nombre_aux) id_ctbaux, a.status FROM f6002 a INNER JOIN f0011 b ON b.id_emp = a.id_emp LEFT OUTER JOIN f0010 c ON c.id_cta = a.id_ctbcue LEFT OUTER JOIN f0009 d on d.id_aux = a.id_ctbaux");
    }
    static function guardar($data){
        return $id = DB::insert('f6002', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f6002', $data, ['id' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f6002 WHERE id = {$id}");
        return $r[0];
    }
    static function show_row($id){
        $r = DB::query("SELECT a.id_emp, a.codigo_con, a.nombre_con, a.id_ctbcue, a.agrupa_con, CASE WHEN ISNULL(a.id_ctbcue) THEN '' ELSE CONCAT(c.cod_cta, ' - ', c.nombre_cta) END nombre_cta, a.id_ctbaux, CASE WHEN ISNULL(a.id_ctbaux) THEN '' ELSE CONCAT(d.cod_aux, ' - ', d.nombre_aux) END nombre_aux, a.status, c.aux_cta FROM f6002 a INNER JOIN f0011 b ON b.id_emp = a.id_emp LEFT OUTER JOIN f0010 c ON c.id_cta = a.id_ctbcue LEFT OUTER join f0009 d ON d.id_aux = a.id_ctbaux WHERE a.id = {$id}");
        return $r[0];
    }
    static function destroy($id){
        $sql = "SELECT COUNT(*) tot_row FROM f6002 WHERE codigo_con LIKE '$id%'";
        $r = DB::query($sql);
        if ($r[0]['tot_row'] == 1) {
            return $r = DB::delete('f6002', ['codigo_con' => $id]);
        }
        return false;
    }
    static function datosCue($id){
        $r = DB::query("SELECT * FROM f0010 WHERE id_cta = {$id}");
        return $r[0];
    }
    static function listar_conceptos_CXC($id_emp){
        return $r = DB::query("SELECT * FROM f6002 WHERE status = 1 AND agrupa_con = 'N'");
    }
    static function val_con($id){
        $id = substr($id, 0, -1);
        $sql = "SELECT * FROM f6002 WHERE codigo_con = '$id' AND agrupa_con = 'S' LIMIT 1";
        $r = DB::query($sql);
        return $r;
    }
}