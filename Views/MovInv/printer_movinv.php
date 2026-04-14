<?php
require(FPDF1 . 'fpdf.php');
//Variables
$filename = $r[0]->nombre_emp . '-' . $r[0]->cod_tmoinv  .'-' . $r[0]->nom__tmoinv . '-' . $r[0]->num_movinv;
$tam_letra = 7;
class PDF extends FPDF{
    public $ruta_logo, $nombre_emp, $fecha_comp, $nom_movim, $num_movinv, $nom_alm, $observa, $origen;
    public function __construct($r){
        parent::__construct();
        $this->ruta_logo = IMG . 'companies/' . $r[0]->logo;
        $this->nombre_emp = $r[0]->nombre_emp;
        $this->fecha_comp = $r[0]->fecha_comp;
        $this->nom_movim = $r[0]->cod_tmoinv . ' - ' . $r[0]->nom__tmoinv;
        $this->num_movinv = $r[0]->num_movinv;
        $this->nom_alm = $r[0]->cod_alm . ' - ' . $r[0]->nom_alm;
        $this->observa = $r[0]->descrip_movinv;
        $this->origen = $r[0]->origen;
    }
    function header(){
        //Logo
        $this->Image($this->ruta_logo, 10, 8, 50);
        //Nombre Empresa
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 5, $this->nombre_emp, 0, 1, 'C');
        //Fecha del registro
        $this->SetFont('Arial', '', 14);
        $this->Cell(0, 5, 'Caracas, ' . formatFecha($this->fecha_comp), 0, 1, 'R');
        //Tipo y Nombre de Movimiento
        $this->Cell(0, 5, $this->nom_movim, 0, 1, 'C');
        //Numero de Movimiento
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Número: ');
        $this->Cell(0, 5, $cadena . $this->num_movinv, 0, 1, 'C');
        //Almacen 
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Almacén: ' . $this->nom_alm);
        $this->Cell(0, 5, $cadena , 0, 1, 'C');
        //Observaciones
        $this->Ln(15);
        $cadena = iconv("UTF-8", "ISO-8859-1",  html_entity_decode($this->observa));
        $this->Cell(0, 3, 'Observaciones: ' . $cadena, 0, 1);
        //origen
        $this->Ln(2);
        $this->Cell(0, 3, $this->origen, 0, 1);
        //Titulos
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 7);
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Ítem' );
        $this->Cell(5, 3, $cadena, 0, 0, 'C');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Producto');
        $this->Cell(90, 3, $cadena, 0, 0, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Código');
        $this->Cell(20, 3, $cadena, 0, 0, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Referencia');
        $this->Cell(20, 3, $cadena, 0, 0, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Ubicación');
        $this->Cell(20, 3, $cadena, 0, 0, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Nombre Unbicación');
        $this->Cell(50, 3, $cadena, 0, 0, 'L');
        $this->Cell(25, 3, 'Lote', 0, 0, 'L');
        $this->Cell(10, 3, 'Fecha Venc.', 0, 0, 'C');
        $this->Cell(20, 3, 'Cantidad', 0, 1, 'R');
    }
    function footer(){
        // Posicion:  a 1.5 cm del final
        $this->SetY(-15);
        //Número de página
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Página ');
        $this->Cell(0, 10, $cadena . $this->PageNo() . '/{nb}',0, 0, 'C');
    }
}
//Creación del objeto de la lase heredada
$pdf = new PDF($r);
$pdf->AliasNbPages();
$pdf->AddPage('L', 'Letter');
$pdf->SetFont('Arial', '', $tam_letra);
$rows = 0;
$items = 0;
$tot_cant = 0;
$pdf->Ln(2);
//Impresión de detalles del Movimiento
for($i = 0; $i < count($r); $i++){
    $items++;
    $pdf->Cell(5,3, $items, 0, 0, 'C');
    $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($r[$i]->nom_prod));
    $pdf->Cell(90, 3, $cadena, 0, 0, 'L');
    $pdf->Cell(20, 3, $r[$i]->cod_prod, 0, 0, 'L');
    $pdf->Cell(20, 3, $r[$i]->ref_prod, 0, 0, 'L');
    $pdf->Cell(20, 3, $r[$i]->cod_ubi, 0, 0, 'L');
    $pdf->Cell(50, 3, $r[$i]->nom_ubi, 0, 0, 'L');
    $pdf->Cell(25, 3, $r[$i]->lote, 0, 0, 'L');
    $pdf->Cell(10, 3, formatFecha($r[$i]->fec_venc), 0, 0, 'C');
    $pdf->Cell(20, 3, number_format($r[$i]->cantidad, 0, '.', ','), 0, 1, 'R');
    $tot_cant += $r[$i]->cantidad;
}
//Mostrar Total de Cantidades
$pdf->SetFont('Arial', 'UB', $tam_letra);
$pdf->Ln(2);
$pdf->Cell(5, 3, '', 0, 0, 'C');
$pdf->Cell(90, 3, '', 0, 0, 'L');
$pdf->Cell(20, 3, '', 0, 0, 'L');
$pdf->Cell(20, 3, '', 0, 0, 'L');
$pdf->Cell(20, 3, '', 0, 0, 'L');
$pdf->Cell(50, 3, '', 0, 0, 'L');
$pdf->Cell(25, 3, '', 0, 0, 'L');
$pdf->Cell(10, 3, 'Total:', 0, 0, 'R');
$pdf->Cell(20, 3, number_format($tot_cant, 0, ',', '.' ), 0, 1, 'R');
//Mostrar quien realízo el registro
$pdf->Ln(10);
$ancho = 80;
$separar = 20;
$ancho_total = $ancho * 2 + $separar;
$xinicial = ($pdf->GetPageWidth() - $ancho_total) / 2;
if($r[0]->modify_user){
    $pdf->SetX($xinicial);
    $pdf->Cell($ancho, 3, 'Elaborado por: ', 0, 0, 'C');
    $pdf->Cell($ancho, 3, 'Modificado por: ', 0, 1, 'C');
    $pdf->Ln(20);
    $pdf->SetX($xinicial);
    $pdf->SetFont('Arial', 'U', $tam_letra);
    $cadena = iconv("UTF-8", "ISO-8859-1", $r[0]->create_user);
    $pdf->Cell($ancho, 3, $cadena, 0, 0, 'C');
    $cadena = iconv("UTF-8", "ISO-8859-1", $r[0]->modify_user);
    $pdf->Cell($ancho, 3, $cadena, 0, 1, 'C');
    $pdf->SetX($xinicial);
    $pdf->SetFont('Arial', '', $tam_letra);
    $fecha_string = $r[0]->create_date;
    $timestamp = strtotime($fecha_string);
    $formato = date("d-m-Y H:i:s", $timestamp);
    $pdf->Cell($ancho, 3, 'Elaborado el: ' . $formato, 0, 0, 'C');
    $fecha_string = $r[0]->modify_date;
    $timestamp = strtotime($fecha_string);
    $formato = date("d-m-Y H:i:s", $timestamp);
    $pdf->Cell($ancho, 3, 'Modificado el: ' . $formato, 0, 0, 'C');
}else{
    $xinicial = ($pdf->GetPageWidth() - $ancho_total);
    $pdf->SetX($xinicial);
    $pdf->Cell($ancho, 3, 'Elaborado por: ', 0, 1, 'C');
    $pdf->Ln(20);
    $pdf->SetX($xinicial);
    $pdf->SetFont('Arial', 'U', $tam_letra);
    $cadena = iconv("UTF-8", "ISO-8859-1", $r[0]->create_user);
    $pdf->Cell($ancho, 3, $cadena, 0, 1, 'C');
    $pdf->SetX($xinicial);
    $pdf->SetFont('Arial', '', $tam_letra);
    $fecha_string = $r[0]->create_date;
    $timestamp = strtotime($fecha_string);
    $formato = date("d-m-Y H:i:s", $timestamp);
    $pdf->Cell($ancho, 3, 'Elaborado el: ' . $formato, 0, 0, 'C');
}
//Salida de archivo
$pdf->Output('', htmlentities($filename));