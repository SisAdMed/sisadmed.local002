<?php
// Declaramos la libreria
require_once(SPREADEXCEL . "/vendor/autoload.php");
include_once VARTAX;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\View; // Asegúrate de importar View
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\Wizard\Number;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class Downloader
{
    public function DownExcel($r){
        //Variables
        $filename = "Compras Internacional " . $r[0]->id_comint . '-' . $r[0]->fecha_comint;
        $ext = ".xlsx";
        //Pprocedimiento
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator($_SESSION['name_user'] . ' ' . $_SESSION['last_user'])->setTitle($filename);
        $spreadsheet->getActiveSheetIndex(0);
        //Crear hoja
        $sheet = $spreadsheet->getActiveSheet();
        // Obtener el objeto SheetView

        // Desactivar las líneas de cuadrícula
        //$sheetView->setSheetState(View::SHEETSTATE_HIDDEN); // Esto puede que no funcione para todas las versiones de PHPSpreadsheet
        //$sheetView->setShowGridLines(false); // Este es el método principal para ocultar la cuadrícula

        $pageSetup = $sheet->getPageSetup();
        $sheetView = $sheet->getSheetView();
        // Establecer el tamaño del papel a A4
        $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);

        $sheet->setTitle('Cotización');
        //Escribir contenido del Excel
        //Logo de la empresa
        $logocia = IMAGE_PATH . 'companies' . DS . $r[0]->logo;
        if (file_exists($logocia)) {
            //echo '<br>existe imagen';
            $drawing = new Drawing();
            $drawing->setName('Logo de Empresa');
            $drawing->setDescription('Logo de Emprsa');
            $drawing->setPath($logocia);
            $drawing->setHeight(80);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        } else {
            echo '<br>no existe la imagen';
        }

        //Encabezado de Cotizaciómn
        //Empresa
        $sheet->mergeCells('A1:L1')->setCellValue('A1', htmlentities($r[0]->nombre_emp))
            ->getStyle('A1:L1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //RIF
        $sheet->mergeCells('A2:L2')->setCellValue('A2', $r[0]->rif_empresa)
            ->getStyle('A2:L2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //Dirección
        $sheet->mergeCells('C3:I3')->setCellValue('C3', htmlentities($r[0]->dir_emp))
            ->getStyle('C3:I3')->getFont()->setBold(true)->setSize(8);
        // Establecer el ajuste de texto para la celda C3
        $sheet->getStyle('C3:I3')->getAlignment()->setWrapText(true);
        $sheet->getRowDimension(3)->setRowHeight(30); // Ejemplo con altura fija
        $sheet->getStyle('C3:I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //Email
        $sheet->mergeCells('C4:I4')->setCellValue('C4', $r[0]->email_emp)
            ->getStyle('C4:I4')->getFont()->setBold(true)->setSize(8);
        $sheet->getStyle('C4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        //Cliente
        $sheet->setCellValue("A6", 'Proveedor:')->getStyle("A6")->getFont()->setSize(8);
        $sheet->mergeCells('B6:E6')->setCellValue('B6', $r[0]->nombre_provint)
            ->getStyle('B6:E6')->getFont()->setBold(true)->setSize(8);
        if (strlen($r[0]->nombre_provint) > 44) {
            $sheet->getStyle('B6:E6')->getAlignment()->setWrapText(true);
            $sheet->getRowDimension(6)->setRowHeight(30); // Ejemplo con altura fija
        }
        //Fecha
        $sheet->mergeCells('F6:G6')->setCellValue('F6', "Fecha: " . formatFecha($r[0]->fecha_comint))
            ->getStyle('F6:G6')->getFont()->setSize(8);
        $sheet->getStyle('F6:G6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER_CONTINUOUS);
        //Cotización
        $sheet->mergeCells('F7:G7')->setCellValue('F7', "Compra Internac.: " . $r[0]->id_provint)
            ->getStyle('F7:G7')->getFont()->setBold(true)->setSize(10);

        //Titulos de Columnas
        $sheet->mergeCells('A10:A10')->setCellValue('A10', 'CÓDIGO')->getStyle('A10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('B10:G10')->setCellValue('B10', 'DESCRIPCIÓN')->getStyle('B10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue("H10", 'REFERENCIA')->getStyle('H10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue("I10", 'MARCA.')->getStyle('I10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue("J10", 'UNIDAD DE EMPAQUE')->getStyle('J10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setWrapText(true);
        $sheet->setCellValue("K10", 'CANTIDAD')->getStyle('K10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue("L10", 'PRECIO')->getStyle('L10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->setCellValue("M10", 'TOTAL UNIDADES')->getStyle('M10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setWrapText(true);
        $sheet->setCellValue("N10", 'PRECIO UNIT')->getStyle('N10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setWrapText(true);
        $sheet->setCellValue("O10", 'TOTAL')->getStyle('O10')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);;

        $sheet->getStyle('A10:O10')->getFont()->setBold(true)->setSize(10);

        //Contenido de columnas

        $totrows = count($r);
        $line_item = 11;
        $first_i = 11;
        $item = 0;
        $sub_tot = 0;
        $sub_exe = 0;
        $sub_imp = 0;
        $sub_iva = 0;
        $total = 0;
        for ($i = 0; $i < $totrows; $i++) {
            $sheet->mergeCells("A$line_item:A$line_item")->setCellValue("A$line_item", html_entity_decode($r[$i]->cod2_prod))
                ->getStyle("A$line_item:B$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("B$line_item:G$line_item")->getAlignment()->setWrapText(true);
            $sheet->mergeCells("B$line_item:G$line_item")->setCellValue("B$line_item", html_entity_decode($r[$i]->nom_prod))
                ->getStyle("B$line_item:G$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("B$line_item:G$line_item")->getAlignment()->setWrapText(true);
            $sheet->mergeCells("H$line_item:H$line_item")->setCellValue("H$line_item", html_entity_decode($r[$i]->ref_prod))
                ->getStyle("H$line_item:H$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("H$line_item:H$line_item")->getAlignment()->setWrapText(true);
            $sheet->setCellValue("I$line_item", $r[$i]->nom_fab)->getStyle("I$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getCell("I$line_item");
            $sheet->setCellValue("J$line_item", $r[$i]->nom_pre)->getStyle("J$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getCell("J$line_item");
            $sheet->setCellValue("K$line_item", $r[$i]->cantidad)->getStyle("K$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getCell("K$line_item")
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###');
            $sheet->setCellValue("L$line_item", $r[$i]->costo)->getStyle("L$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getCell("L$line_item")
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.0000');
            $sheet->setCellValue("M$line_item", $r[$i]->tot_unidades)->getStyle("M$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getCell("M$line_item")
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###');
            $sheet->setCellValue("N$line_item", $r[$i]->tot_comp / $r[$i]->tot_unidades)->getStyle("N$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getCell("N$line_item")
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.0000');
            $sheet->setCellValue("O$line_item", $r[$i]->tot_comp)->getStyle("O$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getCell("O$line_item")
                ->getStyle()->getNumberFormat()
                ->setFormatCode('#,###.0000');
            $sub_tot += $r[$i]->tot_comp;
            $line_item++;
        }
        $sheet->getStyle("A11:O$line_item")->getFont()->setSize(8);
        
        //
        //Imprimir totales
        $line_item++;
        $sheet->setCellValue("N$line_item", 'TOTAL')->getStyle("N$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("N$line_item:N$line_item")->getFont()->setBold(true)->setSize(8);
        $sheet->setCellValue("O$line_item", $sub_tot)->getStyle("O$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("O$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.0000');
        $sheet->getStyle("O$line_item:O$line_item")->getFont()->setSize(8);
        /*
        
        
        $sheet->setCellValue("L$line_item", ($sub_tot * $r[0]->tasa_cambio))->getStyle("L$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("L$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->getStyle("A$line_item:L$line_item")->getFont()->setBold(true)->setSize(8);
        $line_item++;
        $sheet->mergeCells("H$line_item:J$line_item")->setCellValue("H$line_item", html_entity_decode('Sub-Total Exento'));
        $sheet->setCellValue("K$line_item", $sub_exe)->getStyle("K$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("K$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->setCellValue("L$line_item", ($sub_exe * $r[0]->tasa_cambio))->getStyle("L$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("L$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->getStyle("A$line_item:L$line_item")->getFont()->setBold(true)->setSize(8);
        $line_item++;
        $sheet->mergeCells("H$line_item:J$line_item")->setCellValue("H$line_item", html_entity_decode('Sub-Total Base Imponible'));
        $sheet->setCellValue("K$line_item", $sub_imp)->getStyle("K$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("K$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->setCellValue("L$line_item", ($sub_imp * $r[0]->tasa_cambio))->getStyle("L$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("L$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->getStyle("A$line_item:L$line_item")->getFont()->setBold(true)->setSize(8);
        $line_item++;
        $sheet->mergeCells("H$line_item:J$line_item")->setCellValue("H$line_item", html_entity_decode('IVA'));
        $sheet->setCellValue("K$line_item", $sub_iva)->getStyle("K$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("K$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->setCellValue("L$line_item", ($sub_iva * $r[0]->tasa_cambio))->getStyle("L$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("L$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->getStyle("A$line_item:L$line_item")->getFont()->setBold(true)->setSize(8);
        $line_item++;
        $sheet->mergeCells("H$line_item:J$line_item")->setCellValue("H$line_item", html_entity_decode('Total'));
        $sheet->setCellValue("K$line_item", $total)->getStyle("K$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("K$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->setCellValue("L$line_item", ($total * $r[0]->tasa_cambio))->getStyle("L$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getCell("L$line_item")
            ->getStyle()->getNumberFormat()
            ->setFormatCode('#,###.00');
        $sheet->getStyle("A$line_item:L$line_item")->getFont()->setBold(true)->setSize(8);

        //Imprimir Notas 
        $line_item += 5;
        $sheet->mergeCells("A$line_item:L$line_item")->setCellValue("A$line_item", html_entity_decode($r[0]->note_pre))
            ->getStyle("A$line_item:L$line_item")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A$line_item:L$line_item")->getFont()->setBold(true)->setSize(8);
        $sheet->getStyle("A$line_item:L$line_item")->getAlignment()->setWrapText(true);
        if (strlen($r[0]->note_pre) > 45) {
            $sheet->getRowDimension("$line_item")->setRowHeight(30); // Ejemplo con altura fija
        }
        //

        */

        //Nombre del archivo
        $filename = $filename . $ext;

        //Configurar cabeceras
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
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
if (isset($r)) {
    $excel = new Downloader();
    return $excel->DownExcel($r);
}
