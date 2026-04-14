<?php
require(FPDF1 . 'fpdf.php');
//Varuiables
$sub_total = 0;
$mon_base = 0;
$mon_exe = 0;
$iva = 0;
$total = 0;

class PDF extends FPDF {
    function cabeceraVertical($cabecera){
        $this->SetXY(10, 10); //Seleccionamos posición
        $this->SetFont('Arial','B',10); //Fuente, Negrita, tamaño
        foreach($cabecera as $columna)
        {
            //Parámetro con valor 2, cabecera vertical
            $this->Cell(30,7, utf8_decode($columna),1, 2 , 'L' );
        }
    }
    function cabeceraHorizontal($cabecera){
        $this->SetXY(10, 70);
        $this->SetFont('Arial','B',10);
        foreach($cabecera as $fila)
        {
            //Atención!! el parámetro valor 0, hace que sea horizontal
            $this->Cell(24,7, utf8_decode($fila),1, 0 , 'L' );
        }
    }
}

$filemame = $r[0]->id_cot . '-' . $r[0]->nom_ent . '.pdf';
$tman_letra = 7;

$pdf = new FPDF();

$pdf->AddPage();



$ruta_logo = IMG .'companies/' . $r[0]->logo;

//Títulos que llevará la cabecera
$miCabecera = array('DESCRIPCIÓN', 'IVA', 'CANT', 'PRECIO', 'TOTAL');

$pdf->Image($ruta_logo, 10, 8, 40);
// Arial bold 15
$pdf->SetFont('Arial','B',$tman_letra);
// Movernos a la derecha
$pdf->Cell(50);
// Título
$pdf->cell(120,10,utf8_decode('RIF: ' . $r[0]->rif_empresa), 0, 1, 'C');
$pdf->Cell(50);
$pdf->MultiCell(120,10,utf8_decode($r[0]->dir_emp), 0, 'C');
$pdf->Ln(15);
// Salto de línea
$pdf->Ln(7);
$pdf->SetFont('Arial','',$tman_letra);
$pdf->cell(20, 4, 'Nombre:');
$pdf->SetFont('Arial','B',$tman_letra);
$pdf->cell(30, 4, utf8_decode($r[0]->nom_ent), 0);
$pdf->Cell(60);
$pdf->SetFont('Arial','',$tman_letra, 0, 1);
$pdf->cell(20, 4, 'Fecha:', 0);
$pdf->SetFont('Arial','B',$tman_letra);
$pdf->cell(20, 4, formatFecha($r[0]->fecha_comp), 0, 1);
$pdf->SetFont('Arial','',$tman_letra, 0, 1);
$pdf->cell(20, 4, 'RIF:');
$pdf->SetFont('Arial','B',$tman_letra);
$pdf->cell(30, 4, utf8_decode($r[0]->rif_ent));
$pdf->Cell(60);
$pdf->SetFont('Arial','',$tman_letra);
$pdf->Cell(20, 4,utf8_decode('Cotización Nro.: '), 0);
$pdf->SetFont('Arial','B',$tman_letra);
$pdf->Cell(20, 4,utf8_decode($r[0]->num_tdo), 0, 1);

$pdf->SetFont('Arial','',$tman_letra);
$pdf->cell(20, 4, utf8_decode('Dirección:'));
$pdf->SetFont('Arial','B',$tman_letra);
$pdf->MultiCell(100, 4, utf8_decode($r[0]->dir_ent . ' ' . strtoupper($r[0]->nombre_ciudad) . ' ' . strtoupper($r[0]->nombre_edo) . ' ' . strtoupper($r[0]->nombre_pais)));
if($r[0]->postal_ent != ' '){
    $pdf->SetFont('Arial','',$tman_letra);
    $pdf->cell(20, 4, 'Zona postal:');
    $pdf->SetFont('Arial','B',$tman_letra);
    $pdf->cell(30, 4, utf8_decode($r[0]->postal_ent));
}
$pdf->Cell(60);
$pdf->SetFont('Arial','',$tman_letra);
$pdf->Cell(20, 4,'Moneda:', 0);
$pdf->SetFont('Arial','B',$tman_letra);
$pdf->Cell(20, 4, $r[0]->codigo_moneda, 0);
$pdf->Ln(5);
$pdf->SetXY(10, 70);
$pdf->SetFont('Arial','B', $tman_letra);

$pdf->Line(10, 70, $width -10, 70);
//Métodos llamados con el objeto $pdf
//foreach($miCabecera as $fila)
//{
//    //Atención!! el parámetro valor 0, hace que sea horizontal
//    $pdf->Cell(40,7, utf8_decode($fila),0, 0 , 'C' );
//}
//

$pdf->cell(100, 4, utf8_decode('DESCRIPCIÓN'), 0, 0, 'C');
$pdf->Cell(20);
$pdf->cell(5, 4, utf8_decode('IVA'), 0, 0, 'C');
$pdf->cell(30, 4, utf8_decode('CANT.'), 0, 0, 'C');
$pdf->cell(8, 4, utf8_decode('PRECIO'), 0, 0, 'C');
$pdf->cell(32, 4, utf8_decode('TOTAL'), 0, 0, 'C');

$pdf->ln(5);
$pdf->Line(10, 75, $width -10, 75);


$pdf->SetFont('Arial','',$tman_letra);
for ($i = 0; $i < count($r); $i++) {
    $pdf->MultiCell(90, 4, utf8_decode($r[$i]->nom_prod));
    $pdf->ln(-5);
    $pdf->Cell(120);
    $pdf->cell(5, 4, utf8_decode($r[$i]->iva_prod ? '16.00' : '(E)'), 0, 0, 'C');
    $pdf->cell(20, 4, $r[$i]->can_det, 0, 0, 'R');
    $pdf->cell(20, 4, number_format($r[$i]->pre_vta, 2), 0, 0, 'R');
    $pdf->cell(20, 4, number_format($r[$i]->sub_total, 2), 0, 0, 'R');
    //Acumular variables
    $sub_total += $r[$i]->sub_total;
    if($r[$i]->iva_prod){
        $mon_base += $r[$i]->sub_total;
        $mon_exe += 0;
    }else{
        $mon_base += 0;
        $mon_exe += $r[$i]->sub_total;
    }
    //Realizar rompimiento cada 43 registros
    $rows++;
    if ($rows == 35) {
        $rows = 0;
        $pdf->AddPage('P', 'Letter');
    }
    $pdf->Ln();
}
//Footer
$pdf->ln(150);
$pdf->cell(130);
$pdf->SetFont('Arial','B', $tman_letra);
$pdf->cell(20, 4, utf8_decode('Sub-Total'), 0, 0, 'L');
$pdf->cell(35, 4, number_format(($mon_base + $mon_exe), 2), 0, 0, 'R');
$pdf->ln();
$pdf->cell(130);
$pdf->cell(20, 4, utf8_decode('Sub-Total Excento'), 0, 0, 'L');
$pdf->cell(35, 4, number_format($mon_exe, 2), 0, 0, 'R');
$pdf->ln();
$pdf->cell(130);
$pdf->cell(20, 4, utf8_decode('Sub-Total Base Imponible'), 0, 0, 'L');
$pdf->cell(35, 4, number_format($mon_base, 2), 0, 0, 'R');
$pdf->ln();
$pdf->cell(130);
$pdf->cell(20, 4, utf8_decode('Sub-Total IVA Sobre '.number_format($mon_base,2).' 16,00%'), 0, 0, 'L');
$mon_iva = $mon_base * (16 / 100);
$pdf->cell(35, 4, number_format($mon_iva, 2), 0, 0, 'R');
$pdf->ln();
$pdf->cell(130);
$pdf->cell(20, 4, utf8_decode('Total'), 0, 0, 'L');
$pdf->cell(35, 4, number_format($mon_base + $mon_exe + $mon_iva, 2), 0, 0, 'R');
//Salida del DPF
$pdf->Output('', $filemame);