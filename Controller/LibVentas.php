<?php
   class LibVentas extends Controller{
      public function __construct() {
         Auth::noAuth();
         parent::__construct();
         Permisos::getPermisos(155);
      }
      public function index(){
         if (empty($_SESSION['permisosMod']['r'])) {
            header('Location:' . base_url . '/Perfil');
         }
         
         $this->views->getView($this, "index", [
            'page_name' => "Reporte Libro de Ventas",
            'function_js' => "LibVentas.js",
         ]);
      }
      public function index2(){
         if(empty($_SESSION['permisosMod']['r'])){
            header('Localtion:' . base_url . '/perfil');
         }
         $this->views->getView($this, "index2", [
            'page_name' => 'Reporte Libro de Compras',
            'function_js' => 'LibVentas.js',
         ]);
      }
      public function reportSalesExcel(){
         if(Permisos::read()){
            $id_emp = $_GET['id_emp'];
            $fec_ini = $_GET['fec_ini'];
            $fec_fin = $_GET['fec_fin'];
            $r = LibVentasModel::ventas($id_emp, $fec_ini, $fec_fin);
            if(empty($r)){
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/LibVentas');
            }
            $this->views->getView($this, "reportSalesExcel", [
              'r' => to_obj($r)
            ]);
            return;
            Alertas::new('No tiene permiso para realizar esta acción', 'warning');
            header('Location:' . base_url);
         }
      }
      public function reportComprasExcel(){
         if(Permisos::read()){
            $id_emp = $_GET['id_emp'];
            $fec_ini = $_GET['fec_ini'];
            $fec_fin = $_GET['fec_fin'];
            $r = LibVentasModel::compras($id_emp, $fec_ini, $fec_fin);
            if(empty($r)){
               Alertas::new('El registro no existe', 'warning');
               header('Location:' . base_url . '/LibVentas');
            }
            $this->views->getView($this, "reportComprasExcel", [
              'r' => to_obj($r)
            ]);
            return;
            Alertas::new('No tiene permiso para realizar esta acción', 'warning');
            header('Location:' . base_url);
         }
      }
}