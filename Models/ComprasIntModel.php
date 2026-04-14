<?php
class ComprasIntModel extends DB{
    public function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT * FROM f8002 a INNER JOIN f8001 b ON b.id_provint = a.id_provint");
    }
    static function listar_proveeint(){
        $r = DB::query("SELECT * FROM f8001");
        return $r;
    }
    static function guardar($data){
        return $r = DB::insert("f8002", $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update("f8002", $data, ['id_comint' => $id]);
    }
    static function borrardet($id){
        return $r = DB::delete("f80021", ['id_comint' => $id]);
    }
    static function agregardet($data){
        return $r = DB::insert("f80021", $data);
    }
    static function edit($id){
       return $r = DB::query("SELECT a.id_comint, b.nombre_provint FROM f8002 a INNER JOIN f8001 b on b.id_provint = a.id_provint WHERE a.id_comint = {$id}");
    }
    static function cargar_data($id){
        $r = DB::query("SELECT * FROM f8002 WHERE id_comint = {$id}");
        return $r[0];
    }
    static function show_row($id){
        $r = DB::query("SELECT * FROM f80021 a INNER JOIN f4005 b on b.id_prod = a.id_prod INNER JOIN f4004 c ON b.id_pre = c.id_pre INNER JOIN f4003 d ON d.id_fab = b.id_fab WHERE a.id_comint = {$id}");
        return $r;
    }
    static function destroy($id){
        if(DB::delete('f80021', ['id_comint' => $id])){
            return DB::delete('f8002', ['id_comint' => $id]);
        }
    }
    static function print_data($id){
        $sql = "SELECT a.id_comint, a.fecha_comint, c.id_provint, c.nombre_provint, CONCAT(f.name_user, ' ', f.name_user) create_por, CONCAT(z.name_user, ' ', z.name_user) modify_por, d.id_prod, d.cod2_prod, d.nom_prod, d.id_fab, e.nom_fab, d.ref_prod, g.nom_pre, b.cantidad, b.costo, (d.uni_ven_prod * b.cantidad) tot_unidades, (b.cantidad * b.costo) tot_comp, a.descrip_compint, h.id_emp, h.nombre_emp, h.rif_empresa, h.dir_emp, h.logo, h.email_emp FROM f8002 a INNER JOIN f80021 b ON b.id_comint = a.id_comint INNER JOIN f8001 c ON c.id_provint = a.id_provint INNER JOIN f4005 d ON d.id_prod = b.id_prod INNER JOIN f4003 e ON e.id_fab = d.id_fab INNER JOIN f0002 f ON f.id_user = a.create_user INNER JOIN f4004 g on g.id_pre = d.id_pre INNER JOIN f0011 h ON h.id_emp = 2 LEFT OUTER JOIN f0002 z ON z.id_user = a.modify_user WHERE a.id_comint = {$id};";
        return DB::query($sql);
    }
}