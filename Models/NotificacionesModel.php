<?php
    /**
     * Clases Modal para el manejo de las Notificaciones
     */
    class NotificacionesModel extends DB{
        public function __construct(){
            parent::__construct();
        }
        static function all(){
            return $r = DB::query("SELECT * FROM fgenmsg");
        }
        static function all_index(){
            return $r = DB::query("SELECT * FROM fgenmsg");
        }
    }
?>