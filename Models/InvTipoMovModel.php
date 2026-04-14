<?php class InvTipoMovModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f4006 a INNER JOIN f0011 b ON a.id_emp = b.id_emp");
    }
    static function all_emp($id){
        return $r = DB::query("SELECT * FROM f4006 WHERE id_emp = {$id_emp}");
    }
    static function guardar($data)
    {
        return $id = DB::insert('f4006', $data);
    }
    static function actualizar($id, $data)
    {
        return $res = DB::update('f4006', $data, ['id_tmoinv' => $id]);
    }
    static function borrar($id, $aux){
        return  $id = DB::delete('f4006', ['id_tmoinv' => $id], 1);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f4006 WHERE id_tmoinv = {$id}");
        return $r[0];
    }
    static function validar_codigo($codigo){
        return $r = DB::query("SELECT * FROM f4006 WHERE cod_tmoinv = '".$codigo."'");
    }
    static function listar_InvTipoMov($id, $tipo){
        if($tipo !=''){
            $r = DB::query("SELECT * FROM f4006 WHERE id_emp = {$id} AND status = 1 AND tipo_tmoinv = '". $tipo ."'");
        }else{
            $r = DB::query("SELECT * FROM f4006 WHERE id_emp = {$id} AND status = 1");
        }
        return $r;
    }
    static function show_row($id){
        $r = DB::query("SELECT a.id_emp, a.cod_tmoinv, a.nom__tmoinv, a.tipo_tmoinv, a.tmosal_tmoinv, a.id_alm, a.status, a.consecutiv__tmoinv, a.proximo_tmoinv, a.id_cta, a.id_aux, b.nombre_cta, c.nombre_aux FROM f4006 a INNER JOIN f0010 b ON b.id_cta = a.id_cta LEFT OUTER JOIN f0009 c ON c.id_aux = a.id_aux WHERE a.id_tmoinv = {$id}");
        return $r[0];
    }
}
