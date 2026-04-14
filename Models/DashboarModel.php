<?php
    /***
     * Clase para el Módulo de Dashboard
     */
    class DashboardModel extends DB{
        public function __construct(){
            parent::__construct();
        }
       static function grafica_001(){
            $r = DB::query("SELECT YEAR(fecha_comp) id_prod, sum(can_det) as can_det FROM grafica_001 GROUP BY YEAR(fecha_comp) ORDER BY 2 DESC");
            return $r;
        }
    }
?>