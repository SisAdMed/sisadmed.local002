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
    $tman_letra = 7;
    $total_cant = 0;
    $GLOBALS['moneda_cot'] = $r[0]->codigo_moneda;
    $GLOBALS['moneda_emp'] = $r[0]->moneda_emp;
    $GLOBALS['tasa_cambio'] = $r[0]->tasa_cambio;
    $GLOBALS['sub_total'] = 0;
    $GLOBALS['mon_exe'] = 0;
    $GLOBALS['mon_base'] = 0;
    $GLOBALS['note_fac'] = $r[0]->note_not_no_fis;
    $GLOBALS['note_fac_custom'] = $r[0]->note_fac_custom ?? '';
    $GLOBALS['descrip_cot'] = $r[0]->descrip_cot ?? '';

    $tasa_iva_cfg = VatTaxModel::ratevatTax($r[0]->fecha_comp, 'IVA');
    $GLOBALS['tasa_iva'] = $tasa_iva_cfg[0]['txr1_iva'];

    class PDF extends FPDF{
        public $rif_empresa, $ruta_logo, $dir_emp, $nom_ent, $fecha_comp, $rif_ent, $num_tdo, $codigo_moneda;
        public $dir_ent, $nombre_ciudad, $nombre_edo, $nombre_pais, $postal_ent, $moneda_cot, $moneda_emp;
        public $width, $height, $nom_tdoc, $tasa_cambio, $oc_cliente, $nombre_emp, $email_emp;
        //Totales
        public $sub_total_foot;
        protected $col = 0; // Columna actual
        protected $y0;      // Ordenada de comienzo de la columna
        public function __construct($r){
            parent::__construct();
            $this->nombre_emp = $r[0]->nombre_emp;
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
             $this->email_emp = $r[0]->email_emp;
            $this->width = $this->GetPageWidth();
            $this->height = $this->GetPageHeight();
            $this->oc_cliente = $r[0]->oc_cliente;
        }
        // Cabecera de página
        function Header(){
              // Logo
        $this->Image($this->ruta_logo,10, 8, 50, 0, 'PNG');
        // Arial bold 15
        $this->SetFont('Arial','B', 7);
        // Movernos a la derecha
        $this->Cell(100);
        // Nombre empresa
        $this->SetFont('Arial','B', 14);
        $this->Cell(30,10, $this->nombre_emp,0,1,'C');
        $this->Cell(100);
        $this->Cell(30,10, 'RIF: '. $this->rif_empresa,0,'C' );
        // Rif
        $this->SetFont('Arial','B', 7);
        //Dirección
        $this->Cell(-60);
        $this->MultiCell(0,4, $this->dir_emp, 0, 'C');
        $this->Cell(100);
        $this->Cell(30,10, $this->email_emp,0,1,'C');
        $this->Ln(5);
        $this->SetFont('Arial','B',10);
        $this->cell(200, 4, 'NOTA DE ENTREGA', 0, 0, 'C');
        $this->Ln(25);
        //Nombre Cliente
        $this->SetFont('Arial','',7);
        $this->cell(20, 4, 'Nombre:');
        $this->SetFont('Arial','B',7);
        $this->cell(30, 4, html_entity_decode($this->nom_ent), 0);
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
        //RIF
        $this->SetFont('Arial','',7, 0, 1);
        $this->cell(20, 4, 'RIF:');
        $this->SetFont('Arial','B',7);
        $this->cell(30, 4, html_entity_decode($this->rif_ent));
        //Factura
        $this->Cell(60);
        $this->SetFont('Arial','',7);
        //$this->Cell(20, 4,html_entity_decode($this->nom_tdoc), 0);
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Número');
        $this->Cell(20, 4,$cadena, 0);
        $this->SetFont('Arial','B',7);
        $this->Cell(20, 4,html_entity_decode($this->num_tdo), 0);
        if($this->codigo_moneda != $this->moneda_emp){
            $this->cell(20, 4, 'Tasa de cambio Bs.: ' . number_format($this->tasa_cambio,4, ",", "."), 0, 1);
        }else{
            $this->Ln(4);
        }
        //Dirección
        $this->SetFont('Arial','',7);
        $cadena = iconv("UTF-8", "ISO-8859-1", 'Dirección:');
        $this->cell(20, 4, $cadena);
        $this->SetFont('Arial','B',7);
        $this->MultiCell(90, 4, html_entity_decode($this->dir_ent . ' ' . strtoupper($this->nombre_ciudad) . ' ' . strtoupper($this->nombre_edo) . ' ' . strtoupper($this->nombre_pais)));
       /* $this->SetFont('Arial','',7);
        $this->cell(20, 4, 'Zona postal:');
        $this->SetFont('Arial','B',7);
        $this->cell(30, 4, html_entity_decode($this->postal_ent));*/
         //Orden de Compra
        if(!empty($this->oc_cliente)){
            $this->ln();
            $this->cell(22, 4, html_entity_decode('Orden de Compra:'));
            $this->cell(50, 4, html_entity_decode($this->oc_cliente));
        }
        //Titulos
        $this->ln();
        $cadena = iconv("UTF-8", "ISO-8859-1", 'DESCRIPCIÓN');
        $this->cell(150, 4, $cadena, 0, 0, 'C');
        $this->Cell(20);
        //$this->cell(5, 4, html_entity_decode('IVA'), 0, 0, 'C');
        $this->cell(15, 4, html_entity_decode('CANT.'), 0, 0, 'C');
        /*$this->cell(10, 4, html_entity_decode('PRECIO'), 0, 0, 'R');
        if($this->moneda_cot != $this->moneda_emp){
            $this->cell(20, 4, html_entity_decode('TOTAL USD'), 0, 0, 'R', );
            $this->cell(20, 4, html_entity_decode('TOTAL VEB'), 0, 0, 'R', );
        }else{
            $this->cell(40, 4, html_entity_decode('TOTAL VEB'), 0, 0, 'R', );
        }*/
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
        /*$this->Cell(0,10, html_entity_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');*/
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
        $cadena = iconv("UTF-8", "ISO-8859-1", $txt);
        // Fuente
        $this->SetY(-40);
        $this->SetFont('Arial','',9);
        // Imprimir texto en una columna de 6 cm de ancho
        $this->MultiCell(100,3,html_entity_decode(($cadena)), 0, 'J');
        $this->Ln();
        // Volver a la primera columna
        $this->SetCol(0);
    }
    function PrintTotal(){
          // Posición: a 4 cm para los totales
        //if($GLOBALS['sub_total'] != 0){
        $this->SetY(-50);
        $this->cell(100);
        $this->SetFont('Arial','B', 7);
        $this->cell(20, 4, html_entity_decode('Sub-Total'), 0, 0, 'L');
        if($GLOBALS['moneda_cot'] != $GLOBALS['moneda_emp']){
            //Sub-total
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['sub_total'], 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['sub_total'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 0, 1, 'R');
            //Exento
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Sub-Total Exento'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_exe'], 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['mon_exe'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 0, 1, 'R');
            //Base imponible
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Sub-Total Base Imponible'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_base'], 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format($GLOBALS['mon_base'] * $GLOBALS['tasa_cambio'], 2, ",", "."), 0, 1, 'R');
            //IVA
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('IVA '.$GLOBALS['tasa_iva'].'% sobre ') . number_format($GLOBALS['mon_base'],2, ",", "."), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100), 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4, 'Bs ' . number_format(($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100)) * $GLOBALS['tasa_cambio'], 2, ",", "."), 0, 1, 'R');
            //Total
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Total'), 0, 0, 'L');
            $this->cell(30);
            $this->Cell(20, 4, '$ ' . number_format(($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100)), 2, ",", "."), 0, 0, 'R');
            $this->Cell(20, 4,'Bs ' .  number_format((($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100))) * $GLOBALS['tasa_cambio'],2, ",", "."), 0, 1 , 'R');
        }else{
            //Sub-total
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['sub_total'], 2, ",", "."), 0, 1, 'R');
            //Exento
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Sub-Total Exento'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_exe'], 2, ",", "."), 0, 1, 'R');
            //Base imponible
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Sub-Total Base Imponible'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_base'], 2, ",", "."), 0, 1, 'R');
            //IVA
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('IVA '.$GLOBALS['tasa_iva'].'% sobre ') . number_format($GLOBALS['mon_base'],2, ",", "."), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100), 2, ",", "."), 0, 1, 'R'); 
            //Total
            $this->cell(100);
            $this->cell(20, 4, html_entity_decode('Total'), 0, 0, 'L');
            $this->cell(50);
            $this->Cell(20, 4, number_format(($GLOBALS['mon_base'] + $GLOBALS['mon_exe']) + ($GLOBALS['mon_base'] * ($GLOBALS['tasa_iva']/100)), 2, ",", "."), 0, 0, 'R');
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
    $nom_prod = html_entity_decode($r[$i]->nom_prod) . ' Marca ' . html_entity_decode($nom_fab) . ' Ref. ' . html_entity_decode($r[$i]->ref_prod);
    $pdf->MultiCell(150, 3, html_entity_decode($nom_prod), 0, 'L');
    $pdf->ln(-3);
    $pdf->Cell(170);
    /*if($r[$i]->iva_prod == 'S'){
        $pdf->Cell(5, 3, html_entity_decode( number_format($GLOBALS['tasa_iva'], 2, ",", ".")), 0, 0, 'C');
    }else{
        $pdf->Cell(5, 3, html_entity_decode("(E)"), 0, 0, 'C');
    }*/
    /*$pdf->Cell(5, 3, html_entity_decode($r[$i]->iva_prod ? '16.00' : '(E)'), 0, 0, 'C');*/
    $pdf->Cell(10, 3, $r[$i]->can_det, 0, 1, 'R');
    $total_cant += $r[$i]->can_det;
    /*$pdf->Cell(15, 3, number_format($r[$i]->pre_vta, 4, ",", "."), 0, 0, 'R');
    if($r[$i]->codigo_moneda != $r[$i]->moneda_emp){
        $pdf->Cell(20, 3, number_format($r[$i]->sub_total, 2), 0, 0, 'R');
        $pdf->Cell(20, 3, number_format($r[$i]->sub_total * $r[$i]->tasa_cambio, 2), 0, 1, 'R');
    }else{
        $pdf->Cell(40, 3, number_format($r[$i]->sub_total * $r[$i]->tasa_cambio, 2), 0, 1, 'R');
    }*/
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
$pdf->ln(1);
$pdf->Cell(160);
$pdf->SetFont('Arial','B', $tman_letra);
$pdf->Cell(10, 3, 'TOTAL CANTIDAD', 0, 0, 'R');
$pdf->Cell(10, 3, $total_cant, 'T', 1, 'R');
$GLOBALS['sub_total'] = $sub_total;
$GLOBALS['mon_exe'] = $mon_exe;
$GLOBALS['mon_base'] = $mon_base;

$pdf->PrintChapter($GLOBALS['note_fac'] . ' ' . $GLOBALS['descrip_cot']);
/*$pdf->PrintTotal();*/

$pdf->Output('', html_entity_decode($filemame));
?>