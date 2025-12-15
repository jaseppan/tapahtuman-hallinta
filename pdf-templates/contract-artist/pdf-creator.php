<?php
ini_set('display_errors', 0);
// Include the main TCPDF library (search for installation path).
require_once('../../tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Esiintymissopimus');
$pdf->SetSubject($_POST['doc-name']);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, "20", PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)


// Taulukko
$html = "<h1>" . $_POST['title'] . "</h1>";
for($x = 0; $x < count($_POST['label']); $x++) {
	$html .= '<h3>' . $_POST['label'][$x] . "</h3>";
	$html .= '<p style="line-height:20px;">'  . $_POST['text'][$x] . "</p>";	
}
$html .= "</table>";
$html .= "<table>";
$html .= "<tr margin='10px'><td width='250'>" . $_POST['date_loc_employer'] . "</td>";
$html .= "<td width='50'>" . $_POST['date_loc_employee']. "</td></tr>";
$html .= "<tr><td></td><td></td></tr>";
$html .= "<tr><td></td><td></td></tr>";
$html .= "<tr><td>___________________________________________</td><td>___________________________________________</td></tr>";
$html .= "<tr margin='10px'><td width='250'>" . $_POST['signature_employer'] . "</td>";
$html .= "<td width='50'>" . $_POST['signature_employee']. "</td></tr>";

$html .= "</table>";


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document

$pdf->Output($_POST['doc-name'], 'I');

//============================================================+
// END OF FILE
//============================================================+
?>
