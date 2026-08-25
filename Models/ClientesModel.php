<?php
class ClientesModel extends DB{
    public function __construct() {
        parent::__construct();
    }
    static function all() {
        return $r = DB::query("SELECT e.id_ent id_ent, e.rif_ent rif_ent, e.nom_ent nom_ent, z.nombre_zona nombre_zona, CONCAT(v.nom_vend, ' ', v.ape_vend) vendedor, p.nombre_pais nombre_pais, d.nombre_edo nombre_edo, em.nombre_emp nombre_emp, m.nom_motcam nom_motcam, mo.codigo_moneda codigo_moneda, e.status status FROM f0014 e LEFT OUTER JOIN f0004 p ON p.id_pais = e.id_pais LEFT OUTER JOIN f00041 d ON d.id_edo = e.id_edo LEFT OUTER JOIN f00042 c ON c.id_ciudad = e.id_ciudad LEFT OUTER JOIN f0003 z ON z.id_zona = e.id_zona LEFT OUTER JOIN f0016 v ON v.id_vend = e.id_vend LEFT JOIN f0011 em ON em.id_emp = e.id_emp LEFT JOIN f0012a m ON m.id_motcam = e.id_motcam LEFT JOIN f0005 mo ON mo.id_moneda = e.id_moneda WHERE e.tip_ent = 'C'");
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
        $r = DB::query("SELECT * FROM view_entidad_edit WHERE id_ent = {$id}");
        return $r;
    }
    static function show_row($id){
        $sql = "SELECT e.id_ent id_ent, e.id_pais id_pais, e.id_edo id_edo, e.id_ciudad id_ciudad, e.id_vend id_vend, e.id_zona id_zona, e.id_motcam id_motcam, e.id_emp id_emp, e.id_moneda id_moneda, e.rif_ent rif_ent, e.nom_ent nom_ent, e.cor_ent cor_ent, e.postal_ent postal_ent, e.dir_ent dir_ent, e.status as status, con.nom_con nom_con, con.ape_con ape_con, con.email_con email_con, con.id_pre id_pre, con.num_tel_con num_tel_con, con.id_dep id_dep, pre.id_cod_pre id_cod_pre, dep.nom_dep nom_dep, e.id_diascre, dicr.cod_diascre, e.note_fac note_fac_custom, e.id_alm, e.c_consig, e.id_ubi, e.handling_conver, e.print_lote, e.req_exc_rat, e.print_special, e.id_tipocliente, e.contr_esp, e.id_por_ret_iva, e.cant_dec, e.view_internet, e.url, e.logo_ent FROM f0014 e LEFT JOIN f0004 p ON p.id_pais = e.id_pais  LEFT JOIN f00041 d ON d.id_edo = e.id_edo LEFT JOIN f00042 c ON c.id_ciudad = e.id_ciudad LEFT JOIN f0003 z ON z.id_zona = e.id_zona LEFT JOIN f0016 v ON v.id_vend = e.id_vend LEFT JOIN f00141 con ON con.id_ent = e.id_ent LEFT JOIN f0018 pre ON pre.id_pre = con.id_pre LEFT JOIN f00142 dep ON dep.id_dep = con.id_dep LEFT JOIN f6005 dicr on dicr.id_diascre = e.id_diascre LEFT JOIN f4014 tipcli on tipcli.id = e.id_tipocliente WHERE e.id_ent = {$id}";        
        $r = DB::query($sql);
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
    static function listar_clientes($tip_ent, $id_emp){
        if($tip_ent == 'C'){
            $r = DB::query("SELECT * FROM f0014 WHERE id_emp = {$id_emp} AND tip_ent = '$tip_ent' AND status = 1 ORDER BY nom_ent");
        }else{
            $r = DB::query("SELECT * FROM f0014 WHERE tip_ent = '$tip_ent' AND status = 1 ORDER BY nom_ent");
        }
        return $r;
    }
    static function consulta_vend(int $id_ent){
        $r = DB::query("SELECT DISTINCT a.id_vend, a.id_moneda, case when ISNULL(b.cod_diascre) THEN 0 ELSE b.cod_diascre END cod_diascre, a.nom_ent, a.handling_conver, a.id_alm, a.id_ubi, c.id_motcam, a.handling_conver, a.print_special, a.req_exc_rat, a.c_consig, a.contr_esp, a.id_por_ret_iva FROM f0014 a LEFT OUTER JOIN f6005 b on b.id_diascre = a.id_diascre left OUTER JOIN f0012a c ON c.id_motcam = a.id_motcam LEFT OUTER JOIN f0020 d on d.id = a.id_por_ret_iva WHERE a.id_ent = {$id_ent}");
        return $r[0];
    }
    static function listar_codigos_area(){
        return $r = DB::query("SELECT * FROM f0018 WHERE status = 1 ORDER BY id_cod_pre");
    }
    static function listar_dpto_ent(){
        return $r = DB::query("SELECT * FROM f00142 WHERE status = 1 ORDER BY nom_dep");
    }
    static function borrar_contactos(int $id_ent){
        return $r = DB::query("DELETE FROM f00141 WHERE id_ent = {$id_ent}");
    }
    static function guardar_contactos($id_ent, $data){
        return $id = DB::insert('f00141', $data);
    }
    static function destroy(int $id){        
        $id2 = DB::delete("f00141", ['id_ent' => $id]);
        $id = DB::delete('f0014', ['id_ent' => $id], 1);
        return $id;
    }
    static function consulta_motivo(int $id){
        return $r = DB::query("SELECT b.id_fab, b.adicional FROM f0014 a INNER JOIN f0012a1 b on b.id_motcam = a.id_motcam WHERE a.id_ent = {$id}");
    }
    public static function tot_cli(){
        $r = DB::query("SELECT count(*) tot_cli FROM f0014 WHERE tip_ent = 'C'");
        return $r[0];
    }
    ////Llenar combp de Status de Entidad (Clientes)
    public static function statusEntidad($id){
        return $r = DB::query("SELECT * FROM f0015");
    }
    //Llenar combo Días de Crédito
    static function listar_dias_credito(){
        return $r = DB::query("SELECT * FROM f6005 WHERE status = 1");
    }
    static function total_rows(int $id){
        return $r = DB::query("SELECT count(*) total FROM f0014 WHERE rif_ent = '". $id ."'");
    }
    //Llenar combo Tipos de Clientes agregado el 04-07-2025 a las 10:46:00 por jose Vargas
    static function listar_tipos_clientes(){
        $r = DB::query("SELECT * FROM f4014 WHERE status = 1 ORDER BY description");
        return $r;
    }
    static function getlogoEnt(int $id){
        $sql = "SELECT logo_ent FROM f0014 WHERE id_ent = {$id}";
        $r = DB::query($sql);
        return $r[0];
    }
}