<?php class MonedasModel extends DB
{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f0005 m INNER JOIN f0004 p ON m.id_pais = p.id_pais");
    }
    static function guardar($data)
    {
        return $id = DB::insert('f0005', $data);
    }
     static function actualizar($id, $data)
    {
        return $res = DB::update('f0005', $data, ['id_moneda' => $id]);
    }
    static function borrar($id){
        return $id = DB::delete('f0005', ['id_moneda' => $id], 1);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0005 WHERE id_moneda = {$id}");
        return $r[0];
    }
    static function selPaises(){
        $r = DB::query("SELECT * FROM f0004 ORDER BY nombre_pais");
        return to_obj($r);
    }
    static function  listar_monedas(){
        return $r = DB::query("SELECT * FROM f0005");
    }
}
