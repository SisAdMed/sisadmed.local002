<?php
class TipoComprobanteModel extends DB {
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f0019");
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM f0019 WHERE id_tipcom = {$id}");
        return $r[0];
    }
    static function guardar($data){
        return $id = DB::insert('f0019', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('f0019', $data, ['id_tipcom' => $id]);
    }
    static function destroy($id){
        return $r = DB::query("UPDATE f0019 SET status = 0 WHERE id_tipcom = {$id}");
    }
    static function listar_tipos_comprobantes(){
        $r = DB::query("SELECT * FROM f0019 WHERE id_emp = {$id_emp} AND status = 1");
        return $r;
    }
}