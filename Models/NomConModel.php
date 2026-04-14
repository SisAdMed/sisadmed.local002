<?php
/**
 * Clase para loa transacciones de Conceptos de Nómina
 * Creado por José Vargas
 * Fecha: 23-10-2024
 * Hora: 15:11:00
 * Corporación MMQ
 */
class NomConModel extends DB{
    function __construct(){
        parent::__construct();
    }
    static function all(){
        return $r = DB::query("SELECT id_nomcue, codigo, nombre, CASE tipo WHEN 'A' THEN 'Asignación' ELSE 'Deducción' END tipo, CASE parametro WHEN 'D' THEN 'DD' WHEN 'B' THEN 'Bs' WHEN 'H' THEN 'HH' WHEN '$' THEN '$' WHEN 'C' THEN nomuni WHEN 'P' THEN '%' END parametro, factop, status FROM nomcue a");
    }
    static function guardar($data){
        return $r = DB::insert('nomcue', $data);
    }
    static function actualizar($id, $data){
        return $r = DB::update('nomcue', $data, ['id_nomcue' => $id]);
    }
    static function guardarConcepInt($data){
        return $r = DB::insert('nomdcu', $data);
    }
    static function borrarConcepInteNom($id){
        return $r = DB::delete('nomdcu', ['id_nomcue' => $id]);
    }
    static function modal_ConceptosNOM(){
        return $r = DB::query("SELECT id_nomcue, codigo, nombre, CASE tipo WHEN 'A' THEN 'Asignación' ELSE 'Deducción' END tipo, CASE parametro WHEN 'D' THEN 'DD' WHEN 'B' THEN 'Bs' WHEN 'H' THEN 'HH' WHEN '$' THEN '$' WHEN 'C' THEN nomuni WHEN 'P' THEN '%' END parametro, factop FROM nomcue WHERE status = 1");
    }
    static function nom_conceptoNOM($id_nomcue){
        return $r = DB::query("SELECT id_nomcue, codigo, nombre, CASE tipo WHEN 'A' THEN 'Asignación' ELSE 'Deducción' END tipo, CASE parametro WHEN 'D' THEN 'DD' WHEN 'B' THEN 'Bs' WHEN 'H' THEN 'HH' WHEN '$' THEN '$' WHEN 'C' THEN nomuni WHEN 'P' THEN '%' END parametro, factop FROM nomcue WHERE id_nomcue = {$id_nomcue}");
    }
    static function edit($id){
        $r = DB::query("SELECT * FROM nomcue WHERE id_nomcue = {$id} LIMIT 1");
        return $r;
    }
    static function show_row_NomConcepto($id){
        $r = DB::query("SELECT a.id_nomcue, a.codigo, a.nombre, CASE a.tipo WHEN 'A' THEN 'Asignación' ELSE 'Deducción' END tipo, CASE a.parametro WHEN 'D' THEN 'DD' WHEN 'B' THEN 'Bs' WHEN 'H' THEN 'HH' WHEN '$' THEN '$' WHEN 'C' THEN a.nomuni WHEN 'P' THEN '%' END parametro, a.factop, a.status, b.nombre_cta, a.tipo ttipo, a.parametro tparametro, a.id_ctb FROM nomcue a INNER JOIN f0010 b ON b.id_cta = a.id_ctb WHERE a.id_nomcue = {$id}");
        return $r;
    }
    static function total_rows($id){
        return $r = DB::query("SELECT count(*) total FROM nomcue WHERE id_nomcue = {$id}");
    }
}