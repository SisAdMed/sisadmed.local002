<?php class EmpresasModel extends DB
{
    public function __construct(){ 
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f0011");
    }
    static function guardar($data)
    {
        return $id = DB::insert('f0011', $data);
    }
     static function actualizar($id, $data)
    {
        return $res = DB::update('f0011', $data, ['id_emp' => $id]);
    }
    static function borrar($id){
        return $id = DB::delete('f0011', ['id_emp' => $id], 1);
    }
    static function edit($id){
        $logo = IMG ;
        $r = DB::query("SELECT a.id_emp, a.cod_emp, a.nombre_emp, a.rif_empresa, a.dir_emp, a.id_pais, a.tel_emp, a.email_emp, a.id_moneda, CASE WHEN a.logo THEN CONCAT('$logo', 'companies/', a.logo) ELSE '' END logo, a.status, a.create_user, a.create_date, a.modify_user, a.modify_date, a.host, a.usuario, a.pass_email, a.puerto_send, a.fec_ini_fis, a.fec_fin_fis, a.fec_ctb, a.fec_ban, a.fec_cxc, a.fec_cxp, a.fec_nom, a.id_iva, a.especial_contrib, a.iva_deb_fis, IFNULL(CONCAT( b.cod_cta, ' - ',  b.nombre_cta), ' ') nom_ctb_iva_deb_fis, a.iva_cre_fis, IFNULL(CONCAT( c.cod_cta, ' - ', c.nombre_cta), ' ') nom_ctb_iva_cre_fis FROM f0011 a LEFT OUTER JOIN f0010 b ON b.id_cta = a.iva_deb_fis LEFT OUTER JOIN f0010 c ON c.id_cta = a.iva_cre_fis WHERE a.id_emp = {$id}");
        return $r[0];
    }
    static function listar_empresas(){
        return $r = DB::query("SELECT * FROM f0011 WHERE status = 1 ORDER BY nombre_emp");
    }
    static function listar_zona_fiscal(){
        return $r = DB::query("SELECT * FROM f0017 WHERE status = 1 LIMIT 1");
    }
    static function listar_modulos($mod){
        $sql = "SELECT * FROM f0022 WHERE module IN ($mod)";
        return $r = DB::query($sql);
    }
    static function get_empresa_config($id_emp){
        $r = DB::query("SELECT fec_ini_fis, fec_fin_fis, fec_ctb, fec_ban, fec_cxc, fec_cxp, fec_nom FROM f0011 WHERE id_emp = {$id_emp}");
        return $r[0];
    }   
}
