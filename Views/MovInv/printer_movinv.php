<?php
// Limpiar búfer para evitar errores de encabezado en producción
if (ob_get_length()) {
    ob_end_clean();
}

require(FPDF1 . 'fpdf.php');

$nombreArchivo = $r[0]->nombre_emp . '-' . $r[0]->cod_tmoinv . '-' . $r[0]->nom__tmoinv . '-' . $r[0]->num_movinv;
$filename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombreArchivo) . '.pdf';

class PDF extends FPDF {
    public $ruta_logo;
    public $nombre_emp;
    public $fecha_comp;
    public $nom_movim;
    public $num_movinv;
    public $nom_alm;
    public $observa;
    public $origen;

    public function __construct($r) {
        parent::__construct('L', 'mm', 'Letter'); // Horizontal, milímetros, Carta
        
        // Determinar ruta física del logo para compatibilidad local/producción
        $logoName = !empty($r[0]->logo) ? $r[0]->logo : '';
        $this->ruta_logo = (defined('ROOT') ? ROOT . DS : '') . 'Assets/img/companies/' . $logoName;
        if (!file_exists($this->ruta_logo) && defined('IMG')) {
            $this->ruta_logo = IMG . 'companies/' . $logoName;
        }

        $this->nombre_emp = $r[0]->nombre_emp ?? '';
        $this->fecha_comp = $r[0]->fecha_comp ?? '';
        $this->nom_movim  = ($r[0]->cod_tmoinv ?? '') . ' - ' . ($r[0]->nom__tmoinv ?? '');
        $this->num_movinv = $r[0]->num_movinv ?? '';
        $this->nom_alm    = ($r[0]->cod_alm ?? '') . ' - ' . ($r[0]->nom_alm ?? '');
        $this->observa    = $r[0]->descrip_movinv ?? '';
        $this->origen     = $r[0]->origen ?? '';
    }

    // Función auxiliar para codificar texto a ISO-8859-1 sin errores
    public function conv($texto) {
        return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', (string)$texto);
    }

    function Header() {
        // 1. Logo (Valida si existe el archivo antes de pintarlo)
        if (!empty($this->ruta_logo) && file_exists($this->ruta_logo)) {
            $this->Image($this->ruta_logo, 10, 8, 45);
        }

        // 2. Encabezado principal
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 5, $this->conv($this->nombre_emp), 0, 1, 'C');

        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 5, 'Caracas, ' . formatFecha($this->fecha_comp), 0, 1, 'R');

        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 5, $this->conv($this->nom_movim), 0, 1, 'C');

        $this->SetFont('Arial', '', 9);
        $this->Cell(0, 5, $this->conv('Número: ') . $this->num_movinv, 0, 1, 'C');
        $this->Cell(0, 5, $this->conv('Almacén: ' . $this->nom_alm), 0, 1, 'C');

        // 3. Observaciones y Origen
        $this->Ln(4);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(25, 4, 'Observaciones: ', 0, 0, 'L');
        $this->SetFont('Arial', '', 8);
        $this->MultiCell(0, 4, $this->conv(html_entity_decode($this->observa)), 0, 'L');

        if (!empty($this->origen)) {
            $this->SetFont('Arial', 'B', 8);
            $this->Cell(25, 4, 'Origen / Doc: ', 0, 0, 'L');
            $this->SetFont('Arial', '', 8);
            $this->Cell(0, 4, $this->conv($this->origen), 0, 1, 'L');
        }

        // 4. Encabezados de la Tabla de Productos (Ancho Total: 259 mm)
        $this->Ln(3);
        $this->SetFont('Arial', 'B', 7.5);
        $this->SetFillColor(230, 235, 240); // Fondo tenue para títulos

        $this->Cell(8,  5, $this->conv('Ítem'), 1, 0, 'C', true);
        $this->Cell(85, 5, $this->conv('Producto'), 1, 0, 'L', true);
        $this->Cell(22, 5, $this->conv('Código'), 1, 0, 'L', true);
        $this->Cell(22, 5, $this->conv('Referencia'), 1, 0, 'L', true);
        $this->Cell(18, 5, $this->conv('Ubicación'), 1, 0, 'L', true);
        $this->Cell(45, 5, $this->conv('Nombre Ubicación'), 1, 0, 'L', true);
        $this->Cell(22, 5, $this->conv('Lote'), 1, 0, 'L', true);
        $this->Cell(17, 5, $this->conv('Fec. Venc.'), 1, 0, 'C', true);
        $this->Cell(20, 5, $this->conv('Cantidad'), 1, 1, 'R', true);
    }

    function Footer() {
        $this->SetY(-12);
        $this->SetFont('Arial', 'I', 7.5);
        $this->Cell(0, 8, $this->conv('Página ') . $this->PageNo() . ' de {nb}', 0, 0, 'C');
    }
}

// Inicialización
$pdf = new PDF($r);
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage('L', 'Letter');
$pdf->SetFont('Arial', '', 7);

$items = 0;
$tot_cant = 0;

// Detalles del movimiento
foreach ($r as $row) {
    $items++;
    $pdf->Cell(8,  4, $items, 'B', 0, 'C');
    $pdf->Cell(85, 4, $pdf->conv(html_entity_decode($row->nom_prod ?? '')), 'B', 0, 'L');
    $pdf->Cell(22, 4, $row->cod_prod ?? '', 'B', 0, 'L');
    $pdf->Cell(22, 4, $row->ref_prod ?? '', 'B', 0, 'L');
    $pdf->Cell(18, 4, $row->cod_ubi ?? '', 'B', 0, 'L');
    $pdf->Cell(45, 4, $pdf->conv($row->nom_ubi ?? ''), 'B', 0, 'L');
    $pdf->Cell(22, 4, $row->lote ?? '', 'B', 0, 'L');
    $pdf->Cell(17, 4, formatFecha($row->fec_venc ?? ''), 'B', 0, 'C');
    $pdf->Cell(20, 4, number_format((float)($row->cantidad ?? 0), 0, '.', ','), 'B', 1, 'R');
    
    $tot_cant += (float)($row->cantidad ?? 0);
}

// Fila de Total
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(222, 5, 'Total General: ', 0, 0, 'R');
$pdf->Cell(17,  5, '', 0, 0, 'C');
$pdf->Cell(20,  5, number_format($tot_cant, 0, '.', ','), 1, 1, 'R');

// Firmas (Elaborado y Modificado)
$pdf->Ln(8);
$anchoCaja = 85;
$espacio = 20;

if (!empty($r[0]->modify_user)) {
    $anchoTotal = ($anchoCaja * 2) + $espacio;
    $xInicial = ($pdf->GetPageWidth() - $anchoTotal) / 2;

    $pdf->SetX($xInicial);
    $pdf->SetFont('Arial', 'B', 7.5);
    $pdf->Cell($anchoCaja, 4, 'Elaborado por:', 0, 0, 'C');
    $pdf->Cell($espacio, 4, '', 0, 0);
    $pdf->Cell($anchoCaja, 4, 'Modificado por:', 0, 1, 'C');

    $pdf->Ln(12); // Espacio para firma manuscrita si aplica

    $pdf->SetX($xInicial);
    $pdf->SetFont('Arial', '', 7.5);
    $pdf->Cell($anchoCaja, 4, $pdf->conv($r[0]->create_user ?? ''), 'T', 0, 'C');
    $pdf->Cell($espacio, 4, '', 0, 0);
    $pdf->Cell($anchoCaja, 4, $pdf->conv($r[0]->modify_user ?? ''), 'T', 1, 'C');

    $pdf->SetX($xInicial);
    $pdf->SetFont('Arial', 'I', 6.5);
    $fCreate = !empty($r[0]->create_date) ? date("d-m-Y H:i:s", strtotime($r[0]->create_date)) : '';
    $fModif  = !empty($r[0]->modify_date) ? date("d-m-Y H:i:s", strtotime($r[0]->modify_date)) : '';
    $pdf->Cell($anchoCaja, 3, 'Fecha: ' . $fCreate, 0, 0, 'C');
    $pdf->Cell($espacio, 3, '', 0, 0);
    $pdf->Cell($anchoCaja, 3, 'Fecha: ' . $fModif, 0, 1, 'C');

} else {
    $xInicial = ($pdf->GetPageWidth() - $anchoCaja) / 2;

    $pdf->SetX($xInicial);
    $pdf->SetFont('Arial', 'B', 7.5);
    $pdf->Cell($anchoCaja, 4, 'Elaborado por:', 0, 1, 'C');

    $pdf->Ln(12);

    $pdf->SetX($xInicial);
    $pdf->SetFont('Arial', '', 7.5);
    $pdf->Cell($anchoCaja, 4, $pdf->conv($r[0]->create_user ?? ''), 'T', 1, 'C');

    $pdf->SetX($xInicial);
    $pdf->SetFont('Arial', 'I', 6.5);
    $fCreate = !empty($r[0]->create_date) ? date("d-m-Y H:i:s", strtotime($r[0]->create_date)) : '';
    $pdf->Cell($anchoCaja, 3, 'Fecha: ' . $fCreate, 0, 1, 'C');
}

// Salida directa al navegador para abrir en pestaña
$pdf->Output('I', $filename);