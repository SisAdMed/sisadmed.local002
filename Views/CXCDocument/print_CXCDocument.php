<?php
require(FPDF1 . 'fpdf.php');
//Variables
$sub_total = 0;
$mon_base = 0;
$mon_exe = 0;
$iva = 0;
$total = 0;
$filemame = $r[0]->nombre_emp . ' - '. $r[0]->nom_ent . ' - '. $r[0]->nom_tdoc . ' - ' . $r[0]->num_tdo . '.pdf';
$tman_letra = 7;
$GLOBALS['moneda_cot'] = $r[0]->codigo_moneda;
$GLOBALS['moneda_emp'] = $r[0]->moneda_emp;
$GLOBALS['tasa_cambio'] = $r[0]->tasa_cambio;
$GLOBALS['sub_total'] = 0;
$GLOBALS['mon_exe'] = 0;
$GLOBALS['mon_base'] = 0;

class PDF extends FPDF{
    public $rif_empresa, $ruta_logo, $dir_emp, $nom_ent, $fecha_comp, $rif_ent, $num_tdo, $codigo_moneda;
    public $dir_ent, $nombre_ciudad, $nombre_edo, $nombre_pais, $postal_ent, $moneda_cot, $moneda_emp;
    public $width, $height, $nom_tdoc, $tasa_cambio, $doc_afectado, $porciones, $id_emp;
    //Totales
    public $sub_total_foot;

    public function __construct($r){
        parent::__construct();
        $this->rif_empresa = $r[0]->rif_empresa;
        $this->ruta_logo = IMG .'companies/' . $r[0]->logo;
        $this->dir_emp = htmlentities(($r[0]->dir_emp));
        $this->nom_ent = htmlentities($r[0]->nom_ent);
        $this->fecha_comp = formatFecha($r[0]->fecha_comp);
        $this->rif_ent = $r[0]->rif_ent;
        $this->num_tdo = $r[0]->num_tdo;
        $this->nom_tdoc = $r[0]->nom_tdoc;
        $this->codigo_moneda = $r[0]->codigo_moneda;
        $this->dir_ent = $r[0]->dir_ent;
        $this->nombre_ciudad = $r[0]->nombre_ciudad;
        $this->nombre_edo = $r[0]->nombre_edo;
        $this->nombre_pais = $r[0]->nombre_pais;
        $this->postal_ent = $r[0]->postal_ent;
        $this->moneda_cot = $r[0]->codigo_moneda;
        $this->tasa_cambio = $r[0]->tasa_cambio;
        $this->moneda_emp = $r[0]->moneda_emp;
        $this->doc_afectado = $r[0]->doc_afectado;
        if($this->doc_afectado){
            $this->porciones = explode("/", $this->doc_afectado);
        }
        $this->id_emp = $r[0]->id_emp;
        $this->width = $this->GetPageWidth();
        $this->height = $this->GetPageHeight();
    }
    // Cabecera de página
    function Header(){
        if ($this->id_emp == 1) {
            $this->Ln(35);
        } elseif ($this->id_emp == 3) {
            $this->Ln(44);
        } elseif ($this->id_emp == 4) {
            $this->Ln(54);
        } else {
            $this->Ln(30);
        }
        // Logo
        //$this->Image($this->ruta_logo,10, 8, 50);
        // Arial bold 15
        //$this->SetFont('Arial','B', 7);
        // Movernos a la derecha
        //$this->Cell(100);
        // Rif
        //$this->Cell(30,10, 'RIF: '. $this->rif_empresa,0,1,'C' );
        //Dirección
        //$this->Cell(60);
        //$this->MultiCell(0,4, $this->dir_emp, 0, 'C');
        // Salto de línea
        //$this->Ln(35);
        //Nombre Cliente
        $this->SetFont('Arial','',7);
        $this->cell(20, 4, 'Nombre:');
        $this->SetFont('Arial','B',7);
        $this->cell(30, 4, htmlentities($this->nom_ent), 0);
        //Fecha
        $this->Cell(60);
        $this->SetFont('Arial','',7, 0, 1);
        $this->cell(20, 4, 'Fecha:', 0);
        $this->SetFont('Arial','B',7);
        $this->cell(20, 4, formatFecha($this->fecha_comp), 0, 0);
        //Moneda
        $this->Cell(5);
        if($this->codigo_moneda != $this->moneda_emp){
            $this->SetFont('Arial','B',7, 0, 1);
            $this->cell(20, 4, 'Moneda: ' . $this->codigo_moneda, 0, 1);
        }else{
            $this->Ln(4);
        }
        //Documento Afectado
        //if($this->doc_afectado != ''){
           // $this->cell(60);
            //$this->SetFont('Arial','',7, 0, 1);
            //$this->cell(20, 4, 'Fecha:', 0);
        //}
        //RIF
        $this->SetFont('Arial','',7, 0, 1);
        $this->cell(20, 4, 'RIF:');
        $this->SetFont('Arial','B',7);
        $this->cell(30, 4, htmlentities($this->rif_ent));
        //Cotización
        $this->Cell(60);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 4,htmlentities($this->nom_tdoc .':'), 0); 
        $this->Cell(10);
        $this->SetFont('Arial','B',7);
        $this->Cell(35, 4,htmlentities($this->num_tdo), 0, 1);
   
        //Dirección
        $this->SetFont('Arial','',7);
        $this->cell(20, 4, mb_convert_encoding('Dirección:', 'ISO-8859-1', 'UTF-8'));

        if($this->codigo_moneda != $this->moneda_emp){
            $this->cell(90);
            $this->SetFont('Arial','B',7);
            $this->cell(20, 4, 'Cambio Bs.: ' . number_format($this->tasa_cambio,8), 0, 1);
            $this->SetFont('Arial','',7);
        }else{
            $this->Ln(4);
        }

        $this->SetFont('Arial','B',7);
        $this->MultiCell(90, 4, htmlentities($this->dir_ent . ' ' . strtoupper($this->nombre_ciudad) . ' ' . strtoupper($this->nombre_edo) . ' ' . strtoupper($this->nombre_pais)));
        if($this->doc_afectado != null){
            $this->cell(80);
            $this->cell(40, 4, 'Documento afectado Factura: ' . $this->porciones[0] . ' de fecha  ' . $this->porciones[1] . ' Monto ' . $this->porciones[2] . ' Control ' . $this->porciones[3], 0, 1);
        }
        //Titulos
        $this->ln();
        //$this->Line(10, 63, $this->width -10, 63);
        $this->cell(100, 4, mb_convert_encoding('DESCRIPCIÓN', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
        $this->Cell(20);
        $this->cell(5, 4, htmlentities('IVA'), 0, 0, 'C');
        $this->cell(15, 4, htmlentities('CANT.'), 0, 0, 'C');
        $this->cell(10, 4, htmlentities('PRECIO'), 0, 0, 'R');
        if($this->moneda_cot != $this->moneda_emp){
            $this->cell(20, 4, htmlentities('TOTAL USD'), 0, 0, 'R', );
            $this->cell(20, 4, htmlentities('TOTAL VEB'), 0, 0, 'R', );
        }else{
            $this->cell(40, 4, htmlentities('TOTAL VEB'), 0, 0, 'R', );
        }
        //$this->Line(10, 66.5, $this->width -10, 66.5);
        $this->ln(5);
    }

    // Pie de página
    function Footer(){
        // Posición: a 4 cm para los totales
        //if($GLOBALS['sub_total'] != 0){
        $this->SetY(-40);
        $this->cell(100);
        $this->SetFont('Arial','B', 7);
        $this->cell(20, 4, htmlentities('Sub-Total'), 0, 0, 'L');
        if($GLOBALS['moneda_cot'] != $GLOBALS['moneda_emp']){
            //Sub-total
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['sub_total'], 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['sub_total'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 0, 1, 'R');
            //Exento
            $this->cell(100);
            $this->cell(20, 4, htmlentities('Sub-Total Exento'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_exe'], 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['mon_exe'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 0, 1, 'R');
            //Base imponible
            $this->cell(100);
            $this->cell(20, 4, htmlentities('Sub-Total Base Imponible'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_base'], 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['mon_base'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 0, 1, 'R');
            //IVA
            $this->cell(100);
            $this->cell(20, 4, htmlentities('IVA 16.00% sobre ') . number_format($GLOBALS['mon_base'],2), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_base'] * (16/100), 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format(($GLOBALS['mon_base'] * (16/100)) * $GLOBALS['tasa_cambio'], 2, ",", "."), 0, 1, 'R');
            //Total
            $this->cell(100);
            $this->cell(20, 4, htmlentities('Total'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format(($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * (16/100)), 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4,'Bs ' .  number_format((($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * (16/100))) * $GLOBALS['tasa_cambio'],2, ",", "."), 0, 1 , 'R');
        }else{
            //Sub-total
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['sub_total'], 2, ",", "."), 0, 1, 'R');
            //Exento
            $this->cell(100);
            $this->cell(20, 4, htmlentities('Sub-Total Exento'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_exe'], 2, ",", "."), 0, 1, 'R');
            //Base imponible
            $this->cell(100);
            $this->cell(20, 4, htmlentities('Sub-Total Base Imponible'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_base'], 2, ",", "."), 0, 1, 'R');
            //IVA
            $this->cell(100);
            $this->cell(20, 4, htmlentities('IVA 16.00% sobre ') . number_format($GLOBALS['mon_base'],2, ",", "."), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_base'] * (16/100), 2, ",", "."), 0, 1, 'R');
             //Total
            $this->cell(100);
            $this->cell(20, 4, htmlentities('Total'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format(($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * (16/100)), 2, ",", "."), 0, 0, 'R');
        }
        //}
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I', 7);
        // Número de página
        //$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF($r);
$pdf->AliasNbPages();
$pdf->AddPage('P', 'Letter');
$pdf->SetFont('Arial','', $tman_letra);
$rows = 0;
for ($i = 0; $i < count($r); $i++) {
    $pdf->MultiCell(120, 3, htmlentities($r[$i]->nombre_con), 0, 'L');
    $pdf->ln(-3);
    $pdf->Cell(120);
    $pdf->Cell(5, 3, htmlentities($r[$i]->mon_iva ? '16.00' : '(E)'), 0, 0, 'C');
    $pdf->Cell(10, 3, 1, 0, 0, 'R');
    $pdf->Cell(15, 3, number_format($r[$i]->monto, 2), 0, 0, 'R');
    if($r[$i]->codigo_moneda != $r[$i]->moneda_emp){
        $pdf->Cell(20, 3, number_format($r[$i]->monto, 2), 0, 0, 'R');
        $pdf->Cell(20, 3, number_format($r[$i]->monto * $r[$i]->tasa_cambio, 2), 0, 1, 'R');
    }else{
        $pdf->Cell(40, 3, number_format($r[$i]->monto * $r[$i]->tasa_cambio, 2), 0, 1, 'R');
    }
    //Acumular variables
     $sub_total += $r[$i]->monto;
    if($r[$i]->mon_iva){
        $mon_base += $r[$i]->monto;
        $mon_exe += 0;
    }else{
        $mon_base += 0;
        $mon_exe += $r[$i]->monto;
    }
    //Realizar rompimiento cada 50 registros
    $rows++;
    if($rows == 50){
        $rows = 0;
        $pdf->AddPage('P', 'Letter');
    }
    $GLOBALS['sub_total'] = $sub_total;
}
$GLOBALS['sub_total'] = $sub_total;
$GLOBALS['mon_exe'] = $mon_exe;
$GLOBALS['mon_base'] = $mon_base;

$pdf->Output('', htmlentities($filemame));
?>