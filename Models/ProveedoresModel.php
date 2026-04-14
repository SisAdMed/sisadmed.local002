<?php
class ProveedoresModel extends DB{
    public function __construct() {
        parent::__construct();
    }
    static function all() {
        $sql = "SELECT e.id_ent id_ent, e.rif_ent rif_ent, e.nom_ent nom_ent, p.nombre_pais nombre_pais, d.nombre_edo nombre_edo, e.status status FROM f0014 e INNER JOIN f0004 p ON p.id_pais = e.id_pais INNER JOIN f00041 d ON d.id_edo = e.id_edo INNER JOIN f00042 c ON c.id_ciudad = e.id_ciudad WHERE e.tip_ent = 'P' ORDER BY e.nom_ent";
        $r = DB::query($sql);
        return $r;
    }
    static function listar_paises(){
        return $r = DB::query("SELECT * FROM f0004 ORDER BY nombre_pais");
    }
    static function listar_estados($id_pais){
        return $r = DB::query("SELECT * FROM f00041 WHERE id_pais = {$id_pais} AND status = 1");
    }
    static function listar_ciudades($id){
        return $r = DB::query("SELECT * FROM f00042 WHERE id_edo = {$id} AND status = 1");
    }
//Revisar
    static function guardar($data){
        return $id = DB::insert('f0014', $data);
    }
    static function actualizar($id, $data){
        return $res = DB::update('f0014', $data, ['id_ent' => $id]);
    }
    static function edit($id){
        $r = DB::query("SELECT e.id_ent id_ent, e.id_pais id_pais, e.id_edo id_edo, e.id_ciudad id_ciudad, e.rif_ent rif_ent, e.nom_ent nom_ent, e.cor_ent cor_ent, e.postal_ent postal_ent, e.dir_ent dir_ent, e.status as status, con.nom_con nom_con, con.ape_con ape_con, con.email_con email_con, con.id_pre id_pre, con.num_tel_con num_tel_con, con.id_dep id_dep, pre.id_cod_pre id_cod_pre, dep.nom_dep nom_dep, e.id_diascre, dicr.cod_diascre FROM f0014 e INNER JOIN f0004 p ON p.id_pais = e.id_pais
            INNER JOIN f00041 d ON d.id_edo = e.id_edo INNER JOIN f00042 c ON c.id_ciudad = e.id_ciudad LEFT JOIN f00141 con ON con.id_ent = e.id_ent LEFT JOIN f0018 pre ON pre.id_pre = con.id_pre LEFT JOIN f00142 dep ON dep.id_dep = con.id_dep LEFT JOIN f6005 dicr on dicr.id_diascre = e.id_diascre WHERE e.id_ent = {$id}");
        return $r;
    }
    static function show_row($id){
        $r = DB::query("SELECT e.id_ent id_ent, e.id_pais id_pais, e.id_edo id_edo, e.id_ciudad id_ciudad, e.rif_ent rif_ent, e.nom_ent nom_ent, e.cor_ent cor_ent, e.postal_ent postal_ent, e.dir_ent dir_ent, e.status as status, con.nom_con nom_con, con.ape_con ape_con, con.email_con email_con, con.id_pre id_pre, con.num_tel_con num_tel_con, con.id_dep id_dep, pre.id_cod_pre id_cod_pre, dep.nom_dep nom_dep, e.id_diascre, dicr.cod_diascre, e.contr_esp, e.id_por_ret_iva FROM f0014 e INNER JOIN f0004 p ON p.id_pais = e.id_pais INNER JOIN f00041 d ON d.id_edo = e.id_edo INNER JOIN f00042 c ON c.id_ciudad = e.id_ciudad LEFT JOIN f00141 con ON con.id_ent = e.id_ent LEFT JOIN f0018 pre ON pre.id_pre = con.id_pre LEFT JOIN f00142 dep ON dep.id_dep = con.id_dep LEFT JOIN f6005 dicr on dicr.id_diascre = e.id_diascre WHERE e.id_ent =  {$id}");
        return $r;
    }
    static function borrarimg($id){
        return $id = DB::query("DELETE FROM f40051 WHERE id_ent = {$id}");
    }
    static function guardarimg($data){
        return $id = DB::insert('f40051', $data);
    }
    static function showImg($id){
        return $r = DB::query("SELECT * FROM f40051 WHERE id_ent = {$id}");
    }
    static function listar_vendedores(){
        return $r = DB::query("SELECT * FROM f0016 WHERE status = 1");
    }
    static function listar_Proveedores($tip_ent, $id_emp){
        $r = DB::query("SELECT * FROM f0014 WHERE id_emp = {$id_emp} AND tip_ent = '". $tip_ent ."' AND status = 1");
        return $r;
    }
    static function consulta_dias_cre_provee($id_ent){
        $r = DB::query("SELECT DISTINCT a.nom_ent, case when ISNULL(b.cod_diascre) THEN 0 ELSE b.cod_diascre END cod_diascre FROM f0014 a LEFT OUTER JOIN f6005 b on b.id_diascre = a.id_diascre WHERE a.id_ent = {$id_ent}");
        return $r[0];
    }
    static function listar_codigos_area(){
        return $r = DB::query("SELECT * FROM f0018 WHERE status = 1");
    }
    static function listar_dpto_ent(){
        return $r = DB::query("SELECT * FROM f00142 WHERE status = 1");
    }
    static function borrar_contactos($id_ent){
        return $r = DB::query("DELETE FROM f00141 WHERE id_ent = {$id_ent}");
    }
    static function guardar_contactos($id_ent, $data){
        return $id = DB::insert('f00141', $data);
    }
    static function destroy($id){
        $id = DB::delete('f0014', ['id_ent' => $id], 1);
        if($id){
            $id2 = DB::query("DELETE FROM f00141 WHERE id_ent = {$id}");
        }
        return $id;
    }
    static function consulta_motivo($id){
        return $r = DB::query("SELECT b.id_fab, b.adicional FROM f0014 a INNER JOIN f0012a1 b on b.id_motcam = a.id_motcam WHERE a.id_ent = {$id}");
    }
    public static function tot_cli(){
        $r = DB::query("SELECT count(*) tot_cli FROM f0014 WHERE tip_ent = 'C'");
        return $r[0];
    }
    ////Llenar combp de Status de Entidad (ProveedoresModels)
    public static function statusEntidad($id){
        return $r = DB::query("SELECT * FROM f0015");
    }
    //Llenar combo Días de Crédito
    static function listar_dias_credito(){
        return $r = DB::query("SELECT * FROM f6005 WHERE status = 1");
    }
     //Llenar listado entidad modal
    static function listar_entidad_modal($id, $tipo){
        $r = DB::query("SELECT a.id_ent, a.rif_ent, a.nom_ent, '' nombre_zona FROM f0014 a WHERE a.tip_ent = '".$tipo."'");
        return $r;
    }
}