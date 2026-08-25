<?php
// Declaramos la libreria
require(FPDF1 . 'fpdf.php');
//Variables
$filemame = 'RetenciondeIVA.pdf';
$width = 0;
$height = 0;
//Global
$GLOBALS['rif_empresa'] = $r[0]->rif_empresa;
$GLOBALS['usuario'] = $r[0]->ela_por;
//Extender la clase
class PDF extends FPDF{
    //Variables
    public $rif_empresa, $ruta_logo, $dir_emp, $nom_ent, $fecha_comp, $rif_ent, $num_tdo, $codigo_moneda;
    public $dir_ent, $nombre_ciudad, $nombre_edo, $nombre_pais, $postal_ent, $mes, $anio, $por_retiva;
    public $width, $height, $nombre_emp, $num_retiva, $cod_empresa, $id_ent;
    //Totales
    public $sub_total_foot;
    //Constructor
    public function __construct($r){
         //Initialization
        parent::__construct();
        $this->ruta_logo = IMG . 'companies/' . $r[0]->logo;
        $this->nombre_emp = $r[0]->nombre_emp;
        $this->rif_empresa = $r[0]->rif_empresa;
        $this->dir_emp = $r[0]->dir_emp;
        $this->fecha_comp = $r[0]->fecha_comp;
        $this->nom_ent = $r[0]->nom_ent;
        $this->rif_ent = $r[0]->rif_ent;
        $this->cod_empresa = $r[0]->cod_emp;
        $this->id_ent = $r[0]->id_ent;
        $this->dir_ent = $r[0]->dir_ent;
    }
    //Cabecera de página
    function Header(){
        //Logo
        //$this->Image($this->ruta_logo, 10, 8, 50, 0, 'PNG');
        //Rif
        $this->SetFont('Arial','B', 7);
        // Movernos a la derecha
        $this->Cell(120);
        // Nombre empresa
        $this->SetFont('Arial','B', 14);
        $this->Cell(30, 10, $this->nombre_emp, 0, 1, 'C');
        $this->Cell(120);
        $this->Cell(30, 10, 'RIF: '. $GLOBALS['rif_empresa'],0 , 1 ,'C' );
        //Datos de empresa
        //Agente de Retncion
        $this->Ln(15);
        $this->SetFont('Arial','',8);
        $cadena = iconv("UTF-8", "ISO-8859-1", "Agente de Retención:");
        $this->cell(30, 7, $cadena, 0, 0);
        $this->cell(10, 7, $this->cod_empresa, 0, 0);
        $this->SetFont('Arial','B',8);
        $this->cell(60, 7, $this->nombre_emp, 0, 0);
        $this->SetFont('Arial','',8);
        $this->cell(10, 7, 'RIF:', 0, 0);
        $this->SetFont('Arial','B',8);
        $this->cell(10, 7,$GLOBALS['rif_empresa'] , 0, 1);
        $this->SetFont('Arial','',8);
        $cadena = iconv("UTF-8", "ISO-8859-1", "Dirección  Fiscal:");
        $this->cell(10, 7, $cadena, 0, 0);
        $cadena = iconv("UTF-8", "ISO-8859-1", $this->dir_emp);
        $this->cell(20);
        $this->MultiCell(150, 7, $cadena);
        //Sujeto Retenido
        $cadena = iconv("UTF-8", "ISO-8859-1", "Proveedor:");
        $this->cell(30, 7, $cadena, 0, 0);
        $this->cell(10, 7,  $this->id_ent, 0, 0);
        $this->SetFont('Arial','B',8);
        $this->cell(60, 7, $this->nom_ent, 0, 0);
        $this->SetFont('Arial','',8);
        $this->cell(40);
        $this->cell(10, 7, 'RIF:', 0, 0);
        $this->SetFont('Arial','B',8);
        $this->cell(10, 7, $this->rif_ent, 0, 1);
        $this->SetFont('Arial','',8);
        $cadena = iconv("UTF-8", "ISO-8859-1", "Dirección  Fiscal:");
        $this->cell(10, 7, $cadena, 0, 0);
        $cadena = iconv("UTF-8", "ISO-8859-1", $this->dir_ent);
        $this->cell(20);
        $this->MultiCell(150, 7, $cadena);
        $this->ln();
    }
    // Page footer
    function Footer(){
        // Position at 1.5 cm from bottom
        $this->SetY(-30);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        //$this->cell(0, 50, $GLOBALS['fecha_comp'] , 'B', 1, 'R');
        // Firma
        $this->Cell(40);
        $this->cell(40, 7, 'RIF: ' . $GLOBALS['rif_empresa'], 'B', 1, 'L');
        //$this->cell(20, 7, $GLOBALS['fecha_comp'], 'B', 1, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", "Agente de Retención (Fecha de Entrega)");
        $this->cell(135, 7, $cadena, 0, 0, 'C');
        $this->Cell(30);
        $cadena = iconv("UTF-8", "ISO-8859-1", "Sujeto Retenido (Fecha de Recepción)");
        $this->cell(60, 7, $cadena, 'T', 1, 'C');
        $cadena = iconv("UTF-8", "ISO-8859-1", $GLOBALS['usuario']);
        $this->cell(135, 7, $cadena, 0, 0, 'C');
        $this->ln(5);
    }
}
// Creación del objeto de la clase heredada
$pdf = new PDF($r);
$pdf->AliasNbPages();
$pdf->AddPage('L', 'Letter');
$pdf->SetFont('Arial','B', 8);
$width = $pdf->GetPageWidth();  // Width of Current Page
$height = $pdf->GetPageHeight();
//Titulos
//Primera Línea
$pdf->cell(15,7, html_entity_decode("Fecha"), 'TL', 0, 'C');
$pdf->cell(20,7, html_entity_decode("Nro."), 'T', 0, 'C');
$pdf->cell(20,7, iconv("UTF-8", "ISO-8859-1", "Fecha"), 'T', 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", "Número"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Cód"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Monto bruto"), 'T', 0, 'C');

$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Monto"), 'T', 0, 'C');
$pdf->cell(35,7, iconv("UTF-8", "ISO-8859-1", "% base"), 'T', 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", "Porc."), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Monto"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Monto"), 'TR', 0, 'C');
$pdf->ln(3);
//Segunda Línea
$pdf->cell(15,7, html_entity_decode("Oper"), 'L', 0, 'C');
$pdf->cell(20,7, html_entity_decode("Factura"), 0, 0, 'C');
$pdf->cell(20,7, iconv("UTF-8", "ISO-8859-1", "Factura"), 0, 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", "Control"), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Ret."), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "a pagar"), 0, 0, 'C');

$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Objeto de"), 0, 0, 'C');
$pdf->cell(35,7, iconv("UTF-8", "ISO-8859-1", "impon."), 0, 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", "Aplic."), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "deducible"), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Retenido"), 'R', 0, 'C');
$pdf->ln(3);
//Terecra Línea
$pdf->cell(15,7, html_entity_decode(""), 'BL', 0, 'C');
$pdf->cell(20,7, html_entity_decode(""), 'B', 0, 'C');
$pdf->cell(20,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "o abonar"), 'B', 0, 'C');

$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "retención"), 'B', 0, 'C');
$pdf->cell(35,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", ""), 'BR', 1, 'C');
$pdf->SetFont('Arial','', 8);
$total_monto = 0;
$total_base = 0;
$total_retenido = 0;
for($i= 0; $i<count($r);$i++){
    $pdf->cell(15,7, html_entity_decode(formatFecha($r[$i]->fecha_comp)), 'BL', 0, 'C');
    $pdf->cell(20,7, html_entity_decode($r[$i]->num_tdo), 'B', 0, 'C');
    if(!empty($r[$i]->fec_fact)){
        $pdf->cell(20,7, html_entity_decode(formatFecha($r[$i]->fec_fact)), 'B', 0, 'C');
    }else{
        $pdf->cell(20,7, html_entity_decode(formatFecha($r[$i]->fecha_comp)), 'B', 0, 'C');
    }
    $pdf->cell(15,7, html_entity_decode('00-' . str_pad($r[$i]->num_control, 8,'0', STR_PAD_LEFT)), 'B', 0, 'C');
    $pdf->cell(25,7, html_entity_decode(($r[$i]->id_retislr)), 'B', 0, 'C');
    $pdf->cell(25,7, number_format($r[$i]->total_monto, 2, ",", "."), 'B', 0, 'R');
    $pdf->cell(25,7, number_format($r[$i]->total_base, 2, ",", "."), 'B', 0, 'R');
    $pdf->cell(25,7, number_format($r[$i]->base_imp, 2, ",", ".") , 'B', 0, 'R');
    $pdf->cell(25,7, number_format($r[$i]->por_reten, 2, ",", ".") . " %", 'B', 0, 'R');
    $pdf->cell(25,7, number_format($r[$i]->deducible, 2, ",", "."), 'B', 0, 'R');
    $pdf->cell(25,7, number_format($r[$i]->total_retenido, 2, ",", "."), 'BR', 1, 'R');
    //Acumular
    $total_monto += $r[$i]->total_monto;
    $total_base += $r[$i]->total_base;
    $total_retenido += $r[$i]->total_retenido;
}
//Totales
$pdf->SetFont('Arial','B', 8);
$pdf->cell(15,7,'', 0, 0, 'C');
$pdf->cell(20,7, '', 0, 0, 'C');
$pdf->cell(20,7, '', 0, 0, 'C');
$pdf->cell(15,7, '', 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Total"), 0, 0, 'R');
$pdf->cell(25,7, number_format($total_monto, 2, ",", "."), 0, 0, 'R');
$pdf->cell(25,7, number_format($total_base, 2, ",", "."), 0, 0, 'R');
$pdf->cell(25,7, '', 0, 0, 'C');
$pdf->cell(25,7, '', 0, 0, 'C');
$pdf->cell(25,7, '', 0, 0, 'C');
$pdf->cell(25,7, number_format($total_retenido, 2, ",", "."), 0, 1, 'R');
$pdf->SetFont('Arial','', 8);
//Archivo de Salida
$pdf->Output('', html_entity_decode($filemame));
