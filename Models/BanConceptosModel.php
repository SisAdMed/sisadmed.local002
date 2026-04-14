<?php
class BanConceptosModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id_bancon, a.cod_bancon, a.nom_bancon, a.agr_bancon, CONCAT(d.cod_cta, ' - ', d.nombre_cta) cod_cta, CONCAT(e.cod_aux, ' - ', e.nombre_aux) cod_aux, a.status FROM f5005 a  LEFT OUTER JOIN f0010 d ON d.id_cta = a.id_ctb LEFT OUTER JOIN f0009 e ON e.id_aux = a.id_aux;");
    }
    static function guardar($data){
        return $r = DB::insert('f5005', $data);
    }
    static function destroy($id){
        return $r = DB::query("DELETE FROM f5005 WHERE id_bancon = {$id}");
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f5005 WHERE id_bancon = {$id}");
        return $r[0];
    }
    static function showrow($id){ 
        return $r = DB::query("SELECT a.cod_bancon, a.nom_bancon, a.agr_bancon, a.id_bantdo, a.id_ctb, CONCAT(c.cod_cta, ' - ', c.nombre_cta) nombre_cta, CASE WHEN ISNULL(a.id_aux) THEN ' ' ELSE a.id_aux END id_aux, CASE WHEN ISNULL(a.id_aux) THEN ' ' ELSE CONCAT( d.cod_aux, ' - ' , d.nombre_aux)  END nombre_aux, a.status, a.id_retislr FROM f5005 a LEFT OUTER JOIN f0010 c ON c.id_cta = a.id_ctb LEFT OUTER JOIN f0009 d ON d.id_aux = a.id_aux WHERE a.id_bancon = {$id}");
    }
    static function actualizar($id, $data){
        return DB::update('f5005', $data, ['id_bancon' => $id]);
    }
    static function listar_conceptos(){
        return $r = DB::query("SELECT * FROM f5005 WHERE status = 1 AND agr_bancon = 'N' ORDER BY nom_bancon");
    }
    static function listar_conceptos_exc(){
        $sql = "SELECT a.id_bancon, a.nom_bancon, IFNULL(b.id_concept, 0) active FROM f5005 a LEFT OUTER JOIN f4015 b ON b.module = 'B' AND b.id_concept = a.id_bancon WHERE a.status = 1 AND a.agr_bancon = 'N' ORDER BY a.nom_bancon";
        return $r = DB::query($sql);
    }
    static function nom_con_ban($id){
        $r = DB::query("SELECT * FROM f5005 a INNER JOIN f0010 b ON b.id_cta = a.id_ctb WHERE a.id_bancon = {$id}");
        return $r[0];
    }
}