<?php class PaisesModel extends DB
{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f0004");
    }        
    static function guardar($data)
    {
        return $id = DB::insert('f0004', $data);
    }
     static function actualizar($id, $data)
    {
        return $res = DB::update('f0004', $data, ['id_pais' => $id]);
    }
    static function borrar($id){
        return $id = DB::delete('f0004', ['id_pais' => $id], 1);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0004 WHERE id_pais = {$id}");
        return $r[0];
    }
}
