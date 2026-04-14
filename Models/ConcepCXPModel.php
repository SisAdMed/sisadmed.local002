<?php
class ConcepCXPModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT a.id, a.codigo_con, a.nombre_con, a.agrupa_con, IFNULL(CONCAT(c.cod_cta, ' - ', c.nombre_cta), ' ') id_ctb, IFNULL(CONCAT(d.cod_aux, ' - ', d.nombre_aux), ' ') id_aux, a.status, e.descrip, IFNULL(e.por_reten, 0) por_reten FROM f3003 a LEFT OUTER JOIN f0010 c ON c.id_cta = a.id_ctb LEFT OUTER JOIN f0009 d on d.id_aux = a.id_aux LEFT OUTER JOIN f0021 e ON e.id = a.id_retislr ORDER BY a.codigo_con;");
    }
    static function guardar($data){
        return $id = DB::insert('f3003', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f3003', $data, ['id' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f3003 WHERE id = {$id}");
        return $r[0];
    }
    static function editar_row($id){
        $r = DB::query("SELECT a.codigo_con, a.nombre_con, a.id_ctb, a.agrupa_con, CASE WHEN ISNULL(a.id_ctb) THEN '' ELSE CONCAT(c.cod_cta, ' - ', c.nombre_cta) END nombre_cta, a.id_aux, CASE WHEN ISNULL(a.id_aux) THEN '' ELSE CONCAT(d.cod_aux, ' - ', d.nombre_aux) END nombre_aux, a.status, c.aux_cta, a.id_retislr FROM f3003 a LEFT OUTER JOIN f0010 c ON c.id_cta = a.id_ctb LEFT OUTER join f0009 d ON d.id_aux = a.id_aux  LEFT OUTER JOIN f0021 e ON e.id = a.id_retislr WHERE a.id = {$id}");
        return $r[0];
    }
    static function destroy($id){
        $sql = "SELECT COUNT(*) tot_row FROM f3003 WHERE codigo_con LIKE '$id%'";
        $r = DB::query($sql);
        if($r[0]['tot_row'] == 1){
            return $r = DB::delete('f3003', ['codigo_con' => $id]);
        }
    }
    static function datosCue($id){
        $r = DB::query("SELECT * FROM f0010 WHERE id_cta = {$id}");
        return $r[0];
    }
    static function listar_conceptos_CXP(){
        return $r = DB::query("SELECT a.id, a.codigo_con, a.nombre_con, a.agrupa_con, CONCAT(c.cod_cta, ' - ', c.nombre_cta) id_ctb, CONCAT(d.cod_aux, ' - ', d.nombre_aux) id_aux, a.status, e.descrip, e.por_reten FROM f3003 a LEFT OUTER JOIN f0010 c ON c.id_cta = a.id_ctb LEFT OUTER JOIN f0009 d on d.id_aux = a.id_aux LEFT OUTER JOIN f0021 e ON e.id = a.id_retislr WHERE a.status = 1 AND a.agrupa_con = 'N' ORDER BY a.nombre_con ");
    }
    static function listar_conceptos_CXP_EXC(){
        return $r = DB::query("SELECT a.id, a.codigo_con, a.nombre_con, a.agrupa_con, CONCAT(c.cod_cta, ' - ', c.nombre_cta) id_ctb, CONCAT(d.cod_aux, ' - ', d.nombre_aux) id_aux, a.status, e.descrip, e.por_reten, IFNULL(f.id_concept, 0) active  FROM f3003 a LEFT OUTER JOIN f0010 c ON c.id_cta = a.id_ctb LEFT OUTER JOIN f0009 d on d.id_aux = a.id_aux LEFT OUTER JOIN f0021 e ON e.id = a.id_retislr LEFT OUTER JOIN f4015 f on f.module = 'P' AND f.id_concept = a.id WHERE a.status = 1 AND a.agrupa_con = 'N' ORDER BY a.nombre_con ");
    }
    static function ret_islr($id){
        $sql = "SELECT a.id_retislr, b.minimo, b.maximo, b.por_reten, b.por_imp_suj_ret, b.fac_reten FROM f3003 a INNER JOIN f0021 b on b.id = a.id_retislr WHERE a.id = {$id}";
        $r = DB::query($sql);
        if($r){
            return $r[0];
        }else{
            return false;
        }        
    }
    static function val_con($id){
        $id = substr($id, 0, -1);
        $sql = "SELECT * FROM f3003 WHERE codigo_con = '$id' AND agrupa_con = 'S' LIMIT 1";
        $r = DB::query($sql);
        return $r;
    }
}