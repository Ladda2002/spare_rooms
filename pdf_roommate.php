<?php

require_once('assets/tcpdf/tcpdf.php');
session_start();
require("function/function.php");

$reportRoomate = getReportRoomate();
$request_map = array( 0=>'<label style="color:green">ปฏิเสธคำขอ</label>',1=>'<label style="color:blue">รอการตอบรับ</label>',2=>'<label style="color:green">ยืนยันคำขอ</label>');
$yThai = date("Y")+543;
$dateNow = date("d/m/").$yThai;

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Spare Room');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// set font

//$fontname = $pdf->addTTFfont('fonts/Browa.ttf', 'TrueTypeUnicode', '', 32);
$pdf->SetFont('cordiaupc', '', 12, '', true);


$line_html="";
//PAGE 3 >> PAGE 1
$pdf->AddPage();
$pdf->setPageOrientation ('L', $autopagebreak='', $bottommargin='');
// get the current page break margin
$bMargin = $pdf->getBreakMargin();
// get current auto-page-break mode
$auto_page_break = $pdf->getAutoPageBreak();
// disable auto-page-break
$pdf->SetAutoPageBreak(true, 0);
// Set some content to print

$total_price = 0;
$total_weight = 0;

foreach($reportRoomate as $data){
    $a++;
    $cStat = $request_map[$data["request_status"]];
$line_html  .= <<<EOD
                <tr>
                    <td align="center" style="border-right-width:0.1px;">{$a}</td>
                    <td style="border-right-width:0.1px;text-align:center;">{$data['username']}</td>
                    <td style="border-right-width:0.1px;text-align:center;">{$data['phone']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['email']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['apart_name']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['room_name']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$cStat}</td>
                </tr>
EOD;
}

$header_html  .= <<<EOD
<div style="text-align:center;margin:0;"><b style="font-size:20px;">รายงานการจับคู่</b></div>

EOD;


$body_html  .= <<<EOD
<table style="width:100%;" border="1">
    <tr>
        <td style="text-align:center;"><b>ลำดับ</b></td>
        <td style="text-align:center;"><b>ชื่อ-นามสกุล</b></td>
        <td style="text-align:center;"><b>โทรศัพท์</b></td>
        <td style="text-align:center;"><b>อีเมล</b></td>
        <td style="text-align:center;"><b>ชื่อหอพัก</b></td>
        <td style="text-align:center;"><b>ห้อง</b></td>
        <td style="text-align:center;"><b>สถานะ</b></td>
    </tr>
    {$line_html}
</table>
<div align="right">
    รวม {$a} รายงาน
</div>
EOD;


$html = <<<EOD
{$header_html}
{$body_html}

EOD;


// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
ob_end_clean();
$pdf->Output('รายงาน.pdf', 'I');
?>

<?php die(); ?>
