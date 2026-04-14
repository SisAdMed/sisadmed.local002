<?php
$ruta = ROOT . '\Views\Facturacion\mmq.pdf';

require 'Assets/vendor/autoload.php'; 
use Smalot\PdfParser\Parser;
$config = new \Smalot\PdfParser\Config();
// An empty string can prevent words from breaking up
$config->setHorizontalOffset('');
// A tab can help preserve the structure of your document
$config->setHorizontalOffset("\t");
$parser = new \Smalot\PdfParser\Parser([], $config);


$pdfFile = $ruta;
$parser = new Parser();
$pdf = $parser->parseFile($pdfFile);

$text = $pdf->getText();

echo "<pre>" . $text . "</pre>";

// También puedes obtener metadatos
$details = $pdf->getDetails();
print_r($details);

$pages = $pdf->getPages();
foreach ($pages as $page) {
    echo "<hr>";
    echo "<pre>" . $page->getText() . "</pre>";
}

//$data = $pdf->getPages()[0]->getDataTm();

//$tot_rows = count($data);

/*
for( $i = 0; $i < $tot_rows; $i++){
    for( $x = 0; $x < 1; $x++ ){
        debug($data[$i][1]);
    }
}
*/

//$tot_rows = json_decode(nl2br($text));

//debug($tot_rows);

//echo nl2br($text);

