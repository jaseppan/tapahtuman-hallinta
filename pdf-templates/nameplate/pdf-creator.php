<?php

ini_set('display_errors', 0);
// Include the main TCPDF library (search for installation path).
require_once('../../tcpdf/tcpdf.php');


// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        $img_file = '../images/nameplate-background-image.jpg';
        $this->Image($img_file, 11, 9, 188, 279, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'ANSI', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Janne Sepp&auml;nen');
$pdf->SetTitle($_POST['doc-title']);
$pdf->SetSubject('Sommelon nimilaput');
$pdf->SetKeywords('Sommelo, nimi, nimilaput, nimikyltit, nameplate');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins
$pdf->SetMargins(15, 20, 8);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, -20);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

//$pdf->setCellHeightRatio(10);

// set font
$pdf->SetFont('dejavusans', '', 45);
$pdf->SetLineWidth(1);

// add a page
$pdf->AddPage();

// Print a text
if($_POST['table']) {
	$html = $_POST['table'];
} else {
	include('test-table.php');
}


// DEBUG 

// echo $html;
// exit();

$pdf->writeHTML($html, true, false, true, false, '');




//Close and output PDF document
$pdf->Output($_POST['file-name'], 'I');

//============================================================+
// END OF FILE
//============================================================+
