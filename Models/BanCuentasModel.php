<?php
class BanCuentasModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_bancue, b.nombre_emp, c.cod_banco, c.nombre_banco, a.cuenta_bancue, CONCAT(d.cod_cta, ' - ', d.nombre_cta) cod_cta, CONCAT(e.cod_aux, ' - ', e.nombre_aux) cod_aux, a.status FROM f5004 a INNER JOIN f0011 b ON b.id_emp = a.id_emp INNER JOIN f5003 c ON c.id_banco = a.id_banco LEFT OUTER JOIN f0010 d ON d.id_cta = a.id_ctb LEFT OUTER JOIN f0009 e ON e.id_aux = a.id_aux;");
    }
    static function guardar($data){
        return $id = DB::insert('f5004', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f5004', $data, ['id_bancue' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f5004 WHERE id_bancue = {$id}");
        return $r[0];
    }
    static function editar_row($id){
        $r = DB::query("SELECT * FROM f5004 WHERE id_bancue = {$id}");
        return $r[0];
    }
    static function destroy($id){
        return $r = DB::query("DELETE FROM f5004 WHERE id_bancue = {$id}");
    }
    static function showrow($id){
        return $r = DB::query("SELECT a.id_emp, a.id_banco, b.cod_banco, a.suc_bancue, a.con_bancue, a.cue_bancue, a.id_ctb, CONCAT(c.cod_cta, ' - ', c.nombre_cta) nombre_cta, CASE WHEN ISNULL(a.id_aux) THEN ' ' ELSE a.id_aux END id_aux, CASE WHEN ISNULL(a.id_aux) THEN ' ' ELSE CONCAT( d.cod_aux, ' - ' , d.nombre_aux) END nombre_aux, a.status FROM f5004 a INNER JOIN f0011 e ON e.id_emp = a.id_emp INNER JOIN f5003 b ON b.id_banco = a.id_banco INNER JOIN f0010 c ON c.id_cta = a.id_ctb LEFT OUTER JOIN f0009 d ON d.id_aux = a.id_aux WHERE a.id_bancue = {$id}");
    }
    static function listar_bancos(){
        return $r = DB::query("SELECT * FROM f5003 WHERE status = 1");
    }
    static function val_cod_banco($id){
        return $r = DB::query("SELECT * FROM f5003 WHERE id_banco = {$id}");
    }
    static function datosCue($id){
        $r = DB::query("SELECT * FROM f0010 WHERE id_cta = {$id}");
        return $r[0];
    }
    static function listar_cuentas_ban($id_emp){
        $r = DB::query("SELECT a.id_bancue, concat(b.nombre_banco, ' - ', a.cuenta_bancue) cuenta_bancue FROM f5004 a INNER JOIN f5003 b on b.id_banco = a.id_banco WHERE a.id_emp = {$id_emp} AND a.status = 1");
        return $r;
    }
}