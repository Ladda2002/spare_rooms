<?php

require_once('assets/tcpdf/tcpdf.php'); 
session_start();
require("function/function.php"); 

$reportRoomate = getReportRoomate(); 
$request_map = array( 0=>'<label style="color:green">ปฏิเสธคำขอ</label>', 1=>'<label style="color:blue">รอการตอบรับ</label>', 2=>'<label style="color:green">ยืนยันคำขอ</label>'); 

$yThai = date("Y") + 543; 
$dateNow = date("d/m/") . $yThai; 

// สร้างเอกสาร PDF ใหม่
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// กำหนดข้อมูลของเอกสาร
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni'); 
$pdf->SetTitle('Spare Room'); 
$pdf->SetSubject('TCPDF Tutorial'); 
$pdf->SetKeywords('TCPDF, PDF, example, test, guide'); 

// ลบ header/footer ที่เป็นค่าเริ่มต้น
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// ตั้งค่าฟอนต์ monospaced
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// ตั้งค่าระยะขอบ
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);

// ตั้งค่าการแบ่งหน้าอัตโนมัติ
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// ตั้งค่าอัตราส่วนการปรับขนาดรูปภาพ
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ตั้งค่าภาษา
$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// ตั้งค่าฟอนต์
//$fontname = $pdf->addTTFfont('fonts/Browa.ttf', 'TrueTypeUnicode', '', 32);
$pdf->SetFont('cordiaupc', '', 12, '', true); // ตั้งค่าฟอนต์ CordiaUPC

$line_html="";
// สร้างหน้าใหม่ในเอกสาร PDF
$pdf->AddPage();
// กำหนดหน้าเป็นแนวนอน
$pdf->setPageOrientation ('L', $autopagebreak='', $bottommargin=''); 
// รับค่า page break margin ปัจจุบัน
$bMargin = $pdf->getBreakMargin();
// รับค่า auto-page-break ปัจจุบัน
$auto_page_break = $pdf->getAutoPageBreak();
// ปิดการแบ่งหน้าอัตโนมัติ
$pdf->SetAutoPageBreak(true, 0); 

$total_price = 0; // ตัวแปรเก็บราคารวม
$total_weight = 0; // ตัวแปรเก็บน้ำหนักรวม

$x = 0;
$y = 0;
$z = 0;
// วนลูปสร้างแถวสำหรับข้อมูลแต่ละรายการ
foreach($reportRoomate as $data){
    $a++; // นับลำดับ
    $cStat = $request_map[$data["request_status"]];
    if($data["request_status"] == 0){
        $x++;
    }else if($data["request_status"] == 1){
        $y++;
    }else{
        $z++;
    }
$line_html  .= <<<EOD
                <tr>
                    <td align="center" style="border-right-width:0.1px;">{$a}</td>
                    <td style="border-right-width:0.1px;text-align:center;">{$data['username']}</td>
                    <td style="border-right-width:0.1px;text-align:center;">{$data['phone']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['email']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['apartment']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['room_name']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$cStat}</td>
                </tr>
EOD;
}

// ส่วนหัวของรายงาน
$header_html  .= <<<EOD
<div style="text-align:center;margin:0;"><b style="font-size:30px;">รายงานการจับคู่</b></div>

EOD;

// สร้างเนื้อหาของรายงาน
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

<div align="right" >
    <label style="color:red">รวมปฏิเสธคำขอ {$x} รายการ</label><br/>
    <label style="color:blue">รวมรอการตอบรับ {$y} รายการ</label><br/>
    <label style="color:green">รวมยืนยันคำขอ {$z} รายการ</label>
</div>
<div align="right">
    รวม {$a} รายงาน
</div>
EOD;

// รวมส่วนหัวและเนื้อหาในเอกสาร
$html = <<<EOD
{$header_html}
{$body_html}
EOD;

// พิมพ์ข้อมูลในเอกสาร PDF
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// ปิดและแสดงเอกสาร PDF
ob_end_clean(); // ล้าง buffer
$pdf->Output('รายงาน.pdf', 'I');

?>

<?php die(); 
?>
