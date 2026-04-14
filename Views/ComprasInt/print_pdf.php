<?php
require(FPDF1 . 'fpdf.php');
include_once VARTAX;
//Variables
$sub_total = 0;
$mon_base = 0;
$mon_exe = 0;
$iva = 0;
$total = 0;
$tasa_iva;
$filemame = 'Compras Internacionales' . '-' . $r[0]->id_comint . '-' . $r[0]->fecha_comint . '.pdf';
$tman_letra = 7;

class PDF extends FPDF
{
    public $rif_empresa, $ruta_logo, $dir_emp, $nom_ent, $fecha_comp, $rif_ent, $num_tdo, $codigo_moneda;
    public $dir_ent, $nombre_ciudad, $nombre_edo, $nombre_pais, $postal_ent, $moneda_cot, $moneda_emp;
    public $width, $height, $nombre_emp, $email_emp, $nom_tdo;
    //Totales
    public $sub_total_foot;
    protected $col = 0; // Columna actual
    protected $y0;      // Ordenada de comienzo de la columna

    public function __construct($r)
    {
        parent::__construct();
        $this->nombre_emp = $r[0]->nombre_emp;
        $this->rif_empresa = $r[0]->rif_empresa;
        $this->ruta_logo = IMG . 'companies/' . $r[0]->logo;
        $this->dir_emp = html_entity_decode($r[0]->dir_emp);
        $this->nom_ent = html_entity_decode($r[0]->nombre_provint);
        $this->fecha_comp = formatFecha($r[0]->fecha_comint);
        $this->num_tdo = $r[0]->id_provint;
        $this->width = $this->GetPageWidth();
        $this->height = $this->GetPageHeight();
    }
    // Cabecera de página
    function Header()
    {
        // Logo
        
        $this->Image($this->ruta_logo, 10, 8, 50, 0, 'PNG');
        
        // Arial bold 15
        $this->SetFont('Arial', 'B', 7);
        // Movernos a la derecha
        $this->Cell(100);
        // Nombre empresa
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(30, 10, $this->nombre_emp, 0, 1, 'C');
        $this->Cell(100);
        $this->Cell(30, 10, 'RIF: ' . $this->rif_empresa, 0, 'C');
        // Rif
        $this->SetFont('Arial', 'B', 7);
        //Dirección
        $this->Cell(-60);
        $this->MultiCell(0, 4, $this->dir_emp, 0, 'C');
        $this->Cell(100);
        $this->Cell(30, 10, $this->email_emp, 0, 1, 'C');
        // Salto de línea
        $this->Ln(10);
        //Nombre Cliente
        $this->SetFont('Arial', '', 7);
        $this->cell(20, 4, 'Proveedor:');
        $this->SetFont('Arial', 'B', 7);
        $this->cell(30, 4, html_entity_decode($this->nom_ent), 0);
        //Fecha
        $this->Cell(80);
        $this->SetFont('Arial', '', 7, 0, 1);
        $this->cell(15, 4, 'Fecha:', 0);
        $this->SetFont('Arial', 'B', 7);
        $this->cell(15, 4, formatFecha($this->fecha_comp), 0, 1);
        //Cotización
        $this->Cell(80);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 4, html_entity_decode('Compra Internacional' . ': ' . $this->num_tdo), 0, 1);

        //Titulos
        $this->SetFont('Arial', 'B', 7);
        $this->ln();
        $cadena = iconv("UTF-8", "ISO-8859-1", html_entity_decode('DESCRIPCIÓN'));
        $this->cell(90, 4, $cadena, 0, 0, 'C');
        $this->cell(20, 4, html_entity_decode('REFERENCIA'), 0, 0, 'C');
        $this->cell(30, 4, html_entity_decode('MARCA'), 0, 0);
        $this->cell(20, 4, html_entity_decode('EMPAQUE'), 0, 0);
        $this->cell(20, 4, html_entity_decode('CANTIDAD'), 0, 0, 'R',);
        $this->cell(20, 4, html_entity_decode('PRECIO'), 0, 0, 'R');
        $this->cell(20, 4, html_entity_decode('TOTAL UNID.'), 0, 0, 'R');
        $this->cell(20, 4, html_entity_decode('PRECIO UNIT.'), 0, 0, 'R');
        $this->cell(20, 4, html_entity_decode('TOTAL'), 0, 0, 'R');
        $this->SetFont('Arial', '', 7);
        $this->ln(5);
        // Guardar ordenada
        $this->y0 = $this->GetY();
    }
    function Footer() {
        // Pie de página
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128);
        $this->Cell(0, 10, html_entity_decode('Pagina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    function SetCol($col) {
        // Establecer la posición de una columna dada
        $this->col = $col;
        $x = 10 + $col * 65;
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }
}
// Creación del objeto de la clase heredada
$pdf = new PDF($r);
$pdf->AliasNbPages();
$pdf->AddPage('L', 'Letter');
$pdf->SetFont('Arial', '', $tman_letra);
$rows = 0;

$total = 0;
for ($i = 0; $i < count($r); $i++) {
    $pdf->MultiCell(120, 3, html_entity_decode($r[$i]->nom_prod), 0, 'L');
    $pdf->ln(-3);
    $pdf->Cell(90);
    $pdf->Cell(20, 3, $r[$i]->ref_prod, 0, 0);
    $pdf->Cell(30, 3, $r[$i]->nom_fab, 0, 0);
    $pdf->Cell(20, 3, $r[$i]->nom_pre, 0, 0);
    $pdf->Cell(20, 3, $r[$i]->cantidad, 0, 0, 'R');
    $pdf->Cell(20, 3, number_format($r[$i]->costo, 4, ',', '.'), 0, 0, 'R');
    $pdf->Cell(20, 3, number_format($r[$i]->tot_unidades, 0, ',', '.'), 0, 0, 'R');
    $pdf->Cell(20, 3, number_format($r[$i]->costo, 4, ',', '.'), 0, 0, 'R');
    $pdf->Cell(20, 3, number_format(($r[$i]->tot_comp / $r[$i]->tot_unidades), 4, ',', '.'), 0, 0, 'R');
    $pdf->Cell(20, 3, number_format($r[$i]->tot_comp, 2, ',', '.'), 0, 0, 'R');
    $total += $r[$i]->tot_comp;
    $pdf->ln();
}
//Imprimir Total
$pdf->ln(2);
$pdf->Cell(220);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(20, 3, 'Total:', 0, 0, 'R');
$pdf->Cell(20, 3, number_format($total, 2, ',', '.'), 0, 0, 'R');
$pdf->SetFont('Arial', '', 7);



$pdf->Output('', html_entity_decode($filemame));
