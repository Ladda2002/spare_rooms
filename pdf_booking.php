<?php

require_once('assets/tcpdf/tcpdf.php'); 
session_start(); 
require("function/function.php"); 

$start_date = $_POST["start_date"]; 
$end_date = $_POST["end_date"]; 

$reportBooking = getReportBooking($start_date,$end_date);
$booking_map = array( 0=>'<label style="color:red">ยกเลิก</label>', 1=>'<label style="color:blue">รอยืนยัน</label>', 2=>'<label style="color:green">ยืนยัน</label>'); 

$yThai = date("Y")+543; 
$dateNow = date("d/m/").$yThai;

// สร้างเอกสาร PDF ใหม่
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// กำหนดข้อมูลเอกสาร
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni'); 
$pdf->SetTitle('Spare Room'); 
$pdf->SetSubject('TCPDF Tutorial'); 
$pdf->SetKeywords('TCPDF, PDF, example, test, guide'); 

// ลบ header และ footer เริ่มต้น
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
$pdf->SetFont('cordiaupc', '', 12, '', true); 

$line_html="";
// สร้างหน้าใหม่
$pdf->AddPage();
// กำหนดให้หน้าเป็นแนวนอน
$pdf->setPageOrientation ('L', $autopagebreak='', $bottommargin=''); 
// รับค่า margin ของการแบ่งหน้าปัจจุบัน
$bMargin = $pdf->getBreakMargin();
// ปิดการแบ่งหน้าอัตโนมัติ
$pdf->SetAutoPageBreak(true, 0);

// ตัวแปรรวมนับจำนวนแถว
$a = 0;

$x = 0;
$y = 0;
$z = 0;
// วนลูปเพิ่มข้อมูลในแต่ละแถว
foreach($reportBooking as $data){
    $a++; // ลำดับ
    $cDate = formatDateFull($data["booking_date"]);
    $cStat = $booking_map[$data["booking_status"]]; 
    if($data["booking_status"] == 0){
        $x++;
    }else if($data["booking_status"] == 1){
        $y++;
    }else{
        $z++;
    }
$line_html  .= <<<EOD
                <tr>
                    <td align="center" style="border-right-width:0.1px;">{$a}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$cDate}</td>
                    <td style="border-right-width:0.1px;text-align:center;">{$data['booking_name']}</td>
                    <td style="border-right-width:0.1px;text-align:center;">{$data['booking_phone']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['booking_email']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['apart_name']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$data['room_name']}</td>
                    <td style="text-align:center;border-right-width:0.1px;">{$cStat}</td>
                </tr>
EOD;
}

// สร้างส่วนหัวของรายงาน
$header_html  .= <<<EOD
<div style="text-align:center;margin:0;"><b style="font-size:30px;">รายงานการจอง</b></div>
<div style="text-align:center;margin:0;">
<b style="font-size:20px;">วันที่ {$start_date} ถึง {$end_date}</b>
</div>
EOD;

// สร้างตารางเนื้อหาของรายงาน
$body_html  .= <<<EOD
<table style="width:100%;" border="1">
    <tr>
        <td style="text-align:center;"><b>ลำดับ</b></td>
        <td style="text-align:center;"><b>วันที่</b></td>
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
    <label style="color:red">รวมยกเลิก {$x} รายการ</label><br/>
    <label style="color:blue">รวมรอยืนยัน {$y} รายการ</label><br/>
    <label style="color:green">รวมยืนยัน {$z} รายการ</label>
</div>

<div align="right">
    รวมทั้งหมด {$a} รายการ
</div
EOD;

// รวมส่วนหัวและเนื้อหาในเอกสาร
$html = <<<EOD
{$header_html}
{$body_html}
EOD;

// พิมพ์ข้อมูลในเอกสาร PDF
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------

// ปิดและแสดงไฟล์ PDF
ob_end_clean(); // ล้าง buffer ก่อนแสดงผล
$pdf->Output('รายงาน.pdf', 'I'); // แสดงไฟล์ PDF ในเบราว์เซอร์

?>

<?php die(); 
?>
