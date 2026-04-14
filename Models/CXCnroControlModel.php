<?php 
    /**
     * Clase Model Numero de Control
     * 
     */
    class CXCnroControlModel extends DB{
        function __construct(){
            parent::__construct();
        }
        static function all() {
            return $r = DB::query("SELECT a.id_nrocontrol, b.nombre_emp, a.ini_nroControl, a.fin_nroControl, a.next_nroControl, a.fec_asig, a.status FROM f6007 a INNER JOIN f0011 b ON b.id_emp = a.id_emp");
        }
        static function guardar($data){
            return $r = DB::insert('f6007', $data);
        }
        static function actualizar($id, $data){
            return $r = DB::update('f6007', $data, ['id_nrocontrol' => $id]);
        }
        static function showrowupdate_nroContrl($id){
            $r = DB::query("SELECT a.id_nrocontrol, b.id_emp, a.ini_nroControl, a.fin_nroControl, a.next_nroControl, a.fec_asig, a.status FROM f6007 a INNER JOIN f0011 b ON b.id_emp = a.id_emp WHERE a.id_nrocontrol = {$id}");
            return $r;
        }
    }
?>