<?php
require(FPDF1 . 'fpdf.php');
//Variables

$filemame = 'Asiento Contable '. ' - ' . $r[0]->num_comp .'.pdf';
$mon_debe = 0;
$mon_habe = 0;
$GLOBALS['mon_debe'] = 0;
$GLOBALS['mon_habe'] = 0;
class PDF extends FPDF{
    public $rif_empresa, $ruta_logo, $fecha_comp, $codigo_moneda, $nombre_moneda, $moneda_emp, $num_comp, $tasa_cambio;
    public $width, $height, $nombre_emp, $desc_comp;
//Totales
    public $sub_total_foot;
    public function __construct($r){
        parent::__construct();
        $this->nombre_emp = $r[0]->nombre_emp;
        $this->rif_empresa = $r[0]->rif_empresa;
        $this->ruta_logo = IMG .'companies/' . $r[0]->logo;
        $this->fecha_comp = formatFecha($r[0]->fecha_comp);
        $this->num_comp = $r[0]->num_comp;
        $this->nombre_moneda = $r[0]->nombre_moneda;
        $this->codigo_moneda = $r[0]->codigo_moneda;
        $this->tasa_cambio = $r[0]->tasa_cambio;
        $this->desc_comp = $r[0]->desc_comp;
        /*$this->moneda_emp = $r[0]->moneda_emp;*/
        $this->width = $this->GetPageWidth();
        $this->height = $this->GetPageHeight();
    }
// Cabecera de página
    function Header(){
// Logo
        if(base_url != 'https://sisadmed.local'){
            $this->Image($this->ruta_logo, 10, 8, 50, 0, 'PNG');   
        }
// Arial bold 15
        $this->SetFont('Arial','B', 7);
// Movernos a la derecha
        $this->Cell(100);
// Nombre empresa
        $this->SetFont('Arial','B', 12);
        $this->Cell(30,10, $this->nombre_emp,0,1,'C');
        $this->Cell(100);
        $this->Cell(30,10, 'RIF: '. $this->rif_empresa,0,1,'C' );
        $this->Cell(100);
        $this->Cell(30,10, 'Asiento Contable Nro.: ' . $this->num_comp . ' de fecha: ' . $this->fecha_comp, 0, 1, 'C');
        $this->Cell(100);
        $this->Cell(30,10, 'Moneda.: ' . $this->codigo_moneda . ' - ' . $this->nombre_moneda . ' - Tasa: ' . formatNumber($this->tasa_cambio,2), 0, 1, 'C');
        $this->SetFont('Arial', 'B', 7);
        $cadena = iconv("UTF-8", "ISO-8859-1",  'Descripción: ' . $this->desc_comp);
        $this->Cell(30, 10,  $cadena , 0, 0);

// Titulos
        $this->SetFont('Arial','B', 7);
        $this->ln(10);
        $this->cell(25, 4, 'CUENTA     ', 0, 0);
        $this->cell(30, 4, 'DESCRIPCION', 0, 0);
        $this->cell(30);
        $this->cell(30, 4, 'AUXILIAR   ', 0, 0);
        $this->cell(30, 4, 'DESCRIPCION', 0, 0);
        $this->cell(30, 4, 'DEBE        ', 0, 0, 'R');
        $this->cell(30, 4, 'HABER       ', 0, 1, 'R');

    }
// Pie de página
    function Footer(){
// Posición: a 1,5 cm del final
        $this->SetY(-10);
        $this->SetFont('Arial','I', 7);
// Número de página
        $this->Cell(0,10,html_entity_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}
// Creación del objeto de la clase heredada
$pdf = new PDF($r);

$pdf->AliasNbPages();
$pdf->AddPage('P', 'Letter');
$rows = 0;
$pdf->ln();
$pdf->SetFont('Arial','',7);
for($i = 0; $i < count($r); $i++){
    $pdf->cell(25, 4, $r[$i]->cod_cta, 0, 0);
    $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($r[$i]->nombre_cta));
    $pdf->cell(30, 4, $cadena, 0, 0);
    $pdf->cell(30);
    $pdf->cell(30, 4, html_entity_decode($r[$i]->cod_aux ?? ""), 0, 0);
    $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($r[$i]->nombre_aux ?? ""));
    $pdf->cell(30, 4, $cadena, 0, 0);
    if($r[$i]->det_tipo == "D"){
        $pdf->cell(30, 4, formatNumber($r[$i]->mon_debe,2), 0, 0, 'R');
        $mon_debe = $mon_debe + $r[$i]->mon_debe;
    }else{
        $pdf->cell(25);
        $pdf->cell(30, 4, formatNumber($r[$i]->mon_habe,2), 0, 0, 'R');
        $mon_habe = $mon_habe +$r[$i]->mon_habe;
    }
    $pdf->ln();
}
$GLOBALS['mon_debe'] = $mon_debe;
$GLOBALS['mon_habe'] = $mon_habe;

$pdf->ln(2);
$pdf->cell(135);
$pdf->SetFont('Arial','B',7);
$pdf->cell(30, 4, 'Total Asiento', 0, 0);
$pdf->cell(-20);
$pdf->SetFont('Arial','UB',7);
$pdf->cell(30, 4, formatNumber($GLOBALS['mon_debe'], 2), 0, 0, 'R');
$pdf->cell(25, 4, formatNumber($GLOBALS['mon_habe'], 2), 0, 0, 'R');

$pdf->ln(5);
$pdf->SetFont('Arial','B', 7);
$cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($r[0]->create_name . ' ' . $r[0]->create_last));
$pdf->Cell(20,10, "Elaborado por: " . $cadena, 0,1, 'L');
if($r[0]->modify_user){
    $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode($r[0]->modify_name . ' ' . $r[0]->modify_last));
    $pdf->Cell(20,10, "Modificado por: " . $cadena, 0,0, 'L');
}

$pdf->Output('', html_entity_decode($filemame));