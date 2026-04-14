<?php class AuxiliarCtbModel extends DB
{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f0009");
    }
    static function guardar($data){
        return $id = DB::insert('f0009', $data);
    }
    static function actualizar($id, $data){
        return $res = DB::update('f0009', $data, ['id_aux' => $id]);
    }
    static function borrar($id, $aux){
        $sql = "SELECT * FROM f0009 WHERE cod_aux LIKE '".$aux.".%'" ;
        $row = DB::query($sql);
        if(empty($row)){                        
            $id = DB::delete('f0009', ['id_aux' => $id], 1);
            return true;            
        }else{
            return false;
        }
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0009 WHERE id_aux = {$id}");
        return $r[0];
    }
    static function validar_cod_aux($codigo){
        $r = DB::query("SELECT count(*) totrows FROM f0009 WHERE cod_aux = '".$codigo."'");
        return $r;
    }
    static function validar_cod_cta($codigo){
        $r = DB::query("SELECT * FROM f0010 WHERE cod_cta = '".$codigo."'");
        return $r[0];
    }
    static function listar_aux_ctbles(){
        return $r = DB::query("SELECT * FROM f0009 WHERE status_aux = 1 AND agrupa_aux = 'N'");
    }
    static function listar_aux_ctbles_mod($id, $mod){
        if($mod == "CXC"){
            $r = DB::query("SELECT c.id_aux, c.cod_aux, c.nombre_aux FROM f6002 a INNER JOIN f0010 b on b.id_cta = a.id_ctbcue LEFT OUTER JOIN f0009 c on c.agrupa_aux = 'N' WHERE a.id_emp = {$id} AND b.aux_cta = 'S' ORDER BY 3");
        }
        return $r;
    }
    static function modal_AuxiliarCtb(){
        return $r = DB::query("SELECT * FROM f0009 WHERE status_aux = 1 AND agrupa_aux = 'N' ORDER BY cod_aux");
    }
    static function nom_aux($id){
        $r = DB::query("SELECT cod_aux, nombre_aux FROM f0009 WHERE id_aux = {$id}");
        return $r[0];
    }
}
