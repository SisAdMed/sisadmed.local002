<?php
/**
 * Clase para los metodos de Tipos de Nómina
 * Creado por José Vargas
 * Fecha: 28-10-2024
 * Hora: 10:15:00
 * Corporación MMQ
 */
class NomTipNom extends Controller{
    function __construct(){
        Auth::noAuth();
        parent::__construct();
        Permisos::getPermisos(138);
    }
     public function index(){
      if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
        }
        $objeto = NomTipNomModel::all();
        $this->views->getView($this, "index", [
            'page_name' => "Listado Tipos de Nómina",
            'function_js' => "NomTipNom.js",
            'objeto' => to_obj($objeto),
        ]);
   }
    public function nuevo(){
        $this->views->getView($this, "nuevo", [
            'page_name' => "Nuevo Tipo de Nómina",
            'function_js' => "NomTipNom.js"
        ]);
    }
}