<?php
class BanTipoMovModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_bantmo, a.cod_bantmo, a.nom_bantmo, a.acc_bantmo, a.con_bantmo, a.efe_bantmo, a.status FROM f5002 a");
    }
    static function guardar($data){
        return $id = DB::insert('f5002', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f5002', $data, ['id_bantmo' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f5002 WHERE id_bantmo = {$id}");
        return $r[0];
    }
    static function editar_row($id){
        $r = DB::query("SELECT * FROM f5002 WHERE id_bantmo = {$id}");
        return $r[0];
    }
    static function destroy($id){
        return $r = DB::query("DELETE FROM f5002 WHERE id_bantmo = {$id}");
    }
    static function showrow($id, $id_emp){
        return $r = DB::query("SELECT a.cod_bantmo, a.nom_bantmo, a.acc_bantmo, a.idb_bantmo, a.con_bantmo, a.cash_bantmo, a.che_bantmo, a.tra_bantmo, a.efe_bantmo, a.id_cxtdo, a.dev_bantmo, a.rec_bantmo, a.id_cxcon, a.status, a.id_cxtmo, c.id_bancon, CONCAT(c.cod_bancon, ' - ', c.nom_bancon) nom_bancon, b.id_bancon_RETIVA,  CONCAT(d.cod_bancon, ' - ', d.nom_bancon) nom_bancon_RETIVA FROM f5002 a LEFT OUTER JOIN f5999 b ON b.id_emp = {$id_emp} LEFT OUTER JOIN f5005 c ON (c.id_bancon = b.id_bancon_CXC AND a.efe_bantmo = 'C' )  OR (c.id_bancon = b.id_bancon_CXP AND a.efe_bantmo = 'P') LEFT OUTER JOIN f5005 d ON d.id_bancon = b.id_bancon_RETIVA WHERE a.id_bantmo = {$id}");
    }
    static function listar_tipomov_bancos(){
        return $r = DB::query("SELECT * FROM f5002 WHERE status = 1 ORDER BY nom_bantmo");
    }
}