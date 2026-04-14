<?php
        /**
         * Clase para el manejo de las notificaciones
         */
        class HeaderModel extends DB{
            function __construct(){
                    parent::__construct();
            }
            static function totmsj(){
                $r = DB::query("SELECT count(*) totmsj, TIMESTAMPDIFF(MINUTE, fecha_genmsgcol, NOW()) tiempo FROM fgenmsg WHERE status = 1 AND tipo_fgenmsgcol = 1 GROUP BY TIMESTAMPDIFF(MINUTE, fecha_genmsgcol, NOW())");
                return $r;
            }
        }
?>