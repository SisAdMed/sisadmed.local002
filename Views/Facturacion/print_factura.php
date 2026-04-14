<?php
    require(FPDF1 . 'fpdf.php');
    include_once VARTAX;

    //Variables
    $sub_total = 0;
    $mon_base = 0;
    $mon_exe = 0;
    $iva = 0;
    $total = 0;
    $filemame = $r[0]->num_tdo . '-' . $r[0]->nom_ent . '.pdf';
    $tman_letra = 6.5;
    $foraneo = false;
    $GLOBALS['moneda_cot'] = $r[0]->codigo_moneda;
    $GLOBALS['moneda_emp'] = $r[0]->moneda_emp;
    $GLOBALS['tasa_cambio'] = $r[0]->tasa_cambio;
    $GLOBALS['sub_total'] = 0;
    $GLOBALS['mon_exe'] = 0;
    $GLOBALS['mon_base'] = 0;
    $GLOBALS['note_fac'] = $r[0]->note_fac;
    $GLOBALS['note_fac_custom'] = $r[0]->note_fac_custom ?? '';
    $GLOBALS['descrip_cot'] = $r[0]->descrip_cot ?? '';
    $tasa_iva_cfg = VatTaxModel::ratevatTax($r[0]->fecha_comp, 'IVA');
    $GLOBALS['tasa_iva'] = $tasa_iva_cfg[0]['txr1_iva'];

    class PDF extends FPDF{
        public $rif_empresa, $ruta_logo, $dir_emp, $nom_ent, $fecha_comp, $rif_ent, $num_tdo, $codigo_moneda;
        public $dir_ent, $nombre_ciudad, $nombre_edo, $nombre_pais, $postal_ent, $moneda_cot, $moneda_emp;
        public $width, $height, $nom_tdoc, $tasa_cambio, $oc_cliente, $diascre, $id_emp, $doc_afe, $id_ent;
        //Totales
        public $sub_total_foot;
        protected $col = 0; // Columna actual
        protected $y0;      // Ordenada de comienzo de la columna
        public function __construct($r){
            parent::__construct();
            $this->rif_empresa = $r[0]->rif_empresa;
            $this->ruta_logo = IMG .'companies/' . $r[0]->logo;
            $this->dir_emp = html_entity_decode($r[0]->dir_emp);
            $this->nom_ent = html_entity_decode($r[0]->nom_ent);
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
            $this->diascre = $r[0]->diascre;
            $this->width = $this->GetPageWidth();
            $this->height = $this->GetPageHeight();
            $this->oc_cliente = $r[0]->oc_cliente;
            $this->id_emp = $r[0]->id_emp;
            $this->doc_afe = $r[0]->doc_afe;
            $this->id_ent = $r[0]->id_ent;
           
        }
        // Cabecera de página
        function Header(){
        if($this->id_emp == 1){
            $this->Ln(35);
        }elseif($this->id_emp == 3){
            $this->Ln(44);
        }elseif($this->id_emp == 4) {
            $this->Ln(54);
        }else{
            $this->Ln(30);
        }
        //Nombre Cliente
        $this->SetFont('Arial','',7);
        $this->cell(20, 4, 'Nombre:');
        $this->SetFont('Arial','B',7);
        $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode(substr($this->nom_ent, 0,60)));
        $this->cell(30, 4, $cadena, 0);
        //Fecha
        $this->Cell(60);
        $this->SetFont('Arial','',7, 0, 1);
        $this->cell(20, 4, 'Fecha:', 0);
        $this->SetFont('Arial','B',10);
        $this->cell(20, 4, formatFecha($this->fecha_comp), 0, 0);
        $this->SetFont('Arial','B',7);
        //Moneda
        $this->Cell(5);
        if($this->codigo_moneda != $this->moneda_emp){
            $foraneo = true;
            $this->SetFont('Arial','B',7, 0, 1);
            $this->cell(20, 4, 'Moneda: ' . $this->codigo_moneda, 0, 1);
        }else{
            $this->Ln(4);
        }
        if(substr($this->nom_ent, 60,120) > ''){
            $this->SetFont('Arial','B',7);
            $this->cell(30, 4, html_entity_decode(substr($this->nom_ent, 60,120)), 0,1);
        }
        //RIF
        $this->SetFont('Arial','',7, 0, 1);
        $this->cell(20, 4, 'RIF:');
        $this->SetFont('Arial','',7);
        $this->cell(30, 4, html_entity_decode($this->rif_ent));
        //Factura
        $this->Cell(60);
        $this->SetFont('Arial','',7);
        $this->Cell(25, 4,html_entity_decode($this->nom_tdoc) .':', 0);
        $this->SetFont('Arial','B',10);
        $this->Cell(20, 4,html_entity_decode($this->num_tdo), 0); 
        $this->SetFont('Arial','B',7);
        if($this->codigo_moneda != $this->moneda_emp){
            $this->cell(20, 4, 'Tasa de cambio Bs.: ' . number_format($this->tasa_cambio,4, ",", "."), 0, 1);
        }else{
            $this->Ln(4);
        }
        //Dirección
        $this->SetFont('Arial','',7);
        $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Dirección'));
        $this->cell(20, 4, $cadena);
        $this->SetFont('Arial','',7);
        $cadena = iconv("UTF-8", "ISO-8859-1", (($this->dir_ent) . ' ' . strtoupper($this->nombre_ciudad) . ' ' . strtoupper($this->nombre_edo) . ' ' . strtoupper($this->nombre_pais)));
        //Caso cliente Vizcaya, Exception
        if($this->id_ent == 292){
            $cadena = iconv("UTF-8", "ISO-8859-1", (($this->dir_ent) . ' ' . strtoupper($this->nombre_ciudad) . ' ' . strtoupper($this->nombre_edo) . ' ZONA POSTAL ' . strtoupper($this->postal_ent)));    
        }
        $this->MultiCell(90, 4, $cadena);
        
        $this->SetFont('Arial','',7);
        $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Condición de pago'));
        $this->cell(20, 4, $cadena);
        $this->SetFont('Arial','',7);
        $this->cell(2);
        $this->cell(50, 4, html_entity_decode($this->diascre));
        $this->Cell(16);
        $this->SetFont('Arial','B',7);
        $this->Cell(50, 4,html_entity_decode($this->doc_afe), 0, 1);
        //Orden de Compra
        if(!empty($this->oc_cliente)){
            $this->ln();
            $this->cell(22, 4, html_entity_decode('Orden de Compra:'));
            $this->cell(50, 4, html_entity_decode($this->oc_cliente));
        }
        //Titulos
        $this->ln();
        //$this->line(10, 80, 10, 80);
        //$this->Line(10, 64, 210-10, 64);
        //$this->Line(10, 64, 210-10, 64);
        //$this->Line(10, 64, $this->width -10, 64);
        //$this->Line(10, 64, $this->width -10, 64);
        $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('Descripción'));
        $this->cell(80, 4, $cadena, 0, 0, 'C');
        //$this->Cell(5);
        if($this->moneda_cot != $this->moneda_emp){
            $this->Cell(10);
            $this->cell(5, 4, html_entity_decode('IVA'), 0, 0, 'C');
            $this->cell(15, 4, html_entity_decode('CANT.'), 0, 0, 'C');
            $this->cell(20, 4, html_entity_decode('PRECIO USD'), 0, 0, 'R');
            $this->cell(20, 4, html_entity_decode('PRECIO VEB'), 0, 0, 'R');
            $this->cell(20, 4, html_entity_decode('TOTAL USD'), 0, 0, 'R', );
            $this->cell(20, 4, html_entity_decode('TOTAL VEB'), 0, 0, 'R', );
        }else{
            $this->Cell(50);
            $this->cell(5, 4, html_entity_decode('IVA'), 0, 0, 'C');
            $this->cell(15, 4, html_entity_decode('CANT.'), 0, 0, 'C');
            $this->cell(20, 4, html_entity_decode('PRECIO VEB'), 0, 0, 'R');
            $this->cell(20, 4, html_entity_decode('TOTAL VEB'), 0, 0, 'R', );
        } 
        //$this->Line(10, 67.5, $this->width -10, 67.5);
        //$this->Line(10, 67.5, $this->width -10, 67.5);
        $this->ln(5);
         // Guardar ordenada
        $this->y0 = $this->GetY();
    }

    // Pie de8 página
    function Footer(){
        // Pie de página
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(128);
        //$this->Cell(0,10, html_entity_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
    function SetCol($col){
        // Establecer la posición de una columna dada
        $this->col = $col;
        $x = 10+$col*65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }
    function PrintChapter($file){
        // Añadir capítulo
        $this->ChapterBody($file);
        // Guardar ordenada
        $this->y0 = $this->GetY();
    }
    function ChapterBody($file){
        // Abrir fichero de texto
        $txt = $file;
        // Fuente
        $this->SetY(-50);
        $this->SetFont('Arial','',6);
        // Imprimir texto en una columna de 6 cm de ancho
    
        $cadena = iconv("UTF-8", "ISO-8859-1", $txt);
        $this->MultiCell(100,3,html_entity_decode($cadena), 0, 'J');
        $this->Ln();
        // Volver a la primera columna
        $this->SetCol(0);
    }
    function PrintTotal(){
          // Posición: a 4 cm para los totales
        //if($GLOBALS['sub_total'] != 0){
        $this->SetY(-45);
        $this->cell(100);
        $this->SetFont('Arial','B', 7);
        $this->cell(20, 4, html_entity_decode('Sub-Total'), 0, 0, 'L');
        if($GLOBALS['moneda_cot'] != $GLOBALS['moneda_emp']){
            //Sub-total
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['sub_total'], 2, ",", "."), 'T', 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['sub_total'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 'T', 1, 'R');
            //Exento
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Sub-Total Exento'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_exe'], 2, ",", "."), 'T', 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['mon_exe'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 'T', 1, 'R');
            //Base imponible
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Sub-Total Base Imponible'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_base'], 2, ",", "."), 'T', 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['mon_base'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 'T', 1, 'R');
            //IVA
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('IVA '.$GLOBALS['tasa_iva'].'% sobre ') . number_format($GLOBALS['mon_base'],2), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100), 2, ",", "."), 'T', 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format(($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100)) * $GLOBALS['tasa_cambio'], 2, ",", "."), 'T', 1, 'R');
            //Total
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Total'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format(($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100)), 2, ",", "."), 'T', 0, 'R');
            $this->Cell(20, 4,'Bs ' .  number_format((($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100))) * $GLOBALS['tasa_cambio'],2, ",", "."), 'T', 1 , 'R');
        }else{
            //Sub-total
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['sub_total'], 2, ",", "."), 'T', 1, 'R');
            //Exento
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Sub-Total Exento'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_exe'], 2, ",", "."), 'T', 1, 'R');
            //Base imponible
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Sub-Total Base Imponible'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_base'], 2, ",", "."), 'T', 1, 'R');
            //IVA
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('IVA '.$GLOBALS['tasa_iva'].'% sobre ') . number_format($GLOBALS['mon_base'],2), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100), 2, ",", "."), 'T', 1, 'R'); 
            //Total
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Total'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format(($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100)), 2, ",", "."), 'T,B', 0, 'R');
        }
    }
}

// Creación del objeto de la clase heredada
$pdf = new PDF($r);
$pdf->AliasNbPages();
$pdf->AddPage('P', 'Letter');
$pdf->SetFont('Arial','', $tman_letra);
$rows = 0;
for ($i = 0; $i < count($r); $i++) {
    $nom_fab = is_null($r[$i]->nom_fab_fac) ? $r[$i]->nom_fab : $r[$i]->nom_fab_fac;
    if($r[$i]->print_lote == 1 && $r[$i]->lote_prod == 1){
        $lote = FacturacionModel::lote($r[$i]->id_cot, $r[$i]->id_prod);
        $num_lote = $lote['lote'];
        $fec_venc = formatFecha($lote['fec_venc']);
        $nom_prod = html_entity_decode($r[$i]->nom_prod) . ' Marca ' . html_entity_decode($nom_fab) . ' Ref. ' . html_entity_decode($r[$i]->ref_prod) . ' Lote: ' . $num_lote . ' Fec.Venc. ' . $fec_venc;
    }else if($r[$i]->id_emp == 1) {
        //$nom_prod = html_entity_decode($r[$i]->nom_prod) . ' Marca ' . html_entity_decode($r[$i]->nom_fab) . ' Ref. ' . html_entity_decode($r[$i]->ref_prod);
        if($r[$i]->nom_fab == 'EUROFARM'){
            $nom_prod = html_entity_decode($r[$i]->nom_prod) . ' Ref. ' . html_entity_decode($r[$i]->ref_prod);
        }else{
            $nom_prod = html_entity_decode($r[$i]->nom_prod) . ' Marca ' . html_entity_decode($nom_fab) . ' Ref. ' . html_entity_decode($r[$i]->ref_prod);
        }
    }else{
        $nom_prod = html_entity_decode($r[$i]->nom_prod) . ' Marca ' . html_entity_decode($nom_fab) . ' Ref. ' . html_entity_decode($r[$i]->ref_prod);
        //17-09-2025 Alejandra. Quitar Referencia para que quepa en la factura una sola pagina
        //$nom_prod = html_entity_decode($r[$i]->nom_prod) . ' Marca ' . html_entity_decode($nom_fab);
    }
    $nom_prod = iconv("UTF-8", "ISO-8859-1", $nom_prod);
    if($r[$i]->codigo_moneda != $r[$i]->moneda_emp){
        $pdf->MultiCell(90, 3, ($nom_prod), 0, 'L');
        $pdf->ln(-3);
        $pdf->Cell(90);
        if($r[$i]->iva_prod == 'S'){
            $pdf->Cell(5, 3, html_entity_decode( number_format($GLOBALS['tasa_iva'], 2, ",", ".")), 0, 0, 'C');
        }else{
            $pdf->Cell(5, 3, html_entity_decode("(E)"), 0, 0, 'C');
        }
        $pdf->Cell(10, 3, $r[$i]->can_det, 0, 0, 'R');
        $pdf->Cell(5);
        $pdf->Cell(20, 3, number_format(($r[$i]->sub_total /  abs($r[$i]->can_det)), 4, ",", "."), 0, 0, 'R');
        $pdf->Cell(20, 3, number_format(($r[$i]->sub_total / abs($r[$i]->can_det))* $r[$i]->tasa_cambio, 4, ",", "."), 0, 0, 'R');
        $pdf->Cell(20, 3, number_format($r[$i]->sub_total, 2, ",", "."), 0, 0, 'R');
        $pdf->Cell(20, 3, number_format($r[$i]->sub_total * $r[$i]->tasa_cambio, 2, ",", "."), 0, 1, 'R');
    }else{
        $pdf->MultiCell(120, 3, ($nom_prod), 0, 'L');
        $pdf->ln(-3);
        $pdf->Cell(130);
        if($r[$i]->iva_prod == 'S'){
            $pdf->Cell(5, 3, html_entity_decode( number_format($GLOBALS['tasa_iva'], 2, ",", ".")), 0, 0, 'C');
        }else{
            $pdf->Cell(5, 3, html_entity_decode("(E)"), 0, 0, 'C');
        }
        $pdf->Cell(10, 3, $r[$i]->can_det, 0, 0, 'R');
        $pdf->Cell(5);
        $pdf->Cell(20, 3, number_format($r[$i]->sub_total / $r[$i]->can_det, 4, ",", "."), 0, 0, 'R');
        $pdf->Cell(20, 3, number_format($r[$i]->sub_total, 2, ",", "."), 0, 1, 'R');
    }
    //Acumular variables
    $sub_total += $r[$i]->sub_total;
    if($r[$i]->iva_prod == 'S'){
        $mon_base += $r[$i]->sub_total;
        $mon_exe += 0;
    }else{
        $mon_base += 0;
        $mon_exe += $r[$i]->sub_total;
    }
    //Realizar rompimiento cada 43 registros
    $rows++;
    if($rows == 40){
        $rows = 0;
        $pdf->AddPage('P', 'Letter');
    }
    $GLOBALS['sub_total'] = $sub_total;
}
$GLOBALS['sub_total'] = $sub_total;
$GLOBALS['mon_exe'] = $mon_exe;
$GLOBALS['mon_base'] = $mon_base;

$pdf->PrintChapter($GLOBALS['note_fac'] . ' ' . $GLOBALS['note_fac_custom'] . ' ' . $GLOBALS['descrip_cot']);
$pdf->PrintTotal();

$pdf->Output('', html_entity_decode($filemame));
?>