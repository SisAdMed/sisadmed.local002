<?php
// Declaramos la libreria
require(FPDF1 . 'fpdf.php');
//Variables
$filemame = 'RetenciondeIVA.pdf';
$width = 0;
$height = 0;
//Global
$GLOBALS['rif_empresa'] = $r[0]->rif_empresa;
$GLOBALS['fecha_comp'] = formatFecha($r[0]->fecha_pago);
$GLOBALS['usuario'] = $r[0]->ela_por;
//Extender la clase
class PDF extends FPDF{
    //Variables
    public $rif_empresa, $ruta_logo, $dir_emp, $nom_ent, $fecha_comp, $rif_ent, $num_tdo, $codigo_moneda;
    public $dir_ent, $nombre_ciudad, $nombre_edo, $nombre_pais, $postal_ent, $mes, $anio, $por_retiva;
    public $width, $height, $nombre_emp, $num_retiva;
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
        $this->num_retiva = $r[0]->num_retiva;
        $this->anio = $r[0]->anio;
        $this->mes = $r[0]->mes;
        $this->fecha_comp = $r[0]->fecha_comp;
        $this->nom_ent = $r[0]->nom_ent;
        $this->rif_ent = $r[0]->rif_ent;
        $this->por_retiva = $r[0]->por_retiva;
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
        $this->Cell(30, 10, 'RIF: '. $this->rif_empresa,0, 1 ,'C' );
        $this->SetFont('Arial','B', 8);
        $cadena = iconv("UTF-8", "ISO-8859-1", "Decreto con Rango, Valor y Fuerza de Ley de Reforma del Impuesto al Valor Agregado N°. 1.436 del 17 de Noviembre de 2014");
        $this->Cell(0, 10, $cadena ,0, 1 ,'C' );
        $this->SetFont('Arial','', 8);
        $cadena = iconv("UTF-8", "ISO-8859-1", "Art. 11: La Administración Tributarria podrá designar como responsables de pago del impuesto, en calidad de agentes de retención, a quienes por sus funciones publicas o por razón de sus actividades privadas intervengan en operaciones gravadas con el impuesto establecidoen este Decreto con Rango y Fuerza de Ley. (....)");
        $this->Cell(20);
        $this->MultiCell(230, 7, $cadena, 0, 'C' );
        $this->ln(5);
        $this->Cell(180);
        $this->Cell(50, 7, 'NUMERO DE COMPROBANTE', 'BLT', 0, 'C');
        $this->Cell(35, 7, 'FECHA DE EMISION', 'BRT', 1, 'C');
        $this->Cell(180);
        $this->SetFont('Arial','B', 8);
        $this->Cell(50, 7,  $this->anio . $this->mes . str_pad($this->num_retiva, 8, '0', STR_PAD_LEFT ), 'BLT', 0, 'C');
        $this->Cell(35, 7,  formatFecha($this->fecha_comp), 'BRT', 1, 'C');
        $this->SetFont('Arial','B', 8);
        //Datos de empresa
        $this->Cell(120, 7, 'NOMBRES Y APELLIDOS O RAZON SOCIAL DEL AGENTE DE RETENCION', 'TL', 0, 'L');
        $this->Cell(40, 7, 'RIF AGENTE DE RETENCION', 'T', 0, 'L');
        $this->Cell(60, 7, 'TIPO DE PERSONA', 'T', 0, 'L');
        $this->Cell(45, 7, 'PERIODO FISCAL', 'TR', 1, 'L');
        //
        $this->SetFont('Arial','', 8);
        $this->Cell(120, 7, $this->nombre_emp, 'TL', 0, 'L');
        $this->Cell(40, 7, $this->rif_empresa, 'T', 0, 'L');
        $this->Cell(60, 7, 'PERSONA JURIDICA DOMICILIADA', 'T', 0, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", 'MES: ' . $this->mes . ' AÑO: ' . $this->anio);
        $this->Cell(45, 7, $cadena, 'TR', 1, 'L');
        $cadena = "DIRECCION FISCAL DEL AGENTE DE RETENCION";
        $this->SetFont('Arial','B', 8);
        $this->Cell(265, 7, $cadena, 'BTRL', 1, 'C');
        $cadena = iconv("UTF-8", "ISO-8859-1", $this->dir_emp );
        $this->SetFont('Arial','', 8);
        $this->Cell(265, 7, $cadena, 'BTRL', 1, 'L');
        $cadena = "NOMBRES Y APELLIDOS O RAZON SOCIAL DEL SUJETO RETENIDO";
        $this->SetFont('Arial','B', 8);
        $this->Cell(120, 7, $cadena, 'BTL', 0, 'L');
        $this->Cell(105, 7, 'RIF SUJETO RETENIDO', '', 0, 'L');
        $this->Cell(40, 7, 'RETENCION', 'R', 1, 'L');
        $this->SetFont('Arial','', 8);
        $cadena = iconv("UTF-8", "ISO-8859-1", $this->nom_ent );
        $this->Cell(120, 7, $cadena, 'BTL', 0, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", $this->rif_ent );
        $this->Cell(105, 7, $cadena, 'BT', 0, 'L');
        $this->Cell(40, 7, number_format($this->por_retiva, 2, ",", ".") . ' %', 'TR', 1, 'C');


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
        $this->cell(40, 7, 'RIF: ' . $GLOBALS['rif_empresa'], 'B', 0, 'L');
        $this->cell(20, 7, $GLOBALS['fecha_comp'], 'B', 1, 'L');
        $cadena = iconv("UTF-8", "ISO-8859-1", "Agente de Retención (Fecha de Entrega)");
        $this->cell(135, 7, $cadena, 0, 0, 'C');
        $this->Cell(30);
        $cadena = iconv("UTF-8", "ISO-8859-1", "Sujeto Retenido (Fecha de Recepción)");
        $this->cell(60, 7, $cadena, 'T', 1, 'C');
        $cadena = iconv("UTF-8", "ISO-8859-1", $GLOBALS['usuario']);
        $this->cell(135, 7, $cadena, 0, 0, 'C');
        $this->ln(5);
        $cadena = iconv("UTF-8", "ISO-8859-1",'Este Comprobante se emite según lo establecido en el artículo N°. 16 de la Providencia Administrativa SNAT/2015/0049 del 14/07/2015 G.O. 40.720 del 10/08/2015');
        $this->Cell(0, 10, html_entity_decode($cadena), 0, 0, 'C');
    }
}
// Creación del objeto de la clase heredada
$pdf = new PDF($r, $d);
$pdf->AliasNbPages();
$pdf->AddPage('L', 'Letter');
$pdf->SetFont('Arial','B', 8);
$width = $pdf->GetPageWidth();  // Width of Current Page
$height = $pdf->GetPageHeight();

//Titulos
//Primera Línea
$pdf->cell(10,7, html_entity_decode("Nro"), 'TL', 0, 'C');
$pdf->cell(20,7, html_entity_decode("Fecha"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Número"), 'T', 0, 'C');
$pdf->cell(10,7, iconv("UTF-8", "ISO-8859-1", "Número"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Número"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Número"), 'T', 0, 'C');
$pdf->cell(10,7, iconv("UTF-8", "ISO-8859-1", "Tipo"), 'T', 0, 'C');
$pdf->cell(35,7, iconv("UTF-8", "ISO-8859-1", "Total Compras"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Compra sin"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Base"), 'T', 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", "Alícuota"), 'T', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Impuesto"), 'T', 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", "IVA"), 'TR', 0, 'C');
$pdf->ln(3);
//Segunda Línea
$pdf->cell(10,7, html_entity_decode("Oper"), 'L', 0, 'C');
$pdf->cell(20,7, html_entity_decode("Factura"), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Factura"), 0, 0, 'C');
$pdf->cell(10,7, iconv("UTF-8", "ISO-8859-1", "Control"), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Nota Débito"), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Nota Crédito"), 0, 0, 'C');
$pdf->cell(10,7, iconv("UTF-8", "ISO-8859-1", "Trans"), 0, 0, 'C');
$pdf->cell(35,7, iconv("UTF-8", "ISO-8859-1", "Incluyendo IVA"), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Derecho a"), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Imponible"), 0, 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", "IVA %"), 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "IVA"), 0, 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", "Retenido"), 'R', 0, 'C');
$pdf->ln(3);
//Terecra Línea
$pdf->cell(10,7, html_entity_decode(""), 'BL', 0, 'C');
$pdf->cell(20,7, html_entity_decode(""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(10,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(10,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(35,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Crédito IVA"), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", ""), 'B', 0, 'C');
$pdf->cell(15,7, iconv("UTF-8", "ISO-8859-1", ""), 'BR', 1, 'C');
//Poblar tabla con los registros
$pdf->SetFont('Arial','', 8);
$xtot_compras = 0;
$xtot_exento = 0;
$xtot_base = 0;
$xtot_iva = 0;
$xtot_ret = 0;
for($i= 0; $i<count($d);$i++){ 
    $pdf->cell(10,7, html_entity_decode($d[$i]->item), 'BL', 0, 'C');
    $pdf->cell(20,7, html_entity_decode(formatFecha($d[$i]->fecha_pago)), 'B', 0, 'C');
    $pdf->cell(25,7, html_entity_decode(($d[$i]->num_tdo)), 'B', 0, 'C');
    $pdf->cell(10,7, html_entity_decode($d[$i]->num_control), 'B', 0, 'C');
    $pdf->cell(25,7, html_entity_decode($d[$i]->num_debito), 'B', 0, 'C');
    $pdf->cell(25,7, html_entity_decode($d[$i]->num_credito), 'B', 0, 'C');
    $pdf->cell(10,7, html_entity_decode($d[$i]->tipo_tran), 'B', 0, 'C');
    $pdf->cell(35,7, number_format($d[$i]->tot_compras, 2, ",", "."), 'B', 0, 'C');
    $pdf->cell(25,7, number_format($d[$i]->tot_exento, 2, ",", "."), 'B', 0, 'C');
    $pdf->cell(25,7, number_format($d[$i]->tot_base, 2, ",", "."), 'B', 0, 'C');
    $pdf->cell(15,7, number_format($d[$i]->tasa_iva, 2, ",", ".") . " %", 'B', 0, 'C');
    $pdf->cell(25,7, number_format($d[$i]->tot_iva, 2, ",", "."), 'B', 0, 'C');
    $pdf->cell(15,7, number_format($d[$i]->tot_ret, 2, ",", "."), 'BR', 1, 'C');
    //Acumular
    $xtot_compras += $d[$i]->tot_compras;
    $xtot_exento += $d[$i]->tot_exento;
    $xtot_base += $d[$i]->tot_base;
    $xtot_iva += $d[$i]->tot_iva;
    $xtot_ret += $d[$i]->tot_ret;
}
//Totales
$pdf->SetFont('Arial','B', 8);
$pdf->cell(10,7,'', 0, 0, 'C');
$pdf->cell(20,7, '', 0, 0, 'C');
$pdf->cell(25,7, '', 0, 0, 'C');
$pdf->cell(10,7, '', 0, 0, 'C');
$pdf->cell(25,7, '', 0, 0, 'C');
$pdf->cell(25,7, iconv("UTF-8", "ISO-8859-1", "Total Comprobante"), 0, 0, 'R');
$pdf->cell(10,7, '', 0, 0, 'C');
$pdf->cell(35,7, number_format($xtot_compras, 2, ",", "."), 0, 0, 'C');
$pdf->cell(25,7, number_format($xtot_exento, 2, ",", "."), 0, 0, 'C');
$pdf->cell(25,7, number_format($xtot_base, 2, ",", "."), 0, 0, 'C');
$pdf->cell(15,7, '', 0, 0, 'C');
$pdf->cell(25,7, number_format($xtot_iva, 2, ",", "."), 0, 0, 'C');
$pdf->cell(15,7, number_format($xtot_ret, 2, ",", "."), 0, 0, 'C');
$pdf->SetFont('Arial','', 8);
//Archivo de Salida
$pdf->Output('', html_entity_decode($filemame));
