<?php
// Declaramos la libreria
require_once(SPREADEXCEL . "/vendor/autoload.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use \PhpOffice\PhpSpreadsheet\Shared\Date;

Class Downloader{
    public function DownExcel($r){
        //Variables
        $filename = "LIbro de Compras de " . $r[0]->nombre_emp; $ext = "xlsx";
        $fec_ini = $_GET['fec_ini'];
        $fec_fin = $_GET['fec_fin'];
        //Pprocedimiento
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator($_SESSION['name_user'] . ' ' . $_SESSION['last_user'])->setTitle($filename);
        $spreadsheet->getActiveSheetIndex(0);
        //Crear hoja
        $sheet = $spreadsheet->getActiveSheet();
        $sheet ->setTitle('Libro de Compras');
        //Escribir contenido del Excel
        //Logo de la empresa
        $logocia = IMAGE_PATH .'companies' . DS . $r[0]->logo;
        if(file_exists($logocia)){
            //echo '<br>existe imagen';
            $drawing = new Drawing();
            $drawing->setName('Logo de Empresa');
            $drawing->setDescription('Logo de Emprsa');
            $drawing->setPath($logocia);
            $drawing->setHeight(100);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        }else{
            echo '<br>no existe la imagen';
        }
        //Titulo del Reporte
        $sheet->mergeCells('A3:Q3')->setCellValue('A3', $r[0]->nombre_emp);
        $sheet->mergeCells('A4:Q4')->setCellValue('A4', 'LIBRO DE COMPRAS');
        $sheet->mergeCells('A5:Q5')->setCellValue('A5', 'Período desde: ' . formatFechaSlash($fec_ini) . ' hasta: ' . formatFechaSlash($fec_fin));
        $sheet->getStyle('A3:Q5')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3:Q5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        //Titulos de Columnas
        $sheet -> setCellValue("A10", 'Nro.Oper.');
        $sheet -> setCellValue("B10", 'Fecha');
        $sheet -> setCellValue("C10", 'RIF');
        $sheet -> setCellValue("D10", 'Nombre o Razón Social');
        $sheet -> setCellValue("E10", 'Planilla de Exportación Forma D');
        $sheet -> setCellValue("F10", 'Nro. Factura');
        $sheet -> setCellValue("G10", 'Ctrol.Fact.');
        $sheet -> setCellValue("H10", 'Nro. Débito');
        $sheet -> setCellValue("I10", 'Nro. Crédito');
        $sheet -> setCellValue("J10", 'Tipo Trans.');
        $sheet -> setCellValue("K10", 'Fact. Afectada');
        $sheet -> setCellValue("L10", 'Total Compras');
        $sheet -> setCellValue("M10", 'Monto Gravable');
        $sheet -> setCellValue("N10", 'Monto No Gravable');
        $sheet -> setCellValue("O10", 'Base Imponible');
        $sheet -> setCellValue("P10", '% Alícuota');
        $sheet -> setCellValue("Q10", 'Impuesto IVA');
        $sheet->getStyle('A10:Q10')->getFont()->setBold(true);
        //$sheet->freezePane('J2');
        //Contenido de columnas
        $totrows = count($r);
        
        $line_item = 11;
        $first_i = 11;
        $item = 1;
        for($i = 0; $i < $totrows; $i++){
            $sheet -> setCellValue("A".$line_item, $item++);
            $sheet -> setCellValue("B".$line_item, formatFechaSlash($r[$i]->fecha_comp));
            $sheet -> setCellValue("C".$line_item, $r[$i]->rif_ent);
            $sheet -> setCellValue("D".$line_item, $r[$i]->nom_ent);
            $sheet -> setCellValue("E".$line_item, '');
            $sheet -> setCellValue("F".$line_item, $r[$i]->factura);
            $sheet -> setCellValue("G".$line_item, $r[$i]->nro_control);
            $sheet -> setCellValue("H".$line_item, $r[$i]->debito);
            $sheet -> setCellValue("I".$line_item, $r[$i]->credito);
            $sheet -> setCellValue("J".$line_item, $r[$i]->tipo_tra);
            $sheet -> setCellValue("K".$line_item, $r[$i]->afectado);
            $sheet -> setCellValue("L".$line_item, $r[$i]->total_venta_mas_iva);
            $sheet->getCell('L'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('M'.$line_item, $r[$i]->total_gravable);
            $sheet->getCell('M'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('N'.$line_item, $r[$i]->total_exento);
            $sheet->getCell('N'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('O'.$line_item, $r[$i]->total_gravable);
            $sheet->getCell('O'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('P'.$line_item, $r[$i]->trr1_iva);
            $sheet->getCell('P'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $sheet -> setCellValue('Q'.$line_item, $r[$i]->mon_iva);
            $sheet->getCell('Q'.$line_item)
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.00');
            $line_item++;
        }
        $dateFormat = 'dd-mm-yyyy'; // You can modify this format as needed
        $sheet->getStyle("B11:B{$line_item}")
            ->getNumberFormat()
            ->setFormatCode($dateFormat);
    //
        $last_i = $line_item - 1;
                //
        $sumrange = 'L' . $first_i . ':L' . $last_i;
        $sheet->setCellValue('L' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('L'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'O' . $first_i . ':O' . $last_i;
        $sheet->setCellValue('O' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('O'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'M' . $first_i . ':M' . $last_i;
        $sheet->setCellValue('M' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('M'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'N' . $first_i . ':N' . $last_i;
        $sheet->setCellValue('N' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('N'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'O' . $first_i . ':O' . $last_i;
        $sheet->setCellValue('O' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('O'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //
        $sumrange = 'Q' . $first_i . ':Q' . $last_i;
        $sheet->setCellValue('Q' . $line_item, '=SUM(' . $sumrange . ')');
        $sheet->getCell('Q'.$line_item)
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        //Border
        $styleArray = [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('L' . $line_item . ':Q' . $line_item)->applyFromArray($styleArray);
        //
        $sheet->getStyle('A'.$line_item.':Q'. $line_item)->getFont()->setBold(true);
        
        $sheet->getStyle("A10:K{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $sheet->setCellValue('K' . $line_item, 'Total:');
        $sheet->getStyle("L10:Q{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("A10:A{$line_item}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        //
        foreach (range("A1", "Q{$line_item}") as $col) {
            $sheet ->getColumnDimension($col)->setAutoSize(true);
        }
        //Nombre del archivo
        $filename = $filename . $ext;
        //Configurar cabeceras
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        //Escribir Excel
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        if (file_exists($filename)) {
            unlink($filename);
        }
        $writer->save('php://output');
        exit;
    }
}
if(isset($r)){
    $excel = new Downloader();
    return $excel->DownExcel($r);
}
