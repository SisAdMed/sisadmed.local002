<?php
/**
 * Clase para loa transacciones de Tipos de Nómina
 * Creado por José Vargas
 * Fecha: 28-10-2024
 * Hora: 10:15:00
 * Corporación MMQ
 */
class NomTipNomModel extends DB{
    function __construct(){
        parent::__construct();
    }
    static function all(){
        $r = DB::query("SELECT * FROM nomtip");
    }
}