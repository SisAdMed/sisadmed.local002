<?php class MenuModel extends DB
{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $menu = DB::query("SELECT id_menu, nombre_menu, desc_menu, icono_menu, orden_menu, status_menu, page_menu, (SELECT p.nombre_menu FROM f0001 p WHERE p.id_menu = f0001.padre_menu) as padre FROM f0001");
    }
    static function padre_menu(){
        $rp = DB::query("SELECT * FROM f0001 WHERE page_menu = '#' AND status_menu <> 0");
        return to_obj($rp);
    }
    static function guardar($data)
    {
        return $id = DB::insert('f0001', $data);
    }
     static function actualizar($id, $data)
    {
        return $res = DB::update('f0001', $data, ['id_menu' => $id]);
    }
    static function borrar($id){
        return $id = DB::delete('f0001', ['id_menu' => $id], 1);
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0001 WHERE id_menu = {$id}");
        return $r[0];
    }
}
